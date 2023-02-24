<?php

namespace Tests\Feature;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\ProductChangeRequest;
use App\Models\Tag;
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

    /** @test */
    public function it_can_store_a_new_product_change_request()
    {
        list($product, $tags, $category, $user, $ingredients) = $this->makeDummyRequestData();
        $response = $this->post(route('admin.product-change-requests.store'), $product);
        unset($product['image_id']);
        $response->assertCreated();
        $response->assertJsonFragment([
            'name' => $product['name'],
            'where_to_find' => $product['where_to_find'],
            'id' => $product['brand']['id'],
            'id' => $category['id'],
        ]);
    }

    public function it_can_approve_a_product_change_request()
    {
        $productChangeRequest = ProductChangeRequest::factory()->create();
        $response = $this->post(route('admin.product-change-requests.approve', ['product_change_request' => $productChangeRequest->id]));

        $response->assertCreated();
        $response->assertJsonFragment([
            'name' => $productChangeRequest->data['name'],
            'where_to_find' => $productChangeRequest->data['where_to_find'],
            'is_sponsored' => $productChangeRequest->data['is_sponsored'],
            'description' => $productChangeRequest->data['description'],
            'created_by' => $productChangeRequest->data['created_by'],
            'brand.id' => $productChangeRequest->data['brand_id'],
            'category.id' => $productChangeRequest->data['category']['id'],
            'tags.id' => $productChangeRequest->data['tags'][0],
            'ingredients.id' => $productChangeRequest->data['ingredients'][0],
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
        list($productChanged, $tags) = $this->makeDummyRequestData();
        $response = $this->put(route('admin.product-change-requests.update', ['product_change_request' => $product->id]), $productChanged);
        $response->assertOk();

        $response->assertJsonFragment([
            'name' => $productChanged['name'],
            'where_to_find' => $productChanged['where_to_find'],
            'is_sponsored' => $productChanged['is_sponsored'],
            'description' => $productChanged['description'],
            'created_by' => $productChanged['created_by'],
            'id' => $productChanged['brand_id'],
            'id' => $productChanged['category'],
            'id' => $tags[0]['id'],
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
            'is_active' => $product->data['is_active'],
            'is_sponsored' => $product->data['is_sponsored'],
            'description' => $product->data['description'],
            'created_by' => $product->data['created_by'],
            'brand_id' => $product->data['brand_id'],
            'id' => $product->data['category']['id'],
            'id' => $product->data['tags'][0]['id'],
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
        $category = Category::factory()->create(['type' => CategoryTypeEnum::Product]);
        $tags = Tag::factory()->count(2)->create();
        $product = ProductFactory::new()->raw();
        $product['brand']['id'] = $product['brand_id'];
        $product['image']['id'] = $product['image_id'];
        $ingredients = Ingredient::factory()->count(2)->create();
        $user = User::factory()->create();
        $product['category'] = $category;
        $product['ingredients'] = $ingredients->toArray();
        $product['tags'] = $tags->toArray();
        $product['created_by'] = $user->id;

        return [$product, $tags, $category, $user, $ingredients];
    }
}
