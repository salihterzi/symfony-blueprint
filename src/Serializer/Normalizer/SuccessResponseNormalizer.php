<?php

namespace App\Serializer\Normalizer;

use App\Response\SuccessResponse;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SuccessResponseNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param SuccessResponse $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $response['statusCode']= $object->getStatusCode();
        $response['status']= $object->getStatusType();
        $response['message']= $object->getMessage();

        $data = $object->getData();
        if (isset($data)) {
            $response['data'] = $this->normalizer->normalize($data, $format, $context);
        }

        return $response;
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof SuccessResponse;
    }
}
