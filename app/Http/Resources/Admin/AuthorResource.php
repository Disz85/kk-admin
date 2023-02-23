<?php

namespace App\Http\Resources\Admin;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Author
 */
class AuthorResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'description' => $this->description,
            'image' => new MediaResource($this->image),
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
        ];
    }
}
