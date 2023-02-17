<?php

namespace Tests\Feature;

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
        list($product, $tags, $categories, $user, $ingredients) = $this->makeDummyRequestData();
        $response = $this->post(route('admin.product-change-requests.store'), $product);
        unset($product['image_id']);
        $response->assertCreated();
        $response->assertJsonFragment([
            'data' => $product,
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
            'id' => $productChangeRequest->data['categories'][0],
            'id' => $productChangeRequest->data['tags'][0],
            'id' => $productChangeRequest->data['ingredients'][0],
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
        list($productChanged, $tags, $categories, $user) = $this->makeDummyRequestData();
        $response = $this->put(route('admin.product-change-requests.update', ['product_change_request' => $product->id]), $productChanged);
        $response->assertOk();

        $response->assertJsonFragment([
            'name' => $productChanged['name'],
            'where_to_find' => $productChanged['where_to_find'],
            'is_sponsored' => $productChanged['is_sponsored'],
            'description' => $productChanged['description'],
            'created_by' => $productChanged['created_by'],
            'tags' => $productChanged['tags'],
            'categories' => $productChanged['categories'],
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
            'is_sponsored' => $product->data['is_sponsored'],
            'description' => $product->data['description'],
            'created_by' => $product->data['created_by'],
            'categories' => $product->data['categories'],
            'tags' => $product->data['tags'],
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
        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(2)->create();
        $product = ProductFactory::new()->raw();
        $product['image']['id'] = $product['image_id'];
        $ingredients = Ingredient::factory()->count(2)->create();
        $user = User::factory()->create();
        $product['categories'] = (array_column($categories->toArray(), 'id'));
        $product['ingredients'] = (array_column($ingredients->toArray(), 'id'));
        $product['tags'] = (array_column($tags->toArray(), 'id'));
        $product['created_by'] = $user->id;

        return [$product, $tags, $categories, $user, $ingredients];
    }
}
