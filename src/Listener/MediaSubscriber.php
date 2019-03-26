<?php

namespace App\Listener;

use App\Aws\S3Manager;
use App\Entity\Media;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class MediaSubscriber implements EventSubscriber
{
    /**
     * @var S3Manager
     */
    private $s3;

    public function __construct(S3Manager $s3)
    {
        $this->s3 = $s3;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postRemove',
        ];
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Media) {
            if ($object->getFile()) {
                if ($object->getOldS3key()) {
                    $this->s3->removeImage($object->getOldS3key());
                }
                $this->s3->upload($object);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Media) {
            $this->s3->upload($object);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Media) {
            $this->s3->removeImage($object->getS3key());
        }
    }
}
