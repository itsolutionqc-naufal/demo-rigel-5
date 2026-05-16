<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersRoleFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_user_marketing_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $marketing = User::factory()->create([
            'role' => 'marketing',
            'username' => 'mktboss',
        ]);

        $ownedUser = User::factory()->create([
            'role' => 'user',
            'marketing_owner_id' => $marketing->id,
            'email' => 'owned@example.test',
        ]);

        $regularUser = User::factory()->create([
            'role' => 'user',
            'marketing_owner_id' => null,
            'email' => 'regular@example.test',
        ]);

        $this->actingAs($admin);

        $this->get(route('users.index', ['role' => 'user_marketing']))
            ->assertOk()
            ->assertSee($ownedUser->email)
            ->assertDontSee($regularUser->email);
    }
}

