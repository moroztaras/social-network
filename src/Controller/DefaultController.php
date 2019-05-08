<?php

namespace App\Controller;

use App\Entity\Friends;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="front")
     */
    public function front()
    {
//        $this->addFlash('primary', 'this is primary');
//        $this->addFlash('success', 'this is success');
//        $this->addFlash('danger', 'this is danger');
//        $this->addFlash('warning', 'this is warning');

//        return $this->render('front.html.twig');

        if (0 != count($this->getDoctrine()->getRepository(Friends::class)->findBy(['user' => $this->getUser()]))) {
            return $this->redirectToRoute('svistyn_feed_following');
        } else {
            return $this->redirectToRoute('svistyn_post');
        }
    }
}
