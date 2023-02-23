<?php

namespace App\Resources\Elastic;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
{
    /**
     * @param $request
     * @return array<string, int>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
