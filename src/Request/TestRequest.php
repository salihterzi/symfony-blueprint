<?php

namespace App\Request;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'testFİeld' => [
                new NotBlank(),
                new Email()
            ],
        ];
    }
}
