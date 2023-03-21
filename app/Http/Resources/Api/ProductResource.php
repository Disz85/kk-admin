<?php

namespace App\Http\Resources\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => new MediaResource($this->whenLoaded('image')),
            'price' => $this->price,
            'size' => $this->size,
            'where_to_find' => $this->where_to_find,
            'description' => $this->description,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'ingredients' => IngredientResource::collection($this->whenLoaded('ingredients')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'is_active' => $this->is_active,
            'is_sponsored' => $this->is_sponsored,
            'is_18_plus' => $this->is_18_plus,
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'ingredients_by' => new UserResource($this->whenLoaded('ingredientsBy')),
            'published_at' => $this->published_at,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'category_hierarchy' => $this->whenLoaded(
                'categories',
                new CategoryCollection(
                    Category::ancestorsAndSelf($this->productCategory)->toFlatTree()
                )
            ),
        ];
    }
}
