<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\Top50;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Top50Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('artiste', TextType::class, [
                'label' => 'Artiste / groupe',
                'required' => true
            ])
            ->add('annee', NumberType::class, [
                'label' => 'Année',
                'required' => false
            ])
            ->add('style', EntityType::class, [
                'class' => Style::class,
                'choice_label' => 'style',
                'placeholder' => 'Choisir un style'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Top50::class,
        ]);
    }
}
