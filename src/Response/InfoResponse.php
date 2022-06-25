<?php

namespace App\Response;

class InfoResponse extends ApiResponse
{
    /**
     * @param int $statusCode
     * @param StatusType $statusType
     * @param MessageType $messageType
     */
    public function __construct(int $statusCode = 200, StatusType $statusType = StatusType::INFO, MessageType $messageType = MessageType::SUCCESS)
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
}
