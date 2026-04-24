<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingDashboardController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $baseQuery = SaleTransaction::query()
            ->whereBetween('created_at', [$start, $end])
            ->where(function ($query) {
                $query->whereNull('transaction_type')
                    ->orWhereNotIn('transaction_type', ['topup', 'withdrawal']);
            });

        if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
            $baseQuery->visibleToMarketing($actor);
        }

        $totalSales = (float) (clone $baseQuery)
            ->where('status', 'success')
            ->sum('amount');

        $totalCommissions = (float) (clone $baseQuery)
            ->where('status', 'success')
            ->sum('commission_amount');

        $successfulTransactions = (clone $baseQuery)
            ->where('status', 'success')
            ->count();

        $failedTransactions = (clone $baseQuery)
            ->where('status', 'failed')
            ->count();

        $dateExpression = DB::getDriverName() === 'sqlite'
            ? 'date(created_at)'
            : 'DATE(created_at)';

        $trend = (clone $baseQuery)
            ->where('status', 'success')
            ->selectRaw("{$dateExpression} as date")
            ->selectRaw('SUM(amount) as daily_sales')
            ->selectRaw('SUM(commission_amount) as daily_commission')
            ->selectRaw('COUNT(*) as daily_transactions')
            ->groupBy(DB::raw($dateExpression))
            ->orderBy('date')
            ->get();

        $trendByDate = $trend->keyBy('date');

        $trendLabels = [];
        $trendSalesData = [];
        $trendCommissionData = [];
        $dailyRows = [];

        $current = $start->copy()->startOfDay();
        $endCursor = $end->copy()->startOfDay();

        while ($current->lte($endCursor)) {
            $dateKey = $current->toDateString();
            $row = $trendByDate->get($dateKey);

            $dailySales = (float) ($row->daily_sales ?? 0);
            $dailyCommission = (float) ($row->daily_commission ?? 0);
            $dailyTransactions = (int) ($row->daily_transactions ?? 0);

            $trendLabels[] = $current->format('d M');
            $trendSalesData[] = $dailySales;
            $trendCommissionData[] = $dailyCommission;

            $dailyRows[] = [
                'date' => $dateKey,
                'sales' => $dailySales,
                'commission' => $dailyCommission,
                'transactions' => $dailyTransactions,
            ];

            $current->addDay();
        }

        $userLabels = [];
        $userSalesData = [];
        $userCommissionData = [];

        $managedUsers = $actor?->managedUsers()->get(['id', 'name']) ?? collect();
        $managedUserIds = $managedUsers->pluck('id')->all();

        if (! empty($managedUserIds)) {
            $managedNames = $managedUsers->keyBy('id')->map(fn ($u) => $u->name);

            $perUser = SaleTransaction::query()
                ->whereBetween('created_at', [$start, $end])
                ->where('status', 'success')
                ->whereIn('user_id', $managedUserIds)
                ->where(function ($query) {
                    $query->whereNull('transaction_type')
                        ->orWhereNotIn('transaction_type', ['topup', 'withdrawal']);
                })
                ->selectRaw('user_id, SUM(amount) as total_sales, SUM(commission_amount) as total_commission')
                ->groupBy('user_id')
                ->orderByDesc('total_sales')
                ->limit(10)
                ->get();

            foreach ($perUser as $row) {
                $name = $managedNames->get((int) $row->user_id);
                if (! $name) {
                    continue;
                }

                $userLabels[] = $name;
                $userSalesData[] = (float) ($row->total_sales ?? 0);
                $userCommissionData[] = (float) ($row->total_commission ?? 0);
            }
        }

        return view('marketing.dashboard', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalCommissions',
            'successfulTransactions',
            'failedTransactions',
            'trendLabels',
            'trendSalesData',
            'trendCommissionData',
            'dailyRows',
            'userLabels',
            'userSalesData',
            'userCommissionData',
        ));
    }
}
