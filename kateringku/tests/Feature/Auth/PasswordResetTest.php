<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_forgot_password_creates_token_and_redirects_directly_to_reset_page(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $location = (string) $response->headers->get('Location');
        $this->assertStringContainsString('/reset-password/', $location);
        $this->assertStringContainsString('email='.urlencode($user->email), $location);

        $resetPage = $this->get($location);
        $resetPage->assertOk();
    }

    public function test_forgot_password_with_unregistered_email_returns_validation_error(): void
    {
        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'tidakada@example.com',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('email');
    }

    public function test_password_can_be_reset_with_token_from_direct_redirect(): void
    {
        $user = User::factory()->create();

        $forgotResponse = $this->post('/forgot-password', ['email' => $user->email]);
        $location = (string) $forgotResponse->headers->get('Location');

        $path = parse_url($location, PHP_URL_PATH) ?? '';
        $token = basename($path);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('new-password-123', $user->refresh()->password));
    }
}
