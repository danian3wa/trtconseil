<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ConsultantController extends AbstractController
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }
 
  #[Route('/consultant/{id}', name: 'app_consultant', requirements: ['id' => '\d+'])]
  public function index(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $em = $this->doctrine->getManager();
    $consultant = $em->getRepository(User::class)->findOneBy(['id' => $id, 'role' => 'consultant']);

    return $this->render('consultant/index.html.twig', [
      'consultant' => $consultant,
    ]);
  }


  #[Route('/consultant/create/', name: 'consultant_create')]
  #[Route('/consultant/update/{id}/{back}', name: 'consultant_update', requirements: ['id' => '\d+'])]
  public function edit(User $consultant = null, UserPasswordHasherInterface $userPasswordHasher, Request $request, $back = 'consultants'): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    // Savoir si on est en MODIFICATION (edit) ou AJOUT d'un consultant
    $editMode = true;

    if (!$consultant) {
      $consultant = new User();
      $editMode = false;
      $consultant->setRoles(['ROLE_CONSULTANT']);
    }

    $form = $this->createFormBuilder($consultant)
      ->add('nom')
      ->add('prenom')
      ->add('email')
      ->add('password')
      /* ->add('password_confirm') */
      ->add('role')
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      // SET ROLES []
      if ($consultant->getRole() === 'consultant') {
        $consultant->setRoles(['ROLE_CONSULTANT']);
      } else {
        $consultant->setRoles(['ROLE_CONSULTANT_TOVALID']);
      } // Fin SET ROLES []
      // encodage du password
      $consultant->setPassword(
        $userPasswordHasher->hashPassword(
          $consultant,
          $form->get('password')->getData()
        )
      );

      /* $consultant->setPasswordConfirm(
          $userPasswordHasher->hashPassword(
            $consultant,
              $form->get('password_confirm')->getData()
          )
        ); */
      // FIN de l'encodage du PASSWORD    


      $em = $this->doctrine->getManager();
      $em->persist($consultant);
      $em->flush();

      return $this->redirectToRoute($back);
      //return $this->render('consultant', ['id' => $consultant->getId()]);
    }

    return $this->render('consultant/create.html.twig', [
      'formConsultant' => $form->createView(),
      'editMode' => $editMode,
      'back' => $back
    ]);
  } // FIN function create

  
  #[Route('/consultant_remove/{id}', name: 'consultant_remove')]
  public function remove(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    // Entity manager de Symfony
    $em = $this->doctrine->getManager();
    // On récupère le consultant (User) concerné
    $consultant = $em->getRepository(User::class)->findBy(['id' => $id])[0];

    // Suppression de l'arbre
    $em->remove($consultant);
    $em->flush();

    return $this->redirectToRoute('consultants');
  }
} // FIN de la classe
