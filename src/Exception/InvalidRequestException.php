<?php

namespace App\Exception;

use App\Response\MessageType;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvalidRequestException extends ApiException
{
    /**
     * @var array
     */
    private array $errors;

    /**
     * InvalidRequestException constructor.
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct(422);
        $this->errors = $errors;
    }

    /**
     * @return MessageType
     */
    public function getMessageType()
    {
        return MessageType::ERROR_INVALID_REQUEST;
    }

    /**
     * @return array
     */
    public function getErrors(TranslatorInterface $translator = null): array
    {
        return $this->errors;
    }
}
