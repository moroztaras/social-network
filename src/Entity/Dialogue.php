<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Dialogue.
 *
 * @ORM\Entity(repositoryClass="App\Repository\DialogueRepository")
 * @ORM\Table(name="dialogue")
 * @ORM\HasLifecycleCallbacks()
 */
class Dialogue
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    private $receiver;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Message", mappedBy="dialogue", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $messages;

    /**
     * Dialogue constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->messages = new ArrayCollection();
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
     * Get creator.
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * Set creator.
     *
     * @param User $creator
     *
     * @return $this
     */
    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get receiver.
     */
    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    /**
     * Set receiver.
     *
     * @param User $receiver
     *
     * @return $this
     */
    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Dialogue
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
     * @return Dialogue
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
}
