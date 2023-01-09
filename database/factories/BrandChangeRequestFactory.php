<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
