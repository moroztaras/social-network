<?php

namespace App\Components\File\Events;

use App\Components\File\FileAssistant;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use App\Entity\FileUsage;

class FileUsageSubscriber implements EventSubscriber
{
    private $fileUsage;

    public function __construct(FileAssistant $fileAssistant)
    {
        $this->fileAssistant = $fileAssistant;
    }

    public function getSubscribedEvents()
    {
        return [
      'onFlush',
      'postFlush',
    ];
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();
        $this->fileUsage = [];
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof FileUsage) {
                if ($entity->getEntity() && $entity->getEntity()->getId() != $entity->getEntityId()) {
                    $this->fileUsage[] = $entity;
                }
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof FileUsage) {
                if ($entity->getEntity() && $entity->getEntity()->getId() != $entity->getEntityId()) {
                    $this->fileUsage[] = $entity;
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $events)
    {
        if (empty($this->fileUsage)) {
            return;
        }
        $list = [];
        foreach ($this->fileUsage as $usage) {
            $list[$usage->getId()] = $usage;
        }

        if (empty($list)) {
            return;
        }

        $em = $events->getEntityManager();
        foreach ($list as $fileUsage) {
            $entity = $fileUsage->getEntity();
            $fileUsage->setEntityId($entity->getId());
            $fileUsage->setEntity(null);
            $em->persist($fileUsage);
        }
        $em->flush();
        $this->fileUsage = [];
    }
}
