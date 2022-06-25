<?php

namespace App\Response;

class ErrorResponse extends ApiResponse
{
    public array $errors;

    /**
     * @param int $statusCode
     * @param StatusType $statusType
     * @param MessageType $messageType
     */
    public function __construct(int $statusCode = 400, StatusType $statusType = StatusType::FAIL, MessageType $messageType = MessageType::ERROR)
    {
        parent::__construct($statusCode, $statusType, $messageType);
    }

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
