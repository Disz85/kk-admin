<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.index'));
        $response->assertStatus(200)
            ->assertJsonFragment([
            'email' => $user->email,
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
                ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $this->get(route('users.show', ['user' => $user->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'email' => $user->email,
                'id' => $user->id,
                'lastname' => $user->lastname,
            ]);
    }

}
