<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Friends;
use App\Entity\Svistyn;
use App\Entity\User;
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

        if (null != $this->getUser()) {
//            $user = $this->getUser();
//            $user = $this->getDoctrine()->getRepository(User::class)->find($user->getId());
//            if ($user->getStatus() == 0)
//            {
//                return $this->redirectToRoute('user_check_block');
//            }
//            else
            $this->check();
            if (0 != count($this->getDoctrine()->getRepository(Friends::class)->findBy(['user' => $this->getUser()]))) {
                return $this->redirectToRoute('svistyn_feed_following');
            } else {
                return $this->redirectToRoute('svistyn_post');
            }
        } else {
            return $this->redirectToRoute('svistyn_post');
        }
    }

    public function check()
    {
        $user = $this->getUser();
        $user = $this->getDoctrine()->getRepository(User::class)->find($user->getId());
        if (0 == $user->getStatus()) {
            return $this->redirectToRoute('user_check_block');
        } else {
            return $this;
        }
    }

    /**
     * @Route("/admin/charts", methods={"GET"}, name="admin_highcharts")
     */
    public function highcharts()
    {
        $time = new \DateTime();
        $month = $time->format('n');
        $months = [];
        $i = 1;
        while ($i <= $month) {
            array_push($months, $i);
            ++$i;
        }
        $users = [];
        $svistyns = [];
        $comments = [];
        $views = [];
        foreach ($months as $value) {
            array_push(
              $users,
              $this->getDoctrine()->getRepository(User::class)->findUsersByMonth($value));
            array_push(
              $svistyns,
              count($this->getDoctrine()->getRepository(Svistyn::class)->findSvistynsByMonth($value)));
            array_push(
              $comments,
              $this->getDoctrine()->getRepository(Comment::class)->findCommentsByMonth($value));
            array_push(
              $views,
              $this->getDoctrine()->getRepository(Svistyn::class)->getCountAllViewsSvistynsByMonth($value)
            );
        }
        return $this->render('Admin/highcharts.html.twig', [
          'users' => $users,
          'svistyns' => $svistyns,
          'comments' => $comments,
          'views' => $views,
        ]);
    }
}
