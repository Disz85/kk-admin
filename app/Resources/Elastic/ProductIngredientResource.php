<?php

namespace App\Resources\Elastic;

use App\Models\Ingredient;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ingredient
 */
class ProductIngredientResource extends JsonResource
{
    /**
     * @param $request
     * @return array<string, string>
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}
