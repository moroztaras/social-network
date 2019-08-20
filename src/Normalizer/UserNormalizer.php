<?php

namespace App\Normalizer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    /**
     * @param User  $user
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($user, $format = null, array $context = [])
    {
        if (isset($context['registration']) || isset($context['login'])) {
            return [
                'api_token' => $user->getApiToken(),
            ];
        }

        return [
          'id' => $user->getId(),
          'fullName' => $user->getFullname(),
          'email' => $user->getEmail(),
          'gender' => $user->getGender(),
          'birthday' => $user->getBirthday()->format('d-m-Y'),
          'api_token' => $user->getApiToken(),
        ];
    }

    public function supportsNormalization($user, $format = null)
    {
        return $user instanceof User;
    }
}
