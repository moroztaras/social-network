<?php

namespace App\Components\File;

use App\Components\File\Events\FileEvents;
use App\Components\File\Events\FileUploadEvent;
use App\Components\Utils\View\ViewJson;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileUploadHandler
{
    private $dispatcher;

    private $fileAssistant;

    private $viewJson;

    public function __construct(EventDispatcherInterface  $dispatcher, FileAssistant $file_assistant, ViewJson $viewJson)
    {
        $this->fileAssistant = $file_assistant;
        $this->dispatcher = $dispatcher;
        $this->viewJson = $viewJson;
    }

    /**
     * @param Request $request
     *
     * @todo HANDLER LEVEL ERRORS DISPLAY FOR UPLOAD FILE
     */
    public function handler(Request $request)
    {
        $fileUpload = $request->files->get('file');
        if (!$fileUpload instanceof UploadedFile) {
            $fileUpload = $request->files->get('image');

            if (!$fileUpload instanceof UploadedFile) {
                return;
            }
        }

        $file = $this->fileAssistant->prepareUploadFile($fileUpload, 'trash');
        $events = new FileUploadEvent();

        $events->setFileAssistant($this->fileAssistant)->setFile($file)->setOptions($request->request->all());
        $this->dispatcher->dispatch(FileEvents::FILE_UPLOAD, $events);
    }
}
