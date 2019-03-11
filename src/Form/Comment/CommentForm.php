<?php

namespace App\Form\Comment;

use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', TextType::class, [
          'label' => 'add_comment',
          'label_attr' => [
            'class' => 'uk-hidden',
          ],
          'compound' => false,
          'attr' => [
            'placeholder' => 'add_comment',
          ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Comment::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
