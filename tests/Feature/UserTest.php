<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->user->givePermissionTo('manage-admin', 'show-users');
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_show_users(): void
    {
        $user = User::factory()->create();
        $response = $this->get(route('admin.users.index'));
        $response->assertOk()
            ->assertJsonFragment([
            'email' => $user->email,
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
                ]);
    }

    /** @test */
    public function it_can_show_a_user(): void
    {
        $user = User::factory()->create();
        $this->get(route('admin.users.show', ['user' => $user->id]))
            ->assertOk()
            ->assertJsonFragment([
                'email' => $user->email,
                'id' => $user->id,
                'lastname' => $user->lastname,
            ]);
    }
}
