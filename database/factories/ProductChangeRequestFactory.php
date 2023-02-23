<?php

namespace Database\Factories;

use App\Enum\CategoryTypeEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\ProductChangeRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductChangeRequest>
 */
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

    /**
     * @return array<string, mixed>
     */
    private function getProductDummyData(): array
    {
        /** @var Category $category */
        $category = Category::factory(['type' => CategoryTypeEnum::Product->value ])->createOne();

        /** @var Collection<int, Ingredient> $ingredients */
        $ingredients = Ingredient::factory()->count(2)->create();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Brand $brand */
        $brand = Brand::factory()->create();

        /** @var array<string, mixed> $product */
        $product = ProductFactory::new()->raw();

        $product['category']['id'] = $category->id;
        $product['brand']['id'] = $brand->id;
        $product['ingredients'] = $ingredients->pluck('name')->toArray();
        $product['created_by'] = $user->id;
        $product['ingredients_by'] = $user->id;

        return $product;
    }
}
