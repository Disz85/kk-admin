<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public const COUNT = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::factory()->count(15)->create();

        foreach (range(1, self::COUNT) as $iter) {
            Ingredient::factory()
                ->withCategories($categories->random(rand(1, 5)))
                ->create();
        }
    }
}
