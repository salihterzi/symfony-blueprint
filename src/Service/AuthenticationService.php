<?php

namespace App\Service;

use App\Entity\User;
use App\Response\SuccessResponse;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AuthenticationService
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
        private TokenInterface $token,
       // private SessionInterface $session,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function getCurrentUser(): ?User
    {
        $user = $this->token->getUser();

        if (!$user instanceof User) {
            $user = null;
        }

        return $user;
    }

    public function getAuthResponse(): SuccessResponse
    {


           }

}
