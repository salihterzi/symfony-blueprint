<?php

namespace App\Request;

use JetBrains\PhpStorm\ArrayShape;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class TestRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
//        return [
//            'testField' => [
//                new NotBlank(),
//                new Email()
//            ],
//        ];
        return [];
    }
}
