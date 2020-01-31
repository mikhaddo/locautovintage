<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// extensions form
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; # maybe
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

// validat0rs
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('roles') sensible et array to string conversion garçon !

            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'required' => true,
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez rentrer une adresse email valide.'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de mettre une adresse email.',
                    ]),
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('postcode', IntegerType::class, [
                'label' => 'Code Postal',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'le champ \'Numéro de téléphone\' doit contenir un numéro de téléphone valide ',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 15,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('insurance_name', TextType::class, [
                'label' => 'Nom de l\'assurance',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'La confirmation ne correspond pas.',
                'first_options' => [
                    'label' => 'Nouveau Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                ],
                'constraints' => [
                    // new NotBlank([
                    //     'message' => 'Merci de créer un mot de passe.',
                    // ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} charactères.',
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} charactères.',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les chagements ?',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci d\'accepter et confirmer les chagements.',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn btn-warning'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
