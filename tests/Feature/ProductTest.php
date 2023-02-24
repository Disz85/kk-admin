<?php

namespace Tests\Feature;

use App\Enum\CategoryTypeEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-products');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_list_products(): void
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertOk();
    }

    /** @test */
    public function a_product_belongs_to_a_brand(): void
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id' => $brand->id]);

        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertEquals($brand->id, $product->brand_id);
    }

    /** @test */
    public function a_product_belongs_to_many_ingredients(): void
    {
        $ingredients = Ingredient::factory()->count(3)->create();
        $product = Product::factory()->withIngredients($ingredients)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $product->ingredients);
    }

    /** @test */
    public function it_can_show_a_product()
    {
        list($product, $tags, $category, $ingredients) = $this->createAProductWithRelations();
        $response = $this->get(route('admin.products.show', ['product' => $product->id]));

        $response->assertOk()
            ->assertJsonFragment(['id' => $product->id])
            ->assertJsonFragment(['name' => $product->name]);

        $response->assertJsonFragment([
            'name' => $category->name,
            'id' => $category->id,
        ]);

        foreach ($tags as $tag) {
            $response->assertJsonFragment([
                'name' => $tag->name,
                'id' => $tag->id,
            ]);
        }
        foreach ($ingredients as $ingredient) {
            $response->assertJsonFragment([
                'name' => $ingredient->name,
                'id' => $ingredient->id,
            ]);
        }
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        list($product, $tags, $category, $ingredients) = $this->makeDummyRequestData();
        $response = $this->post(route('admin.products.store'), $product);
        $response->assertCreated();
        $this->assertDatabaseHas(Product::class, Arr::only($product, ['name','price','brand_id']));

        $response->assertJsonFragment([
            'name' => $category->name,
            'id' => $category->id,
        ]);

        foreach ($tags as $tag) {
            $response->assertJsonFragment([
                'name' => $tag->name,
                'id' => $tag->id,
            ]);
        }
        foreach ($ingredients as $ingredient) {
            $response->assertJsonFragment([
                'name' => $ingredient->name,
                'id' => $ingredient->id,
            ]);
        }
    }

    /** @test */
    public function it_can_update_a_product(): void
    {
        list($product, $tags, $category, $ingredients) = $this->createAProductWithRelations();
        list($changedProduct, $changedTags, $changedCategory, $changedIngredients) = $this->makeDummyRequestData();

        $response = $this->put(
            route('admin.products.update', ['product' => $product->id]),
            $changedProduct
        );

        $response->assertOk();
        $this->assertDatabaseHas(Product::class, Arr::only($changedProduct, ['name','price']));

        $response->assertJsonFragment([
            'name' => $changedCategory->name,
            'id' => $changedCategory->id,
        ]);

        foreach ($changedTags as $tag) {
            $response->assertJsonFragment([
                'name' => $tag->name,
                'id' => $tag->id,
            ]);
        }
        foreach ($changedIngredients as $ingredient) {
            $response->assertJsonFragment([
                'name' => $ingredient->name,
                'id' => $ingredient->id,
            ]);
        }
    }

    /** @test */
    public function it_can_remove_a_product(): void
    {
        $product = Product::factory()->create();
        $this->delete(route('admin.products.destroy', ['product' => $product->id]))
            ->assertNoContent();
        $this->assertNull(Product::find($product->id));
    }

    private function makeDummyRequestData(): array
    {
        $category = Category::factory()->createOne(['type' => CategoryTypeEnum::Product]);
        $tags = Tag::factory()->count(2)->create();
        $ingredients = Ingredient::factory()->count(2)->create();
        $product = ProductFactory::new()->raw();
        $product['image']['id'] = $product['image_id'];
        $product['brand']['id'] = $product['brand_id'];
        $product['category'] = $category;
        $product['tags'] = $tags->toArray();
        $product['ingredients'] = $ingredients->toArray();

        return [$product, $tags, $category, $ingredients];
    }

    private function createAProductWithRelations(): array
    {
        $category = Category::factory()->createOne(['type' => CategoryTypeEnum::Product]);
        $tags = Tag::factory()->count(2)->create();
        $ingredients = Ingredient::factory()->count(2)->create();

        $product = Product::factory()
            ->withTags($tags)
            ->withCategory($category)
            ->withIngredients($ingredients)
            ->createOne();

        return [$product, $tags, $category, $ingredients];
    }
}
