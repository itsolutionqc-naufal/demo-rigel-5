<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_users_are_redirected_away_from_users_management(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('mobile.app'));
    }

    public function test_marketing_users_are_redirected_from_admin_users_management_route(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('marketing.dashboard'));
    }

    public function test_admin_users_can_access_users_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('users.index'));
        $response->assertOk();
    }

    public function test_marketing_users_can_access_marketing_users_management(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $response = $this->get(route('marketing.users.index'));
        $response->assertOk();
    }
}
