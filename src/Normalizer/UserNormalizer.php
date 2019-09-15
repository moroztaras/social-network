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

        if (isset($context['profile'])) {
            return [
                'id' => $user->getId(),
                'fullName' => $user->getFullname(),
                'email' => $user->getEmail(),
                'gender' => $user->getGender(),
                'birthday' => $user->getBirthday()->format('d-m-Y'),
            ];
        }
        return [
          'id' => $user->getId(),
          'fullName' => $user->getFullname(),
          'email' => $user->getEmail(),
          'gender' => $user->getGender(),
          'birthday' => $user->getBirthday()->format('d-m-Y'),
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($context['edit']) && isset($context['object_to_populate'])) {
            $user = $context['object_to_populate'];

            if (isset($data['fullName'])) {
                $user->setFullName($data['fullName']);
            }

            if (isset($data['email'])) {
                $user->setEmail($data['email']);
            }

            if (isset($data['gender'])) {
                $user->setGender($data['gender']);
            }

            if (isset($data['region'])) {
                $user->setRegion($data['region']);
            }

            if (isset($data['birthday'])) {
                $user->setBirtDay($data['birthday']);
            }

            return $user;
        }
    }

    public function supportsNormalization($user, $format = null)
    {
        return $user instanceof User;
    }
}
