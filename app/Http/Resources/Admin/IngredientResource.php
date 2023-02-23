<?php

namespace App\Http\Resources\Admin;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ingredient
 */
class IngredientResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'ewg_data' => $this->ewg_data,
            'ewg_score' => $this->ewg_score,
            'ewg_score_max' => $this->ewg_score_max,
            'comedogen_index' => $this->comedogen_index,
            'published_at' => $this->published_at,
            'created_by' => $this->created_by,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
