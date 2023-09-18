<?php

namespace App\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SigninType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class'      => Task::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id'   => 'authenticate',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('identifier', Type\TextType::class, [
            'label' => 'Username',
            'required' => true,
            'attr' => [
                'autocomplete' => 'username',
                'autofocus' => true,
            ],
        ]);

        $builder->add('password', Type\PasswordType::class, [
            'label' => 'Password',
            'required' => true,
            'attr' => [
                'autocomplete' => 'current-password',
            ],
        ]);

        $builder->add('_remember_me', Type\CheckboxType::class, [
            'label' => 'Remember me',
            'required' => false,
        ]);

        $builder->add('submit', Type\SubmitType::class, [
            'label' => 'Sign in',
        ]);
    }
}