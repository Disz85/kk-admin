<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'lead' => $this->lead,
            'body' => $this->body,
            'active' => $this->active,
            'hidden' => $this->hidden,
            'sponsored' => $this->sponsored,
            'is_18_plus' => $this->is_18_plus,
            'image' => new MediaResource($this->whenLoaded('image')),
            'authors' => new AuthorCollection($this->whenLoaded('authors')),
            'tags' => new TagCollection($this->whenLoaded('tags')),
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
        ];
    }
}
