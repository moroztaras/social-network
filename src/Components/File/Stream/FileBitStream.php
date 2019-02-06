<?php

namespace App\Components\File\Stream;

use App\Components\File\FileAssistant;
use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class FileBitStream
{
    private $fileAssistant;

    private $em;

    public function __construct(FileAssistant $file_assistant, EntityManagerInterface $entity_manager)
    {
        $this->fileAssistant = $file_assistant;
        $this->em = $entity_manager;
    }

    public function stream($id)
    {
        $file = $this->em->getRepository(File::class)->find($id);

        if (!$file) {
            return new Response('Access denied');
        }

        if (!$this->fileAssistant->privateFileAccess($file)) {
            return new Response('Access denied');
        }

        $rootUrl = $this->fileAssistant->rootUrl($file->getUrl());

        if (!is_file($rootUrl)) {
            return new Response('Access denied');
        }

        return new BinaryFileResponse($rootUrl);
    }
}
