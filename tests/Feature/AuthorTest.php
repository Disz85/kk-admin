<?php

namespace Tests\Feature\Feature;

use App\Models\Author;
use App\Models\User;
use Database\Factories\AuthorFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'manage-authors');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_list_authors(): void
    {
        $response = $this->get(route('admin.authors.index'));
        $response->assertOk();
    }

    /** @test */
    public function it_can_store_a_author(): void
    {
        $data = AuthorFactory::new()->raw();
        $data['image']['id'] = $data['image_id'];
        $this->post(route('admin.authors.store'), $data)
            ->assertCreated();
        unset($data['image']);
        $this->assertDatabaseHas(Author::class, $data);
    }

    /** @test */
    public function it_can_update_a_author(): void
    {
        $author = Author::factory()->create();
        $data = AuthorFactory::new()->raw();
        $data['image']['id'] = $data['image_id'];
        $this->put(route('admin.authors.update', ['author' => $author->id]), $data)
            ->assertOk();
        unset($data['image']);
        $this->assertDatabaseHas(Author::class, $data);
    }

    /** @test */
    public function it_can_show_a_author(): void
    {
        $author = Author::factory()->create();
        $this->get(route('admin.authors.show', ['author' => $author->id]))
            ->assertOk()
            ->assertJsonFragment(['email' => $author->email])
            ->assertJsonFragment(['title' => $author->title])
            ->assertJsonFragment(['id' => $author->id])
            ->assertJsonFragment(['slug' => $author->slug])
            ->assertJsonFragment(['name' => $author->name]);
    }

    /** @test */
    public function it_can_remove_a_author(): void
    {
        $author = Author::factory()->create();
        $this->delete(route('admin.authors.destroy', ['author' => $author->id]))
            ->assertNoContent();
        $this->assertNull(Author::find($author->id));
    }
}
