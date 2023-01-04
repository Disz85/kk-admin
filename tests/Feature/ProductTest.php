<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_products(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertOk();
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        list($product, $tags, $categories) = $this->makeDummyRequestData();
        $response = $this->post(route('products.store'), $product);
        $response->assertCreated();
        $this->assertDatabaseHas(Product::class, Arr::only($product, ['name','price']));
        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'name' => $category->name,
                'id' => $category->id,
            ]);
        }
        foreach ($tags as $tag) {
            $response->assertJsonFragment([
                'name' => $tag->name,
                'id' => $category->id,
            ]);
        }
    }

    /** @test */
    public function it_can_update_a_product(): void
    {
        list($product, $tags, $categories) = $this->createAProductWithTagsAndCategories();
        list($changedProduct, $changedTags, $changedCategories) = $this->makeDummyRequestData();

        $response = $this->put(
            route('products.update', ['product' => $product->id]),
            $changedProduct
        );

        $response->assertOk();
        $this->assertDatabaseHas(Product::class, Arr::only($changedProduct, ['name','price']));
        foreach ($changedCategories as $category) {
            $response->assertJsonFragment([
                'name' => $category->name,
                'id' => $category->id,
            ]);
        }
        foreach ($changedTags as $tag) {
            $response->assertJsonFragment([
                'name' => $tag->name,
                'id' => $category->id,
            ]);
        }
    }

    /** @test */
    public function it_can_show_a_product()
    {
        list($product, $tags, $categories) = $this->createAProductWithTagsAndCategories();
        $response = $this->get(route('products.show', ['product' => $product->id]));
        $response->assertOk()
            ->assertJsonFragment(['id' => $product->id])
            ->assertJsonFragment(['name' => $product->name]);
        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'name' => $category->name,
                'id' => $category->id,
            ]);
        }
        foreach ($tags as $tag) {
            $response->assertJsonFragment([
                'name' => $tag->name,
                'id' => $category->id,
            ]);
        }
    }

    /** @test */
    public function it_can_remove_a_product(): void
    {
        $product = Product::factory()->create();
        $this->delete(route('products.destroy', ['product' => $product->id]))
            ->assertNoContent();
        $this->assertNull(Product::find($product->id));
    }

    private function makeDummyRequestData(): array
    {
        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(2)->create();
        $product = ProductFactory::new()->raw();
        $product['categories'] = (array_column($categories->toArray(), 'id'));
        $product['tags'] = (array_column($tags->toArray(), 'id'));

        return [$product,$tags, $categories];
    }

    private function createAProductWithTagsAndCategories(): array
    {
        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(2)->create();
        $product = Product::factory()
            ->withTags($tags)
            ->withCategories($categories)
            ->create();

        return [$product,$tags, $categories];
    }
}
