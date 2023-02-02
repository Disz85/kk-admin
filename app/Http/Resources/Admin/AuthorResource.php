<?php

namespace App\Http\Resources\Admin;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Author $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'description' => $this->description,
            'image' => new MediaResource($this->image),
        ];
    }
}
