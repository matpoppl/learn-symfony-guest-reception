<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestEditType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Guest::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', Type\TextType::class, [
            'label' => 'First name',
            'required' => true,
            'trim' => true,
            'attr' => [
                'class' => 'form-text',
            ],
        ]);

        $builder->add('lastName', Type\TextType::class, [
            'label' => 'Last name',
            'required' => true,
            'trim' => true,
            'attr' => [
                'class' => 'form-text',
            ],
        ]);

        $builder->add('companyName', Type\TextType::class, [
            'label' => 'Company name',
            'required' => true,
            'trim' => true,
            'attr' => [
                'class' => 'form-text',
            ],
        ]);

        $builder->add('paymentStatus', Type\ChoiceType::class, [
            'label' => 'Payment status',
            'required' => true,
            'choices' => [
                'no payment' => 0,
                'payment before' => 1,
                'payment during' => 2,
            ],
            'attr' => [
                'class' => 'form-select',
            ],
        ]);

        $builder->add('arrived', Type\ChoiceType::class, [
            'label' => 'Arrived',
            'required' => true,
            'choices' => [
                'No' => false,
                'Yes' => true,
            ],
            'attr' => [
                'class' => 'form-select',
            ],
        ]);

        $builder->add('receivedPkg', Type\ChoiceType::class, [
            'label' => 'Package received',
            'required' => true,
            'choices' => [
                'No' => 0,
                'Yes' => 9,
                'program/summary' => 2,
                'No package' => 1,
            ],
            'attr' => [
                'class' => 'form-select',
            ],
        ]);

        $builder->add('receivedCert', Type\ChoiceType::class, [
            'label' => 'Certificate received',
            'required' => true,
            'choices' => [
                'No' => false,
                'Yes' => true,
            ],
            'attr' => [
                'class' => 'form-select',
            ],
        ]);

        $builder->add('notes', Type\TextareaType::class, [
            'label' => 'Notes',
            'required' => false,
            'attr' => [
                'class' => 'form-text',
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