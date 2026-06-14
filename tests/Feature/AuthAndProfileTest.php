<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthAndProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_login_code_via_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'debug_code']);
            
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    public function test_user_can_verify_code_and_get_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'login_code' => '1234',
            'login_code_expires_at' => now()->addMinutes(5)
        ]);

        $response = $this->postJson('/api/login/verify', [
            'email' => 'test@example.com',
            'code' => '1234'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_authorized_user_can_get_and_update_profile()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJsonPath('first_name', 'John');

        $updateResponse = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->putJson('/api/profile', [
            'first_name' => 'Jane',
            'last_name' => 'Smith'
        ]);

        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Jane'
        ]);
    }

    public function test_authorized_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Вы успешно вышли из системы.');

        // Verify token is revoked
        $this->assertEquals(0, $user->tokens()->count());
    }
}
