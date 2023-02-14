<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('description')
            ->add('image')
            ->add('idCategorie',EntityType::class,[  //foreign key
                'class' => Categorie::class,
                'choice_label'=>'nom',
                'multiple'=>False,
                'expanded'=>False,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
