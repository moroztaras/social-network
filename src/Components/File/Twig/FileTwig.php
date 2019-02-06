<?php

namespace App\Components\File\Twig;

use App\Components\File\FileAssistant;
use App\Entity\File;
use Symfony\Component\Routing\RouterInterface;

class FileTwig extends \Twig_Extension
{
    private $fileAssistant;
    private $router;

    public function __construct(FileAssistant $fileAssistant, RouterInterface $router)
    {
        $this->fileAssistant = $fileAssistant;
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
      new \Twig_SimpleFilter('file_url', [$this, 'fileUrl']),
      new \Twig_SimpleFilter('file_audio_stream', [$this, 'fileAudioStream']),
    ];
    }

    public function fileUrl(File $file = null)
    {
        if (!$file) {
            return '';
        }

        return $this->fileAssistant->webDir($file->getUrl());
    }

    public function fileAudioStream(File $file = null)
    {
        if (!$file) {
            return '';
        }
        $url = $file->isPrivate() ? $this->router->generate('file_private_audio', ['id' => $file->getId()]) : $this->fileAssistant->webDir($file->getUrl());

        return $url;
    }
}
