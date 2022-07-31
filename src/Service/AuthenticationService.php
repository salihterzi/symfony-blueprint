<?php

namespace App\Service;

use App\Entity\User;
use App\Response\SuccessResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationService
{
    public function __construct(
        private TokenStorageInterface $tokenStorage)
    {
    }

    public function getCurrentUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        $user = $token?->getUser();
        if (!$user instanceof User) {
            $user = null;
        }

        return $user;
    }

    public function createPermission(UserInterface $user)
    {
        $permissionMap = [];
        $roles = $user->getRolesAsCollection();
        foreach ($roles as $role) {
            $permissions = $role->getPermissions();
            foreach ($permissions as $permission) {
                $key = 'CAN_' . strtoupper($permission->getName());
                $permissionMap[$key] = true;
            }
        }
        return $permissionMap;
    }

    public function getPermissions(): array
    {
        $permissions = [];

        $token = $this->tokenStorage->getToken();
        if ($token !== null && $token->hasAttribute('permissions')) {
            $permissions = $token->getAttribute('permissions');
        }

        return $permissions;
    }

    public function getAuthResponse(): SuccessResponse
    {
        $currentUser = $this->getCurrentUser();
        $user = null;

        if ($currentUser !== null) {
            $user = [
                'email' => $currentUser->getEmail(),
                'firstName' => $currentUser->getFirstName(),
                'lastName' => $currentUser->getLastName()
            ];

            $user['permissions'] = $this->getPermissions();
        }

        return SuccessResponse::create()->setData(['user' => $user])->setGroups(['auth']);
    }

}
