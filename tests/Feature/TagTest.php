<?php

namespace Tests\Feature;

use App\Models\Tag;
use Database\Factories\TagFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_list_tags(): void
    {
        $this->get(route('tags.index'))
            ->assertOk();
    }

    /** @test */
    public function it_can_store_a_tag(): void
    {
        $data = TagFactory::new()->raw();
        $this->post(route('tags.store'), $data)
            ->assertCreated();
        $this->assertDatabaseHas(Tag::class, $data);
    }

    /** @test */
    public function it_can_update_a_tag(): void
    {
        $tag = Tag::factory()->create();
        $data = TagFactory::new()->raw();
        $this->put(route('tags.update', ['tag' => $tag->id]), $data)
            ->assertOk();
        $this->assertDatabaseHas(Tag::class, $data);
    }

    /** @test */
    public function it_can_show_a_tag(): void
    {
        $tag = Tag::factory()->create();
        $this->get(route('tags.show', ['tag' => $tag->id]))
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
        $this->delete(route('tags.destroy', ['tag' => $tag->id]))
            ->assertOk();
        $this->assertNull(Tag::find($tag->id));
    }
}
