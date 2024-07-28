<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    private $user;
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            "name" => "test",
            "email" => "test@example.com",
            "password" => bcrypt("password")
        ]);
    }

    #[Test]
    public function should_update_the_user(): void
    {
        $data = [
            "name" => "new name",
            "email" => "newemail@example.com",
        ];
        $response = $this->actingAs($this->user)->putJson(route("users.update", [
            "user" => $this->user->id
        ]), $data);

        dd($response->json());

        $response->assertStatus(200);
    }
}