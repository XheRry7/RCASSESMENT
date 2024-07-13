<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AdminRoleTest extends TestCase
{
    use RefreshDatabase;

    public function only_admin_can_access_admin_routes()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_admin' => false]);

        Sanctum::actingAs($admin);
        $response = $this->postJson('/api/moderate', ['content' => 'Some content']);
        $response->assertStatus(200);

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/moderate', ['content' => 'Some content']);
        $response->assertStatus(403);
    }
}
