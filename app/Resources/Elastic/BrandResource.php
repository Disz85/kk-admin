<?php

namespace App\Resources\Elastic;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Brand
 */
class BrandResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'created_at' => new Carbon($this->created_at),
            'created_by' => $this->whenLoaded('createdBy', new UserResource($this->createdBy)),
        ];
    }
}
