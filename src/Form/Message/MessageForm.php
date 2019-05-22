<?php

namespace App\Form\Message;

use App\Entity\Message;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', TextareaType::class, [
          'label' => 'messages',
          'label_attr' => [
            'class' => 'uk-hidden',
          ],
          'compound' => false,
          'attr' => [
            'placeholder' => 'messages',
          ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Message::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
