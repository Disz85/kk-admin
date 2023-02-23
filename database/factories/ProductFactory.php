<?php

namespace Database\Factories;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Tag;
use Database\Helpers\BlockStyleEditorFakeContentBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
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
            'canonical_name' => fake()->unique()->name(),
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
            'updated_by' => UserFactory::new(),
            'published_at' => function ($values) {
                return $values['is_active'] ? $this->faker->dateTime()->format('Y-m-d H:i:s') : null;
            },
        ];
    }

    /**
     * @param Tag|Collection<int, Tag>|null $tags
     * @param int $count
     * @return self
     */
    public function withTags(Tag|Collection $tags = null, int $count = 1): self
    {
        return $this->hasAttached(
            $tags ?? TagFactory::new()->count($count),
            [],
            'tags'
        );
    }

    /**
     * @param Category|Collection<int, Category> $category
     * @param int $count
     * @return self
     */
    public function withCategory(Category|Collection $category, int $count = 1): self
    {
        return $this->hasAttached(
            $category,
            [],
            'categories'
        );
    }

    /**
     * @param Category|Collection<int, Category>|null $categories
     * @param int $count
     * @return self
     */
    public function withSkinTypes(Category|Collection $categories = null, int $count = 1): self
    {
        return $this->hasAttached(
            $categories ?? CategoryFactory::new(['type' => CategoryTypeEnum::SkinType->value])->count($count),
            [],
            'categories'
        );
    }

    /**
     * @param Category|Collection<int, Category>|null $categories
     * @param int $count
     * @return self
     */
    public function withSkinConcerns(Category|Collection $categories = null, int $count = 1): self
    {
        return $this->hasAttached(
            $categories ?? CategoryFactory::new(['type' => CategoryTypeEnum::SkinConcern->value])->count($count),
            [],
            'categories'
        );
    }

    /**
     * @param Category|Collection<int, Category>|null $categories
     * @param int $count
     * @return self
     */
    public function withHairProblems(Category|Collection $categories = null, int $count = 1): self
    {
        return $this->hasAttached(
            $categories ?? CategoryFactory::new(['type' => CategoryTypeEnum::HairProblem->value])->count($count),
            [],
            'categories'
        );
    }

    /**
     * @param Ingredient|Collection<int, Ingredient>|null $ingredients
     * @param int $count
     * @return self
     */
    public function withIngredients(Ingredient|Collection $ingredients = null, int $count = 1): self
    {
        return $this->hasAttached(
            $ingredients ?? IngredientFactory::new()->count($count),
            [],
            'ingredients'
        );
    }

    /**
     * @return array<string, mixed>
     * @throws BindingResolutionException
     */
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
