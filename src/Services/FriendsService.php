<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Friends;
use Doctrine\Common\Persistence\ManagerRegistry;

class FriendsService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * UserService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function save($id_user, $id_friend)
    {
        $user = $this->doctrine->getRepository(User::class)->find($id_user);
        $friend = $this->doctrine->getRepository(User::class)->find($id_friend);

        $friendship = $this->doctrine->getRepository(Friends::class)->findOneBy(['user' => $user, 'friend' => $friend]);

        if ($friendship) {
            $this->doctrine->getManager()->remove($friendship);
        } else {
            $fr = new Friends();
            $fr->setUser($user);
            $fr->setFriend($friend);
            $this->doctrine->getManager()->persist($fr);
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
