<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ingredient;
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

    /**
     * @param Tag|Collection|null $tags
     * @param int $count
     * @return $this
     */
    public function withTags(Tag|Collection $tags = null, int $count = 1): self
    {
        return $this->hasAttached(
            $tags ?? TagFactory::new()->count($count)
        );
    }

    /**
     * @param Category|Collection|null $categories
     * @param int $count
     * @return $this
     */
    public function withCategories(Category|Collection $categories = null, int $count = 1): self
    {
        return $this->hasAttached(
            $categories ?? CategoryFactory::new()->count($count)
        );
    }

    /**
     * @param Ingredient|Collection|null $ingredients
     * @param int $count
     * @return $this
     */
    public function withIngredients(Ingredient|Collection $ingredients = null, int $count = 1): self
    {
        return $this->hasAttached(
            $ingredients ?? IngredientFactory::new()->count($count)
        );
    }
}
