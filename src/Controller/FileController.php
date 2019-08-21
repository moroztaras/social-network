<?php

namespace App\Controller;

use App\Components\File\FileUploadHandler;
use App\Components\Utils\View\ViewJson;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController.
 */
class FileController extends AbstractController
{
    /**
     * @Route("/file_upload", name="file_upload")
     * @Security("is_granted('ROLE_USER')")
     */
    public function fileUpload(Request $request, FileUploadHandler $fileUploadHandler)
    {
        $fileUploadHandler->handler($request);

        return ViewJson::response();
    }
}
