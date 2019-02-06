<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserAccount;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $roleRepo = $manager->getRepository(Role::class);
        $role = $roleRepo->findOneByRole('ROLE_USER');
        $roleAdmin = $roleRepo->findOneByRole('ROLE_ADMIN');
        if (!$role) {
            return;
        }
        $encoder = $this->container->get('security.password_encoder');

        $user = new User();
        $password = $encoder->encodePassword($user, 'moroztaras');
        $user->setPassword($password);
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
        $password = $encoder->encodePassword($user, 'user_pass');
        $user->setPassword($password);
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
