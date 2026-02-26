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
        // Owner
        User::create([
            'name' => 'Owner Restaurant',
            'email' => 'owner@restaurant.com',
            'Role' => 'Owner',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Manajer
        User::create([
            'name' => 'Manager Restaurant',
            'email' => 'manager@restaurant.com',
            'Role' => 'Manajer',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Kasir 1
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@restaurant.com',
            'Role' => 'Kasir',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Kasir 2
        User::create([
            'name' => 'Kasir 2',
            'email' => 'kasir2@restaurant.com',
            'Role' => 'Kasir',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Additional inactive user example
        User::create([
            'name' => 'Kasir Inactive',
            'email' => 'kasir.inactive@restaurant.com',
            'Role' => 'Kasir',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
            'email_verified_at' => now(),
        ]);

    }
}
