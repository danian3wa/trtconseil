<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\Recruteur;
use App\Entity\Candidat;

class CompteController extends AbstractController
{
  private $doctrine;
 

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  #[Route('/compte', name: 'compte')]
  public function index(): Response
  {
    $message='';

    $em = $this->doctrine->getManager();
    $user = $em->getRepository(User::class)->find($this->getUser());
    //$user = $this->getUser();

    //$userRole = $user->getRole();
    //dd($user);
    if ($user !== null) {
      if ($user->getRole() === 'admin' || $user->getRole() === 'admin_tovalid') {
        return $this->render('admin/index.html.twig', [
          'admin' => $user,
          'back' => 'compte'
        ]);
      } else if ($user->getRole() === 'consultant' || $user->getRole() === 'ROLE_CONSULTANT_TOVALID') {
        return $this->render('consultant/index.html.twig', [
          'consultant' => $user,
          'back' => 'compte'
        ]);
      } else if ($user->getRole() === 'recruteur' || $user->getRole() === 'recruteur_tovalid') {
        $recruteur = $em->getRepository(Recruteur::class)->findBy(['recruteur_user' => $user])[0];
        //$recruteur = $em->getRepository(Recruteur::class)->findOneBy(['recruteur_user' => $user]);
        return $this->render('recruteur/index.html.twig', [
          'recruteur' => $recruteur,
          'back' => 'compte'
        ]);
      } else if ($user->getRole() === 'candidat' || $user->getRole() === 'candidat_tovalid') {
        $candidat = $em->getRepository(Candidat::class)->findBy(['candidat_user' => $user])[0];
        
        //$candidat = $em->getRepository(Candidat::class)->findOneBy(['candidat_user' => $user]);
        //dd($candidat);
        return $this->render('candidat/index.html.twig', [ //candidat/index.html.twig
          'candidat' => $candidat,
          'back' => 'compte'
        ]);
      }
    } else {
      $message = "Vous devez vous connecter pour accéder à votre compte utilisateur.";
    }
    return $this->render('compte/index.html.twig', [
      'message' => $message,
    ]);
  
  }
}
