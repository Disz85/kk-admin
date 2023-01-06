<?php
namespace App\Http\Resources\Admin;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\MediaResource;

class CategoryResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Category $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'image' => new MediaResource($this->image),
        ];
    }
}
