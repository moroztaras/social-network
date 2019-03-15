<?php

namespace App\Controller;

use App\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/media", methods={"GET", "POST"}, name="media_upload")
     */
    public function indexAction(Request $request)
    {
        $image = new Media();
        $image
          ->setContentType($request->headers->get('Content-Type'))
          ->setContent($request->getContent());

        $this->getDoctrine()->getManager()->persist($image);
        $this->getDoctrine()->getManager()->flush();

        return new Response();
    }

    /**
     * @Route("/media/{id}", methods={"DELETE"}, name="media_remove")
     */
    public function removeAction($id)
    {
        $this->getDoctrine()->getManager()->remove($this->getDoctrine()->getRepository(Media::class)->find($id));
        $this->getDoctrine()->getManager()->flush();

        return new Response();
    }
}
