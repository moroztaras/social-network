<?php

namespace App\Components\User;

use App\Components\User\Models\ProfileSecurityModel;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserSecurityManager
{
    private $securityModel;
    private $encoderFactory;
    private $passwordEncoder;
    private $em;

    public function __construct(ProfileSecurityModel $securityModel, EncoderFactoryInterface $encoderFactory, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->securityModel = $securityModel;
        $this->encoderFactory = $encoderFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $entityManager;
    }

    public function getChange(User $user)
    {
        if ($user->getEmail() == $this->securityModel->getEmail()) {
            $user->setEmail($user->getEmail());
        } else {
            $user->setEmail($this->securityModel->getEmail());
        }
        if (null == $this->securityModel->getNewPassword()) {
            $user->setPassword($user->getPassword());
        } else {
            $password = $this->passwordEncoder->encodePassword($user, $this->securityModel->getNewPassword());
            $user->setPassword($password);
        }
        $this->em->persist($user);
        $this->em->flush();

        return true;
    }
}
