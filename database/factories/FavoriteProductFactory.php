<?php

namespace Database\Factories;

use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FavoriteProduct>
 */
class FavoriteProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->unique()->name(),
            'product1_id' => Product::factory()->create(),
            'product2_id' => Product::factory()->create(),
            'product3_id' => Product::factory()->create(),
        ];
    }
}
