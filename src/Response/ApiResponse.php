<?php

namespace App\Response;

abstract class ApiResponse
{
    private string $message;

    /**
     * @param int $statusCode
     * @param StatusType $statusType
     * @param MessageType $messageType
     */
    public function __construct(private int         $statusCode,
                                private StatusType  $statusType,
                                private MessageType $messageType)
    {
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return StatusType|null
     */
    public function getStatusType(): ?StatusType
    {
        return $this->statusType;
    }

    /**
     * @param StatusType $statusType
     * @return $this
     */
    public function setStatusType(StatusType $statusType): static
    {
        $this->statusType = $statusType;

        return $this;
    }

    /**
     * @return MessageType
     */
    public function getMessageType(): MessageType
    {
        return $this->messageType;
    }

    /**
     * @param MessageType $messageType
     */
    public function setMessageType(MessageType $messageType): self
    {
        $this->messageType = $messageType;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
