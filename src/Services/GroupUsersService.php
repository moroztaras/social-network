<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\GroupUsers;
use App\Entity\GroupUsersRequest;
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
          ->setAdmin($user)
          ->addUser($user)
        ;

        $this->saveData($groupUsers);
        $this->flashBag->add('success', 'group_created_successfully');
    }

    public function saveFollower(GroupUsers $groupUsers, $id)
    {
        $status = 0;
        foreach ($groupUsers->getUsers() as $user) {
            if ($user->getId() == $id) {
                $status = 1;
            }
        }
        switch ($status) {
            case 0:
                $groupUsers->addUser($this->doctrine->getRepository(User::class)->find($id));
                $this->flashBag->add('success', 'you_joined_the_group');
                break;
            case 1:
                $groupUsers->removeUser($this->doctrine->getRepository(User::class)->find($id));
                $this->flashBag->add('danger', 'you_left_the_group');
                break;
        }

        $this->saveData($groupUsers);
    }

    public function addOrRemoveFollower(GroupUsers $groupUsers, $id, $status)
    {
        if ($status == "accept"){
            $groupUsers->addUser($this->doctrine->getRepository(User::class)->find($id));
            $this->flashBag->add('success', 'user_joined_the_group');
        }else{
            $this->flashBag->add('danger', 'request_to_group_is_canceled');
        }
        $this->saveData($groupUsers);
    }

    public function sendRequest(User $user, GroupUsers $groupUsers)
    {
        $groupUsersRequest = new GroupUsersRequest();
        $groupUsersRequest->setUser($user);
        $groupUsersRequest->setGroupUsers($groupUsers);
        $this->saveGroupUsersRequest($groupUsersRequest);

        $this->flashBag->add('success', 'your_subscription_request_has_been_submitted_to_the_group');
    }

    private function saveGroupUsersRequest(GroupUsersRequest $groupUsersRequest)
    {
        $this->doctrine->getManager()->persist($groupUsersRequest);
        $this->doctrine->getManager()->flush();

        return $this;
    }

    public function getStatusButton(GroupUsers $groupUsers, $id)
    {
        $status = 0;
        foreach ($groupUsers->getUsers() as $user) {
            if ($user->getId() == $id) {
                $status = 1;
            }
        }

        return $status;
    }

    public function saveData(GroupUsers $groupUsers)
    {
        $this->doctrine->getManager()->persist($groupUsers);
        $this->doctrine->getManager()->flush();

        return $this;
    }
}
