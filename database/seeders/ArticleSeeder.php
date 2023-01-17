<?php

namespace Database\Seeders;

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
        $authors = Author::factory()->count(10)->create();
        $categories = Category::factory()->count(15)->create();
        $tags = Tag::factory()->count(30)->create();

        foreach (range(1, self::COUNT) as $iter) {
            Article::factory()
                ->withAuthors($authors->random(rand(1, 2)))
                ->withTags($tags->random(rand(1, 5)))
                ->withCategories($categories->random(rand(1, 5)))
                ->create();
        }
    }
}
