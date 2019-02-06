<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
//        $this->addFlash('primary', 'this is primary');
//        $this->addFlash('success', 'this is success');
//        $this->addFlash('danger', 'this is danger');
//        $this->addFlash('warning', 'this is warning');

        return $this->render('front.html.twig');
    }
}
