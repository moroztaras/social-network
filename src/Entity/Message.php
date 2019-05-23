<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Message.
 *
 * @ORM\Entity()
 * @ORM\Table(name="message")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    private $receiver;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 2048,
     *      minMessage = "Message must be at least {{ limit }} characters long",
     *      maxMessage = "Message cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="Dialogue", inversedBy="messages")
     * @ORM\JoinColumn(name="dialogue_id", referencedColumnName="id")
     */
    private $dialogue;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * //status of read message
     */
    private $status;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = 0; //message not read
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

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get dialogue.
     *
     * @return Dialogue|null
     */
    public function getDialogue(): ?Dialogue
    {
        return $this->dialogue;
    }

    /**
     * Set dialogue.
     *
     * @param Dialogue|null $dialogue
     *
     * @return Message
     */
    public function setDialogue(?Dialogue $dialogue): self
    {
        $this->dialogue = $dialogue;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Message
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
     * @return Message
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
