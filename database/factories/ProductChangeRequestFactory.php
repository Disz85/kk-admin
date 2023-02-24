<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductChangeRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'data' => fn () => $this->getProductDummyData(),
            'product_id' => null,
        ];
    }

    private function getProductDummyData()
    {
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();
        $ingredients = Ingredient::factory()->count(2)->create();
        $user = User::factory()->create();
        $product = ProductFactory::new()->raw();
        $product['category'] = $category;
        $product['ingredients'] = $ingredients->toArray();
        $product['tags'] = $tags->toArray();
        $product['created_by'] = $user->id;
        $product['ingredients_new'] = [];

        return $product;
    }
}
