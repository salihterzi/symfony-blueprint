<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissonVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return \str_starts_with($attribute, 'CAN_');
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        $permissions = $token->getAttribute('permissions');
        if (isset($permissions[ $attribute ])) {
             return (bool)$permissions[ $attribute ];

        }
        return false;
    }
}
