<?php

namespace App\Components\Svistyn;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class SvistynApi
{
    private $requestStack;

    private $em;

    public function __construct(
    RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->em = $entityManager;
    }

    public function handlerRequest()
    {
        return new Response('404!');
    }
}
