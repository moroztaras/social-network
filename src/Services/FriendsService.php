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

    public function changeStatusFriendship(User $user, $id_friend, $status)
    {
        $friend = $this->doctrine->getRepository(User::class)->find($id_friend);
        if (0 == $status) {
            $friendship = $this->doctrine->getRepository(Friends::class)->findOneBy(['user' => $user, 'friend' => $friend]);
            if ($friendship) {
                $this->doctrine->getManager()->remove($friendship);
                $this->flashBag->add('danger', 'friend_remove');
            } else {
                $fr = new Friends();
                $fr->setUser($user);
                $fr->setFriend($friend);
                $this->doctrine->getManager()->persist($fr);
                $this->flashBag->add('success', 'application_has_been_sent');
            }
            $this->doctrine->getManager()->flush();
        } else {
            $friendship = $this->doctrine->getRepository(Friends::class)->findOneBy(['user' => $friend, 'friend' => $user]);
            $friendship->setStatus($status);
            $this->doctrine->getManager()->persist($friendship);
            $this->doctrine->getManager()->flush();
        }
        switch ($status) {
            case 1: $this->flashBag->add('success', 'application_has_been_successfully_verified');
                break;
            case 2: $this->flashBag->add('danger', 'application_has_been_canceled');
                break;
        }
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
