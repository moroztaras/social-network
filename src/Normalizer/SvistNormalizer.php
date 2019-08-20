<?php

namespace App\Normalizer;

use App\Entity\Svistyn;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SvistNormalizer implements NormalizerInterface
{
    /**
     * @param Svistyn $svist
     * @param null    $format
     * @param array   $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($svist, $format = null, array $context = [])
    {
        return  [
            'id' => $svist->getId(),
            'text' => $svist->getText(),
            'embedVideo' => $svist->getEmbedVideo(),
            'photo' => $svist->getPhoto(),
            'comments' => count($svist->getComments()),
            'author' => [
              'fullName' => $svist->getUser()->getFullname(),
            ],
            'countSvists' => $svist->getCountSvists(),
            'countZvizds' => $svist->getCountZvizds(),
            'createdAt' => $svist->getCreated()->format('d-m-Y H:i:s'),
        ];
    }

    public function supportsNormalization($svist, $format = null)
    {
        return $svist instanceof Svistyn;
    }
}
