<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    protected $stopOnFirstFailure = FALSE;

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $response = response()->json([
            'success' => false,
            'message' => static::DEFAULT_ERROR_MESSAGE,
            'fails' => $errors
        ], static::DEFAULT_ERROR_CODE);

        throw new ValidationException($validator, $response);
    }

}
