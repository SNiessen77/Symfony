<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName')
        ->add('lastName')
        ->add('email')
        ->add('sex', ChoiceType::class, [
            'label' => 'Geschlecht',
            'placeholder' => 'Bitte wählen', // пустой пункт
            'choices' => [
                'männlich' => 'männlich',
                'weiblich' => 'weiblich',
            ],
            // 'required' => false, // если в БД поле nullable
            'row_attr' => ['class' => 'mb-3'], // для Bootstrap 5
        ])
        ->add('birthday', DateType::class, [
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
            'required' => false,
        ])
        ->add('login')
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,                 // не пишем напрямую в Entity
            'invalid_message' => 'Passwörter müssen übereinstimmen',
            'first_options' => [
                'label' => 'Password',                  // вместо "Plain password"
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Geben Sie ein sicheres Passwort ein',
                ],
            ],
            'second_options' => [
                'label' => 'Repeat password',
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Passwort wiederholen',
                ],
            ],
            'constraints' => [
                new NotBlank(['message' => 'Passwort eingeben']),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Mindestens {{ limit }} Zeichen',
                    'max' => 4096,
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
