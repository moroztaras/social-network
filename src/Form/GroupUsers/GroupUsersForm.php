<?php

namespace App\Form\GroupUsers;

use App\Entity\GroupUsers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupUsersForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
          'label' => 'name',
          'label_attr' => [
            'class' => 'uk-hidden',
          ],
            'compound' => false,
            'attr' => [
                'placeholder' => 'name',
            ],
        ]);
        $builder->add('description', TextareaType::class, [
          'label' => 'description',
          'label_attr' => [
            'class' => 'uk-hidden',
          ],
          'attr' => ['placeholder' => 'description'],
        ]);
        $builder->add('confidentiality', ChoiceType::class, [
          'label' => 'confidentiality',
            'choices' => [
              'open' => 'open',
              'close' => 'close',
              'private' => 'private',
            ],
            'attr' => [
                'class' => 'uk-flex uk-flex-middle kuk-child-margin-small-left',
            ],
            'label_attr' => [
                'class' => 'uk-hidden',
            ],
            'multiple' => false,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => GroupUsers::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
