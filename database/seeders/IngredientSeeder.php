<?php

namespace Database\Seeders;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public const COUNT = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::where(['type' => CategoryTypeEnum::Ingredient->value])->inRandomOrder()->pluck('id');
        $users = User::inRandomOrder()->pluck('id');

        foreach (range(1, self::COUNT) as $iter) {
            Ingredient::factory()
                ->withCategories($categories->random(rand(1, 5)))
                ->create(['created_by' => $users->random(1)->first()]);
        }
    }
}
