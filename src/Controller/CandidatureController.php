<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Candidature;
use App\Entity\Candidat;
use App\Entity\Annonce;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class CandidatureController extends AbstractController
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  #[Route('/candidature/{id}', name: 'candidature', requirements: ['id' => '\d+'])]
  public function index(int $id): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    $em = $this->doctrine->getManager();

    $candidature = $em->getRepository(Candidature::class)->findOneBy(['id' => $id]);

    $candidatId = $candidature->getCandidat();
    $annonceId = $candidature->getAnnonce();
    $annonce = $em->getRepository(Annonce::class)->findOneBy(['id' => $annonceId]);
    //dd($annonce);
    /* Ajout d'un candidat fictif pour pouvoir tester le bouton POSTULER A UNE ANNONCE */
    $candidat = $em->getRepository(Candidat::class)->findOneBy(['id' => $candidatId]);
    //dd($candidat);
    return $this->render('candidature/index.html.twig', [
      'annonce' => $annonce,
      /* 'recruteur' => $recruteur, */
      'candidature' => $candidature,
      'candidat' => $candidat,
    ]);
  }

  #[Route('/candidatures', name: 'candidatures')]
  public function allCandidatures(): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    $em = $this->doctrine->getManager();
    $consultant = $this->getUser();

    $candidatures = $em->getRepository(Candidature::class)->findAll();
    $candidat = $em->getRepository(Candidat::class)->findAll();
    //dd($candidatures);


    $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
    return $this->render('candidature/all.html.twig', [
      'controller_name' => 'CandidatureController',
      'candidatures' => $candidatures,
      'candidat' => $candidat,
    ]);
  }

  #[Route('/candidature/valider/{id}', name: 'candidature_valider', requirements: ['id' => '\d+'])]
  #[Route('/candidature/bloquer/{id}', name: 'candidature_bloquer', requirements: ['id' => '\d+'])]
  public function etat($id, Candidature $candidature = null, MailerInterface $mailer, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CONSULTANT');
    $em = $this->doctrine->getManager();
    $etat = '';

    if ($request->attributes->get('_route') === 'candidature_bloquer') {
      $etat = 'tovalid';
    }

    if ($request->attributes->get('_route') === 'candidature_valider') {
      $etat = 'valid';

      $candidature = $em->getRepository(Candidature::class)->findOneBy(['id' => $id]);
      $annonceObjet = $candidature->getAnnonce();
      $candidatObjet = $candidature->getCandidat();
      $cv = $candidatObjet->getCV();
      $mailRecruteur = $annonceObjet->getRecruteur()->getRecruteurUser()->getEmail();
      $recruterName = $annonceObjet->getRecruteur()->getNom();
      $nom = $candidatObjet->getCandidatUser()->getNom();
      $prenom = $candidatObjet->getCandidatUser()->getPrenom();
      if ($cv !== "") {
        // Create the email
        $email = (new Email())
          ->priority(Email::PRIORITY_HIGH)
          ->from('trtconseil@technidan.com')
          ->to($mailRecruteur)
          ->subject('Candidature à une annonce')
          ->text('Votre annonce a un nouveau candidat, il s\'agit de ' . $nom . ' ' . $prenom . '.');

        // Embed CV attachment

        $cvPath = $this->getParameter('uploads_cv') . '/' . $cv;
        //if (file_exists($cvPath)) {
        $attachment = (new File($cvPath));
        $email->addPart(new DataPart($attachment));


        try {
          // Send the email
          $mailer->send($email);
          //dd($mailer);
          $this->addFlash('success', 'Un email a été envoyé au recruteur.');
        } catch (TransportExceptionInterface $e) {
          $this->addFlash('error', 'There was a problem sending the email. Please try again later.');
          echo "Message could not be sent. Mailer Error: " . $e->getMessage();
        }

        $_SESSION["resultat_mail"] = "Envoie du mail au recruteur : " . $recruterName;
      } else {
        // Handle case where CV file is not found
        $etat = 'tovalid';
        $this->addFlash('warning', 'Le CV du candidat n\'a pas été trouvé. Merci de contacter le candidat ' . $nom . ' ' . $prenom . '.');
        $this->addFlash('warning', 'Email non envoyé au recruteur : ' . $recruterName);
        //$_SESSION["resultat_mail"] = "Email non envoyé au recruteur : " . $recruterName;
      }
    }

    if ($etat !== '') {
      $consultant = $this->getUser();
      $candidature->setConsultantApproval($consultant);
      $candidature->setEtat($etat);
      try {
        $em->persist($candidature);
        $em->flush();
      } catch (\Doctrine\DBAL\Exception $e) {
        // Handle exception
        $this->addFlash('error', 'There was a problem saving the data. Please try again later.');
      }
    }

    return $this->redirectToRoute('candidatures');
  } // FIN function VALIDER ou BLOQUER

  #[Route('/candidature/create/{annonce}/{candidat}', name: 'candidature_create', requirements: ['annonce' => '\d+', 'candidat' => '\d+'])]
  public function createOnly(int $annonce, int $candidat): Response
  {
    $this->denyAccessUnlessGranted('ROLE_CANDIDAT');
    $candidature = new Candidature();

    $em = $this->doctrine->getManager();

    $annonceObjet = $em->getRepository(Annonce::class)->find($annonce);

    // TROUVER LE CANDIDAT qui est connecté !
    $candidatObjet = $em->getRepository(Candidat::class)->find($candidat);

    $candidature->setAnnonce($annonceObjet);
    $candidature->setCandidat($candidatObjet);
    $candidature->setEtat('tovalid');
    $em->persist($candidature);
    $em->flush();
    return $this->redirectToRoute('annonces');
  } // FIN function create
}
