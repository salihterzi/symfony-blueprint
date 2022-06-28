<?php

namespace App\Service;

use App\Entity\User;
use App\Response\SuccessResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AuthenticationService
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $authorizationChecker,
        private SessionInterface $session,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function getCurrentUser(): ?User
    {
        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user instanceof User) {
            $user = null;
        }

        return $user;
    }

    public function getAuthResponse(): SuccessResponse
    {
        $currentUser = $this->getCurrentUser();
        $user = null;

        if (null !== $currentUser) {
            $user = [
                'id'        => $currentUser->getId(),
                'email'     => $currentUser->getEmail(),
                'firstName' => $currentUser->getFirstName(),
                'lastName'  => $currentUser->getLastName(),
            ];
        }

        $switched = $this->authorizationChecker->isGranted('IS_IMPERSONATOR');

        return SuccessResponse::create()->setData(compact('user', 'switched'))->setGroups('auth');
    }

    /**
     * @param Request $request
     * @param User $user
     */
    public function loginUser(Request $request, User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

        $this->tokenStorage->setToken($token);

        // If the firewall name is not main, then the set value would be instead:
        $this->session->set('_security_main', serialize($token));

        // Fire the login event manually
        $event = new InteractiveLoginEvent($request, $token);
        $this->dispatcher->dispatch($event,"security.interactive_login", );
    }

}
