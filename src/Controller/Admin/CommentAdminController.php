<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Services\CommentService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentAdminController.
 *
 * @Route("/admin/comments")
 */
class CommentAdminController extends Controller
{
    /**
     * @var CommentService
     */
    private $commentService;

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
     * @param CommentService     $commentService
     * @param FlashBagInterface  $flashBag
     * @param PaginatorInterface $paginator
     */
    public function __construct(CommentService $commentService, FlashBagInterface $flashBag, PaginatorInterface $paginator)
    {
        $this->commentService = $commentService;
        $this->flashBag = $flashBag;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @Route("", methods={"GET"}, name="admin_comments_list")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function commentList(Request $request)
    {
        $user = $this->getUser();

        $comments = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(Comment::class)->getAllComments(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/Comment/list.html.twig', [
          'comments' => $comments,
          'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}/block", methods={"GET"}, name="admin_comment_block")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function commentBlock($id)
    {
        $comment = $this->getDoctrine()->getManager()->getRepository(Comment::class)->find($id);

        $this->commentService->block($comment);

        return $this->redirectToRoute('admin_comments_list');
    }

    /**
     * @param Request $request
     * @Route("/block", methods={"GET"}, name="admin_comments_list_block")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     *
     * @return Response
     */
    public function commentListBlock(Request $request)
    {
        $user = $this->getUser();

        $comments = $this->paginator->paginate(
          $this->getDoctrine()->getManager()->getRepository(Comment::class)->findBlockComments(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );

        return $this->render('Admin/Comment/list.html.twig', [
          'comments' => $comments,
          'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/delete", methods={"GET", "DELETE"}, name="admin_comment_delete")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $referer = $request->headers->get('referer');
        if (!$comment) {
            $this->flashBag->add('danger', 'comment_not_found');

            return $this->redirect($referer);
        }
        $this->commentService->remove($comment);
        $this->flashBag->add('danger', 'comment_was_deleted');

        return $this->redirect($referer);
    }

    /**
     * @return Response
     */
    public function getAdminCountBlockComments()
    {
        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findBlockComments();
        if (!$comments) {
            return new Response('0');
        } else {
            return new Response(count($comments));
        }
    }
}
