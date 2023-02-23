<?php

namespace Database\Factories;

use App\Models\BrandChangeRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BrandChangeRequest>
 */
class BrandChangeRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'data' => BrandFactory::new()->raw(),
            'brand_id' => null,
        ];
    }
}
