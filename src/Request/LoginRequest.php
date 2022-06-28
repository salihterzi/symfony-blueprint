<?php

namespace App\Request;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                new NotBlank(),
                new Email()
            ],
            'password' => new NotBlank()
        ];
    }
}
