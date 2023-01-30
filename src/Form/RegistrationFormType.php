<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        #formulario para el registro con FormBuilderInterface 

        $builder
            ->add('name')
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,#se establece el false para que pase antes por config/packages/security.yaml para encriptarla  
                'attr' => ['autocomplete' => 'new-password'],#uso del navegador para que nos pregunte si queremos guardar contraseña
                'constraints' => [
                    #filtros no blancos y un min length
                    new NotBlank([
                        'message' => 'Please enter a password', 
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
