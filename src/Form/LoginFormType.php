<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'class' => 'form',
            ]
        ])
            // RepeatedType crée deux inputs : mot de passe et confirmation du mot de passe
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ["label" => "Mot de passe",
            'attr' => [
                    'class' => 'form',
                    'placeholder' => 'Entrez votre mot de passe',
                ]],
            ])
            // SubmitType crée un input bouton pour soumettre le formulaire
            ->add('login', SubmitType::class, [
                'attr' => [
                    'class' => 'form_submit',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
