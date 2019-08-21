<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Svistyn;
use App\Entity\User;
use App\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;
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
     * @param UserService        $userService
     * @param FlashBagInterface  $flashBag
     * @param PaginatorInterface $paginator
     */
    public function __construct(UserService $userService, FlashBagInterface $flashBag, PaginatorInterface $paginator)
    {
        $this->userService = $userService;
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
     * @param Request $request
     * @Route("/admin/users/block", methods={"GET"}, name="admin_user_list_block")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function userListBlock(Request $request)
    {
        $user = $this->getUser();

        $users = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(User::class)->findUsersBlock(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/User/list.html.twig', [
          'users' => $users,
          'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}/block", methods={"GET"}, name="admin_user_block")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function userBlock($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        if ($user == $this->getUser()) {
            $this->flashBag->add('danger', 'blocking_user_is_prohibited');
        } else {
            $this->userService->block($user);
        }

        return $this->redirectToRoute('admin_user_list');
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
    public function getAdminCountBlockUsers()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findUsersBlock();
        if (!$users) {
            return new Response('0');
        } else {
            return new Response(count($users));
        }
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
