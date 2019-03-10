<?php

namespace App\Controller;

use App\Form\Comment\CommentForm;
use App\Entity\User;
use App\Entity\Svistyn;
use App\Entity\Comment;
use App\Services\CommentService;
use App\Security\CommentVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 */
class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    public $commentService;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function new($id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $svistyn = $this->getDoctrine()->getRepository(Svistyn::class)->find($id);

        $comment = $this->commentService->new($id, $user);

        $form = $this->createForm(CommentForm::class, $comment);

        return $this->render('Comment/form.html.twig',
          [
            'user' => $user,
            'comment' => $comment,
            'svistyn' => $svistyn,
            'form_comment' => $form->createView(),
          ]);
    }

    /**
     * @Route("/comment/{id}", methods={"POST"}, name="comment_add", requirements={"id": "\d+"})
     */
    public function addAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $comment = $this->commentService->new($id, $user);
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->commentService->save($comment);

            return $this->redirect($this->generateUrl('svistyn_post_view',
                [
                  'id' => $comment->getSvistyn()->getId(),
                ]).'#comment-'.$comment->getId()
            );
        }

        return $this->redirectToRoute('svistyn_post_view', ['id' => $id]);
    }

    /**
     * @Route("/comment/{id}/edit", methods={"GET", "POST"}, name="comment_edit")
     */
    public function editAction($id, Request $request)
    {
        /** @var Comment $comment */
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        if (!$comment || $comment->getUser() != $this->getUser()) {
            return $this->redirect($this->generateUrl('svistyn_post_view',
                [
                  'id' => $comment->getSvistyn()->getId(),
                ])
            );
        }
        $this->denyAccessUnlessGranted(CommentVoter::EDIT, $comment);
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);
//            $this->flashBag->add('success', 'Comment was edited');

            return $this->redirect($this->generateUrl('svistyn_post_view',
                [
                  'id' => $comment->getSvistyn()->getId(),
                ]).'#comment-'.$comment->getId()
            );
        }

        return $this->render('Comment/edit.html.twig', [
          'form' => $form->createView(),
          'title' => 'Edit comment',
        ]);
    }
    /**
     * @Route("/{id}/delete", methods={"GET", "DELETE"}, name="comment_delete")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $referer = $request->headers->get('referer');
        if (!$comment || $comment->getUser() != $this->getUser()) {
            return $this->redirect($this->generateUrl('svistyn_post_view',
              [
                'id' => $comment->getSvistyn()->getId(),
              ])
            );
        }
        $this->denyAccessUnlessGranted(CommentVoter::EDIT, $comment);
        $this->commentService->remove($comment);
//        $this->flashBag->add('error', 'Comment was deleted');

        return $this->redirect($referer);
    }
}
