<?php

namespace App\Services;

use App\Entity\Svistyn;
use Doctrine\Common\Persistence\ManagerRegistry;

class SvistService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * SvistService constructor.
     *
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function svistynRepo()
    {
        return  $this->doctrine->getManager()->getRepository(Svistyn::class);
    }

    public function getSvistyn($id)
    {
        return  $this->doctrine->getManager()->getRepository(Svistyn::class)->find($id);
    }
}
