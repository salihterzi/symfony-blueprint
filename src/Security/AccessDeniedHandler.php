<?php

namespace App\Security;

use App\Response\MessageType;
use App\Response\StatusType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(private readonly  TranslatorInterface $translator)
    {
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException):JsonResponse
    {
        return new JsonResponse([
            'statusCode'=>403,
            'status' => StatusType::FAIL,
            'message'=>$this->translator->trans(MessageType::ERROR_ACCESS_DENIED->value)
        ], 403);
    }
}
