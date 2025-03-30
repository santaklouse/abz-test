<?php

namespace App\Http\Requests\User;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SaveUserRequest extends UserRequest
{
    const DEFAULT_ERROR_MESSAGE = 'Validation failed';
    const DEFAULT_ERROR_CODE = 422;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => 'required|string|max:60|min:2',
            'email' => 'required|email',
            'phone' => 'required|string|starts_with:380|max:12|min:12',
            'position_id' => 'required|integer|exists:positions,id',
            'photo' => 'required|image|mimes:jpeg,jpg|max:5120|dimensions:min_width=70,min_height=70'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Username is required',
            'name.string' => 'Username should be a string.',
            'name.max' => 'The name must not be greater than 60 characters.',
            'name.min' => 'The name must be at least 2 characters.',
            'email' => 'The email must be a valid email address.',
            'phone.required' => 'The phone field is required.',
            'phone.integer' => 'Must be a valid phone number.',
            'phone.starts_with' => 'Number should start with code of Ukraine +380',
            'position_id.integer' => 'The position id must be an integer.',
            'photo.max' => 'The photo size must not be greater than 5 Mb',
            'photo.mimes' => 'The photo format must be jpeg/jpg type.',
            'photo.dimensions' => 'Minimum size of photo 70x70px.',
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
        // check user with phone or email already exists
        if (User::checkExists($this->email, $this->phone)) {
            $response = response()->json([
                'success' => false,
                'message' => 'User with this phone or email already exist'

            ], 409);
            throw new ValidationException($this->getValidatorInstance(), $response);
        }
    }

}
