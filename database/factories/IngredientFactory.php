<?php

namespace Database\Factories;

use App\Enum\IngredientEwgDataEnum;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    /**
     * Define the model's default state.
     *
     * @return string[]
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'ewg_data' => fake()->randomElement(IngredientEwgDataEnum::cases())->value,
            'ewg_score' => fake()->randomNumber(1),
            'ewg_score_max' => function ($values) {
                return fake()->numberBetween($values['ewg_score'], 10);
            },
            'comedogen_index' => fake()->numberBetween(0, 5),
            'description' => fake()->paragraph(),
            'is_approved' => fake()->boolean(),
            'image_id' => null,
        ];
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
}
