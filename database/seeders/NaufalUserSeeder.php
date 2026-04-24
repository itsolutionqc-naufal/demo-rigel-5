<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NaufalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'naufal@gmail.com'],
            [
                'name' => 'Naufal',
                'username' => 'naufal',
                'role' => 'user',
                'email_verified_at' => now(),
                'password' => Hash::make('naufal123'),
            ]
        );
    }
}

