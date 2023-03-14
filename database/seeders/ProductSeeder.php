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
        $categories = Category::where(['type' => CategoryTypeEnum::Product->value])->inRandomOrder()->pluck('id');
        $skinTypes = Category::where(['type' => CategoryTypeEnum::SkinType->value])->inRandomOrder()->pluck('id');
        $skinConcerns = Category::where(['type' => CategoryTypeEnum::SkinConcern->value])->inRandomOrder()->pluck('id');
        $hairProblems = Category::where(['type' => CategoryTypeEnum::HairProblem->value])->inRandomOrder()->pluck('id');

        $tags = Tag::inRandomOrder()->pluck('id');
        $ingredients = Ingredient::inRandomOrder()->pluck('id');
        $users = User::inRandomOrder()->pluck('id');
        $brands = Brand::inRandomOrder()->pluck('id');

        foreach (range(1, self::COUNT) as $iter) {
            $user = $users->random(1)->first();
            $brand = $brands->random(1)->first();

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
