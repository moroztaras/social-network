<?php

namespace App\Components\User\Forms;

use App\Components\User\Models\ProfileSecurityModel;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountSecurityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', PasswordType::class, [
      'label' => 'current_password',
    ]);
        $builder->add('email', EmailType::class, [
      'label' => 'Email',
    ]);
        $builder->add('newPassword', RepeatedType::class, [
      'type' => PasswordType::class,
      'invalid_message' => 'The password fields must match.',
      'options' => ['attr' => ['class' => 'password-field']],
      'required' => false,
      'first_options' => ['label' => 'new_password'],
      'second_options' => ['label' => 'new_repeat_password'],
    ]);

        $builder->add('save', SubmitType::class, [
      'label' => 'save',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => ProfileSecurityModel::class,
    ]);
    }
}
