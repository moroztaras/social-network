<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Entity\User;
use App\Exception\JsonHttpException;
use App\Exception\NotFoundException;
use App\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentController.
 *
 * @Route("api/comments")
 */
class CommentController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CommentController constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface  $validator
     * @param PaginatorInterface  $paginator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, PaginatorInterface $paginator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/page={page}", name="api_list_comments", methods={"GET"}, requirements={"page": "\d+"})
     */
    public function listComments(Request $request, string $page, $limit = 10)
    {
        $comments = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();

        if (!$comments) {
            throw new JsonHttpException(Response::HTTP_NOT_FOUND, 'Comments not found');
        }

        return $this->json(
          [
            'comments' => $this->paginator->paginate(
              $comments,
              $request->query->getInt('page', $page), $limit),
          ],
          Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_show_comment", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function showComment(Comment $comment)
    {
        if (!$comment) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Comment Not Found.');
        }

        return $this->json(['comment' => $comment], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_edit_comment", methods={"PUT"}, requirements={"id": "\d+"})
     */
    public function editComment(Request $request, Comment $comment)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_UNAUTHORIZED, 'Authentication error');
        }

        $this->serializer->deserialize($request->getContent(), Comment::class, 'json', ['object_to_populate' => $comment]);

        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->json(['comment' => $comment]);
    }

    /**
     * @Route("/{id}", name="api_comment_delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function removeSvist(Request $request, Comment $comment)
    {
        if (!$comment) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Comment Not Found.');
        }
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }
        if ($user !== $comment->getUser()) {
            throw new AccessDeniedException(Response::HTTP_FORBIDDEN, 'Access Denied.');
        }
        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
          'success' => [
            'code' => Response::HTTP_OK,
            'message' => 'Comment was deleted',
          ],
        ], Response::HTTP_OK);
    }
}
