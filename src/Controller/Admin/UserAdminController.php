<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends Controller
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
     * UserAdminController constructor.
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
     * @Route("/admin", methods={"GET"}, name="admin_dashboard")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function userAdmin()
    {
        $user = $this->getUser();

        if (!$user) {
            $this->flashBag->add('danger', 'user_not_found');
        }

        return $this->render('Admin/User/dashboard.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/admin/users", methods={"GET"}, name="admin_user_list")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function userList(Request $request)
    {
        $user = $this->getUser();

        $users = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(User::class)->findAll(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/User/list.html.twig', [
          'users' => $users,
          'user' => $user,
        ]);
    }

    /**
     * @return Response
     */
    public function getCountAllUsers()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return new Response(count($users));
    }

    /**
     * @return Response
     */
    public function getAdminCountAllSvistyns()
    {
        $svistyns = $this->getDoctrine()->getManager()->getRepository(Svistyn::class)->findAll();
        if ($svistyns) {
            return new Response(count($svistyns));
        } else {
            return new Response('0');
        }
    }

    /**
     * @return Response
     */
    public function getAdminCountAllUsers()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return new Response(count($users));
    }

    /**
     * @return Response
     */
    public function getAdminCountAllComments()
    {
        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
        if ($comments) {
            return new Response(count($comments));
        } else {
            return new Response('0');
        }
    }
}
