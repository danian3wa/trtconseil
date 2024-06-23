<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Entity\Candidat;
use App\Entity\User;
use App\Entity\Recruteur;
use Doctrine\Persistence\ManagerRegistry;

class AnnonceController extends AbstractController
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  #[Route('/annonce/{id}', name: 'app_annonce', requirements: ['id' => '\d+'])]
  public function index(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
    $em = $this->doctrine->getManager();
    $annonce = $em->getRepository(Annonce::class)->findOneBy(['id' => $id]);

    $candidatures = $em->getRepository(Candidature::class)->findBy(['annonce' => $annonce]);
    $candidat = $em->getRepository(Candidat::class)->findOneBy(['candidat_user' => $this->getUser()]);
    return $this->render('annonce/index.html.twig', [
      'annonce' => $annonce,
      'candidatures' => $candidatures,
      'candidat' => $candidat,
    ]);
  }


  #[Route('/annonce/valider/{id}', name: 'annonce_valider', requirements: ['id' => '\d+'])]
  #[Route('/annonce/bloquer/{id}', name: 'annonce_bloquer', requirements: ['id' => '\d+'])]
  public function role($id, Annonce $annonce = null, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_RECRUTEUR');
    $em = $this->doctrine->getManager();

    $validation = false;

    if ($request->attributes->get('_route') === 'annonce_bloquer') {
      $validation = false;
    }

    if ($request->attributes->get('_route') === 'annonce_valider') {
      $validation = true;
    }

    if ($validation !== '') {
      //$consultant = $this->getUser();
      $consultant = $em->getRepository(User::class)->findOneBy(['id' => $id]);
      $annonce->setConsultant($consultant);
      $annonce->setValidation($validation);
      $em->persist($annonce);
      $em->flush();
    }

    return $this->redirectToRoute('annonces');
  } // FIN function VALIDER ou BLOQUER


  #[Route('/annonce/update/{id}', name: 'annonce_update', requirements: ['id' => '\d+'])]
  #[Route('/annonce/create/{recruteur}', name: 'annonce_create', requirements: ['recruteur' => '\d+'])]
  public function edit(Annonce $annonce = null, Request $request, int $recruteur = 0): Response
  {
    $this->denyAccessUnlessGranted('ROLE_RECRUTEUR');
    $em = $this->doctrine->getManager();

    // Savoir si on est en MODIFICATION (edit) ou AJOUT d'un annonce
    $editMode = true;

    if (!$annonce || $request->attributes->get("_route") === "annonce_create") {
      $annonce = new Annonce();


      // TROUVER LE RECRUTEUR qui est connecté !
      $recruteurObjet = $em->getRepository(Recruteur::class)->find($recruteur);
      $annonce->setRecruteur($recruteurObjet);

      $editMode = false;
    }

    //$form = $this->createForm(UserType::class, $annonce);
    $form = $this->createFormBuilder($annonce)
      ->add('titre')
      ->add('typecontrat')
      ->add('poste')
      ->add('ville')
      ->add('datedebut')
      ->add('datefin')
      ->add('nombreheures')
      ->add('salaire')
      ->add('description')
      ->getForm();

    /* , TextareaType::class, [
                        'attr' => array('cols' => '5', 'rows' => '5')]                     */

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Si l'annonce n'existe pas encore, on met une date de création.
      if (!$annonce->getId()) {
        $annonce->setDateajout(new \DateTime());
        $annonce->setValidation(false);
      }

      $em->persist($annonce);
      $em->flush();

      //unset($annonce);
      //unset($recruteurObjet);

      return $this->redirectToRoute('annonces');
      //return $this->render('annonce', ['id' => $recruteur->getId()]);
    }

    return $this->render('annonce/create.html.twig', [
      'formAnnonce' => $form->createView(),
      'editMode' => $editMode
    ]);
  } // FIN function create


  #[Route('/annonce_remove/{id}', name: 'annonce_remove')]
  public function remove(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_RECRUTEUR');
    // Entity manager de Symfony
    $em = $this->doctrine->getManager();
    // On récupère le annonce (User) concerné
    $annonce = $em->getRepository(Annonce::class)->findBy(['id' => $id])[0];

    // 1. Suppression de toutes les candidatures à cette annonce
    $candidatures = $em->getRepository(Candidature::class)->findBy(['annonce' => $annonce]);

    for ($i = 0; $i < count($candidatures); $i++) {
      $em->remove($candidatures[$i]);
      $em->flush();
    }

    // 2. Suppression de l'annonce
    $em->remove($annonce);
    $em->flush();

    return $this->redirectToRoute('annonces');
  }
}

