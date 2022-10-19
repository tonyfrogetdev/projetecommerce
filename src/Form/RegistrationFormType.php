<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['class'=> 'form-control', 'placeholder' => 'Indiquez le pseudo'],
                'required'=>false, 
                ])

            ->add('fullName', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['class'=> 'form-control', 'placeholder' => 'Indiquez le nom complet'],
                'required'=>false, 
                ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class'=> 'form-control', 'placeholder' => 'Indiquez l\'email'],
                'required'=>false, 
                ])
            ->add('civilite', ChoiceType::class,[
                "choices" => [
                    "Madame" => "f",
                    "Monsieur" => "m"
                ],
            
                
            
            ])
            
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos termes.',
                    ]),
                ],
                'required'=>false,
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['class'=> 'form-control', 'placeholder' => 'Indiquez le mot de passe'],
                'required'=>false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe est obligatoire',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit au moins contenir {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
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
