<?php

namespace App\Http\Resources\Admin;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Ingredient $this */
        return [
            'id' => $this->id,
            'legacy_id' => $this->legacy_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'ewg_data' => $this->ewg_data,
            'ewg_score' => $this->ewg_score,
            'ewg_score_max' => $this->ewg_score_max,
            'comedogen_index' => $this->comedogen_index,
            'is_approved' => $this->is_approved,
            'image' => new MediaResource($this->image),
            'products' => new ProductCollection($this->whenLoaded('products')),
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
        ];
    }
}
