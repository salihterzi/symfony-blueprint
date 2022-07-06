<?php

namespace App\Request;

use App\Request\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FormRequestValueResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;

    /**
     * FormRequestValueResolver constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $reflection = new \ReflectionClass($argument->getType());
        if ($reflection->isSubclassOf(FormRequest::class)) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable|\Generator
    {
        $class = $argument->getType();
        $validationRequest = new $class($this->validator, $request);
        $validationRequest->validate();
        yield $validationRequest;
    }
}
