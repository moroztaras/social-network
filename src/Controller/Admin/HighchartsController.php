<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use App\Form\Filter\Model\FilterModel;
use App\Form\Filter\FilterForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function highCharts()
    {
        $time = new \DateTime();
        $month = $time->format('n');
        $year = $time->format('Y');
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
              $this->getDoctrine()->getRepository(User::class)->findUsersByMonth($value, $year));
            array_push(
              $svistyns,
              count($this->getDoctrine()->getRepository(Svistyn::class)->findSvistynsByMonth($value, $year)));
            array_push(
              $comments,
              $this->getDoctrine()->getRepository(Comment::class)->findCommentsByMonth($value, $year));
            array_push(
              $views,
              $this->getDoctrine()->getRepository(Svistyn::class)->getCountAllViewsSvistynsByMonth($value, $year)
            );
        }

        return $this->render('Admin/Highcharts/highcharts.html.twig', [
          'users' => $users,
          'svistyns' => $svistyns,
          'comments' => $comments,
          'views' => $views,
        ]);
    }

    /**
     * @Route("/filter", methods={"GET", "POST"}, name="admin_filter_form_highcharts")
     */
    public function FormHighCharts(Request $request, FilterModel $filterModel)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(FilterForm::class, $filterModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('admin_filter_highcharts', [
              'initial_month' => $filterModel->getInitialMonth(),
              'initial_year' => $filterModel->getInitialYear(),
              'final_month' => $filterModel->getFinalMonth(),
              'final_year' => $filterModel->getFinalYear(),
            ]);
        }

        return $this->render('Admin/Highcharts/Filter/form.html.twig', [
          'form' => $form->createView(),
          'user' => $user,
        ]);
    }

    /**
     * @Route("/filter/{initial_month}/{initial_year}/{final_month}/{final_year}", methods={"GET"}, name="admin_filter_highcharts")
     */
    public function FilterHighCharts($initial_month, $initial_year, $final_month, $final_year, Request  $request)
    {
        $months = [];
        if ($initial_month <= $final_month) {
            while ($initial_month <= $final_month) {
                array_push($months, $initial_month);
                ++$initial_month;
            }
        } else {
            while ($initial_month <= 12) {
                array_push($months, $initial_month);
                ++$initial_month;
            }
            $initial_month = 1;
            while ($initial_month <= $final_month) {
                array_push($months, $initial_month);
                ++$initial_month;
            }
        }
        $users = [];
        $svistyns = [];
        $comments = [];
        $views = [];
        foreach ($months as $value) {
            array_push(
              $users,
              $this->getDoctrine()->getRepository(User::class)->findUsersByMonth($value, $initial_year));
            array_push(
              $svistyns,
              count($this->getDoctrine()->getRepository(Svistyn::class)->findSvistynsByMonth($value, $initial_year)));
            array_push(
              $comments,
              $this->getDoctrine()->getRepository(Comment::class)->findCommentsByMonth($value, $initial_year));
            array_push(
              $views,
              $this->getDoctrine()->getRepository(Svistyn::class)->getCountAllViewsSvistynsByMonth($value, $initial_year)
            );
            if (12 == $value) {
                ++$initial_year;
            }
        }

        return $this->render('Admin/Highcharts/Filter/highcharts.html.twig', [
          'users' => $users,
          'svistyns' => $svistyns,
          'comments' => $comments,
          'views' => $views,
          'months' => $months,
        ]);
    }
}
