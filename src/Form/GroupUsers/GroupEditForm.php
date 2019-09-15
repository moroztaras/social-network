<?php

namespace App\Form\GroupUsers;

use App\Form\GroupUsers\Model\GroupEditModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class, [
            'label' => 'name',
          ])
          ->add('description', TextareaType::class, [
            'label' => 'description',
          ])
          ->add('avatar', FileType::class, [
            'label' => 'avatar',
            'required' => false,
          ])
          ->add('confidentiality', ChoiceType::class, [
            'label' => 'confidentiality',
            'choices' => [
              'open' => 'open',
              'close' => 'close',
              'private' => 'private',
            ],
            'attr' => [
                'class' => 'uk-flex uk-flex-middle kuk-child-margin-small-left',
            ],
            'multiple' => false,
            'expanded' => true,
          ])
          ->add('cover', FileType::class, [
            'label' => 'cover',
            'required' => false,
          ])
        ;
//        $builder->add('birthday', BirthdayType::class, [
//          'label' => 'birthday',
//          'attr' => [
//            'class' => 'uk-flex kuk-child-margin-small-left',
//          ],
//        ]);
//        $builder->add('region', CountryType::class, [
//          'label' => 'country',
//        ]);
//        $builder->add('cover', FileType::class, [
//          'label' => 'cover',
//          'required' => false,
//        ]);
//        $builder->add('save', SubmitType::class, [
//          'label' => 'save',
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => GroupEditModel::class,
        ]);
    }
}
