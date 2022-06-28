<?php

namespace App\Controller;

use App\Response\MessageType;
use App\Response\SuccessResponse;
use App\Service\AuthenticationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthenticationController
 * @package App\Controller
 * @Route("auth")
 */
class AuthenticationController
{
    /**
     * @Route("/login", name="auth_login", methods={"POST"})
     *
     * @param Request $request
     *
     * @return SuccessResponse
     */
    public function login(Request $request)
    {
        return SuccessResponse::create(MessageType::SUCCESS_LOGIN);
    }

    /**
     * @Route("/user", name="auth_user", methods={"GET"})
     *
     * @param AuthenticationService $authenticationService
     *
     * @return SuccessResponse
     */
    public function user(AuthenticationService $authenticationService)
    {
        return $authenticationService->getAuthResponse();
    }

    /**
     * @Route("/logout", name="auth_logout", methods={"POST"})
     *
     * @return SuccessResponse
     */
    public function logout()
    {
        return SuccessResponse::create();
    }
}
