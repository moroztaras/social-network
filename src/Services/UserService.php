<?php

namespace App\Services;

use App\Components\User\Models\ChangePasswordModel;
use App\Entity\User;
use App\Entity\UserAccount;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param ManagerRegistry              $doctrine
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function save(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function changePasswordModel(User $user, UserAccount $userAccount, ChangePasswordModel $changePasswordModel)
    {
        $password = $this->passwordEncoder->encodePassword($user, $changePasswordModel->plainPassword);
        $user->setPassword($password);
        $userAccount->setTokenRecover(null);
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $this;
    }
}
