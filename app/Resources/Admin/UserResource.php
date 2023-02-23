<?php

namespace App\Resources\Admin;

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
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'super-admin' => $this->hasRole('super-admin'),
            'roles' => new RoleCollection($this->roles),
            'permissions' => new PermissionCollection($this->getPermissionsViaRoles()),
        ];
    }
}
