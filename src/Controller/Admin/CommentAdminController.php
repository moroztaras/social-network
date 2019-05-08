<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentAdminController extends Controller
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
     * CommentAdminController constructor.
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
     * @Route("/admin/comments", methods={"GET"}, name="admin_comments_list")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function commentList(Request $request)
    {
        $user = $this->getUser();

        $comments = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/Comment/list.html.twig', [
          'comments' => $comments,
          'user' => $user,
        ]);
    }
}
