<?php

namespace App\Http\Resources\Admin;

use App\Models\BrandChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BrandChangeRequest
 */
class BrandChangeRequestResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'data' => $this->data,
            'brand' => new BrandResource($this->brand),
        ];
    }
}
