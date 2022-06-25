<?php

namespace App\Response;

class SuccessResponse extends ApiResponse
{
    public mixed $data = [];
    private array $groups = [];

    /**
     * @param int $statusCode
     * @param StatusType $statusType
     * @param MessageType $messageType
     */
    public function __construct(int $statusCode = 200, StatusType $statusType = StatusType::SUCCESS, MessageType $messageType = MessageType::SUCCESS)
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
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

}
