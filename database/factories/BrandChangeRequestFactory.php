<?php

namespace Database\Factories;

use App\Models\BrandChangeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<BrandChangeRequest>
 */
class BrandChangeRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'data' => function () {
                $raw = BrandFactory::new()->raw();
                Arr::set($raw, 'image.id', $raw['image_id']);
                unset($raw['image_id']);

                return $raw;
            },
            'brand_id' => null,
        ];
    }
}
