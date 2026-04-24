<?php

namespace Tests\Feature;

use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SalesReportWeeklyTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_report_exposes_transactions_series(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $date = Carbon::parse('2026-04-08 10:00:00');
        $week = $date->format('o-\\WW');
        $start = $date->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $index = $start->diffInDays($date->copy()->startOfDay());

        SaleTransaction::factory()
            ->for($admin)
            ->create([
                'status' => 'success',
                'created_at' => $date,
            ]);

        $response = $this->get(route('reports.sales', [
            'period' => 'weekly',
            'week' => $week,
        ]));

        $response->assertOk();

        $response->assertViewHas('chartTransactionsData', function ($data) use ($index) {
            return is_array($data)
                && count($data) >= 7
                && ((int) ($data[$index] ?? 0)) === 1;
        });
    }
}

