<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_users_are_redirected_to_mobile_app(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('mobile.app'));
    }

    public function test_marketing_users_are_redirected_to_marketing_dashboard(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('marketing.dashboard'));
    }

    public function test_admin_users_can_visit_the_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('dashboard'));
        $response->assertOk();

        $cacheControl = (string) $response->headers->get('Cache-Control');
        $this->assertStringContainsString('no-store', $cacheControl);
    }
}
