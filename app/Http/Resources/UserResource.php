<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => '+' . $this->phone,
            'position' => $this->position->name,
            'position_id' => $this->position_id,
            'photo' => $this->photoUrl(),
        ];
        if (!$request->route('userId')) {
            $data['registration_timestamp'] = $this->created_at;
        }
        return $data;
    }
}
