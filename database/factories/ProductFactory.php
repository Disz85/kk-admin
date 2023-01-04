<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
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
            'description' => fake()->paragraph(),
            'active' => fake()->boolean(),
            'hidden' => fake()->boolean(),
            'sponsored' => fake()->boolean(),
            'is_18_plus' => fake()->boolean(),
            'price' => fake()->randomNumber(),
            'size' => fake()->randomNumber(),
            'where_to_find' => fake()->text(),
        ];
    }

    public function withTags(Tag|Collection $tags = null, int $count = 1): self
    {
        return $this->hasAttached(
            $tags ?? TagFactory::new()->count($count)
        );
    }

    public function withCategories(Category|Collection $categories = null, int $count = 1): self
    {
        return $this->hasAttached(
            $categories ?? CategoryFactory::new()->count($count)
        );
    }
}
