<?php

namespace App\Controller\Ajax;

use App\Request\TestRequest;
use App\Response\InfoResponse;
use App\Response\MessageType;
use App\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/test')]
    #[IsGranted("CAN_TEST_PERMISSION")]
    public function index(TestRequest $request)
    {
        return SuccessResponse::create()->setMessageType(MessageType::SUCCESS_LOGIN)->setData([
            'attribute'=>"value"
        ]);
    }
}
