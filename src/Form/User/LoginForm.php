<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function getBlockPrefix()
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('email', EmailType::class, [
            'label' => 'email',
            'label_attr' => [
              'class' => 'uk-hidden',
            ],
            'attr' => ['placeholder' => 'email'],
          ])
          ->add('password', PasswordType::class, [
            'label' => 'password',
            'label_attr' => [
              'class' => 'uk-hidden',
            ],
            'attr' => ['placeholder' => 'password'],
          ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => User::class,
          'validation_groups' => ['login'],
        ]);
    }
}
