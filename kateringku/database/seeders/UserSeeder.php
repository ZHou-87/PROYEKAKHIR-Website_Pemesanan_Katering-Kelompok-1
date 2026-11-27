<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner KateringKu',
                'email' => 'owner@kateringku.com',
                'role' => 'owner',
            ],
            [
                'name' => 'Admin KateringKu',
                'email' => 'admin@kateringku.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Customer KateringKu',
                'email' => 'customer@kateringku.com',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
