<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Admin\ArticleResource;
use App\Http\Resources\Admin\MediaResource;
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
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
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
