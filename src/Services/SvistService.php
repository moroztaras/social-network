<?php

namespace App\Services;

use App\Entity\Svistyn;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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
     * SvistService constructor.
     *
     * @param ManagerRegistry   $doctrine
     * @param FlashBagInterface $flashBag
     */
    public function __construct(ManagerRegistry $doctrine, FlashBagInterface $flashBag)
    {
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
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
