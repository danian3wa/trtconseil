<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add(
        'email',
        EmailType::class,
        [
          'label' => 'Email*',
          'constraints' => [
            new NotBlank([
              'message' => 'Ce champ ne peut être vide'
            ])
          ]
        ]
      )
      /* ->add('roles') */
      ->add('password')
      ->add('nom')
      ->add('prenom')
      ->add('role');
    /* } */
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
      'allow_extra_fields' => true,
    ]);
  }
}
