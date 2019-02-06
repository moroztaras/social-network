<?php

namespace App\Components\File;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Gumlet\ImageResize;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ImageReader
{
    private $em;

    private $fileRepo;
    /**
     * @var FileAssistant
     */
    private $fileAssistent;

    private $request;

    private $imageStyle;

    /**
     * PrivateReader constructor.
     *
     * @param \Doctrine\ORM\EntityManager                    $entity_manager
     * @param FileAssistant                                  $file_assistent
     * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
     * @param ImageStyle                                     $image_style
     */
    public function __construct(EntityManagerInterface $entity_manager, FileAssistant $file_assistent, RequestStack $request_stack, ImageStyle $image_style)
    {
        $this->em = $entity_manager;
        $this->fileAssistent = $file_assistent;
        $this->fileRepo = $entity_manager->getRepository(File::class);
        $this->request = $request_stack->getCurrentRequest();
        $this->imageStyle = $image_style;
    }

    public function load($fid)
    {
        return $this->fileRepo->find($fid);
    }

    /**
     * @param $fid
     *
     * @return Response
     *
     * @throws \Gumlet\ImageResizeException
     */
    public function outputImageResponse($fid)
    {
        $imageStyle = $this->request->get('image_style');
        if (!$this->imageStyle->existStyle($imageStyle)) {
            return $this->outputDefaultResponse();
        }

        /** @var File $file */
        $file = $this->fileRepo->find($fid);
        if (!$file) {
            return $this->outputDefaultResponse();
        }

        $accessFile = $this->fileAssistent->privateFileAccess($file);

        if (!$accessFile) {
            return $this->outputDefaultResponse();
        }

        $urlFile = $this->imageStyle->privateTrash($file, $imageStyle);
        $urlFile = $this->fileAssistent->rootUrl($urlFile);

        $image = new ImageResize($urlFile);
        $this->imageStyle->actionResizeImage($image, $imageStyle);
        $image->output();

        return new Response();
    }

    /**
     * @return Response
     *
     * @throws \Gumlet\ImageResizeException
     */
    protected function outputDefaultResponse()
    {
        $imgDef = 'images/icons/forbidden.png';
        $urlFile = $this->fileAssistent->rootDir().'/'.$imgDef;
        $image = new ImageResize($urlFile);
        $image->output();

        return new Response();
    }
}
