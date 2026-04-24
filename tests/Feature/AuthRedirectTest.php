<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_login_page_has_no_store_cache_headers(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();

        $cacheControl = (string) $response->headers->get('Cache-Control');
        $this->assertStringContainsString('no-store', $cacheControl);
    }

    public function test_authenticated_user_visiting_login_is_redirected_to_mobile_app(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get(route('login'));
        $response->assertRedirect(route('mobile.app'));
    }

    public function test_authenticated_admin_visiting_login_is_redirected_to_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('login'));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_marketing_visiting_login_is_redirected_to_marketing_dashboard(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $response = $this->get(route('login'));
        $response->assertRedirect(route('marketing.dashboard'));
    }
}
