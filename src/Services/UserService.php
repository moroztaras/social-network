<?php

namespace App\Services;

use App\Components\User\Models\ChangePasswordModel;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\JsonHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserService
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * UserService constructor.'
     *
     * @param SerializerInterface          $serializer
     * @param ManagerRegistry              $doctrine
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param PaginatorInterface           $paginator
     * @param TokenGeneratorInterface      $tokenGenerator
     * @param FlashBagInterface            $flashBag
     */
    public function __construct(SerializerInterface $serializer, ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder, PaginatorInterface $paginator, TokenGeneratorInterface $tokenGenerator, FlashBagInterface $flashBag)
    {
        $this->serializer = $serializer;
        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;
        $this->paginator = $paginator;
        $this->tokenGenerator = $tokenGenerator;
        $this->flashBag = $flashBag;
    }

    public function save(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setApiToken($this->tokenGenerator->generateToken());
        $this->saveData($user);

        return $user;
    }

    public function editProfile(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $apiToken = $request->headers->get('x-api-key');

        $user = $this->doctrine->getRepository(User::class)->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(Response::HTTP_UNAUTHORIZED, 'Authentication error');
        }

        $this->serializer->deserialize($request->getContent(), User::class, JsonEncoder::FORMAT, ['edit' => true, 'object_to_populate' => $user]);

        $this->saveData($user);

        return $user;
    }

    public function changePasswordModel(User $user, ChangePasswordModel $changePasswordModel)
    {
        $password = $this->passwordEncoder->encodePassword($user, $changePasswordModel->plainPassword);
        $user->setPassword($password);
        $user->setTokenRecover(null);
        $this->saveData($user);

        return $this;
    }

    public function getlistSerarchUsers(Request $request)
    {
        if ($request->query->get('search')) {
            $data = $request->query->get('search');
        } else {
            $data = $request->query->get('search_input');
        }

        return $this->paginator->paginate(
          $this->doctrine->getRepository(User::class)->findUsersByData($data),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
    }

    public function block(User $user)
    {
        if (1 == $user->getStatus()) {
            $user->setStatus(0);
            $this->flashBag->add('danger', 'user_is_blocked');
        } else {
            $user->setStatus(1);
            $this->flashBag->add('success', 'user_unblocked');
        }
        $this->saveData($user);

        return $this;
    }

    private function saveData(User $user)
    {
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $this;
    }
}
