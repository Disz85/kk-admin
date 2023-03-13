<?php

namespace Tests\Feature;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\ProductChangeRequest;
use App\Models\User;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductChangeRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-product-change-requests');
        $this->actingAs($this->user);
    }

    public function it_can_approve_a_product_change_request()
    {
        $productChangeRequest = ProductChangeRequest::factory()->create();
        $response = $this->post(route('admin.product-change-requests.approve', ['product_change_request' => $productChangeRequest->id]));

        $json = json_decode($response->getContent());
        $response->assertCreated();
        $this->assertSame($productChangeRequest->data['brand']['id'], $json->data->brand->id);
        $this->assertSame($productChangeRequest->data['category']['id'], $json->data->category->id);
        $this->assertSame($productChangeRequest->data['ingredients'][0]['id'], $json->data->ingredients[0]->id);

        $response->assertJsonFragment([
            'name' => $productChangeRequest->data['name'],
            'description' => $productChangeRequest->data['description'],
            'created_by' => $productChangeRequest->data['created_by'],
        ]);
    }

    /** @test */
    public function it_can_reject_a_product_change_request()
    {
        $productChangeRequest = ProductChangeRequest::factory()->create();
        $response = $this->post(route('admin.product-change-requests.reject', ['product_change_request' => $productChangeRequest->id]));
        $response->assertOk();
        $this->assertNull(ProductChangeRequest::find($productChangeRequest->id));
    }

    /** @test */
    public function it_can_update_a_product_change_request()
    {
        $product = ProductChangeRequest::factory()->create();
        list($productChanged) = $this->makeDummyRequestData();
        $response = $this->put(route('admin.product-change-requests.update', ['product_change_request' => $product->id]), $productChanged);
        $response->assertOk();

        $json = json_decode($response->getContent());
        $this->assertSame($productChanged['brand']['id'], $json->data->brand->id);
        $this->assertSame($productChanged['category']['id'], $json->data->category->id);

        $response->assertJsonFragment([
            'name' => $productChanged['name'],
            'description' => $productChanged['description'],
            'created_by' => $product->data['created_by'],
            'ingredients_by' => $product->data['ingredients_by'],
        ]);
    }

    /** @test */
    public function it_can_show_a_product_change_request()
    {
        $product = ProductChangeRequest::factory()->create();
        $response = $this->get(route('admin.product-change-requests.show', ['product_change_request' => $product->id]));
        $response->assertOk();
        $response->assertJsonFragment([
            'name' => $product->data['name'],
            'where_to_find' => $product->data['where_to_find'],
            'description' => $product->data['description'],
            'created_by' => $product->data['created_by'],
            'brand_id' => $product->data['brand_id'],
            'id' => $product->data['category']['id'],
        ]);
    }

    /** @test */
    public function it_can_show_the_product_change_request_list()
    {
        $products = ProductChangeRequest::factory()->count(3)->create();
        $response = $this->get(route('admin.product-change-requests.index'));
        $response->assertOk();
        foreach ($products as $product) {
            $response->assertJsonFragment([
                'id' => $product['id'],
                'data' => $product['data'],
            ]);
        }
    }

    public function makeDummyRequestData(): array
    {
        $category = Category::factory()->create(['type' => CategoryTypeEnum::Product->value]);
        $product = ProductFactory::new()->raw();
        $product['brand']['id'] = $product['brand_id'];
        $product['image']['id'] = $product['image_id'];
        $ingredients = Ingredient::factory()->count(2)->create();
        $user = User::factory()->create();
        $product['category'] = $category;
        $product['ingredients'] = $ingredients->pluck('name')->toArray();

        return [$product, $category, $user, $ingredients];
    }
}
