<?php

namespace App\Http\Resources\Api;

use App\Models\Brand;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @mixin Brand
 */
class BrandResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => $this->url,
            'description' => $this->description,
            'where_to_find' => $this->where_to_find,
            'image' => new MediaResource($this->image),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'created_by' => new UserResource($this->whenLoaded('created_by')),
            'updated_by' => new UserResource($this->whenLoaded('updated_by')),
        ];
    }
}
