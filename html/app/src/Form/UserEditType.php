<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', Type\TextType::class, [
            'label' => 'Username',
            'required' => true,
            'attr' => [
                'class' => 'form-text',
                'autocomplete' => 'username',
                'autofocus' => true,
            ],
            'constraints' => [
                new Constraints\Length([
                    'min' => 4,
                    'max' => 200,
                ]),
            ],
        ]);

        $builder->add('password', Type\RepeatedType::class, [
            'type' => Type\PasswordType::class,
            'required' => false,
            'first_options' => [
                'label' => 'New password',
                'attr' => [
                    'class' => 'form-text',
                ],
            ],
            'second_options' => [
                'label' => 'Repeat password',
                'attr' => [
                    'class' => 'form-text',
                ],
            ],
            'constraints' => [
                new Constraints\Length([
                    'min' => 6,
                ]),
            ],
        ]);

        $builder->add('role', Type\ChoiceType::class, [
            'label' => 'User role',
            'required' => true,
            'placeholder' => '-- Choose an role --',
            'choices' => [
                'ROLE_USER' => 'ROLE_USER',
                'ROLE_ADMIN' => 'ROLE_ADMIN',
            ],
            'attr' => [
                'class' => 'form-select',
            ],
        ]);

        $builder->add('submit', Type\SubmitType::class, [
            'label' => 'Save',
            'attr' => [
                'class' => 'btn btn--primary',
            ],
        ]);
    }
}