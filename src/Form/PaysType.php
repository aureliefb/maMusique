<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('pays', CountryType::class, [
                'alpha3' => true,
                'choice_translation_locale' => locale_get_default()
            ])*/
            ->add('pays', TextType::class, [
                'label'=>"Pays",
                'attr' => [
                    'required' => true
                ]
            ])
            ->add('code', TextType::class, [
                'label'=>"Code pays",
                'attr' => [
                    'required' => true,
                    'placeholder' => '5 lettres max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pays::class,
        ]);
    }
}
