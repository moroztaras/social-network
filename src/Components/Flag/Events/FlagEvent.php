<?php

namespace App\Components\Flag\Events;

use App\Entity\Flag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;

class FlagEvent extends Event
{
    private $flag;

    private $entityManager;

    /**
     * @return Flag
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager($entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
