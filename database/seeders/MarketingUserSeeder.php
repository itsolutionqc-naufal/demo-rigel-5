<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MarketingUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'marketing@gmail.com';
        $plainPassword = 'marketing123';

        $attributes = [
            'name' => 'Marketing',
            'email_verified_at' => now(),
            'password' => Hash::make($plainPassword),
            'role' => User::ROLE_MARKETING,
        ];

        if (Schema::hasColumn('users', 'username')) {
            $baseUsername = 'marketing';
            $username = $baseUsername;

            if (User::where('username', $username)->where('email', '!=', $email)->exists()) {
                $username = $baseUsername.'_'.Str::lower(Str::random(4));
            }

            $attributes['username'] = $username;
        }

        if (Schema::hasColumn('users', 'avatar')) {
            $attributes['avatar'] = null;
        }

        User::updateOrCreate(['email' => $email], $attributes);
    }
}
