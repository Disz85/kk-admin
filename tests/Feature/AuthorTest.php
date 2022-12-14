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

    public function test_index(): void
    {
        $response = $this->get(route('authors.index'));
        $response->assertStatus(200);
    }

    public function test_create()
    {
        $data = AuthorFactory::new()->raw();
        $this->post(route('authors.store'), $data)
            ->assertStatus(201);
        $this->assertDatabaseHas(Author::class, $data);
    }

    public function test_update()
    {
        $author = Author::factory()->create();
        $data = AuthorFactory::new()->raw();
        $this->put(route('authors.update', ['author' => $author->id]), $data)
            ->assertStatus(200);
        $this->assertDatabaseHas(Author::class, $data);
    }

    public function test_show()
    {
        $author = Author::factory()->create();
        $this->get(route('authors.show', ['author' => $author->id]))
            ->assertStatus(200)
            ->assertJsonFragment(['email' => $author->email])
            ->assertJsonFragment(['title' => $author->title])
            ->assertJsonFragment(['id' => $author->id])
            ->assertJsonFragment(['slug' => $author->slug])
            ->assertJsonFragment(['name' => $author->name]);
    }

    public function test_destroy()
    {
        $author = Author::factory()->create();
        $this->delete(route('authors.destroy', ['author' => $author->id]))
            ->assertStatus(200);
        $this->assertNull(Author::find($author->id));
    }
}
