<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GroupUsersRequest.
 *
 * @ORM\Entity(repositoryClass="App\Repository\GroupUsersRequestRepository")
 * @ORM\Table(name="group_users_request")
 */
class GroupUsersRequest
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="GroupUsers", inversedBy="groupUsersRequests")
     * @ORM\JoinColumn(name="group_users_id", referencedColumnName="id")
     */
    private $groupUsers;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     * // send, accepted, canceled.
     */
    private $statusRequest;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * GroupUsersRequest constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->statusRequest = 'send';
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
     * Get statusRequest.
     *
     * @return null|string
     */
    public function getStatusRequest(): ?string
    {
        return $this->statusRequest;
    }

    /**
     * Set statusRequest.
     *
     * @param string $statusRequest
     *
     * @return GroupUsersRequest
     */
    public function setStatusRequest(string $statusRequest): self
    {
        $this->statusRequest = $statusRequest;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTimeInterface $createdAt
     *
     * @return GroupUsersRequest
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get groupUsers.
     *
     * @return GroupUsers|null
     */
    public function getGroupUsers(): ?GroupUsers
    {
        return $this->groupUsers;
    }

    /**
     * Set groupUsers.
     *
     * @param GroupUsers|null $groupUsers
     *
     * @return GroupUsersRequest
     */
    public function setGroupUsers(?GroupUsers $groupUsers): self
    {
        $this->groupUsers = $groupUsers;

        return $this;
    }

    /**
     * Get User.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set User.
     *
     * @param User|null $user
     *
     * @return GroupUsersRequest
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
