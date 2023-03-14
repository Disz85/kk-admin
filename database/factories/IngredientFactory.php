<?php

namespace Database\Factories;

use App\Enum\CategoryTypeEnum;
use App\Enum\IngredientEwgDataEnum;
use App\Models\Category;
use App\Models\Ingredient;
use Database\Helpers\BlockStyleEditorFakeContentBuilder;
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
            'name' => fake()->unique()->words(2, true),
            'ewg_data' => fake()->randomElement(IngredientEwgDataEnum::cases())->value,
            'ewg_score' => fake()->numberBetween(0, 10),
            'ewg_score_max' => function ($values) {
                return fake()->numberBetween($values['ewg_score'], 10);
            },
            'comedogen_index' => fake()->numberBetween(0, 5),
            'description' => $this->fakeArrayContent(),
            'published_at' => fake()->date('Y-m-d H:i:s'),
            'created_by' => UserFactory::new(),
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
            $categories ?? CategoryFactory::new(['type' => CategoryTypeEnum::Ingredient->value])->count($count),
            [],
            'categories'
        );
    }

    private function fakeArrayContent(): array
    {
        $builder = app()->make(BlockStyleEditorFakeContentBuilder::class);

        $paragraphsCount = $this->faker->numberBetween(3, 6);

        $builder = $builder
            ->addHeader()
            ->addParagraph()
            ->addParagraph()
            ->addQuote()
            ->addList();

        foreach (range(1, $paragraphsCount) as $iter) {
            $builder->addParagraph();
        }

        return $builder->build();
    }
}
