<?php

namespace App\Http\Resources\Admin;

use App\Models\ProductChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandChangeRequestResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var ProductChangeRequest $this */
        return [
            'id' => $this->id,
            'data' => $this->data,
            'brand' => new BrandResource($this->brand),
        ];
    }
}
