<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Annonces;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Précisez votre recherche'
                ]
            ])
            ->add('Region', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    'Toutes les régions'          => 'Toutes les regions',
                    'Auvergne-Rhône-Alpes'        => 'Auvergne-Rhone-Alpes',
                    'Bourgogne-Franche-Comté'     => 'Bourgogne-Franche-Comte',
                    'Bretagne'                    => 'Bretagne',
                    'Centre-Val de Loire'         => 'Centre-Val-de-Loire',
                    'Corse'                       => 'Corse',
                    'Grand Est'                   => 'Grand-Est',
                    'Hauts-de-France'             => 'Hauts-de-France',
                    'Île-de-France'               => 'Ile-de-France',
                    'Normandie'                   => 'Normandie',
                    'Nouvelle-Aquitaine'          => 'Nouvelle-Aquitaine',
                    'Occitanie'                   => 'Occitanie',
                    'Pays de la Loire'            => 'Pays-de-la-Loire',
                    'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Cote-d-azur',
                    'DOM-TOM'                     => 'DOM-TOM',
                    'Autre pays'                  => 'Autre-Pays'
                ],
            ])
            ->add('TypeVehicule', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    'Toutes les annonces'     => 'Peu importe',
                    'un Fourgon'       => 'Fourgon',
                    'une Caravane'      => 'Caravane',
                    'un Camping-Car'   => 'Camping-Car',
                    'un Accessoire / Hangar / Autre'   => 'Autres',
                ],
            ])
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}