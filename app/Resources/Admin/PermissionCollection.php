<?php

namespace App\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class PermissionCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array<int, Permission>|Collection<int, Permission>
     */
    public function toArray($request): array|Collection
    {
        return $this->collection;
    }
}
