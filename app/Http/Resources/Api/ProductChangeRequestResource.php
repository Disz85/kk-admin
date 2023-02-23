<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Admin\MediaResource;
use App\Http\Resources\Admin\ProductResource;
use App\Models\ProductChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductChangeRequest
 */
class ProductChangeRequestResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'product' => new ProductResource($this->whenLoaded('product')),
            'user' => new ProductResource($this->whenLoaded('user')),
            'image' => new MediaResource($this->image),
        ];
    }
}
