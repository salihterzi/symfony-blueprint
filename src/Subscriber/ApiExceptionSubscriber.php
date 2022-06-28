<?php

namespace App\Subscriber;

use App\Exception\ApiException;
use App\Exception\InvalidRequestException;
use App\Response\ErrorResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private SerializerInterface $serializer
    )
    {
    }

    public function onKernelException(ExceptionEvent $event):void
    {
        if ($event->getRequest()->getRequestFormat() === 'json') {
            $exception = $event->getThrowable();
            //todo: exception türlerine göre düzenleme yapılacak
            if ($exception instanceof ApiException) {
                if ($exception instanceof InvalidRequestException) {
                    $response = ErrorResponse::create()
                        ->setStatusCode($exception->getStatusCode())
                        ->setMessage($this->translator->trans($exception->getMessageType()->value))
                        ->setErrors($exception->getErrors());

                } else {
                    $response = ErrorResponse::create()
                        ->setStatusCode($exception->getStatusCode())
                        ->setMessage($this->translator->trans($exception->getMessageType()));
                }

                $json = $this->serializer->serialize($response, 'json');
                $event->setResponse(JsonResponse::fromJsonString($json, $response->getStatusCode(), []));
            }


        }
    }


    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => [
                ['onKernelException', 3]
            ],
        ];
    }
}
