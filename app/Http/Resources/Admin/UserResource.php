<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sso_id' => $this->sso_id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'username' => $this->username,
            'description' => $this->description,
            'birth_year' => $this->birth_year,
            'skin_type' => $this->skin_type,
            'skin_concern' => $this->skin_concern,
            'image' => new MediaResource($this->image),
        ];
    }
}
