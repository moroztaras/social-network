<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Svistyn.
 *
 * @ORM\Entity(repositoryClass="App\Repository\SvistynRepository")
 * @ORM\Table(name="svistyn")
 * @ORM\HasLifecycleCallbacks()
 */
class Svistyn
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=1024, nullable=true)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $embedVideo;

    /**
     * @ORM\Column(type="integer")
     * Share State
     * 1 = good
     * 2 = bad
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     * //View post for display
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Svistyn")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="string")
     */
    private $marking;

    private $countSvists = 0;

    private $countZvizds = 0;

    private $isParent = false;

    public function __construct()
    {
        $this->state = 0; //null of start
    $this->status = 1; //is published
    $this->marking = 'new';
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
     * Set text.
     *
     * @param string $text
     *
     * @return Svistyn
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set user.
     *
     * @param \App\Entity\User $user
     *
     * @return Svistyn
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
     * Set photo.
     *
     * @param \App\Entity\File $photo
     *
     * @return Svistyn
     */
    public function setPhoto(\App\Entity\File $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo.
     *
     * @return \App\Entity\File
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set embed_video.
     *
     * @param string $embedVideo
     *
     * @return Svistyn
     */
    public function setEmbedVideo($embedVideo)
    {
        $this->embedVideo = $embedVideo;

        return $this;
    }

    /**
     * Get embed_video.
     *
     * @return string
     */
    public function getEmbedVideo()
    {
        return $this->embedVideo;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return Svistyn
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Svistyn
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

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Svistyn
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set parent.
     *
     * @param \App\Entity\Svistyn $parent
     *
     * @return Svistyn
     */
    public function setParent(\App\Entity\Svistyn $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \App\Entity\Svistyn
     */
    public function getParent()
    {
        if (is_null($this->parent)) {
            return $this->parent;
        }
        $this->parent->setIsParent(true);

        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getMarking()
    {
        return $this->marking;
    }

    /**
     * @param mixed $marking
     */
    public function setMarking($marking): void
    {
        $this->marking = $marking;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->created = new \DateTime();
        $this->setUpdatedAtValue();
    }

    /**
     * @ORM\PostPersist
     */
    public function setUpdatedAtValue()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @return int
     */
    public function getCountSvists(): int
    {
        return $this->countSvists;
    }

    /**
     * @param int $countSvists
     */
    public function setCountSvists(int $countSvists): void
    {
        $this->countSvists = $countSvists;
    }

    /**
     * @return int
     */
    public function getCountZvizds(): int
    {
        return $this->countZvizds;
    }

    /**
     * @param int $countZvizds
     */
    public function setCountZvizds(int $countZvizds): void
    {
        $this->countZvizds = $countZvizds;
    }

    public function ratingTotal()
    {
        return $this->countSvists + $this->countZvizds;
    }

    /**
     * @return bool
     */
    public function isParent(): bool
    {
        return $this->isParent;
    }

    /**
     * @param bool $isParent
     */
    public function setIsParent(bool $isParent): void
    {
        $this->isParent = $isParent;
    }
}