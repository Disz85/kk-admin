<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-categories');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_list_categories(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertOk();
    }

    /** @test */
    public function it_can_store_a_category(): void
    {
        $data = CategoryFactory::new()->raw();
        $this->post(route('admin.categories.store'), $data)
            ->assertCreated();
        $data['description'] = json_encode($data['description']);
        $this->assertDatabaseHas(Category::class, $data);
    }

    /** @test */
    public function it_can_update_a_category(): void
    {
        $category = Category::factory()->create();
        $data = CategoryFactory::new()->raw();
        $this->put(route('admin.categories.update', ['category' => $category->id]), $data)
            ->assertOk();
        $data['description'] = json_encode($data['description']);
        $this->assertDatabaseHas(Category::class, $data);
    }

    /** @test */
    public function it_can_show_a_category(): void
    {
        $category = Category::factory()->create();
        $this->get(route('admin.categories.show', ['category' => $category->id]))
            ->assertOk()
            ->assertJsonFragment(['type' => $category->type])
            ->assertJsonFragment(['description' => $category->description])
            ->assertJsonFragment(['slug' => $category->slug])
            ->assertJsonFragment(['name' => $category->name]);
    }

    /** @test */
    public function it_can_remove_a_category(): void
    {
        $category = Category::factory()->create();
        $this->delete(route('admin.categories.destroy', ['category' => $category->id]))
            ->assertNoContent();
        $this->assertNull(Category::find($category->id));
    }
}
