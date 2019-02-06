<?php

namespace App\Components\File;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFile
{
    private $fileAssistent;

    private $em;

    private $dispatcher;

    public function __construct(FileAssistant $file_assistent, EntityManagerInterface $entity_manager, EventDispatcherInterface $dispatcher)
    {
        $this->fileAssistent = $file_assistent;
        $this->em = $entity_manager;
        $this->dispatcher = $dispatcher;
    }

    public function response($id)
    {
        $file = $this->em->getRepository(\App\Entity\File::class)->find($id);
        if (!$file) {
            return new Response('Access denied');
        }

        $accessFile = $this->fileAssistent->privateFileAccess($file);
        if (!$accessFile) {
            return new Response('Access denied');
        }

        $fl = new File($this->fileAssistent->rootUrl($file->getUrl()));
        $response = new BinaryFileResponse($fl);

        return $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, null === $fl ? $file->getFilename() : $file->getFilename());
    }
}
