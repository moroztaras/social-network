<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 */
class Comment implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Svistyn", inversedBy="comments")
     * @ORM\JoinColumn(name="svistyn_id", referencedColumnName="id")
     */
    private $svistyn;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $approved;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->setApproved(true);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return $this
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Returns createdAt value.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get svistyn.
     *
     * @return Svistyn|null
     */
    public function getSvistyn(): ?Svistyn
    {
        return $this->svistyn;
    }

    /**
     * Set svistyn.
     *
     * @param Svistyn|null $svistyn
     *
     * @return Comment
     */
    public function setSvistyn(?Svistyn $svistyn): self
    {
        $this->svistyn = $svistyn;

        return $this;
    }

    /**
     * Get user.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get approved.
     *
     * @return bool|null
     */
    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    /**
     * Set approved.
     *
     * @param bool $approved
     *
     * @return Comment
     */
    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function jsonSerialize()
    {
        return[
          'id' => $this->getId(),
          'comment' => $this->getComment(),
          'svistyn' => $this->getSvistyn(),
          'createdAt' => $this->getCreatedAt(),
          'approved' => $this->getApproved(),
          'user' => [
            'id' => $this->getUser()->getId(),
            'fullName' => $this->getUser()->getFullname(),
          ],
        ];
    }
}
