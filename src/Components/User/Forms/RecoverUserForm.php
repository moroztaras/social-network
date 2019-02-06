<?php

namespace App\Components\User\Forms;

use App\Components\User\Models\RecoverUserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecoverUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
      'label' => 'email',
      'label_attr' => [
        'class' => 'uk-hidden',
      ],
      'attr' => ['placeholder' => 'email'],
    ]);
        $builder->add('submit', SubmitType::class, [
      'label' => 'recover_password',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => RecoverUserModel::class,
    ]);
    }
}
