<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidationService
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @param Request $request
     * @param $params
     * @return array|null
     * @throws \Exception
     */
    public function validate(Request $request, $params): ?array
    {
        $invalid = false;
        $errors = [];
        $params = $this->rules();
        if(empty($this->rules())){
            return true;
        }
        foreach ($params as $param => $constraint) {
            if ($request->files->has($param)) {
                $value = $request->files->get($param);
            } else {
                $value = $request->get($param);
            }

            $violations = $this->validator->validate($value, $constraint);

            foreach ($violations as $violation) {
                $errors[$param][] = $violation->getMessage();
            }
            $invalid = $invalid || $violations->count() > 0;
        }

        if ($invalid) {
            throw new InvalidRequestException($errors);
        } else {
            return null;
        }
    }
}
