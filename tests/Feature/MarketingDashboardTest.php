<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get(route('marketing.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_marketing_users_can_visit_the_marketing_dashboard(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $this->actingAs($marketing);

        $response = $this->get(route('marketing.dashboard'));
        $response->assertOk();
    }

    public function test_admin_users_are_redirected_away_from_marketing_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('marketing.dashboard'));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_regular_users_are_redirected_away_from_marketing_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get(route('marketing.dashboard'));
        $response->assertRedirect(route('mobile.app'));
    }

    public function test_marketing_dashboard_metrics_are_scoped_and_exclude_wallet_transactions(): void
    {
        $marketing = User::factory()->create(['role' => 'marketing']);
        $managed1 = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketing->id]);
        $managed2 = User::factory()->create(['role' => 'user', 'marketing_owner_id' => $marketing->id]);
        $otherUser = User::factory()->create(['role' => 'user']);

        $rangeStart = Carbon::parse('2026-04-01')->startOfDay();
        $rangeEnd = Carbon::parse('2026-04-30')->endOfDay();

        // In-scope successful transactions (non-wallet)
        SaleTransaction::factory()->create([
            'user_id' => $managed1->id,
            'status' => 'success',
            'amount' => 100_000,
            'commission_rate' => 10,
            'commission_amount' => 10_000,
            'transaction_type' => null,
            'created_at' => Carbon::parse('2026-04-10 10:00:00'),
        ]);

        SaleTransaction::factory()->create([
            'user_id' => $managed2->id,
            'status' => 'success',
            'amount' => 200_000,
            'commission_rate' => 10,
            'commission_amount' => 20_000,
            'transaction_type' => 'transaction',
            'created_at' => Carbon::parse('2026-04-11 10:00:00'),
        ]);

        // In-scope failed transaction (non-wallet) -> counts to failed card only
        SaleTransaction::factory()->create([
            'user_id' => $managed1->id,
            'status' => 'failed',
            'amount' => 123_000,
            'commission_rate' => 10,
            'commission_amount' => 12_300,
            'transaction_type' => null,
            'created_at' => Carbon::parse('2026-04-12 10:00:00'),
        ]);

        // Out-of-scope successful transaction (not managed) -> must be excluded
        SaleTransaction::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'success',
            'amount' => 999_999,
            'commission_rate' => 10,
            'commission_amount' => 99_999,
            'transaction_type' => null,
            'created_at' => Carbon::parse('2026-04-10 10:00:00'),
        ]);

        // In-scope wallet transactions (topup/withdrawal) -> must be excluded even if success
        SaleTransaction::factory()->create([
            'user_id' => $managed1->id,
            'status' => 'success',
            'amount' => 500_000,
            'commission_rate' => 0,
            'commission_amount' => 0,
            'transaction_type' => 'topup',
            'created_at' => Carbon::parse('2026-04-10 10:00:00'),
        ]);

        SaleTransaction::factory()->create([
            'user_id' => $managed1->id,
            'status' => 'success',
            'amount' => 600_000,
            'commission_rate' => 0,
            'commission_amount' => 0,
            'transaction_type' => 'withdrawal',
            'created_at' => Carbon::parse('2026-04-10 10:00:00'),
        ]);

        // Act
        $this->actingAs($marketing);
        $response = $this->get(route('marketing.dashboard', [
            'start_date' => $rangeStart->toDateString(),
            'end_date' => $rangeEnd->toDateString(),
        ]));

        $response->assertOk();

        // Assert summary cards
        $response->assertViewHas('totalSales', 300_000.0);
        $response->assertViewHas('totalCommissions', 30_000.0);
        $response->assertViewHas('successfulTransactions', 2);
        $response->assertViewHas('failedTransactions', 1);

        // Assert normalized daily rows (Apr 2026 has 30 days)
        $response->assertViewHas('dailyRows', function ($rows) {
            return is_array($rows) && count($rows) === 30;
        });

        // Assert per-user chart includes only managed users (top 10, sorted by sales desc)
        $response->assertViewHas('userLabels', function ($labels) use ($managed1, $managed2) {
            if (! is_array($labels) || count($labels) !== 2) {
                return false;
            }

            return $labels[0] === $managed2->name && $labels[1] === $managed1->name;
        });

        // Sanity: trend series exists for the whole period
        $response->assertViewHas('trendLabels', function ($labels) {
            return is_array($labels) && count($labels) === 30;
        });
    }
}
