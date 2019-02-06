<?php

namespace App\Components\File\Events;

use App\Entity\File;
use Symfony\Component\EventDispatcher\Event;

class FilePrivateEvent extends Event
{
    private $file;

    private $access = false;

    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setAccess($access)
    {
        $this->access = $access;
    }

    public function getAccess()
    {
        return $this->access;
    }
}
