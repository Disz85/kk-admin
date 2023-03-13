<?php

namespace Tests\Feature\Api;

use App\Enum\CategoryTypeEnum;
use App\Http\Middleware\Authenticate;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductChangeRequest;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductChangeRequestApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->actingAs($this->user);
        $this->withoutMiddleware(Authenticate::class);
    }

    /** @test */
    public function it_can_store_a_new_product_change_request()
    {
        $product = $this->makeDummyRequestData();
        $response = $this->post(route('api.product-change-requests.store'), $product);
        $response->assertCreated();
        $json = json_decode($response->getContent());

        $this->assertSame($product['brand']['id'], $json->data->brand->id);
        $this->assertSame($product['category']['id'], $json->data->category->id);
        $this->assertSame($product['ingredients'][0], $json->data->ingredients[0]);
        $this->assertSame($product['image']['id'], $json->data->image->id);

        $response->assertJsonFragment([
            'name' => $product['name'],
            'id' => $product['brand']['id'],
            'created_by' => $this->user->id,
            'price' => $product['price'],
            'size' => $product['size'],
        ]);
    }

    /** @test */
    public function it_can_store_a_product_patch_request()
    {
        $product = Product::factory()->create();
        $data = [
            'product_id' => $product->id,
            'price' => fake()->word(),
            'size' => fake()->text(30),
        ];
        $response = $this->patch(route('api.product-change-requests.store-patch'), $data);
        $response->assertCreated();
        $response->assertJsonFragment([
            'price' => $data['price'],
            'size' => $data['size'],
        ]);
    }

    /** @test */
    public function it_can_list_product_change_requests()
    {
        $productChangeRequests = ProductChangeRequest::factory(['user_id' => $this->user->id ])->count(3)->create();
        $response = $this->get(route('api.product-change-requests.index'));
        $response->assertOk();
        foreach ($productChangeRequests as $productChangeRequest) {
            $response->assertJsonFragment([
                'id' => $productChangeRequest->id,
                'size' => $productChangeRequest->data['size'],
                'price' => $productChangeRequest->data['price'],
                'description' => $productChangeRequest->data['description'],
                'ingredients' => $productChangeRequest->data['ingredients'],
            ]);
        }
    }

    /** @test */
    public function it_can_show_a_product_change_request()
    {
        $productChangeRequest = ProductChangeRequest::factory(['user_id' => $this->user->id ])->create();
        $this->get(route('api.product-change-requests.show', ['product_change_request' => $productChangeRequest->id ]))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $productChangeRequest->id,
            'size' => $productChangeRequest->data['size'],
            'price' => $productChangeRequest->data['price'],
            'description' => $productChangeRequest->data['description'],
            'ingredients' => $productChangeRequest->data['ingredients'],
        ]);
    }

    /** @test */
    public function it_can_delete_a_product_change_request()
    {
        $productChangeRequest = ProductChangeRequest::factory(['user_id' => $this->user->id ])->create();
        $this->delete(route('api.product-change-requests.destroy', ['product_change_request' => $productChangeRequest->id ]))
            ->assertStatus(204);
    }

    /** @test */
    public function it_can_update_a_product_change_request()
    {
        $productChangeRequest = ProductChangeRequest::factory(['user_id' => $this->user->id ])->create();
        $data = $this->makeDummyRequestData();
        $response = $this->put(route('api.product-change-requests.update', ['product_change_request' => $productChangeRequest->id]), $data);
        $response->assertOk();
        $json = json_decode($response->getContent());

        $this->assertSame($data['brand']['id'], $json->data->brand->id);
        $this->assertSame($data['category']['id'], $json->data->category->id);

        $response->assertJsonFragment([
            'name' => $data['name'],
            'description' => $data['description'],
            'created_by' => $productChangeRequest->data['created_by'],
            'ingredients_by' => $productChangeRequest->data['ingredients_by'],
        ]);
    }

    public function makeDummyRequestData(): array
    {
        $category = Category::factory()->create(['type' => CategoryTypeEnum::Product]);
        $product = ProductFactory::new()->raw();
        $product['brand']['id'] = $product['brand_id'];
        $product['image']['id'] = $product['image_id'];
        $ingredients = Ingredient::factory()->count(2)->create();
        $product['category'] = $category;
        $product['ingredients'] = $ingredients->pluck('name')->toArray();

        return $product;
    }
}
