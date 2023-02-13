<?php

namespace App\Form;

use App\Entity\CommentaireNews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireNewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'rows' => 4,
                    'placeholder' => 'Your Message',
                    'required' => true
                ]
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Postez le commentaire',
                'attr' => [
                    'class' => 'btn btn-lg btn-warning'
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentaireNews::class,
        ]);
    }
}
