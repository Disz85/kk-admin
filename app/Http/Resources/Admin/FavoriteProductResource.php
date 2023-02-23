<?php

namespace App\Http\Resources\Admin;

use App\Models\FavoriteProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FavoriteProduct
 */
class FavoriteProductResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'product1_id' => $this->product1_id,
            'product2_id' => $this->product2_id,
            'product3_id' => $this->product3_id,
            'product1' => new ProductResource($this->whenLoaded('product1')),
            'product2' => new ProductResource($this->whenLoaded('product2')),
            'product3' => new ProductResource($this->whenLoaded('product3')),
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
        ];
    }
}
