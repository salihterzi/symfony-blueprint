<?php

namespace App\Controller\Ajax;

use App\Request\TestRequest;
use App\Response\InfoResponse;
use App\Response\MessageType;
use App\Response\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct()
    {
    }


    #[Route('/test')]
    public function index(TestRequest $request)
    {
        return SuccessResponse::create()->setMessageType(MessageType::SUCCESS_LOGIN)->setData([
            'attribute'=>"value"
        ]);
    }
}
