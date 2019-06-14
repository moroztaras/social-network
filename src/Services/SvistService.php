<?php

namespace App\Services;

use App\Entity\Svistyn;
use App\Entity\GroupUsers;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Knp\Component\Pager\PaginatorInterface;

class SvistService
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * SvistService constructor.
     *
     * @param ManagerRegistry    $doctrine
     * @param FlashBagInterface  $flashBag
     * @param PaginatorInterface $paginator
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag, PaginatorInterface $paginator)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
        $this->paginator = $paginator;
    }

    public function getSvistynsForGroup(GroupUsers $groupUsers, Request $request)
    {
        return $this->paginator->paginate(
          $groupUsers->getSvistyns(),
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 10)
        );
    }

    public function block($id)
    {
        $svistyn = $this->getSvistyn($id);
        if (1 == $svistyn->getStatus()) {
            $svistyn->setStatus(0);
            $this->flashBag->add('danger', 'svistyn_is_blocked');
        } else {
            $svistyn->setStatus(1);
            $this->flashBag->add('success', 'svistyn_unblocked');
        }
        $this->save($svistyn);

        return $this;
    }

    public function svistynRepo()
    {
        return  $this->doctrine->getManager()->getRepository(Svistyn::class);
    }

    public function getSvistyn($id)
    {
        return  $this->doctrine->getManager()->getRepository(Svistyn::class)->find($id);
    }

    public function addViewSvistyn(Svistyn $svistyn)
    {
        $svistyn->setViews($svistyn->getViews() + 1);
        $this->save($svistyn);
    }

    public function save(Svistyn $svistyn)
    {
        $this->doctrine->getManager()->persist($svistyn);
        $this->doctrine->getManager()->flush();

        return $svistyn;
    }
}
