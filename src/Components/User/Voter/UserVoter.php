<?php

namespace App\Components\User\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * @param string         $attribute
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $this->token = $token;
        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
      case self::EDIT:
        return $this->canEdit($subject, $user);
    }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(User $entity, User $user)
    {
        if ($entity->getId() == $user->getId()) {
            return true;
        }

        return false;
    }
}
