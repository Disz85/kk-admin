<?php

namespace Database\Factories;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Database\Helpers\BlockStyleEditorFakeContentBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'description' => $this->fakeArrayContent(),
            'type' => fake()->randomElement(CategoryTypeEnum::toArray()),
            'is_archived' => fake()->boolean,
        ];
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
