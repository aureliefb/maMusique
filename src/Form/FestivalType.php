<?php

namespace App\Form;

use App\Entity\Festival;
use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FestivalType extends AbstractType
{
    private $_lieuRepo;

    public function __construct(LieuRepository $lieuRepo) {
        $this->_lieuRepo = $lieuRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_festival', TextType::class, [
                'label' => 'Nom du festival',
                'required' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Affiche du festival',
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
            ->add('site_web', UrlType::class, [
                'attr' => ['placeholder'=>'www.'],
                'required' => false
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                //'choice_label' => 'nom',
                'choice_label' => function($lieu) {
                    return $lieu->getNom() .' ('. $lieu->getVille().')';
                },
                'placeholder' => 'Choisir un lieu',
            ])
            ->add('date_start', TextType::class, [
                'label' => 'Date début',
                'attr' => [
                    'placeholder' => 'aaaa-mm-jj'
                ]
            ])
            ->add('date_end', TextType::class, [
                'label' => 'Date fin',
                'attr' => [
                    'placeholder' => 'aaaa-mm-jj'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
