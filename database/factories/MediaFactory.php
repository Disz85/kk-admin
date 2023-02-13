<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'path' => fake()->unique()->name(),
            'type' => fake()->randomElement(['image/jpg', 'image/png', 'image/gif']),
            'title' => fake()->numberBetween(0, 10),
            'width' => fake()->numberBetween(200, 500),
            'height' => fake()->numberBetween(200, 250),
        ];
    }
}
