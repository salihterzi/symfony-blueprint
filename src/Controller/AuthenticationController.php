<?php

namespace App\Controller;

use App\Response\MessageType;
use App\Response\SuccessResponse;
use App\Service\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthenticationController
 * @package App\Controller
 */
#[Route('auth')]

class AuthenticationController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return SuccessResponse
     */
    #[Route('/login',name:"auth_login",methods: ["POST"] )]
    public function login()
    {
        return SuccessResponse::create()->setMessageType(MessageType::SUCCESS_LOGIN);
    }

    #[Route('/user',name:"auth_user",methods: ["GET"] )]
    public function user(AuthenticationService $authenticationService)
    {
        return $authenticationService->getAuthResponse();
    }

    #[Route('/logout', name:"auth_logout",methods: ["POST"] )]
    public function logout()
    {
        return SuccessResponse::create();
    }
}
