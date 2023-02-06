<?php

namespace Database\Seeders;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public const COUNT = 100;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::factory()->count(10)->create([
            'type' => CategoryTypeEnum::Product->value,
        ]);
        $tags = Tag::factory()->count(30)->create();
        $ingredients = Ingredient::limit(10)->get();

        foreach (range(1, self::COUNT) as $iter) {
            Product::factory()
                ->withTags($tags->random(5))
                ->withCategories($categories->random(2))
                ->withIngredients($ingredients->random(2))
                ->create();
        }
    }
}
