<?php

namespace App\Http\Resources\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
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
            'image' => new MediaResource($this->whenLoaded('image')),
            'image_id' => $this->image_id,
            'tags' => new TagCollection($this->whenLoaded('tags')),
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
            'ingredients' => new IngredientCollection($this->whenLoaded('ingredients')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_by' => $this->created_by,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'brand_id' => $this->brand_id,
        ];
    }
}
