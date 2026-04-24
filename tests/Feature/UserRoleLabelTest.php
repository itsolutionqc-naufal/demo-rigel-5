<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleLabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_label_shows_marketing_owner_username_for_owned_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $marketing = User::factory()->create([
            'role' => 'marketing',
            'username' => 'mktboss',
        ]);

        User::factory()->create([
            'role' => 'user',
            'marketing_owner_id' => $marketing->id,
        ]);

        $this->actingAs($admin);

        $this->get(route('users.index'))
            ->assertOk()
            ->assertSee('User Marketing (mktboss)');
    }
}

