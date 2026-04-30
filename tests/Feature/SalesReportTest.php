<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SalesReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_report_exposes_transactions_series(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        SaleTransaction::factory()
            ->for($admin)
            ->create([
                'status' => 'success',
                'created_at' => Carbon::parse('2026-04-05 10:00:00'),
            ]);

        $response = $this->get(route('reports.sales', [
            'period' => 'monthly',
            'date' => '2026-04-01',
        ]));

        $response->assertOk();

        $response->assertViewHas('chartTransactionsData', function ($data) {
            // April 5th in an April range means index 4 (0-based: Apr 1 = 0).
            return is_array($data)
                && count($data) >= 5
                && ((int) $data[4]) === 1;
        });
    }
}

