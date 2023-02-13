<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Tag;
use Database\Helpers\BlockStyleEditorFakeContentBuilder;
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
            'description' => $this->fakeArrayContent(),
            'is_sponsored' => fake()->boolean(),
            'is_18_plus' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'price' => fake()->word(),
            'size' => fake()->text(),
            'where_to_find' => fake()->text(),
            'brand_id' => BrandFactory::new(),
            'image_id' => MediaFactory::new(),
            'ingredients_by' => UserFactory::new(),
            'created_by' => UserFactory::new(),
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
