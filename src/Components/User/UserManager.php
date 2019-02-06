<?php

namespace App\Components\User;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class UserManager
{
    private $userPasswordEncoder;

    private $tokenStorage;

    public function __construct(UserPasswordEncoderInterface $user_password_encoder, TokenStorageInterface $tokenStorage)
    {
        $this->userPasswordEncoder = $user_password_encoder;
        $this->tokenStorage = $tokenStorage;
    }

    public function generatePassword(UserInterface $user, $password)
    {
        return $this->userPasswordEncoder->encodePassword($user, $password);
    }

    public function changeUser(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    /**
     * @return User|null
     */
    public function currentUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
