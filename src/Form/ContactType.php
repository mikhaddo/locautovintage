<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Importation du type bouton de validation
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_name', TextType::class, [
                'label' => 'Adresse Email',
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez mettre une adresse email valide'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez mettre une adresse email'
                    ]),
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Votre message doit contenir au minimum {{ limit }} charactères.',
                        'maxMessage' => 'Votre message doit contenir au maximum {{ limit }} charactères.',
                        'max' => 1000,
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez remplir votre message.'
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, [ // Ajout d'un champ de type bouton de validation
                'label' => 'Envoyer'    // Texte du bouton
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
