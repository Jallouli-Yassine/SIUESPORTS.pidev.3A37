<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\JeuxRepository;


class NewsType extends AbstractType
{
    private JeuxRepository $jeuxRepository;

    public function __construct(JeuxRepository $jeuxRepository)
    {
        $this->jeuxRepository = $jeuxRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class, [
        'attr' => [
            'class' => 'form-control col-md-5',
            'style' => 'color: white;'
        ]
            ])
            ->add('idJeux', ChoiceType::class, [
                'choices' => $this->jeuxRepository->getJeuxChoices(),
                'choice_label' => 'nomGame',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'color: white;'
                ]
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'color: white;',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary me-2',


                ]
            ])
            ->add('reset', ResetType::class, [
                'attr' => [
                    'class' => 'btn btn-dark',

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
