<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
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
            'body' => fake()->paragraph(),
            'active' => fake()->boolean(),
            'hidden' => fake()->boolean(),
            'sponsored' => fake()->boolean(),
            'is_18_plus' => fake()->boolean(),
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
}
