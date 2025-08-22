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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role?->name, 
            'role_id' => $this->role_id,
            'google_access_token' => $this->google_access_token,
            'google_refresh_token' => $this->google_refresh_token,
            'google_email' => $this->google_email,
            'google_name' => $this->google_name,
            'google_avatar' => $this->google_avatar,
            'is_google_connected' => $this->is_google_connected,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
