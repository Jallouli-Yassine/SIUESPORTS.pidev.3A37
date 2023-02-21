<?php

namespace App\Form;

use App\Entity\Classement;
use App\Entity\Team;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('idTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'nom_team',
                'choice_value' => 'id',
                'multiple' => false,
                'expanded' => false,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Rejoindre le tournoi',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classement::class,
            'idTournois' => null,
        ]);
    }
}
