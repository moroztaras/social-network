<?php

namespace App\Components\Svistyn\Voter;

use App\Entity\Svistyn;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SvistynVoter extends Voter
{
    const EDIT = 'edit';

    private $token;
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Svistyn) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $this->token = $token;
        if (!$user instanceof User) {
            return false;
        }

        /** @var Svistyn $post */
        $svistyn = $subject;

        switch ($attribute) {
      case self::EDIT:
        return $this->canEdit($svistyn, $user);
    }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Svistyn $svistyn, User $user)
    {
        if ($svistyn->getUser()->getId() == $user->getId()) {
            return true;
        }
        if ($this->decisionManager->decide($this->token, ['ROLE_ADMIN'])) {
            return true;
        }

        return false;
    }
}
