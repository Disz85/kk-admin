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
        $width = fake()->numberBetween(200, 500);
        $height = fake()->numberBetween(200, 250);

        $fakerFileName = $this->faker->image(
            storage_path("images/seeder/"),
            $width,
            $height,
        );

        return [
            'path' => "images/seeder/" . basename($fakerFileName),
            'type' => 'image/png',
            'title' => basename($fakerFileName),
            'width' => $width,
            'height' => $height,
        ];
    }
}
