<?php

namespace App\Components\User\Models;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use App\Entity\UserAccount;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationUserModel
{
    /**
     * @Assert\NotBlank(message="user.fullname.not_blank")
     */
    private $fullname;

    /**
     * @Assert\NotBlank(message="user.email.not_blank")
     * @Assert\Email(message="user.email.invalid")
     */
    public $email;

    /**
     * @Assert\NotBlank(message="user.password.not_blank")
     * @Assert\Length(min="8", max="50")
     */
    public $password;
    /**
     * @Assert\NotBlank(message="user.birthday.not_blank")
     */
    public $birthday;
    /**
     * @Assert\NotBlank(message="user.gender.not_blank")
     */
    public $sex;
    /**
     * @Assert\NotBlank(message="user.region.not_blank")
     */
    public $region;

    private $passwordEncoder;
    private $em;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname): void
    {
        $this->fullname = $fullname;
    }

    public function getUserHandler()
    {
        $user = new User();
        $account = new UserAccount();
        $roleRepo = $this->em->getRepository(Role::class);
        $roleUser = $roleRepo->findOneByRole('ROLE_USER');
        $account->setFullname($this->fullname);
        $account->setBirthday($this->birthday);
        $account->setSex($this->sex);
        $account->setRegion($this->region);
        $user->setEmail($this->email);
        $user->addRole($roleUser);
        $user->setAccount($account);
        $password = $this->passwordEncoder->encodePassword($user, $this->password);
        $user->setPassword($password);

        return $user;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $userRepo = $this->em->getRepository(User::class);
        $email = $userRepo->findOneByEmail($this->email);
        if ($email) {
            $context->buildViolation('email_already_in_use')
        ->atPath('email')
        ->addViolation();
        }
    }
}
