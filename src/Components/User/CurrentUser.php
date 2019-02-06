<?php

namespace App\Components\User;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUser
{
    private $token;

    /**
     * CurrentUser constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->token) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->token->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }

    public function getUserId()
    {
        return $this->getUser() instanceof User ? $this->getUser()->getId() : null;
    }
}
