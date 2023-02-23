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
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
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
            'is_active' => $this->is_active,
            'is_sponsored' => $this->is_sponsored,
            'is_18_plus' => $this->is_18_plus,
            'published_at' => $this->published_at,
            'image' => new MediaResource($this->image),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'ingredients_by' => $this->ingredients_by,
            'brand' => new BrandResource($this->brand),
            'category' => new CategoryResource($this->productCategory),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'skin_types' => CategoryResource::collection($this->skinTypeCategories),
            'skin_concerns' => CategoryResource::collection($this->skinConcernCategories),
            'hair_problems' => CategoryResource::collection($this->hairProblemCategories),
            'ingredients' => IngredientResource::collection($this->whenLoaded('ingredients')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
