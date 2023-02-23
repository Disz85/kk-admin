<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Admin\ArticleResource;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Tag
 */
class TagResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_highlighted' => $this->is_highlighted,
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
            'brands' => BrandResource::collection($this->whenLoaded('brands')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
