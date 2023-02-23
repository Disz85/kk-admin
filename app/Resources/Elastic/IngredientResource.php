<?php

namespace App\Resources\Elastic;

use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ingredient
 */
class IngredientResource extends JsonResource
{
    /**
     * @param $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'ewg_score' => $this->ewg_score,
            'categories' => $this->whenLoaded('categories', CategoryResource::collection($this->categories)),
            'published_at' => new Carbon($this->published_at),
        ];
    }
}
