<?php
namespace App\Subscriber;

use App\Response\ApiResponse;
use App\Response\MessageType;
use App\Response\StatusType;
use App\Response\SuccessResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly  TranslatorInterface $translator)
    {
    }

    public function onKernelView(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        if ($result instanceof ApiResponse) {
            if (null === $result->getMessageType()) {
                $messageType = $result->getStatusType() === StatusType::SUCCESS ? MessageType::SUCCESS : MessageType::ERROR;
            } else {
                $messageType = $result->getMessageType();
            }

            $result->setMessageType($messageType);
            $result->setMessage($this->translator->trans($messageType->value));
            $context = [];
            if ($result instanceof SuccessResponse) {
                $context[AbstractObjectNormalizer::ENABLE_MAX_DEPTH] = true;
                if (count($result->getGroups())>0) {
                    $context[AbstractObjectNormalizer::GROUPS] = $result->getGroups();
                }
            }

            $json = $this->serializer->serialize($result, 'json', $context);

            $event->setResponse(JsonResponse::fromJsonString($json, $result->getStatusCode()));
        }

    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            ViewEvent::class => 'onKernelView'
        ];
    }
}
