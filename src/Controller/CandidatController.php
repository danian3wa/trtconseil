<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Candidat;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CandidatController extends AbstractController
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }


  #[Route('/candidat/{id}', name: 'app_candidat', requirements: ['id' => '\d+'])]
  #[Route('/candidat/{id}/{annonce}', name: 'app_candidat_annonce', requirements: ['id' => '\d+'])]
  public function index(int $id, int $annonce = null): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
    $em = $this->doctrine->getManager();
    $candidat = $em->getRepository(Candidat::class)->findOneBy(['id' => $id]);
    //$cv = $candidat->getCv();
    // dd($cv);
    return $this->render('candidat/index.html.twig', [
      'candidat' => $candidat,
      'annonce' => $annonce
    ]);
  }


  #[Route('/candidat/valider/{id}', name: 'candidat_valider', requirements: ['id' => '\d+'])]
  #[Route('/candidat/bloquer/{id}', name: 'candidat_bloquer', requirements: ['id' => '\d+'])]
  public function role($id, ?Candidat $candidat = null, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    $em = $this->doctrine->getManager();
    $role = '';
    $roles = '';

    if ($request->attributes->get('_route') === 'candidat_bloquer') {
      $role = 'candidat_tovalid';
      $roles = ['ROLE_CANDIDAT_TOVALID'];
    }

    if ($request->attributes->get('_route') === 'candidat_valider') {
      $role = 'candidat';
      $roles = ['ROLE_CANDIDAT'];
    }

    if ($role !== '') {
      //$consultant = $this->getUser();
      $consultant = $em->getRepository(User::class)->findOneBy(['id' => $id]);
      $candidat->setConsultant($consultant);
      $candidat->getCandidatUser()->setRole($role);
      $candidat->getCandidatUser()->setRoles($roles);
      $em->persist($candidat);
      $em->flush();
    }

    return $this->redirectToRoute('candidats');
  } // FIN function VALIDER ou BLOQUER


  #[Route('/candidat/update/{id}/{back}', name: 'candidat_update', requirements: ['id' => '\d+'])]
  #[Route('/candidat/create/', name: 'candidat_create')]
  public function edit($id, ?Candidat $candidat = null, User $user = null, UserPasswordHasherInterface $userPasswordHasher, Request $request, $back = 'candidats'): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
    $em = $this->doctrine->getManager();

    //$user = $this->getUser();
    $user = $em->getRepository(User::class)->findOneBy(['id' => $id]);
    //dd($user);
    if ($user->getRole() === 'candidat' || $user->getRole() === 'candidat_tovalid') {
      $candidat = $em->getRepository(Candidat::class)->findBy(['candidat_user' => $user])[0];
    }
    //dd($candidat);
    // Savoir si on est en MODIFICATION (edit) ou AJOUT d'un candidat
    $editMode = true;

    if (!$candidat) {
      //$candidat = new Candidat();
      $editMode = false;
    }

    if (!$user) {
      $user = $em->getRepository(User::class)->findOneBy(['id' => $candidat->getCandidatUser()->getId()]);

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


    $candidat->setCandidatUser($user);

    // Concaténation du chemin de téléchargement configuré avec le nom de fichier stocké et création d'une nouvelle classe File.
    if ($candidat->getCv() !== null) {

      $candidat->setCv(
        /* new File($this->getParameter('uploads_cv').'/'.$candidat->getCv()) */
        ($candidat->getCv())
      );
    }


    // Champs du formulaire, partie USER
    $formUser = $this->createForm(UserType::class, $user);
    /* var_dump($formUser); */

    //$formUser = $this->get('form.factory')->createNamedBuilder('formUser', UserType::class, $user)->getForm();

    // Champs du formulaire CANDIDAT (CV uniquement)
    $form = $this->createFormBuilder($candidat)
      ->add('cv', FileType::class, array('data_class' => null))
      ->getForm();

    $form->handleRequest($request);
    $formUser->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Traitement du fichier PDF du CV

      // Récupération des données du formulaire
      $file = $form['cv']->getData();

      if ($file && $file->guessExtension() === 'pdf') {

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //dd($originalFilename);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        //$safeFilename = "";
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        //dd($newFilename);
        // Move the file to the directory where CV are stored
        try {
          $file->move($this->getParameter('uploads_cv'), $newFilename);
          //$file->move_uploaded_file($_FILES['file']['tmp_name'], 'uploads_cv' . $newFilename);
        } catch (FileException $e) {
          // ... handle exception if something happens during file upload
          echo "File could not be upload. Error: " . $e->getMessage();
        }

        // updates the 'cvFilename' property to store the PDF file name
        // instead of its contents
        $candidat->setCv($newFilename);

        $em->persist($candidat);
        $em->flush();
      } else {
        echo "File could not be upload.";
        $this->addFlash('warning', 'Le fichier n\'a pas été enregistré.');
        return $this->redirectToRoute('compte');
      } // FIN du IF($file)


      return $this->redirectToRoute($back);
      //return $this->render('candidat', ['id' => $candidat->getId()]);
    } // FIN du IF formulaire CV validé

    if ($formUser->isSubmitted() && $formUser->isValid()) {
      //var_dump("valide user");

      // SET ROLES []
      if ($user->getRole() === 'candidat') {
        $user->setRoles(['ROLE_CANDIDAT']);
      } else {
        $user->setRoles(['ROLE_CANDIDAT_TOVALID']);
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
      //return $this->render('candidat', ['id' => $candidat->getId()]);
    } else if (!$formUser->isSubmitted()) {
      //var_dump("pas soumis");
    }

    return $this->render('candidat/create.html.twig', [
      'formCandidat' => $form->createView(),
      'formUser' => $formUser->createView(),
      'editMode' => $editMode,
      'back' => $back
    ]);
  } // FIN function EDIT


  #[Route('/candidat_remove/{id}', name: 'candidat_remove')]
  public function remove(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    // Entity manager de Symfony
    $em = $this->doctrine->getManager();
    // On récupère le candidat (User) concerné
    $candidat = $em->getRepository(Candidat::class)->findBy(['id' => $id])[0];

    // Suppression de l'arbre
    $em->remove($candidat);
    $em->flush();
    $em->remove($candidat->getUser());
    $em->flush();

    return $this->redirectToRoute('candidats');
  }
}
