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
 * @Route("auth")
 */
class AuthenticationController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return SuccessResponse
     */
    #[Route('/login',name:"auth_login",methods: ["POST"] )]
    public function login(Request $request)
    {
        return SuccessResponse::create(MessageType::SUCCESS_LOGIN);
    }

    /**
     * @param AuthenticationService $authenticationService
     *
     * @return SuccessResponse
     */

    #[Route('/user',name:"auth_user",methods: ["GET"] )]
    public function user()
    {
        return SuccessResponse::create()->setData(['user'=>$this->getUser()]);
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
