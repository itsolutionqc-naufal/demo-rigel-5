<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersDeleteAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_regular_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $victim = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin);

        $this->delete(route('users.destroy', $victim))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $victim->id]);
    }

    public function test_admin_can_delete_marketing_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $victim = User::factory()->create(['role' => 'marketing']);

        $this->actingAs($admin);

        $this->delete(route('users.destroy', $victim))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $victim->id]);
    }

    public function test_admin_can_delete_another_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $victim = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        $this->delete(route('users.destroy', $victim))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $victim->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->delete(route('users.destroy', $admin))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_marketing_cannot_delete_users(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $victim = User::factory()->create(['role' => 'user']);

        $this->actingAs($marketing);

        $this->delete(route('users.destroy', $victim))
            ->assertRedirect(route('marketing.dashboard'));

        $this->assertDatabaseHas('users', ['id' => $victim->id]);
    }

    public function test_regular_user_cannot_delete_users(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $victim = User::factory()->create(['role' => 'user']);

        $this->actingAs($user);

        $this->delete(route('users.destroy', $victim))
            ->assertRedirect(route('mobile.app'));

        $this->assertDatabaseHas('users', ['id' => $victim->id]);
    }

    public function test_delete_returns_forbidden_json_for_non_admin(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $victim = User::factory()->create(['role' => 'user']);

        $this->actingAs($marketing);

        $this->deleteJson(route('users.destroy', $victim))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $victim->id]);
    }
}

