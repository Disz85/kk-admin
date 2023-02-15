<?php

namespace Tests\Feature\Feature;

use App\Models\Brand;
use App\Models\Product;
use Database\Factories\BrandFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Support\Arr;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_list_brands(): void
    {
        $response = $this->get(route('admin.brands.index'));
        $response->assertOk();
    }

    /** @test */
    public function it_can_store_a_brand()
    {
        $data = BrandFactory::new()->raw();
        $data['image']['id'] = $data['image_id'];
        $this->post(route('admin.brands.store'), $data)
            ->assertCreated();
        $data['description'] = json_encode($data['description']);
        $this->assertDatabaseHas(Brand::class, Arr::except($data, ['created_by', 'image']));
    }

    /** @test */
    public function it_can_update_a_brand()
    {
        $brand = Brand::factory()->create();
        $data = BrandFactory::new()->raw();
        $data['image']['id'] = $data['image_id'];
        $this->put(route('admin.brands.update', ['brand' => $brand->id]), $data)
            ->assertOk();
        $data['description'] = json_encode($data['description']);
        $this->assertDatabaseHas(Brand::class, Arr::except($data, ['created_by', 'image']));
    }

    /** @test */
    public function it_can_show_a_brand()
    {
        $brand = Brand::factory()->create();
        $this->get(route('admin.brands.show', ['brand' => $brand->id]))
            ->assertOk()
            ->assertJsonFragment(['id' => $brand->id])
            ->assertJsonFragment(['title' => $brand->title])
            ->assertJsonFragment(['slug' => $brand->slug])
            ->assertJsonFragment(['description' => $brand->description]);
    }

    /** @test */
    public function it_cannot_remove_a_brand_that_is_connected_to_a_product()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id' => $brand->id]);
        $this->delete(
            route('admin.brands.destroy', ['brand' => $brand->id]),
            headers: ['accept' => 'application/json']
        )
            ->assertStatus(422);
    }

    /** @test */
    public function it_can_remove_a_brand_that_is_not_connected_to_a_product()
    {
        $brand = Brand::factory()->create();
        $this->delete(route('admin.brands.destroy', ['brand' => $brand->id]))
            ->assertStatus(204);
        $this->assertNull(Brand::find($brand->id));
    }
}
