<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\GroupUsers;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class GroupUsersService
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
     * GroupService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
    }

    public function save(GroupUsers $groupUsers, User $user)
    {
        $groupUsers->setAdmin($user);
        $this->saveData($groupUsers);
        $this->flashBag->add('success', 'Група успішно зареєстрована');
    }

    public function saveData(GroupUsers $groupUsers)
    {
        $this->doctrine->getManager()->persist($groupUsers);
        $this->doctrine->getManager()->flush();

        return $this;
    }
}
