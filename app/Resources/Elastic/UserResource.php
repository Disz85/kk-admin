<?php

namespace App\Resources\Elastic;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @param $request
     * @return array<string, string>
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'username' => $this->username,
        ];
    }
}
