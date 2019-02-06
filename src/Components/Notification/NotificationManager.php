<?php

namespace App\Components\Notification;

use App\Entity\Notification;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class NotificationManager
{
    private $schemas;

    private $reader;

    private $kernel;

    private $container;

    public function __construct(
    Reader $reader, KernelInterface $kernel,
    ContainerInterface $container)
    {
        $this->schemas = [];
        $this->kernel = $kernel;
        $this->reader = $reader;
        $this->container = $container;
    }

    public function findNotificationSchema()
    {
        if (!empty($this->schemas)) {
            return $this->schemas;
        }

        $path = $this->kernel->getRootDir().'/Notification';
        $finder = new Finder();
        $finder->files()->in($path);
        $namespace = 'App\Notification';
        foreach ($finder as $file) {
            $class = $namespace.'\\'.$file->getBasename('.php');

            try {
                $annotation = $this->reader->getClassAnnotation(new \ReflectionClass($class), 'App\Components\Notification\NotificationConfig');
            } catch (\Exception $e) {
                continue;
            }

            if (!$annotation) {
                continue;
            }
            /** @var NotificationRenderInterface $classInstance */
            $classInstance = new $class();
            $classInstance->setContainerService($this->container);
            $classInstance->setData($annotation);
            $this->schemas[$annotation->id] = $classInstance;
        }

        return $this->schemas;
    }

    public function render($notifications)
    {
        $schemas = $this->findNotificationSchema();
        $list = [];
        foreach ($notifications as $notification) {
            if (isset($schemas[$notification->getTemplate()])) {
                $instance = $schemas[$notification->getTemplate()];
                $list[] = $instance->render($notification);
            }
        }

        return $list;
    }

    public function markAsRead()
    {
    }
}
