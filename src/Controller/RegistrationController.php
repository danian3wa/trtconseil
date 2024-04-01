<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recruteur;
use App\Entity\Candidat;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
  #[Route('/register', name: 'app_register')]
  public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
  {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // encode the plain password
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );

      $user->setPasswordConfirm(
        $userPasswordHasher->hashPassword(
          $user,
          $form->get('password_confirm')->getData()
        )
      );
      /* ********** ADMIN **************** */
      // Si le nouvel utilisateur est ADMIN
      if ($user->getRole() == "admin_tovalid") {
        // ROLES
        $user->setRoles(['ROLE_ADMIN_TOVALID']);
      }

      $entityManager->persist($user);
      $entityManager->flush();

      // do anything else you need here, like send an email

      /* ********** RECRUTEUR **************** */
      // Si le nouvel utilisateur est un recruteur, alors il faut également créer une nouvelle
      // entité RECRUTEUR
      if ($user->getRole() == "recruteur_tovalid") {

        // ROLES
        $user->setRoles(['ROLE_RECRUTEUR_TOVALID']);

        $recruteur = new Recruteur();
        // On récupère l'id du User
        $user_created = $entityManager->getRepository(User::class)->findOneBy(['role' => 'recruteur_tovalid'], ['id' => 'DESC']);

        // On enregistre le recruteur
        $recruteur->setRecruteurUser($user_created);
        $entityManager->persist($recruteur);
        $entityManager->flush();
      } // FIN si RECRUTEUR


      /* ********** CANDIDAT **************** */
      // Si le nouvel utilisateur est un candidat, alors il faut également créer une nouvelle
      // entité CANDIDAT
      if ($user->getRole() == "candidat_tovalid") {
        // ROLES
        $user->setRoles(['ROLE_CANDIDAT_TOVALID']);

        $candidat = new Candidat();
        // On récupère l'id du User
        $user_created = $entityManager->getRepository(User::class)->findOneBy(['role' => 'candidat_tovalid'], ['id' => 'DESC']);

        // On enregistre le candidat
        $candidat->setCandidatUser($user_created);
        $entityManager->persist($candidat);
        $entityManager->flush();
      } // FIN si CANDIDAT

      return $security->login($user, LoginAuthenticator::class, 'main');
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form,
    ]);
  }
}
