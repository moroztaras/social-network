<?php

namespace App\Controller\Api;

use App\Entity\User;
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
     * @Route("user/{id}/followers/page/{page}", methods={"GET"}, name="api_user_list_followers")
     */
    public function userListFollowers($id, Request $request, string $page, $limit = 10)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'User Not Found.');
        }

        return $this->json(
          [
            'friends' => $this->paginator->paginate(
              $user->getFriends(),
              $request->query->getInt('page', $page), $limit),
          ],
          Response::HTTP_OK);
    }
}
