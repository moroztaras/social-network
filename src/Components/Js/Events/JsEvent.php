<?php

namespace App\Components\Js\Events;

use App\Components\Js\JsLibrary;
use Symfony\Component\EventDispatcher\Event;

class JsEvent extends Event
{
    private $library;

    public function setLibrary($library)
    {
        $this->library = $library;
    }

    /** @return JsLibrary */
    public function getLibrary()
    {
        return $this->library;
    }
}
