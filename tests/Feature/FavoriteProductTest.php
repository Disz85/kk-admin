<?php

namespace Tests\Feature;

use App\Models\FavoriteProduct;
use Database\Factories\FavoriteProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class FavoriteProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-favorite-products');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_store_a_favorite_product_group()
    {
        $data = FavoriteProductFactory::new()->raw();
        $this->post(route('admin.favorite-products.store'), $data)
            ->assertCreated();
        $this->assertDatabaseHas(FavoriteProduct::class, Arr::only($data, ['product1_id', 'product2_id', 'product3_id', 'name']));
    }

    /** @test */
    public function it_can_show_a_favorite_product_group()
    {
        $favoriteProduct = FavoriteProduct::factory()->create();
        $this->get(route('admin.favorite-products.show', ['favorite_product' => $favoriteProduct->id]))
            ->assertOk()
            ->assertJsonFragment(['name' => $favoriteProduct->name])
            ->assertJsonFragment(['id' => $favoriteProduct->product1_id])
            ->assertJsonFragment(['id' => $favoriteProduct->product2_id])
            ->assertJsonFragment(['id' => $favoriteProduct->product3_id]);
    }

    /** @test */
    public function it_can_update_a_favorite_product_group()
    {
        $favoriteProduct = FavoriteProduct::factory()->create();
        $data = FavoriteProductFactory::new()->raw();
        $this->put(route('admin.favorite-products.update', ['favorite_product' => $favoriteProduct->id]), $data)
            ->assertOk()
            ->assertJsonFragment(['name' => $data['name']])
            ->assertJsonFragment(['product1_id' => $data['product1_id']])
            ->assertJsonFragment(['product2_id' => $data['product2_id']])
            ->assertJsonFragment(['product3_id' => $data['product3_id']]);
        $this->assertDatabaseHas(FavoriteProduct::class, Arr::only($data, ['product1_id', 'product2_id', 'product3_id', 'name']));
    }

    /** @test */
    public function it_can_list_favorite_product_groups(): void
    {
        $favoriteProduct = FavoriteProduct::factory()->create();
        $this->get(route('admin.favorite-products.index'))
            ->assertOk()
            ->assertJsonFragment(['name' => $favoriteProduct->name])
            ->assertJsonFragment(['product1_id' => $favoriteProduct->product1_id])
            ->assertJsonFragment(['product2_id' => $favoriteProduct->product2_id])
            ->assertJsonFragment(['product3_id' => $favoriteProduct->product3_id]);
    }

    /** @test */
    public function it_can_remove_a_favorite_product_group(): void
    {
        $favoriteProduct = FavoriteProduct::factory()->create();
        $this->delete(route('admin.favorite-products.destroy', ['favorite_product' => $favoriteProduct->id]))
            ->assertNoContent();
        $this->assertNull(FavoriteProduct::find($favoriteProduct->id));
    }
}
