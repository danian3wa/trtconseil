<?php

namespace App\DataFixtures;

use App\Entity\Poste;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PosteFixtures extends Fixture
{
  private $counter = 1;

  public function load(ObjectManager $manager): void
  {
    $postes = [
      'Barman',
      'Barmaid',
      'Cuisinier',
      'Cuisinière',
      'Consultant',
      'Consultante',
      'Directeur de restaurant',
      'Directrice de restaurant',
      'Directeur d\'hôtel',
      'Directrice d\'hôtel',
      'Employé de restaurant',
      'Employée de restaurant',
      'Femme de chambre',
      'Valet de chambre',
      'Garçon de café',
      'Serveuse',
      'Gérant de restauration collective',
      'Gérante de restauration collective',
      'Gouvernant',
      'Gouvernante',
      'Maître d\'hôtel',
      'Maîtresse d\'hôtel',
      'Pâtissier',
      'Pâtissière',
      'Personnel de hall d\'hôtel de luxe',
      'Réceptionniste',
      'Sommelier',
      'Sommelière',
    ];

    foreach ($postes as $posteNom) {

      $poste = new Poste();
      $poste->setLibelle($posteNom);
      $manager->persist($poste);

      $this->addReference('post-' . $this->counter, $poste);
      //var_dump("Reference from Poste: post-".$this->counter);
      $this->counter++;
    }

    $manager->flush();
  }
}
