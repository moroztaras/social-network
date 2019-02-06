<?php

namespace App\Components\Utils;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Pagination
{
    private $page;

    private $next = false;

    private $previous = false;

    private $route;

    private $routeOptions;

    private $routing;

    public function __construct(RouterInterface $router)
    {
        $this->page = 1;
        $this->routing = $router;
        $this->routeOptions = ['page' => 1];
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page = null): void
    {
        if ($page <= 1) {
            $page = 1;
        }
        $this->page = $page;
        if ($page > 1) {
            $this->previous = true;
        } else {
            $this->previous = false;
        }
    }

    /**
     * @return bool
     */
    public function isNext(): bool
    {
        return $this->next;
    }

    /**
     * @param bool $next
     */
    public function setNext(bool $next): void
    {
        $this->next = $next;
    }

    /**
     * @return bool
     */
    public function isPrevious(): bool
    {
        return $this->previous;
    }

    /**
     * @param bool $previous
     */
    public function setPrevious(bool $previous): void
    {
        $this->previous = $previous;
    }

    /**
     * @return string
     */
    public function routeNext()
    {
        $options = $this->routeOptions;
        $options['page'] = $this->getPage() + 1;

        return $this->routing->generate($this->route, $options, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @return string
     */
    public function routePrevious()
    {
        $options = $this->routeOptions;
        $options['page'] = $this->getPage() - 1;

        return $this->routing->generate($this->route, $options, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->routing->generate($this->route, $this->routeOptions, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param $route
     * @param array $options
     */
    public function setRoute($route, array $options = []): void
    {
        $this->route = $route;
        $this->routeOptions = $options;
    }
}
