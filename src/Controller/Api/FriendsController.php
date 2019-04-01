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
use App\Exception\JsonHttpException;
use App\Exception\AccessDeniedException;

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

    /**
     * @Route("user/friend/{id_friend}/add", methods={"POST"}, name="api_user_friend_add", requirements={"id_friend": "\d+"})
     */
    public function userFriendAdd($id_friend, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $apiToken = $request->headers->get('x-api-key');

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }

        $friend = $this->getDoctrine()->getRepository(User::class)->find($id_friend);
        if (!$friend) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Friend Not Found.');
        }
        if ($user == $friend) {
            throw new AccessDeniedException(Response::HTTP_FORBIDDEN, 'Access Denied.');
        }
        $friendship = $this->getDoctrine()->getRepository(Friends::class)->findOneBy(['user' => $user, 'friend' => $friend]);

        if ($friendship) {
            $this->getDoctrine()->getManager()->remove($friendship);
            $message = 'You unsubscribed from a friend';
        } else {
            $fr = new Friends();
            $fr->setUser($user);
            $fr->setFriend($friend);
            $this->getDoctrine()->getManager()->persist($fr);
            $message = 'You are subscribed to a friend';
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json([
          'success' => [
            'code' => Response::HTTP_OK,
            'message' => $message,
          ],
        ], Response::HTTP_OK);
    }
}
