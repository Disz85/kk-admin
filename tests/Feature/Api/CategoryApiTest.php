<?php

namespace Tests\Feature\Api;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Category::factory(3)
            ->create([
                'type' => CategoryTypeEnum::Article->value,
            ]);
    }

    /** @test */
    public function it_can_list_categories(): void
    {
        $this->get(route('api.categories.index'))
            ->assertOk();
    }

    /** @test */
    public function it_can_list_root_categories_in_specific_order_with_children(): void
    {
        $rootCategories = Category::query()
            ->select('uuid')
            ->where('type', CategoryTypeEnum::Article->value)
            ->whereNull('parent_id')
            ->orderByDesc('updated_at')
            ->pluck('uuid')
            ->toArray();

        $categoryResponse = $this->get(route('api.categories.index', [
            'include' => 'children',
            'parent_id' => null,
        ]));

        $categoryResponse->assertOk()
            ->assertSeeInOrder($rootCategories);

        foreach ($categoryResponse->json('data') as $item) {
            $this->assertArrayHasKey('children', $item);
        }
    }

    // TODO: Add category hierarchy by setting parent_id on category creation, test children accordingly.
}
