<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command
{
    protected $signature = 'user:create
                            {email : Email untuk login}
                            {--name= : Nama user (default: bagian sebelum @)}
                            {--username= : Username (opsional)}
                            {--password= : Password plain (kalau kosong akan digenerate)}
                            {--role=user : Role user (default: user)}
                            {--verified : Set email_verified_at = now()}
                            {--update : Kalau email sudah ada, update datanya}';

    protected $description = 'Buat user baru (atau update) dengan cepat';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $existing = User::where('email', $email)->first();

        if ($existing && ! $this->option('update')) {
            $this->error("User sudah ada untuk email: {$email} (id={$existing->id}). Gunakan --update kalau mau update.");
            return self::FAILURE;
        }

        $name = (string) ($this->option('name') ?: Str::before($email, '@') ?: 'User');
        $username = $this->option('username');
        $role = (string) ($this->option('role') ?: 'user');
        $verified = (bool) $this->option('verified');

        $plainPassword = $this->option('password');
        $generated = false;

        if (! $plainPassword) {
            $plainPassword = Str::random(20);
            $generated = true;
        }

        $payload = [
            'name' => $name,
            'email' => $email,
            'password' => $plainPassword,
            'role' => $role,
        ];

        if ($username !== null) {
            $payload['username'] = $username;
        }

        if ($verified) {
            $payload['email_verified_at'] = now();
        }

        $user = $existing
            ? tap($existing)->update($payload)
            : User::create($payload);

        $this->info(($existing ? 'Updated' : 'Created') . " user: id={$user->id}, email={$user->email}, role={$user->role}");

        if ($generated) {
            $this->line("Password (generated): {$plainPassword}");
        } else {
            $this->line('Password: (sesuai input)');
        }

        return self::SUCCESS;
    }
}

