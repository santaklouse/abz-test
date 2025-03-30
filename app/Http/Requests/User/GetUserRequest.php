<?php

namespace App\Http\Requests\User;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class GetUserRequest extends UserRequest
{

    const DEFAULT_ERROR_MESSAGE = 'The user with the requested id does not exist.';
    const DEFAULT_ERROR_CODE = 400;

    public function all($keys = null)
    {
        $data = parent::all($keys);
        if (Arr::get(request()->route()->parameters(), 'userId')) {
            $data['userId'] = $this->route('userId');
        }
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'userId' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'userId' => 'The user ID must be an integer.'
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     * @throws ValidationException
     */
    protected function passedValidation()
    {
        // check user with given id exists
        if (!User::checkExistsById(request()->userId)) {
            $response = response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
            throw new ValidationException($this->getValidatorInstance(), $response);
        }
    }

}
