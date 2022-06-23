<?php

namespace App\Controller\Ajax;

use App\Response\InfoResponse;
use App\Response\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return InfoResponse::create()->setMessageType(MessageType::SUCCESS_LOGIN);
    }
}
