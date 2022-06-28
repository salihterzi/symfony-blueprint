<?php

namespace App\Exception;

use App\Response\MessageType;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ApiException
 * @package AppBundle\Exception
 */
class ApiException extends HttpException
{
    /**
     * ApiException constructor.
     *
     * @param int $statusCode
     */
    public function __construct($statusCode = 500)
    {
        parent::__construct($statusCode);
    }

    /**
     * @return MessageType
     */
    public function getMessageType()
    {
        return MessageType::ERROR;
    }

    public function getMessageValue(TranslatorInterface $translator = null)
    {
        return isset($translator) ? $translator->trans($this->getMessageType()->value, $this->getMessageParams()) : '';
    }

    public function getMessageParams()
    {
        return [];
    }

    public function getErrors(TranslatorInterface $translator = null)
    {
        return null;
    }
}
