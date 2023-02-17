<?php

namespace Tests\Feature;

use App\Models\Tag;
use Database\Factories\TagFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-tags');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_list_tags(): void
    {
        $this->get(route('admin.tags.index'))
            ->assertOk();
    }

    /** @test */
    public function it_can_store_a_tag(): void
    {
        $data = TagFactory::new()->raw();
        $this->post(route('admin.tags.store'), $data)
            ->assertCreated();
        $this->assertDatabaseHas(Tag::class, $data);
    }

    /** @test */
    public function it_can_update_a_tag(): void
    {
        $tag = Tag::factory()->create();
        $data = TagFactory::new()->raw();
        $this->put(route('admin.tags.update', ['tag' => $tag->id]), $data)
            ->assertOk();
        $this->assertDatabaseHas(Tag::class, $data);
    }

    /** @test */
    public function it_can_show_a_tag(): void
    {
        $tag = Tag::factory()->create();
        $this->get(route('admin.tags.show', ['tag' => $tag->id]))
            ->assertOk()
            ->assertJsonFragment(['id' => $tag->id])
            ->assertJsonFragment(['slug' => $tag->slug])
            ->assertJsonFragment(['name' => $tag->name])
            ->assertJsonFragment(['description' => $tag->description]);
    }

    /** @test */
    public function it_can_remove_a_tag(): void
    {
        $tag = Tag::factory()->create();
        $this->delete(route('admin.tags.destroy', ['tag' => $tag->id]))
            ->assertNoContent();
        $this->assertNull(Tag::find($tag->id));
    }
}
