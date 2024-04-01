<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
//use Symfony\Bundle\SecurityBundle\Security;

class AdminController extends AbstractController
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  #[Route('/admin/{id}', name: 'app_admin', requirements: ['id' => '\d+'])]
  public function index(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $admin = $this->getUser();
    return $this->render('admin/index.html.twig', [
      'admin' => $admin,
    ]);
  }

  #[Route('/admin/valider/{id}', name: 'admin_valider', requirements: ['id' => '\d+'])]
  #[Route('/admin/bloquer/{id}', name: 'admin_bloquer', requirements: ['id' => '\d+'])]
  public function role(User $admin = null, Request $request): Response
  {
    $em = $this->doctrine->getManager();
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $role = '';
    $roles = '';

    if ($request->attributes->get('_route') === 'admin_bloquer') {
      $role = 'admin_tovalid';
      $roles = ['ROLE_ADMIN_TOVALID'];
    }

    if ($request->attributes->get('_route') === 'admin_valider') {
      $role = 'admin';
      $roles = ['ROLE_ADMIN'];
    }

    if ($role !== '') {
      $admin->setRole($role);
      $admin->setRoles($roles);
      $em->persist($admin);
      $em->flush();
    }

    return $this->redirectToRoute('admins');
  } // FIN function VALIDER ou BLOQUER

  #[Route('/admin/create/', name: 'admin_create')]
  #[Route('/admin/update/{id}/{back}', name: 'admin_update', requirements: ['id' => '\d+'])]
  public function edit(User $admin = null, Request $request, UserPasswordHasherInterface $userPasswordHasher, $back = 'admins'): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    // Savoir si on est en MODIFICATION (edit) ou AJOUT d'un consultant
    $editMode = true;

    if (!$admin) {
      $admin = new User();
      $editMode = false;
    }

    $form = $this->createFormBuilder($admin)
      ->add('nom')
      ->add('prenom')
      ->add('email')
      ->add('password')
      /* ->add('password_confirm') */
      ->add('role')
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      if ($admin->getRole() === 'admin') {
        $admin->setRoles(['ROLE_ADMIN']);
      } else {
        $admin->setRoles(['ROLE_ADMIN_TOVALID']);
      }
      // encodage du password
      $admin->setPassword(
        $userPasswordHasher->hashPassword(
          $admin,
          $form->get('password')->getData()
        )
      );

  /* $admin->setPasswordConfirm(
        $userPasswordHasher->hashPassword(
          $admin,
          $form->get('password_confirm')->getData()
        )
      ); */
      // FIN de l'encodage du PASSWORD    

      $em = $this->doctrine->getManager();
      $em->persist($admin);
      $em->flush();

      return $this->redirectToRoute($back);
    }

    return $this->render('admin/create.html.twig', [
      'formAdmin' => $form->createView(),
      'editMode' => $editMode,
      'back' => $back
    ]);
  } // FIN function create


  #[Route('/admin_remove/{id}', name: 'admin_remove')]
  public function remove(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    // Entity manager de Symfony
    $em = $this->doctrine->getManager();
    // On récupère le consultant (User) concerné
    $admin = $em->getRepository(User::class)->findBy(['id' => $id])[0];

    $em->remove($admin);
    $em->flush();

    return $this->redirectToRoute('admins');
  }
} // FIN de la classe

