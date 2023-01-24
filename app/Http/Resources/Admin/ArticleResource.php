<?php

namespace App\Http\Resources\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Article $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'lead' => $this->lead,
            'body' => $this->body,
            'active' => $this->active,
            'hidden' => $this->hidden,
            'sponsored' => $this->sponsored,
            'is_18_plus' => $this->is_18_plus,
            'image' => new MediaResource($this->image),
            'authors' => new AuthorCollection($this->authors),
            'tags' => new TagCollection($this->tags),
            'categories' => new CategoryCollection($this->categories),
        ];
    }
}
