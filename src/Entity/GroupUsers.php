<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class GroupUsers.
 *
 * @ORM\Entity()
 * @ORM\Table(name="group_users")
 * @ORM\HasLifecycleCallbacks()
 */
class GroupUsers
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text", length=1024)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true, nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $slug;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $confidentiality;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     */
    private $admin;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_fid", referencedColumnName="id")
     */
    private $avatar;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="cover_fid", referencedColumnName="id")
     */
    private $cover;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    private $users;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Svistyn", mappedBy="groupUsers", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $svistyns;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="GroupUsersRequest", mappedBy="groupUsers", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $groupUsersRequests;

    /**
     * GroupUsers constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->users = new ArrayCollection();
        $this->svistyns = new ArrayCollection();
        $this->groupUsersRequests = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return GroupUsers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return GroupUsers
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return GroupUsers
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set admin.
     *
     * @param User $admin
     *
     * @return GroupUsers
     */
    public function setAdmin(User $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin.
     *
     * @return User
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return GroupUsers
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

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return GroupUsers
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getConfidentiality(): ?string
    {
        return $this->confidentiality;
    }

    public function setConfidentiality(string $confidentiality): self
    {
        $this->confidentiality = $confidentiality;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar.
     *
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * Get cover.
     *
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover): void
    {
        $this->cover = $cover;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection|Svistyn[]
     */
    public function getSvistyns(): Collection
    {
        return $this->svistyns;
    }

    public function addSvistyn(Svistyn $svistyn): self
    {
        if (!$this->svistyns->contains($svistyn)) {
            $this->svistyns[] = $svistyn;
            $svistyn->setGroupUsers($this);
        }

        return $this;
    }

    public function removeSvistyn(Svistyn $svistyn): self
    {
        if ($this->svistyns->contains($svistyn)) {
            $this->svistyns->removeElement($svistyn);
            // set the owning side to null (unless already changed)
            if ($svistyn->getGroupUsers() === $this) {
                $svistyn->setGroupUsers(null);
            }
        }

        return $this;
    }

    /**
     * Get groupUsersRequests.
     *
     * @return Collection|GroupUsersRequest[]
     */
    public function getGroupUsersRequests(): Collection
    {
        return $this->groupUsersRequests;
    }

    /**
     * Add groupUsersRequests.
     *
     * @param GroupUsersRequest $groupUsersRequest
     *
     * @return GroupUsers
     */
    public function addGroupUsersRequest(GroupUsersRequest $groupUsersRequest): self
    {
        if (!$this->groupUsersRequests->contains($groupUsersRequest)) {
            $this->groupUsersRequests[] = $groupUsersRequest;
            $groupUsersRequest->setGroupUsers($this);
        }

        return $this;
    }

    /**
     * Remove groupUsersRequest.
     *
     * @param GroupUsersRequest $groupUsersRequest
     *
     * @return GroupUsers
     */
    public function removeGroupUsersRequest(GroupUsersRequest $groupUsersRequest): self
    {
        if ($this->groupUsersRequests->contains($groupUsersRequest)) {
            $this->groupUsersRequests->removeElement($groupUsersRequest);
            // set the owning side to null (unless already changed)
            if ($groupUsersRequest->getGroupUsers() === $this) {
                $groupUsersRequest->setGroupUsers(null);
            }
        }

        return $this;
    }
}
