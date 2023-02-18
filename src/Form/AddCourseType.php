<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Jeux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;

class AddCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class,[
                'constraints'=>[
                    new Length(['min'=>3,'max'=>255]),
                ],
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Course title here',
                ],
            ])
            ->add('description',TextareaType::class,[
                'constraints'=>[
                    new Length(['min'=>5,'max'=>1000])
                ],
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Course Description here',
                    'class'=> 'edgtf-eh-item-inner',
                    'style' => 'color:white;height:150px;background-color:#22152c;width:100%;border: none;margin:0px 0px 10px;padding:24px 33px'
                ],
            ])
            ->add('picture',FileType::class, [
                'label' => 'image du cours',
                'mapped' => false, //maneha maandi attribut esmo photo fl entity mte3na
                'required' => false,
                'attr'=>[
                    'placeholder' => 'Select a file',
                    'style' => 'color:white;height:65px;background-color:#22152c;width:100%;border: none;margin:0px 0px 10px;padding:24px 33px'

                ]
            ])
            ->add('videoC',FileType::class, [
                'label' => 'video du cours',
                'mapped' => false, //maneha maandi attribut esmo photo fl entity mte3na
                'required' => false,
                'attr'=>[
                    'style' => 'color:white;height:65px;background-color:#22152c;width:100%;border: none;margin:0px 0px 10px;padding:24px 33px'

                ]

            ])


            ->add('prix',IntegerType::class,[
                'constraints'=>[
                    new GreaterThanOrEqual(['value'=>0, 'message' => 'Le prix doit être supérieur à 0']),
                ],
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Course Price here',
                    'style' => 'color:white;height:65px;background-color:#22152c;width:100%;border: none;margin:0px 0px 10px;padding:24px 33px'
                ],
            ])
            ->add('niveau',ChoiceType::class,[
                'choices' => [
                    'Débutant' => 'debutant',
                    'Intermédiaire' => 'intermediaire',
                    'Avancé' => 'avance',
                ],
                'constraints' => [
                    new Choice(['choices' => ['debutant', 'intermediaire', 'avance']]),
                ],
                'required' => true,
                'attr' => [
                    'class'=> 'edgtf-eh-item-inner',
                    'style' => 'color:white;height:65px;background-color:#22152c;width:100%;border: none;margin:0px 0px 10px;padding:24px 33px'
                ],
            ])
            ->add('idJeux', EntityType::class, [
                'required' => true,
                'class' => Jeux::class,
                'choice_label' => 'nom_game',
                'attr' => [
                    'class'=> 'edgtf-eh-item-inner',
                    'style' => 'color:white;height:65px;background-color:#22152c;width:100%;border: none;margin:0px 0px 10px;padding:24px 33px'
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
            'csrf_protection' => false,
        ]);
    }
}
