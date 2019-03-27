<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SecurityController.
 *
 * @Route("api/user")
 */
class SecurityController extends Controller
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
     * SecurityController constructor.
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
     * @Route("/login", methods={"GET","POST"}, name="api_user_login")
     */
    public function loginUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /* @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $plainPassword = $user->getPlainPassword();
        if (empty($plainPassword) or empty($user->getEmail())) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $user = $this->getDoctrine()
          ->getManager()
          ->getRepository(User::class)
          ->findOneBy(['email' => $user->getEmail()]);
        if (!$user) {
            throw new JsonHttpException(400, 'Authentication error');
        }
        if ($passwordEncoder->isPasswordValid($user, $plainPassword)) {
            $user->setApiToken(Uuid::uuid4());
            $this->getDoctrine()->getManager()->flush();

            return $this->json(['user' => $user]);
        }

        throw new JsonHttpException(400, 'Incorrect password');
    }
}
