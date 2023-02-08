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
        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(2)->create();
        $product = ProductFactory::new()->raw();
        $user = User::factory()->create();
        $ingredients = Ingredient::factory()->count(2)->create();
        $product['categories'] = $categories->pluck('id')->toArray();
        $product['tags'] = $tags->pluck('id')->toArray();
        $product['ingredients'] = $ingredients->pluck('id')->toArray();
        $product['created_by'] = $user->id;
        $product['ingredients_new'] = [];

        return $product;
    }
}
