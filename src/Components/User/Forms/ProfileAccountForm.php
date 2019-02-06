<?php

namespace App\Components\User\Forms;

use App\Components\User\Models\ProfileAccountModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileAccountForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullname', TextType::class, [
      'label' => 'full_name',
    ]);
        $builder->add('avatar', FileType::class, [
      'label' => 'avatar',
      'required' => false,
    ]);
        $builder->add('gender', ChoiceType::class,
      [
        'label' => 'gender',
        'choices' => [
          'male' => 'm',
          'female' => 'w',
        ],
        'attr' => [
          'class' => 'uk-flex uk-flex-middle kuk-child-margin-small-left',
        ],

        'multiple' => false,
        'expanded' => true,
      ]
    );
        $builder->add('birthday', BirthdayType::class, [
      'label' => 'birthday',
      'attr' => [
        'class' => 'uk-flex kuk-child-margin-small-left',
      ],
    ]);
        $builder->add('region', CountryType::class, [
      'label' => 'country',
    ]);
        $builder->add('cover', FileType::class, [
      'label' => 'cover',
      'required' => false,
    ]);
        $builder->add('save', SubmitType::class, [
      'label' => 'save',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => ProfileAccountModel::class,
    ]);
    }
}
