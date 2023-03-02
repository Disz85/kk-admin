<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Resources\Admin\MediaResource;
use App\Http\Resources\Admin\ProductCollection;
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
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'ewg_data' => $this->ewg_data,
            'ewg_score' => $this->ewg_score,
            'ewg_score_max' => $this->ewg_score_max,
            'comedogen_index' => $this->comedogen_index,
            'published_at' => $this->published_at,
            'products' => new ProductCollection($this->whenLoaded('products')),
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
        ];
    }
}
