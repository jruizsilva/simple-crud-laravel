<?php

namespace Tests\Feature;

use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostTest extends TestCase
{
    use RefreshDatabase; // Create the database and run the migrations in each test

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PostSeeder::class); // Run the seeder PostSeeder
    }
    #[Test]
    public function posts_must_be_created(): void
    {
        $this->assertDatabaseCount('posts', 10);
    }

    #[Test]
    public function must_show_post_list(): void
    {
        $response = $this->getJson(route('posts.index'));
        $response->assertStatus(200);
        $response->assertJsonCount(10);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'content',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    #[Test]
    public function must_show_post(): void
    {
        $response = $this->getJson(route('posts.show', 1));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'content',
            'created_at',
            'updated_at',
        ]);
    }
    #[Test]
    public function must_create_post(): void
    {
        $response = $this->postJson(route('posts.store'), [
            'title' => 'Test Post',
            'content' => 'This is a test post',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'title',
            'content',
            'created_at',
            'updated_at',
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post',
        ]);
    }

    #[Test]
    public function must_update_post(): void
    {
        $response = $this->putJson(route('posts.update', 1), [
            'title' => 'Updated Test Post',
            'content' => 'This is an updated test post',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'content',
            'created_at',
            'updated_at',
        ]);
        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => 'Updated Test Post',
            'content' => 'This is an updated test post',
        ]);
    }

    #[Test]
    public function must_delete_post(): void
    {
        $response = $this->deleteJson(route('posts.destroy', 1));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', [
            'id' => 1,
        ]);
    }
}