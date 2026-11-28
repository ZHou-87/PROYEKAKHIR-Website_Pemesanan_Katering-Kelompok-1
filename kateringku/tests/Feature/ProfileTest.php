<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_profile_page_is_displayed_for_all_roles(): void
    {
        foreach (['customer', 'admin', 'owner'] as $role) {
            $user = User::factory()->create();
            $user->assignRole($role);

            $response = $this
                ->actingAs($user)
                ->get('/profile');

            $response->assertOk();
            $response->assertSee($user->email);
            $response->assertSee(ucfirst($role));
        }
    }

    public function test_profile_updates_only_name_and_ignores_email_and_role(): void
    {
        $user = User::factory()->create([
            'email' => 'asli@kateringku.com',
        ]);
        $user->assignRole('customer');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Nama Diperbarui',
                'email' => 'diubah@kateringku.com',
                'role' => 'owner',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Nama Diperbarui', $user->name);
        $this->assertSame('asli@kateringku.com', $user->email);
        $this->assertTrue($user->hasRole('customer'));
        $this->assertFalse($user->hasRole('owner'));
    }

    public function test_profile_self_deletion_is_forbidden(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertForbidden();
        $this->assertNotNull($user->fresh());
    }
}
