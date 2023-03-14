<?php

namespace Database\Factories;

use Database\Helpers\BlockStyleEditorFakeContentBuilder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->unique()->company,
            'description' => $this->fakeArrayContent(),
            'url' => fake()->url,
            'where_to_find' => fake()->text(),
            'created_by' => UserFactory::new(),
            'image_id' => MediaFactory::new(),
        ];
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
