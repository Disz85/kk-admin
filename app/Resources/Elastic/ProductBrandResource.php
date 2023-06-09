<?php

namespace App\Resources\Elastic;

use App\Models\Brand;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Brand
 */
class ProductBrandResource extends JsonResource
{
    /**
     * @param $request
     * @return array<string, string>
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
        ];
    }
}
