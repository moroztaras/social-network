<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
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
        $userAdmin = new User();
        $userAdmin
          ->setPassword($this->passwordEncoder->encodePassword($userAdmin, 'moroztaras'))
          ->setRoles(['ROLE_SUPER_ADMIN'])
          ->setEmail('moroztaras@i.ua')
          ->setGender('m')
          ->setBirthday(new \DateTime())
          ->setRegion('UA')
          ->setFullname('Moroz Taras');

        $manager->persist($userAdmin);

        $user = new User();
        $user
          ->setPassword($this->passwordEncoder->encodePassword($user, 'user_pass'))
          ->setRoles(['ROLE_USER'])
          ->setEmail('user@mail.ua')
          ->setBirthday(new \DateTime())
          ->setGender('m')
          ->setRegion('UA')
          ->setFullname('FullName');
        $manager->persist($user);

        $manager->flush();
    }
}
