<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recruteur;
use App\Entity\Candidat;
use App\Entity\Annonce;
use App\Entity\Candidature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class UserFixtures extends Fixture

{
  private $counter = 1;
  public function load(ObjectManager $manager): void
  {
    $admin = new User();
    $admin->setEmail("admin@mail.com");
    $admin->setPassword("$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO");
    $admin->setRoles(['ROLE_ADMIN']);
    $admin->setNom("TRT");
    $admin->setPrenom("Conseil");
    $admin->setRole("admin");
    $manager->persist($admin);
    $this->addReference('admin-' . $this->counter, $admin);
    $this->counter++;

    $consultant = new User();
    $consultant->setEmail("consultant@mail.com");
    $consultant->setPassword("$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO");
    $consultant->setRoles(['ROLE_CONSULTANT']);
    $consultant->setNom("JONES");
    $consultant->setPrenom("Adele");
    $consultant->setRole("consultant");
    $manager->persist($consultant);
    $this->addReference('cons-' . $this->counter, $consultant);
    $this->counter++;

    $user_recruteur = new User();
    $user_recruteur->setEmail("recruteur@mail.com");
    $user_recruteur->setPassword("$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO");
    $user_recruteur->setRoles(['ROLE_RECRUTEUR_TOVALID']);
    $user_recruteur->setNom("MICHELLE");
    $user_recruteur->setPrenom("Eda");
    $user_recruteur->setRole("recruteur_tovalid");
    $manager->persist($user_recruteur);
    $this->addReference('recr-' . $this->counter, $user_recruteur);
    $this->counter++;

    $recruteur = new Recruteur();
    $recruteur->setRecruteurUser($user_recruteur);
    $manager->persist($recruteur);

    $user_candidat = new User();
    $user_candidat->setEmail("candidat@mail.com");
    $user_candidat->setPassword("$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO");
    $user_candidat->setRoles(['ROLE_CANDIDAT_TOVALID']);
    $user_candidat->setNom("MARY");
    $user_candidat->setPrenom("Lea");
    $user_candidat->setRole("candidat_tovalid");
    $manager->persist($user_candidat);
    $this->addReference('user-' . $this->counter, $user_candidat);
    $this->counter++;

    $candidat = new Candidat();
    $candidat->setCandidatUser($user_candidat);
    $manager->persist($candidat);

    $faker = Faker\Factory::create('fr_FR');
    for ($i = 1; $i < 28; $i++) {
      $annonce = new Annonce();
      $annonce->setTitre($faker->sentence);
      $annonce->setTypecontrat("CDI");
      $poste = $this->getReference('post-' .$i);
      $annonce->setPoste($poste);
      $annonce->setVille($faker->city);
      $annonce->setNombreheures($faker->numberBetween(35, 40));
      $datedebut = new \DateTime(); // Current date and time
      $randomDays = mt_rand(1, 100); // Generate a random number of days between 1 and 100
      $datedebut->modify('+' . $randomDays . ' days'); // Add the random number of days
      $annonce->setDatedebut($datedebut);
      $annonce->setDateajout(new \DateTime());
      $annonce->setSalaire($faker->numberBetween(1650, 7000));
      $annonce->setValidation(true);
      $annonce->setDescription($faker->paragraph(10));
      $annonce->setRecruteur($recruteur);
      $annonce->setConsultant($consultant); // Ici, le USER doit être un consultant (voir rôle)
      $manager->persist($annonce);
      $this->addReference('annon-' . $this->counter, $annonce);
      $this->counter++;
    }
    $manager->flush();
  }

  public function getDependencies(): array
  {
    return [
      PosteFixtures::class,
    ];
  }
}
