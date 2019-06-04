<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Friends.
 *
 * @ORM\Entity
 * @ORM\Table(name="friends")
 */
class Friends implements \JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="friends")
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $friend;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * //status add friend
     */
    private $status;

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
        $this->status = 0;
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

    public function getFriend(): ?User
    {
        return $this->friend;
    }

    public function setFriend(?User $friend): self
    {
        $this->friend = $friend;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set status.
     *
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function jsonSerialize()
    {
        return [
          'id' => $this->getId(),
          'user' => [
            'id' => $this->getUser()->getId(),
            'fullName' => $this->getUser()->getFullname(),
            'email' => $this->getUser()->getEmail(),
            'gender' => $this->getUser()->getGender(),
            'birthday' => $this->getUser()->getBirthday(),
            'country' => $this->getUser()->getRegion(),
            'roles' => $this->getUser()->getRoles(),
            'create_at' => $this->getUser()->getCreated(),
            'updated_at' => $this->getUser()->getUpdated(),
            'status' => $this->getUser()->getStatus(),
          ],
          'created_at' => $this->getCreatedAt(),
        ];
    }
}
