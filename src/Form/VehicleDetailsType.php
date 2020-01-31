<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// ajouts
use App\Entity\User; # nécéssaire ?
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;

class VehicleDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('brand', TextType::class, [
            'label' => 'Marque du véhicule (ex: Mercedes)',
            'attr' => ['placeholder' => 'Mercedes'],
            'constraints' => [
                new Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('model', TextType::class, [
            'label' => 'Modèle du véhicule (ex: E300TD)',
            'constraints' => [
                new Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('year_produced', TextType::class, [
            'label' => 'Année de production du véhicule (ex: 1998)',
            'constraints' => [
                new Length([
                    'min' => 4,
                    'max' => 4,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('engine_type', TextType::class, [
            'label' => 'Nombres de cylindres du moteur (ex: 6 cylindres en ligne)',
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 20,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('engine_displacement', IntegerType::class, [
            'label' => 'Cylindrée du moteur, en cm³ (ex: 3000)',
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 4,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('engine_power', IntegerType::class, [
            'label' => 'Puissance du moteur, en cheval-vapeur (ex: 177)',
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 4,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('max_speed', IntegerType::class, [
            'label' => 'Vitesse maximale, en km/h (ex: 260)',
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 4,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('max_seats', IntegerType::class, [
            'label' => 'Nombre de sièges (ex: 5)',
            'constraints' => [
                new Length([
                    'min' => 1,
                    'max' => 4,
                    'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                    'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                ]),
            ],
        ])
        ->add('pictures',FileType::class, [
            'label'         => 'Téléversez une photographie du véhicle : jusqu\'à cinq images !',
            'attr' => [
                'class' => '',
            ],
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/x-jpeg',
                        'image/png',
                        'image/x-png',
                    ],
                    'mimeTypesMessage' => 'Veuillez envoyer une image valide en .jpg ou .png jusqu\'à 2Méga-octects',
                ])
            ],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Envoyer',
            'attr' => [
                'class' => 'btn btn-warning'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
