<?php

namespace App\Form;

use App\Entity\ReviewJeux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewJeuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
            'label' => 'Rating',
            'choices' => [
                '1 star' => '1',
                '2 stars' => '2',
                '3 stars' => '3',
                '4 stars' => '4',
                '5 stars' => '5',
            ],
            'expanded' => true,
            'multiple' => false,
            'required' => true,
            'attr' => [
                'class' => 'star-rating'
            ]


        ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary me-2',


                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReviewJeux::class,
        ]);
    }
}
