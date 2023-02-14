<?php

namespace App\Form;

use App\Entity\Coach;
use App\Entity\Cours;
use App\Entity\Jeux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,[
                'attr' => [
                    'placeholder'=>'Enter the title of the course.'
                ]
            ])
            ->add('description')
            ->add('video')
            ->add('image')
            ->add('prix')
            ->add('niveau')
            ->add('idCoach', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'id',
                'label' => 'Coach'
            ])
            ->add('idJeux', EntityType::class, [
                'class' => Jeux::class,
                'choice_label' => 'nom_game',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
