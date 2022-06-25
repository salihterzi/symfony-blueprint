<?php

namespace App\Serializer\Normalizer;

use App\Response\InfoResponse;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InfoResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param InfoResponse $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $response['statusCode'] = $object->getStatusCode();
        $response['status'] = $object->getStatusType();
        $response['message']= $object->getMessage();

        return $response;
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof InfoResponse;
    }
}
