<?php

namespace App\Services;

use App\Components\User\Models\ChangePasswordModel;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
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
     * UserService constructor.
     *
     * @param ManagerRegistry              $doctrine
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param PaginatorInterface           $paginator
     */
    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder, PaginatorInterface $paginator)
    {
        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;
        $this->paginator = $paginator;
    }

    public function save(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function changePasswordModel(User $user, ChangePasswordModel $changePasswordModel)
    {
        $password = $this->passwordEncoder->encodePassword($user, $changePasswordModel->plainPassword);
        $user->setPassword($password);
        $user->setTokenRecover(null);
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

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
}
