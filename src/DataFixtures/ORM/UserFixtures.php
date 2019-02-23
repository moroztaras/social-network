<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserAccount;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
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
        $user = new User();
        $user
          ->setPassword($this->passwordEncoder->encodePassword($user, 'moroztaras'))
          ->setRoles(['ROLE_SUPER_ADMIN'])
          ->setEmail('moroztaras@i.ua');

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
        $user
          ->setPassword($this->passwordEncoder->encodePassword($user, 'user_pass'))
          ->setRoles(['ROLE_USER'])
          ->setEmail('user@mail.ua');

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
}
