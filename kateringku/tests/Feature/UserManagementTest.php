<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_owner_can_view_users_and_create_new_admin_account(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner)
            ->get('/owner/users')
            ->assertOk();

        $response = $this->actingAs($owner)->post('/owner/users', [
            'name' => 'Admin Baru',
            'email' => 'adminbaru@kateringku.com',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/owner/users');

        $created = User::where('email', 'adminbaru@kateringku.com')->first();
        $this->assertNotNull($created);
        $this->assertTrue($created->hasRole('admin'));
    }

    public function test_owner_can_update_another_users_role_and_delete_another_user(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $target = User::factory()->create();
        $target->assignRole('customer');

        $this->actingAs($owner)
            ->patch("/owner/users/{$target->id}/role", ['role' => 'admin'])
            ->assertRedirect('/owner/users');

        $this->assertTrue($target->fresh()->hasRole('admin'));
        $this->assertFalse($target->fresh()->hasRole('customer'));

        $this->actingAs($owner)
            ->delete("/owner/users/{$target->id}")
            ->assertRedirect('/owner/users');

        $this->assertNull($target->fresh());
    }

    public function test_owner_cannot_change_own_role_or_delete_own_account(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner)
            ->patch("/owner/users/{$owner->id}/role", ['role' => 'customer'])
            ->assertForbidden();

        $this->assertTrue($owner->fresh()->hasRole('owner'));

        $this->actingAs($owner)
            ->delete("/owner/users/{$owner->id}")
            ->assertForbidden();

        $this->assertNotNull($owner->fresh());
    }

    public function test_non_owner_cannot_access_owner_user_management(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($admin)->get('/owner/users')->assertForbidden();
        $this->actingAs($customer)->get('/owner/users')->assertForbidden();
    }

    public function test_admin_can_view_readonly_customer_list(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $customer = User::factory()->create(['name' => 'Pelanggan Setia']);
        $customer->assignRole('customer');

        $otherAdmin = User::factory()->create(['name' => 'Admin Lain']);
        $otherAdmin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/customers');

        $response->assertOk();
        $response->assertSee('Pelanggan Setia');
        $response->assertDontSee('Admin Lain');
    }

    public function test_non_admin_cannot_access_admin_customers_route(): void
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($owner)->get('/admin/customers')->assertForbidden();
        $this->actingAs($customer)->get('/admin/customers')->assertForbidden();
    }
}
