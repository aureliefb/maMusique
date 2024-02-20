<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Concert;
use App\Entity\Lieu;
use App\Entity\Festival;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Image du concert',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Format incorrect'
                    ])
                ]
            ])
            ->add('date_concert', TextType::class, [
                'label' => 'Date'
            ])
            ->add('is_festival', ChoiceType::class, [
                'label'=> 'Festival ?',
                'choices' => [
                    'Choisir' => '',
                    'Oui' => 1,
                    'Non' => 0
                ]
            ])
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un artiste',
                'required' => true
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => function($lieu) {
                    return $lieu->getNom() .' ('. $lieu->getVille().')';
                },
                'placeholder' => 'Choisir un lieu',
                'required' => true
            ])
            ->add('Festival', EntityType::class, [
                'class' => Festival::class,
                //'choice_label' => 'nom_festival',
                'choice_label' => function($festival) {
                    return $festival->getNomFestival();
                },
                'placeholder' => 'Choisir un festival',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }
}
