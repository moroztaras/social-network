<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Svistyn;
use App\Exception\JsonHttpException;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\NotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UserController.
 *
 * @Route("api/user")
 */
class UserController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param SerializerInterface          $serializer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface           $validator
     * @param PaginatorInterface           $paginator
     * @param UserService                  $userService
     */
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, PaginatorInterface $paginator, UserService $userService)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->paginator = $paginator;
        $this->userService = $userService;
    }

    /**
     * @Route("/profile", methods={"GET"}, name="api_my_user_profile")
     */
    public function myProfileUserAction(Request $request)
    {
        $apiToken = $request->headers->get('x-api-key');

        /* @var User $user */
        $user = $this->getDoctrine()
          ->getManager()
          ->getRepository(User::class)
          ->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Authentication error');
        }

        return $this->json(['profile' => $user], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/profile", name="api_user_profile", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function profileUser(User $user)
    {
        if (!$user) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json(['profile' => $user], Response::HTTP_OK, [], ['profile' => true]);
    }

    /**
     * @Route("/profile", name="api_edit_user_profile", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function editProfileUser(Request $request)
    {
        return $this->json([
            'profile' => $this->userService->editProfile($request)
        ]);
    }

    /**
     * @Route("/{id}/svist/page={page}", name="api_user_list_svist", methods={"GET"}, requirements={"id": "\d+", "page": "\d+"})
     */
    public function userListSvists($id, Request $request, string $page, $limit = 10)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['id' => $id]);

        if (!$user) {
            throw new JsonHttpException(Response::HTTP_NOT_FOUND, 'User not found');
        }
        $svists = $em->getRepository(Svistyn::class)->findBy(['user' => $user]);

        if (!$svists) {
            throw new JsonHttpException(Response::HTTP_NOT_FOUND, 'Svists not found');
        }

        return $this->json([
            'svists' => $this->paginator->paginate($svists, $request->query->getInt('page', $page), $limit),
          ],
          Response::HTTP_OK
        );
    }
}
