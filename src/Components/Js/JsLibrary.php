<?php

namespace App\Components\Js;

use App\Components\Js\Events\JsEvent;
use App\Components\Js\Events\JsEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JsLibrary
{
    public static $globalSettings;

    private $dispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->dispatcher = $eventDispatcher;
    }

    public static function add($key, $value)
    {
        self::$globalSettings[$key] = $value;
    }

    public static function remove($key)
    {
        unset(self::$globalSettings[$key]);
    }

    public static function get()
    {
        return self::$globalSettings;
    }

    public function json()
    {
        $json = new JsonEncoder();
        if ($this->dispatcher) {
            $event = new JsEvent();
            $event->setLibrary($this);
            $this->dispatcher->dispatch(JsEvents::ATTACH, $event);
        }

        return $json->encode(self::$globalSettings, 'json');
    }
}
