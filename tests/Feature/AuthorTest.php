<?php

namespace Tests\Feature\Feature;

use App\Models\Author;
use Database\Factories\AuthorFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_list_authors(): void
    {
        $response = $this->get(route('authors.index'));
        $response->assertOk();
    }

    /** @test */
    public function it_can_store_a_author()
    {
        $data = AuthorFactory::new()->raw();
        $this->post(route('authors.store'), $data)
            ->assertCreated();
        $this->assertDatabaseHas(Author::class, $data);
    }

    /** @test */
    public function it_can_update_a_author()
    {
        $author = Author::factory()->create();
        $data = AuthorFactory::new()->raw();
        $this->put(route('authors.update', ['author' => $author->id]), $data)
            ->assertOk();
        $this->assertDatabaseHas(Author::class, $data);
    }

    /** @test */
    public function it_can_show_a_author()
    {
        $author = Author::factory()->create();
        $this->get(route('authors.show', ['author' => $author->id]))
            ->assertOk()
            ->assertJsonFragment(['email' => $author->email])
            ->assertJsonFragment(['title' => $author->title])
            ->assertJsonFragment(['id' => $author->id])
            ->assertJsonFragment(['slug' => $author->slug])
            ->assertJsonFragment(['name' => $author->name]);
    }

    /** @test */
    public function it_can_remove_a_author()
    {
        $author = Author::factory()->create();
        $this->delete(route('authors.destroy', ['author' => $author->id]))
            ->assertNoContent();
        $this->assertNull(Author::find($author->id));
    }
}
