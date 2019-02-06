<?php

namespace App\Components\User\Forms;

use App\Components\User\Models\RegisterUserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullname', TextType::class, [
      'label' => 'full_name',
      'label_attr' => [
        'class' => 'uk-hidden',
      ],
      'compound' => false,
      'attr' => [
        'placeholder' => 'full_name',
      ],
    ]);
        $builder->add('email', EmailType::class, [
      'label' => 'email',
      'label_attr' => [
        'class' => 'uk-hidden',
      ],
      'attr' => ['placeholder' => 'email'],
    ]);
        $builder->add('password', RepeatedType::class, [
      'type' => PasswordType::class,
      'invalid_message' => 'password_must_match',
      'options' => ['attr' => ['class' => 'password-field']],
      'required' => false,
      'first_options' => [
        'label' => 'password',
        'label_attr' => [
          'class' => 'uk-hidden uk-form-label',
        ],
        'attr' => ['placeholder' => 'password'],
      ],
      'second_options' => [
        'label' => 'repeat_password',
        'label_attr' => [
          'class' => 'uk-hidden uk-form-label',
        ],
        'attr' => ['placeholder' => 'repeat_password'],
      ],
    ]);
        $builder->add('sex', ChoiceType::class,
      [
        'label' => 'gender',
        'choices' => [
          'male' => 'm',
          'female' => 'w',
        ],
        'attr' => [
          'class' => 'uk-flex uk-flex-middle kuk-child-margin-small-left',
        ],
        'label_attr' => [
          'class' => 'uk-hidden',
        ],
        'multiple' => false,
        'expanded' => true,
      ]
    );
        $builder->add('birthday', BirthdayType::class, [
      'label' => 'birthday',
      'label_attr' => [
        'class' => 'uk-hidden',
      ],
      'attr' => [
        'class' => 'uk-flex kuk-child-margin-small-left',
      ],
    ]);
        $builder->add('region', CountryType::class, [
      'label' => 'country',
      'label_attr' => [
        'class' => 'uk-hidden',
      ],
    ]);
        $builder->add('submit', SubmitType::class, [
      'label' => 'sign_up',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => RegisterUserModel::class,
    ]);
    }
}
