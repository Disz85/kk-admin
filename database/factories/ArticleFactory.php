<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Database\Helpers\BlockStyleEditorFakeContentBuilder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'lead' => fake()->sentence(4),
            'body' => $this->fakeArrayContent(),
            'is_active' => fake()->boolean(),
            'is_sponsored' => fake()->boolean(),
            'is_18_plus' => fake()->boolean(),
            'image_id' => MediaFactory::new(),
            'published_at' => function ($values) {
                return $values['is_active'] ? date('Y-m-d H:i:s') : null;
            },
        ];
    }

    public function withAuthors(Author|Collection $authors = null, int $count = 1): self
    {
        return $this->hasAttached(
            $authors ?? AuthorFactory::new()->count($count)
        );
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
