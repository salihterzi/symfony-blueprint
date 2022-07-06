<?php

namespace App\Subscriber;

use App\Response\ApiResponse;
use App\Response\MessageType;
use App\Response\StatusType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly  TranslatorInterface $translator)
    {
    }

    public function onLogout(LogoutEvent $event)
    {
        $event->setResponse(new JsonResponse([
            'statusCode'=>200,
            'status' => StatusType::SUCCESS,
            'message'=>$this->translator->trans(MessageType::SUCCESS->value)
        ]));
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogout'
        ];
    }
}
