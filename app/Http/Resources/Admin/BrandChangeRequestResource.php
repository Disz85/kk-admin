<?php

namespace App\Http\Resources\Admin;

use App\Models\BrandChangeRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BrandChangeRequest
 */
class BrandChangeRequestResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = $this->image ?? (data_get($this->data, 'image') ? Media::findOrFail($this->data['image']['id']) : null);

        return [
            'id' => $this->id,
            'title' => $this->data['title'],
            'data' => [
                'title' => $this->data['title'],
                'image' => new MediaResource($image),
                'url' => $this->data['url'],
                'where_to_find' => $this->data['where_to_find'],
                'description' => $this->data['description'],
                'created_by' => $this->data['created_by'],
            ],
            'brand' => new BrandResource($this->brand),
        ];
    }
}
