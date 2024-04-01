<?php

namespace App\Controller\Api;


use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use App\Repository\AnnonceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnoncesController extends AbstractController
{
  #[Route(path: 'api/annonces', name: "api_annonces_index", methods: ['GET'])]
  public function index(AnnonceRepository $annoncesRepository, SerializerInterface $serializer): JsonResponse
  {
    $annonces = $annoncesRepository->findAll();

    $context = SerializationContext::create()->setGroups("getAnnonces");
    $jsonannonces = $serializer->serialize($annonces, 'json', $context);

    return new JsonResponse($jsonannonces, Response::HTTP_OK, [], true);
  }

  #[Route(path: 'api/annoncesby', name: "api_annoncesby_index", methods: ['GET'])]
  public function annoncesBy(AnnonceRepository $annoncesRepository, SerializerInterface $serializer, PaginatorInterface $paginator, Request $request): JsonResponse
  {
    $annonces = $annoncesRepository->findBy([], []);
    $annoncesPager = $paginator->paginate(
      $annonces,
      $request->query->getInt('page', 1),
      9
    );

    $data = [];
    foreach ($annoncesPager->getItems() as $key => $value) {
      $dataItem = [
        'annonces' => $value
      ];
      $data[] = $dataItem;
    }

    $getData = [
      'data' => $data,
      'curent_page_number' => $annoncesPager->getCurrentPageNumber(),
      'number_per_page' => $annoncesPager->getItemNumberPerPage(),
      'total_count' => $annoncesPager->getTotalItemCount()
    ];

    $context = SerializationContext::create()->setGroups("getAnnonces");

    $jsonannonces = $serializer->serialize($getData, 'json', $context);

    return new JsonResponse($jsonannonces, Response::HTTP_OK, [], true);
  }

  #[Route(path: 'api/annonce/{id}', name: "api_annonce_show", methods: ['GET'])]
  public function showProduct(int $id, AnnonceRepository $annoncesRepository, SerializerInterface $serializer): JsonResponse
  {
    $annonces = $annoncesRepository->find($id);
    $context = SerializationContext::create()->setGroups("getAnnonces");
    $jsonannonces = $serializer->serialize($annonces, 'json', $context);

    return new JsonResponse($jsonannonces, Response::HTTP_OK, [], true);
  }

  #[Route(path: 'api/annoncesby/{poste}', name: "api_annoncesby_poste", methods: ['GET'])]
  public function annoncesByPoste(AnnonceRepository $annoncesRepository, SerializerInterface $serializer, PaginatorInterface $paginator, Request $request, string $poste): JsonResponse 
  {
    // Use the repository to get annonces filtered by poste
    $annonces = $annoncesRepository->findByPoste($poste);

    // Paginate the results
    $annoncesPager = $paginator->paginate(
      $annonces,
      $request->query->getInt('page', 1),
      9
    );

    // Transform the paginated data into the desired format
    $data = [];
    foreach ($annoncesPager->getItems() as $key => $value) {
      $dataItem = [
        'annonces' => $value
      ];
      $data[] = $dataItem;
    }

    $getData = [
      'data' => $data,
      'current_page_number' => $annoncesPager->getCurrentPageNumber(),
      'number_per_page' => $annoncesPager->getItemNumberPerPage(),
      'total_count' => $annoncesPager->getTotalItemCount()
    ];

    // Serialize and return the response
    $context = SerializationContext::create()->setGroups("getAnnonces");
    $jsonannonces = $serializer->serialize($getData, 'json', $context);

    return new JsonResponse($jsonannonces, Response::HTTP_OK, [], true);
  }

  #[Route(path: 'api/annoncesbyposte/{id}', name: "api_annoncesbyposte_id", methods: ['GET'])]
    public function annoncesByPosteId(int $id, AnnonceRepository $annonceRepository): JsonResponse 
    {
        // Retrieve annonces by poste ID using the repository method
        $annonces = $annonceRepository->findByPosteId($id);

        // Serialize the annonces and return the response
        // You can use a serializer or simply return the array as JSON
        $serializedAnnonces = $this->serializeAnnonces($annonces);

        return new JsonResponse($serializedAnnonces, Response::HTTP_OK);
    }

    /**
     * Serializes the given array of annonces.
     *
     * @param array $annonces An array of Annonce entities
     * @return array The serialized array of annonces
     */
    private function serializeAnnonces(array $annonces): array
    {
        $serializedAnnonces = [];

        foreach ($annonces as $annonce) {
            // Serialize each annonce as needed
            $serializedAnnonce = [
                'id' => $annonce->getId(),
                'titre' => $annonce->getTitre(),
                'typecontrat' => $annonce->getTypecontrat(),
                'poste' => $annonce->getPoste()->getLibelle(),
                'ville' => $annonce->getVille(),
                'datedebut' => $annonce->getDatedebut(),
                'datefin' => $annonce->getDatefin(),
                'nombreheures' => $annonce->getNombreheures(),
                'salaire' => $annonce->getSalaire(),
                'description' => $annonce->getDescription(),
                'validation' => $annonce->isValidation(),
                'recruteur' => $annonce->getRecruteur()->getNom(),
                'dateajout' => $annonce->getDateajout(),
                // Add more fields as needed
            ];

            $serializedAnnonces[] = $serializedAnnonce;
        }

        return $serializedAnnonces;
    }
}
