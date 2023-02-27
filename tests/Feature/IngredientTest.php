<?php

namespace Tests\Feature;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Ingredient;
use Database\Factories\IngredientFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-ingredients');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_list_ingredients(): void
    {
        $response = $this->get(route('admin.ingredients.index'));
        $response->assertOk();
    }

    /** @test */
    public function it_can_store_an_ingredient()
    {
        list($ingredient, $categories) = $this->makeDummyRequestData();

        $response = $this->post(route('admin.ingredients.store'), $ingredient);
        $response->assertCreated();
        $this->assertDatabaseHas(Ingredient::class, Arr::only($ingredient, ['name']));

        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }
    }

    /** @test */
    public function it_can_update_an_ingredient()
    {
        list($ingredient, $categories) = $this->createIngredientWithRelations();
        list($changedIngredient, $changedCategories) = $this->makeDummyRequestData();

        $response = $this->put(route('admin.ingredients.update', ['ingredient' => $ingredient->id]), $changedIngredient)
            ->assertOk();

        $this->assertDatabaseHas(Ingredient::class, Arr::only($changedIngredient, ['name', 'is_approved']));

        foreach ($changedCategories as $category) {
            $response->assertJsonFragment([
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }
    }

    /** @test */
    public function it_can_show_an_ingredient()
    {
        list($ingredient, $categories) = $this->createIngredientWithRelations();
        $response = $this->get(route('admin.ingredients.show', ['ingredient' => $ingredient->id]))
            ->assertOk()
            ->assertJsonFragment(['id' => $ingredient->id])
            ->assertJsonFragment(['name' => $ingredient->name]);

        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }
    }

    /** @test */
    public function it_can_remove_an_ingredient()
    {
        $ingredient = Ingredient::factory()->create();
        $this->delete(route('admin.ingredients.destroy', ['ingredient' => $ingredient->id]))
            ->assertNoContent();
        $this->assertNull(Ingredient::find($ingredient->id));
    }

    /**
     * @return array
     */
    protected function makeDummyRequestData(): array
    {
        $ingredient = IngredientFactory::new()->raw();
        $categories = Category::factory()->count(2)->create([
            'type' => CategoryTypeEnum::Ingredient,
        ]);
        $ingredient['categories'] = $categories->toArray();

        return [$ingredient, $categories];
    }

    /**
     * @return array
     */
    private function createIngredientWithRelations(): array
    {
        $categories = Category::factory()->count(2)->create(['type' => CategoryTypeEnum::Ingredient]);
        $ingredient = Ingredient::factory()
            ->withCategories($categories)
            ->create();

        return [$ingredient, $categories];
    }
}
