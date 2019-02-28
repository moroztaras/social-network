<?php

namespace App\Form\Svistyn;

use App\Form\Svistyn\Model\SvistynModel;
use App\Entity\Svistyn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SvistynForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Svistyn $svist */
        $svist = $options['data'];

        $builder->add('text', TextareaType::class, [
          'label' => 'text',
            'required' => false,
            'attr' => [
                'maxlength' => 255,
            ],
        ]);
        if (!$svist->getParent() instanceof Svistyn) {
            $builder->add('embed', TextType::class, [
              'label' => 'embed_video',
              'required' => false,
            ]);
            $builder->add('image', FileType::class, [
              'label' => 'image',
              'required' => false,
            ]);
        }

        $builder->add('post', SubmitType::class, [
          'label' => $this->getBtnName($svist),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => SvistynModel::class,
          'attr' => [
            'class' => 'form-svistyn-add',
          ],
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }

    private function getBtnName(SvistynModel $svistyn)
    {
        return 2 == $svistyn->getState() ? 'zvizdnuti' : 'svistnuti';
    }
}
