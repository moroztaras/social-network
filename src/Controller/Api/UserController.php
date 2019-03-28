<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController.
 *
 * @Route("api/user")
 */
class UserController extends Controller
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
     * UserController constructor.
     *
     * @param SerializerInterface          $serializer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface           $validator
     */
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
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
            throw new JsonHttpException(400, 'Authentication error');
        }

        return $this->json(['user' => $user]);
    }

    /**
     * @Route("/{id}/profile", name="api_user_profile", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function profileUserAction(User $user)
    {
        if (!$user) {
            throw new NotFoundException(Response::HTTP_NOT_FOUND, 'Not Found.');
        }

        return $this->json(['user' => $user], Response::HTTP_OK);
    }
}
