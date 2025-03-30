<?php

namespace App\Http\Requests\User;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class GetUsersRequest extends UserRequest
{
    const DEFAULT_ERROR_MESSAGE = 'The user with the requested id does not exist.';
    const DEFAULT_ERROR_CODE = 400;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'count' => 'integer',
            'page' => 'integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'count' => 'The count must be an integer.',
            'page' => 'The page must be at least 1.',
        ];
    }

}
