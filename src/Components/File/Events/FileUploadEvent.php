<?php

namespace App\Components\File\Events;

use App\Components\File\FileAssistant;
use App\Entity\File;
use Symfony\Component\EventDispatcher\Event;

class FileUploadEvent extends Event
{
    private $file;

    private $options;

    private $fileAssistant;

    public function setFile(File $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setFileAssistant(FileAssistant $file_assistant)
    {
        $this->fileAssistant = $file_assistant;

        return $this;
    }

    /**
     * @return FileAssistant
     */
    public function getFileAssistant()
    {
        return $this->fileAssistant;
    }
}
