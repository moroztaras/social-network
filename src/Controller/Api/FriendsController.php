<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Friends;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use App\Exception\NotFoundException;

/**
 * Class FriendsController.
 *
 * @Route("api/")
 */
class FriendsController extends Controller
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
     * FriendsController constructor.
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
     * @Route("user/{id}/followers/page/{page}", methods={"GET"}, name="api_user_list_followers", requirements={"id": "\d+", "page": "\d+"})
     */
    public function userListFollowers($id, Request $request, string $page, $limit = 10)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'User Not Found.');
        }

        return $this->json(
          [
            'followers' => $this->paginator->paginate(
              $user->getFriends(),
              $request->query->getInt('page', $page), $limit),
          ],
          Response::HTTP_OK);
    }

    /**
     * @Route("user/{id}/following/page/{page}", methods={"GET"}, name="api_user_list_following", requirements={"id": "\d+", "page": "\d+"})
     */
    public function userListFollowing($id, Request $request, string $page, $limit = 10)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'User Not Found.');
        }
        $following = $this->getDoctrine()->getRepository(Friends::class)->findBy(['user' => $user]);
        if (!$following) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Following Not Found.');
        }

        return $this->json(
          [
            'following' => $this->paginator->paginate(
              $following,
              $request->query->getInt('page', $page), $limit),
          ],
          Response::HTTP_OK);
    }
}
