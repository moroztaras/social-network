<?php

namespace App\Components\Notification;

/**
 * Class NotificationSchema.
 *
 * @Annotation
 * @Target("CLASS")
 */
class NotificationConfig
{
    public $id;

    public $params;

    public $template;
}
