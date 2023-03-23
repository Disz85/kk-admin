<?php

namespace Database\Seeders;

use App\Enum\CategoryTypeEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
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
        $categories = Category::query()->where(['type' => CategoryTypeEnum::Product->value])->pluck('id');
        $skinTypes = Category::query()->where(['type' => CategoryTypeEnum::SkinType->value])->pluck('id');
        $skinConcerns = Category::query()->where(['type' => CategoryTypeEnum::SkinConcern->value])->pluck('id');
        $hairProblems = Category::query()->where(['type' => CategoryTypeEnum::HairProblem->value])->pluck('id');

        $tags = Tag::query()->pluck('id');
        $ingredients = Ingredient::query()->pluck('id');
        $users = User::query()->pluck('id');
        $brands = Brand::query()->pluck('id');

        foreach (range(1, self::COUNT) as $iter) {
            $user = $users->random();
            $brand = $brands->random();

            Product::factory()
                ->withTags($tags->random(rand(0, 5)))
                ->withCategory($categories->random(1))
                ->withSkinTypes($skinTypes->random(rand(0, 2)))
                ->withSkinConcerns($skinConcerns->random(rand(0, 3)))
                ->withHairProblems($hairProblems->random(rand(0, 3)))
                ->withIngredients($ingredients->random(rand(0, 8)))
                ->create([
                    'brand_id' => $brand,
                    'created_by' => $user,
                    'updated_by' => $user,
                    'ingredients_by' => $user,
                ]);
        }
    }
}
