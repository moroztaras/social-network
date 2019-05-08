<?php

namespace App\Controller\Admin;

use App\Entity\Svistyn;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SvistAdminController extends Controller
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * SvistAdminController constructor.
     *
     * @param FlashBagInterface  $flashBag
     * @param PaginatorInterface $paginator
     */
    public function __construct(FlashBagInterface $flashBag, PaginatorInterface $paginator)
    {
        $this->flashBag = $flashBag;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @Route("/admin/posts", methods={"GET"}, name="admin_svistyn_list")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function svistynList(Request $request)
    {
        $user = $this->getUser();
        $svistyns = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->findAll(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/Svistyn/list.html.twig', [
          'svistyns' => $svistyns,
          'user' => $user,
        ]);
    }
}
