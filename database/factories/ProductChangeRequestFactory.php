<?php

namespace Database\Factories;

use App\Enum\CategoryTypeEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductChangeRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'data' => fn () => $this->getProductDummyData(),
            'product_id' => null,
            'user_id' => function ($values) {
                return $values['data']['created_by'];
            },
        ];
    }

    private function getProductDummyData()
    {
        $category = Category::factory(['type' => CategoryTypeEnum::Product->value ])->createOne();
        $ingredients = Ingredient::factory()->count(2)->create();
        $user = User::factory()->create();
        $brand = Brand::factory()->create();
        $product = ProductFactory::new()->raw();
        $product['category']['id'] = $category->id;
        $product['brand']['id'] = $brand->id;
        $product['ingredients'] = $ingredients->pluck('name')->toArray();
        $product['created_by'] = $user->id;
        $product['ingredients_by'] = $user->id;

        return $product;
    }
}
