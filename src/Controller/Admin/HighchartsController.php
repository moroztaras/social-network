<?php

namespace App\Controller\Admin;

use App\Form\Filter\Model\FilterModel;
use App\Form\Filter\FilterForm;
use App\Services\HighChartsService;
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
     * @Route("", methods={"GET", "POST"}, name="admin_highcharts")
     *
     * @param Request           $request
     * @param HighChartsService $chartsService
     * @param FilterModel       $filterModel
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function highCharts(Request $request, HighChartsService $chartsService, FilterModel $filterModel)
    {
        $formIsSubmitted = false;
        $filter_months = [];
        $filter_users = [];
        $filter_comments = [];
        $filter_svistyns = [];
        $filter_views = [];

        $form = $this->createForm(FilterForm::class, $filterModel);
        $users = $chartsService->getUsers();
        $svistyns = $chartsService->getSvistyns();
        $comments = $chartsService->getComments();
        $views = $chartsService->getVies();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formIsSubmitted = true;
            $filter_months = $chartsService->getMonths($filterModel->getInitialMonth(), $filterModel->getFinalMonth());
            $filter_users = $chartsService->getFilterUsers($filter_months, $filterModel->getInitialYear());
            $filter_svistyns = $chartsService->getFilterSvistyns($filter_months, $filterModel->getInitialYear());
            $filter_comments = $chartsService->getFilterComments($filter_months, $filterModel->getInitialYear());
            $filter_views = $chartsService->getFilterViews($filter_months, $filterModel->getInitialYear());
        }

        return $this->render('Admin/Highcharts/highcharts.html.twig', [
          'form' => $form->createView(),
          'users' => $users,
          'svistyns' => $svistyns,
          'comments' => $comments,
          'views' => $views,
          'formIsSubmitted' => $formIsSubmitted,
          'filter_users' => $filter_users,
          'filter_months' => $filter_months,
          'filter_comments' => $filter_comments,
          'filter_svistyns' => $filter_svistyns,
          'filter_views' => $filter_views,
        ]);
    }
}
