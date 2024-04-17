<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Poste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('q', TextType::class, [
        'label' => false,
        'required' => false,
        'attr' => [
          'placeholder' => 'Rechercher'
        ]
      ])
      ->add('poste', EntityType::class, [
        'label' => false,
        'required' => false,
        'class' => Poste::class,
        'expanded' => false,
        'multiple' => true
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => SearchData::class,
      'method' => 'GET',
      'csrf_protection' => false  // formulaire de recherche, pas de probleme de Cross Scripting
    ]);
  }

  public function getBlockPrefix()
  {
    return '';
  }
}
