<?php

namespace App\Http\Resources\Admin;

use App\Models\Brand;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

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
        /* @var Brand $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => $this->url,
            'description' => $this->description,
            'image' => new MediaResource($this->image),
            'where_to_find' => $this->where_to_find,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
