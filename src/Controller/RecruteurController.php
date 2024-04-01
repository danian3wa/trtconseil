<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Recruteur;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;


class RecruteurController extends AbstractController
{

  private $doctrine;
  private $formFactory;

  public function __construct(ManagerRegistry $doctrine, FormFactoryInterface $formFactory)
  {
    $this->doctrine = $doctrine;
    $this->formFactory = $formFactory;
  }


  #[Route('/recruteur/{id}', name: 'app_recruteur', requirements: ['id' => '\d+'])]
  public function index(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_RECRUTEUR');
    $em = $this->doctrine->getManager();
    $recruteur = $em->getRepository(Recruteur::class)->findOneBy(['id' => $id]);
    //dd($recruteur);
    return $this->render('recruteur/index.html.twig', [
      'recruteur' => $recruteur,
    ]);
  }


  #[Route('/recruteur/valider/{id}', name: 'recruteur_valider', requirements: ['id' => '\d+'])]
  #[Route('/recruteur/bloquer/{id}', name: 'recruteur_bloquer', requirements: ['id' => '\d+'])]
  public function role($id, Recruteur $recruteur = null, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    $em = $this->doctrine->getManager();
    //$user = new User();
    //$user = $em->getRepository(User::class)->findOneBy(['id'=>$recruteur->getUser()->getId()]);

    $role = '';
    $roles = '';

    if ($request->attributes->get('_route') === 'recruteur_bloquer') {
      $role = 'recruteur_tovalid';
      $roles = ['ROLE_RECRUTEUR_TOVALID'];
    }

    if ($request->attributes->get('_route') === 'recruteur_valider') {
      $role = 'recruteur';
      $roles = ['ROLE_RECRUTEUR'];
    }

    if ($role !== '') {
      //$consultant = $this->getUser();
      $consultant = $em->getRepository(User::class)->findOneBy(['id' => $id]);
      $recruteur->setConsultant($consultant);
      $recruteur->getRecruteurUser()->setRole($role);
      $recruteur->getRecruteurUser()->setRoles($roles);
      $em->persist($recruteur);
      $em->flush();
    }

    return $this->redirectToRoute('recruteurs');
  } // FIN function VALIDER ou BLOQUER


  #[Route('/recruteur/update/{id}/{back}', name: 'recruteur_update', requirements: ['id' => '\d+'])]
  #[Route('/recruteur/create/', name: 'recruteur_create')]
  public function edit(Recruteur $recruteur = null, User $user = null, UserPasswordHasherInterface $userPasswordHasher, Request $request, $back = 'recruteurs'): Response
  {
    $this->denyAccessUnlessGranted('ROLE_RECRUTEUR');
    $em = $this->doctrine->getManager();
    $user = $this->getUser();
    // Savoir si on est en MODIFICATION (edit) ou AJOUT d'un recruteur
    $editMode = true;
    // dd($recruteur);
    if (!$recruteur) {
      //$recruteur = new Recruteur();
      $editMode = false;
    }
    //dd($user);
    if (!$user) {
      $user = $em->getRepository(User::class)->findOneBy(['id' => $recruteur->getRecruteurUser()->getId()]);
      $editMode = false;
    }
    /*
    if ($request->request->get('formUser')) {
      $user->setNom($request->request->get('formUser')['nom']);
      $user->setPrenom($request->request->get('formUser')['prenom']);
      $user->setEmail($request->request->get('formUser')['email']);
      $user->setPassword($request->request->get('formUser')['password']);
      $user->setRole($request->request->get('formUser')['role']);
    }*/
    $formUserData = $request->request->get('formUser');
    if (isset($formUserData['nom'])) {
      $user->setNom($formUserData['nom']);
    }
    if (isset($formUserData['prenom'])) {
      $user->setPrenom($formUserData['prenom']);
    }
    if (isset($formUserData['email'])) {
      $user->setEmail($formUserData['email']);
    }
    if (isset($formUserData['password'])) {
      $user->setPassword($formUserData['password']);
    }
    if (isset($formUserData['role'])) {
      $user->setRole($formUserData['role']);
    }


    $recruteur->setRecruteurUser($user);

    // Champs du formulaire, partie USER
    $formUser = $this->createForm(UserType::class, $user);
    // var_dump($formUser); 

    $formUser = $this->formFactory->createNamedBuilder('formUser', UserType::class, $user)
      //$formUser = $this->formFactory->createNamed('formUser', UserType::class, $user) 
      /*$formUser = $this->createFormBuilder($user)
      ->add('nom')
      ->add('prenom')
      ->add('email')
      ->add('password')
      ->add('role')*/
      ->getForm();



    // Champs du formulaire RECRUTEUR
    //$form = $this->createForm(RecruteurType::class, $recruteur); 
    $form = $this->createFormBuilder($recruteur)
      ->add('nom')
      ->add('adresse')
      ->add('code_postal')
      ->add('ville')
      //->add('recruteuruser')
      ->getForm();

    $form->handleRequest($request);
    $formUser->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {


      $em->persist($recruteur);
      $em->flush();

      return $this->redirectToRoute($back);
      //return $this->render('recruteur', ['id' => $recruteur->getId()]);
    }

    if ($formUser->isSubmitted() && $formUser->isValid()) {
      //var_dump("valide user");

      // SET ROLES []
      if ($user->getRole() === 'recruteur') {
        $user->setRoles(['ROLE_RECRUTEUR']);
      } else {
        $user->setRoles(['ROLE_RECRUTEUR_TOVALID']);
      } // Fin SET ROLES []

      // encodage du password
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $formUser->get('password')->getData()
        )
      );


      $em->persist($user);
      $em->flush();

      return $this->redirectToRoute($back);
      //return $this->render('recruteur', ['id' => $recruteur->getId()]);
    } else if (!$formUser->isSubmitted()) {

      //var_dump("pas soumis");
    }



    return $this->render('recruteur/create.html.twig', [
      'formRecruteur' => $form->createView(),
      'formUser' => $formUser->createView(),
      'editMode' => $editMode,
      'back' => $back
    ]);
  } // FIN function create


  #[Route('/recruteur_remove/{id}', name: 'recruteur_remove')]
  public function remove(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    // Entity manager de Symfony
    $em = $this->doctrine->getManager();
    // 1. On récupère le recruteur (User) concerné
    $recruteur = $em->getRepository(Recruteur::class)->findBy(['id' => $id])[0];

    // 2. On récupère toutes les annonces du recruteur
    $annonces = $em->getRepository(Annonce::class)->findBy(['recruteur' => $recruteur]);

    for ($i = 0; $i < count($annonces); $i++) {

      // 3. On récupère toutes les candidatures à ces annonces
      $candidatures = $em->getRepository(Candidature::class)->findBy(['annonce' => $annonces[$i]]);
      for ($j = 0; $j < count($candidatures); $j++) {
        $em->remove($candidatures[$j]);
        $em->flush();
      }
      $candidatures = array();

      $em->remove($annonces[$i]);
      $em->flush();
    } // FIN du for i

    // 4. Suppression du recruteur
    $em->remove($recruteur);
    $em->flush();
    $em->remove($recruteur->getUser());
    $em->flush();

    return $this->redirectToRoute('recruteurs');
  }
}
