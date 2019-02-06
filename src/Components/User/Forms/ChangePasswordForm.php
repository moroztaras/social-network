<?php

namespace App\Components\User\Forms;

use App\Components\User\Models\ChangePasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
      'type' => PasswordType::class,
      'invalid_message' => 'The password fields must match.',
      'options' => ['attr' => ['class' => 'password-field']],
      'required' => true,
      'first_options' => [
        'label' => 'password',
        'label_attr' => [
          'class' => 'hide',
        ],
        'attr' => ['placeholder' => 'password'],
      ],
      'second_options' => [
        'label' => 'repeat_password',
        'label_attr' => [
          'class' => 'hide',
        ],
        'attr' => ['placeholder' => 'repeat_password'],
      ],
    ]);
        $builder->add('submit', SubmitType::class, [
      'label' => 'recover',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => ChangePasswordModel::class,
    ]);
    }
}
