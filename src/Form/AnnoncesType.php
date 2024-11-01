<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('TypeVehicule', ChoiceType::class, [
                'label' => 'Vous vendez :',
                'choices'  => [
                    'un Fourgon'       => 'Fourgon',
                    'une Caravane'      => 'Caravane',
                    'un Camping-Car'   => 'Camping-Car',
                    'un Accessoire / Hangar / Autre'   => 'Autres',
                ],
            ])
            ->add('Titre', TextType::class, [
                'label' => 'Ajoutez un titre à votre annonce :'
            ])
            ->add('Description', TextareaType::class, [
                'label' => 'Ajoutez une description à votre annonce :',
                "attr" => ['cols' => '40', 'rows' => '8']
            ])
            ->add('Prix', NumberType::class, [
                'label' => 'Ajoutez un prix à votre annonce :'
            ])
            ->add('Etat', ChoiceType::class, [
                'label' => 'Etat :',
                'choices'  => [
                    'Neuf : Entièrement neuf, n\'a jamais été utilisé et est toujours sous garantie.'  => 'Neuf',
                    'Très bon état : En excellent état, avec très peu de signes d\'usure ou de dommages.'     => 'TBE',
                    'Bon état : A été utilisé mais est toujours en bon état, avec quelques signes d\'usure ou de dommages mineurs.' => 'BE',
                    'État satisfaisant : Présente certains signes d\'usure et de dommages, mais est encore fonctionnel.' => 'ES',
                    'Pour pièces : Endommagé ou ne fonctionne plus correctement et est destiné à être utilisé pour les pièces détachées.' => 'PIECES'
                ],
            ])
            ->add('KM', NumberType::class, [
                'label' => 'Kilométrage : '
            ])
            ->add('DateCT', ChoiceType::class, [
                'label' => 'Contrôle technique valide :',
                'choices'  => [
                    'Oui'        => 'Oui',
                    'Non'        => 'Non',
                    'En cours'   => 'En cours',
                    'Sera fait pour la vente'   => 'Sera fait pour la vente',
                ],
            ])
            ->add('NbrCouchage', NumberType::class, [
                'label' => 'Votre véhicule à combien de couchages ?'
            ])
            ->add('Region', ChoiceType::class, [
                'label' => 'Choisir une région :',
                'choices'  => [
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
            ->add('Ville')
            ->add('imageFile1', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
              ])
            ->add('imageFile2', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
            ->add('imageFile3', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
            ->add('imageFile4', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
            ->add('imageFile5', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])
            ->add('imageFile6', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
