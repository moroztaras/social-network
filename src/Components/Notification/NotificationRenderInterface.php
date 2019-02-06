<?php

namespace App\Components\Notification;

use Symfony\Component\DependencyInjection\ContainerInterface;

interface NotificationRenderInterface
{
    public function setContainerService(ContainerInterface $container);

    public function setData($data);
}
