<?php

namespace App\Http\Resources\Api;

use App\Models\Article;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/** @mixin Article */
class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>|Arrayable<string, mixed>|JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        return [
            'uuid' => $this->uuid,
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
        ];
    }
}
