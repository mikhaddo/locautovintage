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

use App\Entity\User;


class ContactType extends AbstractType
{

    protected $thisUserEmail = 'albert@caramail.fr';

    public function getThisUserEmail()
    {
        return $this->thisUserEmail;
    }

    public function setThisUserEmail(?String $userEmail)
    {
        if($userEmail != NULL){
            $this->thisUserEmail = $userEmail;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', TextType::class, [
                'label' => 'Adresse Email',
                'attr' => [
                    'class' => '',
                    'placeholder' => $this->getThisUserEmail(),
                ],
                'constraints' => [
                    new Email([
                        'message' => 'Veuillez mettre une adresse email valide'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez mettre une adresse email'
                    ]),
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez mettre un sujet'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'essayez avec au moins {{ limit }} caractères entrés au clavier',
                        'maxMessage' => 'essayez avec au maximum {{ limit }} caractères entrés au clavier',
                    ]),
                ]
            ])
            ->add('body', TextareaType::class, [
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
            ],
            'data_class' => User::class,
        ]);
    }
}
