<?php

namespace App\Components\Flag;

use App\Components\Flag\Events\FlagEvent;
use App\Components\Flag\Events\FlagEvents;
use App\Entity\Flag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FlagManager
{
    private $em;

    private $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $entityManager;
        $this->dispatcher = $eventDispatcher;
    }

    public function add(Flag  $flag)
    {
        $this->em->persist($flag);
        $this->em->flush($flag);
        $flagEvent = new FlagEvent();
        $flagEvent->setFlag($flag);
        $flagEvent->setEntityManager($this->em);
        $this->dispatcher->dispatch(FlagEvents::FLAG_ADD, $flagEvent);
    }

    public function remove(Flag  $flag)
    {
        $this->em->remove($flag);
        $this->em->flush($flag);
        $flagEvent = new FlagEvent();
        $flagEvent->setFlag($flag);
        $flagEvent->setEntityManager($this->em);
        $this->dispatcher->dispatch(FlagEvents::FLAG_REMOVE, $flagEvent);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEm()
    {
        return $this->em;
    }
}
