<?php

namespace App\Controller;

use App\Form\Comment\CommentForm;
use App\Entity\User;
use App\Entity\Svistyn;
use App\Services\CommentService;
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
     * @Route("/comment/{id}", name="comment_create", requirements={"id": "\d+"})
     */
    public function createAction(Request $request, $id)
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
}
