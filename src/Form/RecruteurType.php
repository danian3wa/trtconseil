<?php

namespace App\Form;

use App\Entity\Recruteur;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RecruteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entreprise_nom')
            ->add('entreprise_adresse')
            ->add('entreprise_code_postal')
            ->add('entreprise_ville')
            //->add('user', UserType::class)
            ->add('consultant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recruteur::class,
            'allow_extra_fields' => true,
            'extra_fields' => 'nom, prenom',
            'allow_add' => true, 
        ]);
    }
}
