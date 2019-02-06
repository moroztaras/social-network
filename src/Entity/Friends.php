<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Friends.
 *
 * @ORM\Entity(repositoryClass="App\Repository\FriendsRepository")
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
     * @ORM\Column(type="integer")
     */
    private $user;
    /**
     * @ORM\Column(type="integer")
     */
    private $friend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

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
     * Set user.
     *
     * @param int $user
     *
     * @return Friends
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set friend.
     *
     * @param int $friend
     *
     * @return Friends
     */
    public function setFriend($friend)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend.
     *
     * @return int
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Friends
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
}
