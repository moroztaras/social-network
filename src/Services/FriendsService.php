<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Friends;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FriendsService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * FriendsService constructor.
     *
     * @param ManagerRegistry   $doctrine
     * @param FlashBagInterface $flashBag
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
    }

    public function save($id_user, $id_friend)
    {
        $user = $this->doctrine->getRepository(User::class)->find($id_user);
        $friend = $this->doctrine->getRepository(User::class)->find($id_friend);

        $friendship = $this->doctrine->getRepository(Friends::class)->findOneBy(['user' => $user, 'friend' => $friend]);

        if ($friendship) {
            $this->doctrine->getManager()->remove($friendship);
            $this->flashBag->add('danger', 'Ви відписалися від '.$friend->getFullname());
        } else {
            $fr = new Friends();
            $fr->setUser($user);
            $fr->setFriend($friend);
            $this->doctrine->getManager()->persist($fr);
            $this->flashBag->add('success', 'Ви підписалися на '.$friend->getFullname());
        }
        $this->doctrine->getManager()->flush();
    }

    public function check($id_user, $id_friend)
    {
        $friendship = $this->doctrine->getRepository(Friends::class)->findOneBy(
          [
            'user' => $this->doctrine->getRepository(User::class)->find($id_user),
            'friend' => $this->doctrine->getRepository(User::class)->find($id_friend),
          ]);

        if ($friendship) {
            return false;
        } else {
            return true;
        }
    }
}
