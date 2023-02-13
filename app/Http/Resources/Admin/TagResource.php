<?php

namespace App\Http\Resources\Admin;

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
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
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
