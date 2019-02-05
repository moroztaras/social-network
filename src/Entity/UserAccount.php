<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_account")
 */
class UserAccount
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="account")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $fullname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tokenRecover;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_fid", referencedColumnName="id")
     */
    private $avatar;

//    /**
//     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
//     * @ORM\JoinColumn(name="avatar_fid", referencedColumnName="id")
//     */
//    private $cover;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set birthday.
     *
     * @param \DateTime $birthday
     *
     * @return UserAccount
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday.
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set region.
     *
     * @param string $region
     *
     * @return UserAccount
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set sex.
     *
     * @param string $sex
     *
     * @return UserAccount
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex.
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set user.
     *
     * @param \App\Entity\User $user
     *
     * @return UserAccount
     */
    public function setUser(\App\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tokenRecover.
     *
     * @param string $tokenRecover
     *
     * @return UserAccount
     */
    public function setTokenRecover($tokenRecover)
    {
        $this->tokenRecover = $tokenRecover;

        return $this;
    }

    /**
     * Get tokenRecover.
     *
     * @return string
     */
    public function getTokenRecover()
    {
        return $this->tokenRecover;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
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
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

//    /**
//     * @return mixed
//     */
//    public function getCover()
//    {
//        return $this->cover;
//    }

//    /**
//     * @param mixed $cover
//     */
//    public function setCover($cover): void
//    {
//        $this->cover = $cover;
//    }
}
