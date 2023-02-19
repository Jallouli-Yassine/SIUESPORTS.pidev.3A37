<?php

namespace App\Form;

use App\Entity\CommentaireNews;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentaireNewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('description', TextareaType::class, [
                'label' => 'Tapez votre commentaire ici',
                'attr' => [
                    'class' => 'form-control form-control-lg',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ description ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le commantaire doit comporter au moins comprendre {{ limit }} caractères',
                        'maxMessage' => 'Le commentaire ne doit pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary me-2',
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentaireNews::class,
        ]);
    }
}
