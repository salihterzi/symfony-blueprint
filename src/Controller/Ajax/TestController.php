<?php

namespace App\Controller\Ajax;

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

    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        return SuccessResponse::create()->setMessageType(MessageType::SUCCESS_LOGIN)->setData([
            'attribute'=>"value"
        ]);
    }
}
