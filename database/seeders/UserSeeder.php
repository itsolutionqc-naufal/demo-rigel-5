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
        // Create Admin User
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'username' => 'admin',
            ]);
        }

        // Create Regular User
        if (!User::where('email', 'user@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Regular User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'username' => 'user',
            ]);
        }

        // Create 10 dummy users
        User::factory(10)->create();
    }
}