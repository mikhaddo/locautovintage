<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Importation du type bouton de validation
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_name', TextType::class, [
                'label' => 'Adresse Email'
                ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                ])
            ->add('save', SubmitType::class, [ // Ajout d'un champ de type bouton de validation
                'label' => 'Envoyer'    // Texte du bouton
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
