<?php

namespace App\Request;

use App\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Symfony\Component\String\u;

abstract class FormRequest
{
    private ValidatorInterface $validator;

    private Request $request;

    /**
     * FormRequest constructor.
     *
     * @param ValidatorInterface $validator
     * @param Request $request
     */
    public function __construct(ValidatorInterface $validator, Request $request)
    {
        $this->validator = $validator;
        $this->request = $request;
    }

    /**
     * @return bool
     * @throws InvalidRequestException
     */
    public function validate(): bool
    {
        $invalid = false;
        $errors = [];
        $params = $this->rules();
        if(empty($this->rules())){
            return true;
        }

        foreach ($params as $param => $constraint) {
            if ($this->request->files->has($param)) {
                $value = $this->request->files->get($param);
            } else {
                $value = $this->request->get($param);
            }

            $violations = $this->validator->validate($value, $constraint);
            foreach ($violations as $violation) {
                $errors[$param][] = $violation->getMessage();
            }

            $invalid = $invalid || $violations->count() > 0;
        }
        if ($invalid) {
            throw new InvalidRequestException($errors);
        }

        return true;
    }

    abstract public function rules(): array;

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
