<?php

namespace App\Services;

use App\Components\User\Models\ChangePasswordModel;
use App\Components\User\Models\RegistrationUserModel;
use App\Entity\User;
use App\Entity\UserAccount;
use App\Entity\Role;
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

    public function save(RegistrationUserModel $registrationModel)
    {
        $user = new User();
        $account = new UserAccount();
        $roleRepo = $this->doctrine->getRepository(Role::class);
        $roleUser = $roleRepo->findOneByRole('ROLE_USER');
        $account->setFullname($registrationModel->getFullname());
        $account->setBirthday($registrationModel->getBirthday());
        $account->setSex($registrationModel->getSex());
        $account->setRegion($registrationModel->getRegion());
        $user->setEmail($registrationModel->getEmail());
        $user->addRole($roleUser);
        $user->setAccount($account);
        $password = $this->passwordEncoder->encodePassword($user, $registrationModel->getPlainPassword());
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
