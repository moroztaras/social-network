<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserAccount;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $roleRepo = $manager->getRepository(Role::class);
        $role = $roleRepo->findOneByRole('ROLE_USER');
        $roleAdmin = $roleRepo->findOneByRole('ROLE_ADMIN');
        if (!$role) {
            return;
        }

        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'moroztaras'));
        $user->addRole($role);
        $user->addRole($roleAdmin);
        $user->setEmail('moroztaras@i.ua');

        $userAccount = new UserAccount();
        $userAccount->setFullname('Moroz Taras');
        $userAccount->setBirthday(new \DateTime());
        $userAccount->setSex('m');
        $manager->persist($user);
        $manager->flush();
        $userAccount->setUser($user);
        $manager->persist($userAccount);

        $manager->flush();

        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'user_pass'));
        $user->addRole($role);
        $user->addRole($roleAdmin);
        $user->setEmail('user@mail.ua');

        $userAccount = new UserAccount();
        $userAccount->setFullname('User FullName');
        $userAccount->setBirthday(new \DateTime());
        $userAccount->setSex('m');
        $manager->persist($user);
        $manager->flush();
        $userAccount->setUser($user);
        $manager->persist($userAccount);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RoleFixtures::class,
        ];
    }
}
