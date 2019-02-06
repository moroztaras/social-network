<?php

namespace App\Controller;

use App\Components\File\FileUploadHandler;
use App\Components\Utils\View\ViewJson;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends Controller
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
