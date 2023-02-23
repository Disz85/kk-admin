<?php

namespace App\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;

class RoleCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array<int, Role>|Collection<int, Role>
     */
    public function toArray($request): array|Collection
    {
        return $this->collection;
    }
}
