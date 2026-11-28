<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_user_can_login_via_api_and_receive_sanctum_token(): void
    {
        $user = User::factory()->create([
            'email' => 'api@kateringku.com',
        ]);
        $user->assignRole('customer');

        $response = $this->postJson('/api/v1/login', [
            'email' => 'api@kateringku.com',
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'token',
                'token_type',
                'user' => ['id', 'name', 'email', 'roles'],
            ])
            ->assertJsonPath('user.email', 'api@kateringku.com')
            ->assertJsonPath('user.roles.0', 'customer');

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_api_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized();
    }

    public function test_api_login_is_throttled_after_five_requests_per_minute(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertUnauthorized();
        }

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_authenticated_user_can_fetch_me_and_logout(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $token = $user->createToken('kateringku-api')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.roles.0', 'admin');

        $this->withToken($token)
            ->postJson('/api/v1/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_me_and_logout_require_sanctum_authentication(): void
    {
        $this->getJson('/api/v1/me')->assertUnauthorized();
        $this->postJson('/api/v1/logout')->assertUnauthorized();
    }
}
