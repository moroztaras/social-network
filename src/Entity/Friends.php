<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Friends.
 *
 * @ORM\Entity
 * @ORM\Table(name="friends")
 */
class Friends
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id")
     */
    private $friend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Friends constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Get user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * @param User|null $user
     *
     * @return Friends
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get friend.
     *
     * @return User|null
     */
    public function getFriend(): ?User
    {
        return $this->friend;
    }

    /**
     * Set friend.
     *
     * @param User|null $friend
     *
     * @return Friends
     */
    public function setFriend(?User $friend): self
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Friends
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
