<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class, ['attr' => ['placeholder' => 'nom@email.com']])
      ->add('role', ChoiceType::class, [
        'choices' => [
          'candidat' => 'candidat_tovalid',
          'recruteur' => 'recruteur_tovalid',
        ]
      ])
      ->add('nom', TextType::class, ['attr' => ['placeholder' => 'nom']])
      ->add('prenom', TextType::class, ['attr' => ['placeholder' => 'prénom']])

      ->add('agreeTerms', CheckboxType::class, [
        'mapped' => false,
        'constraints' => [
          new IsTrue([
            'message' => 'Vous devez accepter nos Conditions Générales d\'Utilisation.',
          ]),
        ],
      ])
      ->add('plainPassword', PasswordType::class, [
        // instead of being set onto the object directly,
        // this is read and encoded in the controller
        'mapped' => false,
        'attr' => [
          'label' => "Votr mot de passe",
          'autocomplete' => 'new-password',
          'placeholder' => 'minimum 8 caractères'
        ],
        'constraints' => [
          new NotBlank([
            'message' => 'Please enter a password',
          ]),
          new Length([
            'min' => 8,
            'minMessage' => 'Your password should be at least {{ limit }} characters',
            // max length allowed by Symfony for security reasons
            'max' => 4096,
          ]),
        ],
      ])
      ->add('password_confirm', PasswordType::class, [
        // instead of being set onto the object directly,
        // this is read and encoded in the controller
        'mapped' => false,
        'attr' => [
          'autocomplete' => 'new-password',
          'placeholder' => 'minimum 8 caractères'
        ],
        'constraints' => [
          new NotBlank([
            'message' => 'Entrez un mot de passe',
          ]),
          new Length([
            'min' => 8,
            'minMessage' => 'Your password should be at least {{ limit }} characters',
            // max length allowed by Symfony for security reasons
            'max' => 4096,
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
