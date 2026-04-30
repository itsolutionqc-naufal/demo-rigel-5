<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@gmail.com';
        $plainPassword = 'admin123';

        $user = User::where('email', $email)->first();

        $attributes = [
            'password' => Hash::make($plainPassword),
            'role' => User::ROLE_ADMIN,
        ];

        if (! $user) {
            $attributes['name'] = 'Admin';
            $attributes['email_verified_at'] = now();
        }

        if (Schema::hasColumn('users', 'username')) {
            $baseUsername = 'admin';
            $username = $user?->username ?: $baseUsername;

            if (User::where('username', $username)->where('email', '!=', $email)->exists()) {
                $username = $baseUsername.'_'.Str::lower(Str::random(4));
            }

            $attributes['username'] = $username;
        }

        User::updateOrCreate(['email' => $email], $attributes);
    }
}
