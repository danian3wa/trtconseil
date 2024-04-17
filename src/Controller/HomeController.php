<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AnnonceRepository;
use App\Data\SearchData;
use App\Form\SearchForm;
use App\Entity\User;
use App\Entity\Recruteur;
use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Entity\Annonce;

class HomeController extends AbstractController
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  #[Route('/', name: 'home')]
  public function index(AnnonceRepository $annonceRepository, Request $request): Response
  {
    $em = $this->doctrine->getManager();
    $connectedUser = $this->getUser();
    if ($connectedUser !== null) {
      $user = $em->getRepository(User::class)->find($connectedUser);
    } else {
      $user = null;
    }
    //$user = $em->getRepository(User::class)->find($this->getUser());
    $candidat = 0;
    if ($user !== null) {
      if ($user->getRole() === 'candidat') {
        $candidat = $em->getRepository(Candidat::class)->findBy(['candidat_user' => $user])[0];
      }
    }
    $data = new SearchData();
    $data->page = $request->get('page', 1);
    $form = $this->createForm(SearchForm::class, $data);
    $form->handleRequest($request);
    $annonces = $annonceRepository->findSearch($data);
    
    //$liste = $em->getRepository(Annonce::class)->findAll();
    if ($request->get('ajax')) {
      //dd($annonces);
      //dd($form);
      return new JsonResponse([
        'content' => $this->renderView('home/_annonces.html.twig', ['annonces' => $annonces, 'candidat' => $candidat]),
        'pagination' => $this->renderView('home/_pagination.html.twig', ['annonces' => $annonces, 'candidat' => $candidat]),
      ]);
    }
    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
      'user' => $user,
      'annonces' => $annonces,
      'form' => $form->createView(),
      'candidat' => $candidat
    ]);
  }

  #[Route('/admins', name: 'admins')]
  public function allAdmins(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    // On récupère l'Entity Manager de Symfony
    $em = $this->doctrine->getManager();
    $adminsValidated = $em->getRepository(User::class)->findBy(['role' => 'admin']);
    $adminsLocked = $em->getRepository(User::class)->findBy(['role' => 'admin_tovalid']);

    return $this->render('admin/all.html.twig', [
      'admins' => $adminsValidated,
      'admins_locked' => $adminsLocked
    ]);
  }

  #[Route('/consultants', name: 'consultants')]
  public function allConsultants(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    // On récupère l'Entity Manager de Symfony
    $em = $this->doctrine->getManager();
    $consultants = $em->getRepository(User::class)->findBy(['role' => 'consultant']);

    return $this->render('consultant/all.html.twig', [
      'consultants' => $consultants,
    ]);
  }

  #[Route('/recruteurs', name: 'recruteurs')]
  public function allRecruteurs(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_RECRUTEUR');
    // On récupère l'Entity Manager de Symfony
    $em = $this->doctrine->getManager();

    $liste = $em->getRepository(Recruteur::class)->findAll();
    //dd($liste);
    return $this->render('recruteur/all.html.twig', [
      'recruteurs' => $liste
    ]);
  }

  #[Route('/candidats', name: 'candidats')]
  public function allCandidats(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    // On récupère l'Entity Manager de Symfony
    $em = $this->doctrine->getManager();

    $liste = $em->getRepository(Candidat::class)->findAll();
    //dd($liste);
    return $this->render('candidat/all.html.twig', [
      'candidats' => $liste
    ]);
  }

  #[Route('/annonces', name: 'annonces')]
  public function allAnnonces(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
    // On récupère l'Entity Manager de Symfony
    $em = $this->doctrine->getManager();
    $resultatMail = isset($_SESSION["resultat_mail"]) ? $_SESSION["resultat_mail"] : "";
    //dd($resultatMail);
    //dd($_SESSION["resultat_mail"]);
    //dd(isset($_SESSION["resultat_mail"]));
    // Si le USER est RECRUTEUR, on n'affiche que SES annonces
    // Si le USER est CANDIDAT, on n'affiche que les annonces VALIDES et on indique celles où il a déjà postulé
    $recruteurId = 0;
    $candidatId = 0;
    $dejaPostule = [];
    $connectedUser = $this->getUser();
    if ($connectedUser !== null) {
      $user = $em->getRepository(User::class)->find($connectedUser);
    } else {
      $user = null;
    }
    //$user = $em->getRepository(User::class)->find($this->getUser());
    $userRole = $user->getRole();
    /*if ($this->security->getUser()->getRole() === 'recruteur') {*/
    if ($userRole === 'recruteur') {
      $recruteur = $em->getRepository(Recruteur::class)->findOneBy(['recruteur_user' => $this->getUser()]);
      $recruteurId = $recruteur->getId();
      $liste = $em->getRepository(Annonce::class)->findBy(['recruteur' => $recruteur->getId()]);
    } elseif ($userRole === 'candidat') {
      $liste = $em->getRepository(Annonce::class)->findBy(['validation' => '1']);
      $candidat = $em->getRepository(Candidat::class)->findOneBy(['candidat_user' => $this->getUser()]);
      $candidatId = $candidat->getId();
      $dejaPostuleObjects = $em->getRepository(Candidature::class)->findBy(['candidat' => $candidat]);

      for ($dj = 0; $dj < count($dejaPostuleObjects); $dj++) {
        array_push($dejaPostule, $dejaPostuleObjects[$dj]->getAnnonce()->getId());
      }

      //$dejaPostule = $dejaPostuleArray[0]->getAnnonce()->getId();

    } else {
      $liste = $em->getRepository(Annonce::class)->findAll();
    }
    return $this->render('annonce/all.html.twig', [
      'annonces' => $liste,
      'mailok' => $resultatMail,
      'id_recruteur' => $recruteurId,
      'id_candidat' => $candidatId,
      'deja_postule' => $dejaPostule
    ]);
  }
}
