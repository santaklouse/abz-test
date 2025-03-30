<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserApiResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'user' => parent::toArray($request)
        ];

    }
}
