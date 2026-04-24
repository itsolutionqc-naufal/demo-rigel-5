<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_and_transfer_marketing_ownership(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $marketing = User::factory()->create(['role' => 'marketing']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin);

        $this->put(route('users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'user',
            'marketing_owner_id' => $marketing->id,
        ])->assertRedirect(route('users.index'));

        $this->assertSame($marketing->id, $user->fresh()->marketing_owner_id);

        $this->put(route('users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'marketing',
            'marketing_owner_id' => $marketing->id,
        ])->assertRedirect(route('users.index'));

        $this->assertNull($user->fresh()->marketing_owner_id);
    }

    public function test_admin_validation_rejects_non_marketing_owner_id(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $notMarketing = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin);

        $this->put(route('users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'user',
            'marketing_owner_id' => $notMarketing->id,
        ])->assertSessionHasErrors(['marketing_owner_id']);
    }
}

