<?php

namespace App\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class PermissionCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array|Collection
     */
    public function toArray($request): array|Collection
    {
        return $this->collection;
    }
}
