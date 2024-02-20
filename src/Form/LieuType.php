<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Lieu',
                'attr' => [
                    'placeholder' => 'Salle, espace, lieu',
                    'required' => true
                ]
            ])
            ->add('adresse1', TextType::class, [
                'label' => 'Adresse (principale)',
                'attr' => [
                    'required' => true
                ]
            ])
            ->add('adresse2', TextType::class, [
                'label' => 'Adresse (complément)'
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'required' => true
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'required' => true
                ]
            ])
            /*->add('latitude', TextType::class, [
                'label' => 'Latitude',
                'attr' => [
                    'disabled' => true
                ]
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude',
                'attr' => [
                    'disabled' => true
                ]
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
