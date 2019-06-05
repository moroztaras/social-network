<?php

namespace App\Form\Comment;

use App\Form\Comment\Model\CommentImportFileModel;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentImportForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment_file', FileType::class, [
          'label' => 'upload_file',
          'label_attr' => [
            'class' => 'uk-hidden',
          ],
          'compound' => false,
          'attr' => [
            'placeholder' => 'upload_file',
          ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => CommentImportFileModel::class,
          'attr' => [
            'class' => 'form-svistyn-add',
          ],
        ]);
    }
}
