<?php

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('initial_month', ChoiceType::class, [
            'choices' => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 9, '9' => 9, '10' => 10, '11' => 11, '12' => 12],
            'label_attr' => [
                'class' => 'uk-hidden',
                ],
            'attr' => [
                'class' => 'uk-flex kuk-child-margin-small-left',
                ],
            ])
          ->add('initial_year', ChoiceType::class, [
            'choices' => ['2018' => 2018, '2019' => 2019],
            'label_attr' => [
              'class' => 'uk-hidden',
            ],
            'attr' => [
              'class' => 'uk-flex kuk-child-margin-small-left',
            ],
          ])
          ->add('final_month', ChoiceType::class, [
            'choices' => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 9, '9' => 9, '10' => 10, '11' => 11, '12' => 12],
            'label_attr' => [
                    'class' => 'uk-hidden',
                ],
            'attr' => [
                    'class' => 'uk-flex kuk-child-margin-small-left',
                ],
          ])
          ->add('final_year', ChoiceType::class, [
            'choices' => ['2019' => 2019, '2020' => 2020, '2021' => 2021],
            'label_attr' => [
                'class' => 'uk-hidden',
            ],
            'attr' => [
                'class' => 'uk-flex kuk-child-margin-small-left',
            ],
          ]);

//        $builder->add('submit', SubmitType::class, [
//          'label' => 'filter',
//        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
