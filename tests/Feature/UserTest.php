<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_users()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.index'));
        $response->assertOk()
            ->assertJsonFragment([
            'email' => $user->email,
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
                ]);
    }

    /** @test */
    public function it_can_show_a_user()
    {
        $user = User::factory()->create();
        $this->get(route('users.show', ['user' => $user->id]))
            ->assertOk()
            ->assertJsonFragment([
                'email' => $user->email,
                'id' => $user->id,
                'lastname' => $user->lastname,
            ]);
    }
}
