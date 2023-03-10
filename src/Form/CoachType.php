<?php

namespace App\Form;

use App\Entity\Coach;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('prenom',null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prenom',
                ],
            ])
            ->add('photo_url',null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Photo',
                ],
            ])
            ->add('email',null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Mail',
                ],
            ])
            ->add('password',PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Password',
                ],
            ])
            ->add('date_naissance',DateType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Date',
                ],
            ])
            ->add('prix_heure',null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'prix',
                ],
            ])
            ->add('Enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coach::class,
        ]);
    }
}
