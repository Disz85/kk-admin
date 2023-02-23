<?php

namespace App\Http\Resources\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class MediaCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array<string, mixed>|Collection<int, Media>
     */
    public function toArray(Request $request): array|Collection
    {
        return $this->collection;
    }
}
