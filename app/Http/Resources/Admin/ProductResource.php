<?php

namespace App\Http\Resources\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Product $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'canonical_name' => $this->canonical_name,
            'slug' => $this->slug,
            'price' => $this->price,
            'size' => $this->size,
            'where_to_find' => $this->where_to_find,
            'description' => $this->description,
            'active' => $this->active,
            'hidden' => $this->hidden,
            'sponsored' => $this->sponsored,
            'is_18_plus' => $this->is_18_plus,
            'image' => new MediaResource($this->image),
            'tags' => new TagCollection($this->tags),
            'categories' => new CategoryCollection($this->categories),
            'user' => new UserResource($this->user),
        ];
    }
}
