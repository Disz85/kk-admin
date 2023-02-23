<?php

namespace App\Http\Resources\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Article
 */
class ArticleResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'lead' => $this->lead,
            'body' => $this->body,
            'is_active' => $this->is_active,
            'is_sponsored' => $this->is_sponsored,
            'is_18_plus' => $this->is_18_plus,
            'published_at' => $this->published_at,
            'image' => new MediaResource($this->image),
            'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
