<?php

namespace Database\Seeders;

use App\Enum\CategoryTypeEnum;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public const COUNT = 100;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = Author::query()->pluck('id');
        $categories = Category::query()->where('type', CategoryTypeEnum::Article->value)->pluck('id');
        $tags = Tag::query()->pluck('id');

        foreach (range(1, self::COUNT) as $iter) {
            Article::factory()
                ->withAuthors($authors->random(rand(1, 2)))
                ->withTags($tags->random(rand(1, 5)))
                ->withCategories($categories->random(rand(1, 5)))
                ->create();
        }
    }
}
