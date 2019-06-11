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
     * @var array
     */
    private $ukrRus = ['а', 'б', 'в', 'г', 'д', 'е', 'ё', 'є', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'і', 'ь', 'э', 'ю', 'я', ' '];

    /**
     * @var array
     */
    private $lat = ['a', 'b', 'v', 'g', 'd', 'e', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'i', 'y', 'e', 'yu', 'ya', '_'];

    /**
     * GroupUsersService constructor.
     *
     * @param ManagerRegistry   $doctrine
     * @param FlashBagInterface $flashBag
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
    }

    public function save(GroupUsers $groupUsers, User $user)
    {
        $groupUsers
          ->setSlug(str_replace($this->ukrRus, $this->lat, strtolower($groupUsers->getName())))
          ->setAdmin($user);

        $this->saveData($groupUsers);
        $this->flashBag->add('success', 'group_created_successfully');
    }

    public function saveData(GroupUsers $groupUsers)
    {
        $this->doctrine->getManager()->persist($groupUsers);
        $this->doctrine->getManager()->flush();

        return $this;
    }
}
