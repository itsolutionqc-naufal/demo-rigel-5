<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_can_access_marketing_sales_wallet_withdraw_transactions_reports_and_users(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $this->get(route('marketing.sales.index'))->assertOk();
        $this->get(route('marketing.wallet.index'))->assertOk();
        $this->get(route('marketing.withdraw.index'))->assertOk();
        $this->get(route('marketing.transactions.index'))->assertOk();
        $this->get(route('marketing.reports.sales'))->assertOk();
        $this->get(route('marketing.users.index'))->assertOk();
    }

    public function test_marketing_is_redirected_from_legacy_admin_routes(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $this->get(route('sales.index'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('wallet.index'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('transactions.index'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('reports.sales'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('users.index'))->assertRedirect(route('marketing.dashboard'));
    }

    public function test_marketing_is_redirected_from_admin_only_pages(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $this->get(route('articles'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('services.index'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('help.index'))->assertRedirect(route('marketing.dashboard'));
        $this->get(route('notifications.index'))->assertRedirect(route('marketing.dashboard'));
    }

    public function test_marketing_is_redirected_from_mobile_app(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $this->get(route('mobile.app'))->assertRedirect(route('marketing.dashboard'));
    }

    public function test_marketing_can_not_delete_users(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $victim = User::factory()->create(['role' => 'user']);

        $this->actingAs($marketing);

        $this->delete(route('users.destroy', $victim))->assertRedirect(route('marketing.dashboard'));
    }

    public function test_marketing_can_not_assign_admin_role(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $response = $this->post(route('marketing.users.store'), [
            'name' => 'Example',
            'email' => 'example@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('marketing.users.index'));

        $created = User::query()->where('email', 'example@example.com')->firstOrFail();
        $this->assertSame('user', $created->role);
        $this->assertSame($marketing->id, $created->marketing_owner_id);
    }
}
