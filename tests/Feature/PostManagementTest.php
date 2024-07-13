<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_update_and_delete_post()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        // Create Post
        $response = $this->postJson('/api/posts', ['content' => 'First Post']);
        $response->assertStatus(201)
            ->assertJson(['content' => 'First Post']);

        $postId = $response->json('id');

        // Update Post
        $response = $this->putJson("/api/posts/{$postId}", ['content' => 'Updated Post']);
        $response->assertStatus(200)
            ->assertJson(['content' => 'Updated Post']);

        // Delete Post
        $response = $this->deleteJson("/api/posts/{$postId}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Post deleted']);
    }

    /** @test */
    public function content_moderation_filters_hate_speech()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/posts', ['content' => 'Hate speech content']);
        $response->assertStatus(400)
            ->assertJson(['message' => 'Content contains prohibited language']);
    }
}
