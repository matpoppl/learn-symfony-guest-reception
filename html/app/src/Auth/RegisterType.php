<?php

namespace App\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', Type\TextType::class, [
            'label' => 'Username',
            'required' => true,
            'attr' => [
                'autocomplete' => 'username',
                'autofocus' => true,
            ],
            'constraints' => [
                new Constraints\Length([
                    'min' => 6,
                    'max' => 200,
                ]),
            ],
        ]);

        $builder->add('password', Type\RepeatedType::class, [
            'type' => Type\PasswordType::class,
            'required' => true,
            'first_options' => [
                'label' => 'Enter password',
            ],
            'second_options' => [
                'label' => 'Repeat password',
            ],
            'constraints' => [
                new Constraints\Length([
                    'min' => 6,
                ]),
            ],
        ]);

        $builder->add('submit', Type\SubmitType::class, [
            'label' => 'Save',
        ]);
    }
}