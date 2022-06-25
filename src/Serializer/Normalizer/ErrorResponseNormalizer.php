<?php

namespace App\Serializer\Normalizer;

use App\Response\ErrorResponse;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ErrorResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param ErrorResponse $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $response['statusCode'] = $object->getStatusCode();
        $response['status'] = $object->getStatusType();
        $response['message']= $object->getMessage();

        $errors = $object->getErrors();
        if (isset($errors)) {
            $response['errors'] = $errors;
        }
        return $response;
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof ErrorResponse;
    }
}
