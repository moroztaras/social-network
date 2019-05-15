<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HighchartsController.
 *
 * @Route("/admin/charts")
 */
class HighchartsController extends Controller
{
    /**
     * @Route("", methods={"GET"}, name="admin_highcharts")
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
        return $this->render('Admin/Highcharts/highcharts.html.twig', [
          'users' => $users,
          'svistyns' => $svistyns,
          'comments' => $comments,
          'views' => $views,
        ]);
    }
}
