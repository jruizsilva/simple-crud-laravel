<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => '1234',
        ]);
    }

#[Test]
public function should_create_a_user(): void
{
    $data = [
        'name' => 'Test',
        'email' => 'test@gmail.com',
        'password' => '1234',
    ];

    $response = $this->postJson(route('users.store'), $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);
}

#[Test]
public function should_login(): void
{
    $data = [
        'email' => $this->user->email,
        'password' => '1234',
    ];
    $response = $this->postJson(route('login'), $data);

    $response->assertStatus(200);
    $response->assertJsonStructure(['token']);
}

#[Test]
public function should_not_login(): void
{
    $data = [
        'email' => $this->user->email,
        'password' => '12345',
    ];
    $response = $this->postJson(route('login'), $data);
    $response->assertStatus(401);
}

#[Test]
public function should_return_user_authenticated(): void
{
    $response = $this->actingAs($this->user)->getJson(route('me'));
    $response->assertStatus(200);
    $response->assertJsonStructure(['id', 'name', 'email']);
    $response->assertJson(['id' => $this->user->id]);
}
}