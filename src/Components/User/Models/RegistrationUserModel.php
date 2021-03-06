<?php

namespace App\Components\User\Models;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
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
     * @Assert\NotBlank(message="user.plainPassword.not_blank")
     * @Assert\Length(min="8", max="50")
     */
    public $plainPassword;

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
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
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
