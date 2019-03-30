<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentController.
 *
 * @Route("api/comments")
 */
class CommentController extends Controller
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
}
