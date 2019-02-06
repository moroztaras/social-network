<?php

namespace App\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FilterTwigExtension extends \Twig_Extension
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getFilters()
    {
        return [
      new \Twig_SimpleFilter('attr', [$this, 'attr']),
      new \Twig_SimpleFilter('gender', [$this, 'gender']),
    ];
    }

    public function getFunctions()
    {
        return [
      new \Twig_SimpleFunction('is_vote', [$this, 'isVote']),
    ];
    }

    public function attr(array $attributes)
    {
        $string = [];
        foreach ($attributes as $k => $v) {
            $string[] = $k.'="'.$v.'"';
        }

        return implode(' ', $string);
    }

    public function isVote($vote, $object)
    {
        return $this->authorizationChecker->isGranted($vote, $object);
    }

    public function gender($type)
    {
        $gender = [
      'm' => 'Мужской',
      'w' => 'Женский',
    ];

        return isset($gender[$type]) ? $gender[$type] : '';
    }
}
