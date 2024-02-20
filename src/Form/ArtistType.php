<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Pays;
use App\Entity\Style;
use App\Repository\PaysRepository;
use App\Repository\StyleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArtistType extends AbstractType
{
    private $_styleRepo;
    private $_paysRepo;
    public function __construct(StyleRepository $styleRepo, PaysRepository $paysRepo) {
        $this->_styleRepo = $styleRepo;
        $this->_paysRepo = $paysRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom du groupe ou de l\'artiste'
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Nb illimité de caractères',
                    'required' => false,
                    'rows' => 10
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Photo du groupe ou de l\'artiste',
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
            ->add('type', ChoiceType::class, [
                'label'=> 'Type (groupe ou solo)',
                'choices' => [
                    '- Choisir -' => '',
                    'Groupe' => 'Groupe',
                    'Solo' => 'Solo'
                ]
            ])
            ->add('site_web', UrlType::class, [
                'attr' => ['placeholder'=>'www.'],
                'required' => false
            ])
            ->add('style', EntityType::class, [
                'class' => Style::class,
                'choice_label' => 'style',
                'placeholder' => 'Choisir un style'
            ])
            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'choice_label' => 'pays',
                'placeholder' => 'Choisir un pays'
            ])
            //->add('albums')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
