<?php

namespace Database\Seeders;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Database\Factories\CategoryFactory;
use Database\Factories\IngredientFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    use WithoutModelEvents;

    public const COUNT = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::where('type', CategoryTypeEnum::Ingredient->value)->first();

        if ($categories === null) {
            $categories = CategoryFactory::new()->create([
                'type' => CategoryTypeEnum::Ingredient->value,
            ]);
        }

        foreach (range(1, self::COUNT) as $iter) {
            IngredientFactory::new()
                ->withCategories($categories)
                ->create();
        }
    }
}
