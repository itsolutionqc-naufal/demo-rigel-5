<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\Commission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with reports.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAdminDashboard');
        
        // Get date range from request or use default (current month)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        // Total sales in the selected period
        $totalSales = SaleTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->sum('amount');
            
        // Total commissions in the selected period
        $totalCommissions = Commission::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
            
        // Total successful transactions
        $successfulTransactions = SaleTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->count();
            
        // Total failed transactions
        $failedTransactions = SaleTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'failed')
            ->count();
            
        // Top performing users
        $topUsers = User::withSum(['commissions' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'amount')
            ->orderByDesc('commissions_sum_amount')
            ->take(5)
            ->get();
            
        // Sales trend by day
        $salesTrend = SaleTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_sales'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
            
        // Commission trend by day
        $commissionTrend = Commission::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_commission')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalCommissions',
            'successfulTransactions',
            'failedTransactions',
            'topUsers',
            'salesTrend',
            'commissionTrend',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display the commission summary for all users.
     */
    public function commissionSummary(Request $request)
    {
        $this->authorize('viewAdminDashboard');
        
        $periodType = $request->input('period_type', 'daily');
        $periodDate = $request->input('period_date', Carbon::today()->toDateString());
        
        // Get all users with their commission totals
        $usersWithCommissions = User::withSum(['commissions' => function($query) use ($periodType, $periodDate) {
                $query->where('period_type', $periodType);
                if ($periodType === 'daily') {
                    $query->whereDate('period_date', $periodDate);
                } elseif ($periodType === 'monthly') {
                    $query->whereYear('period_date', Carbon::parse($periodDate)->year)
                          ->whereMonth('period_date', Carbon::parse($periodDate)->month);
                }
            }], 'amount')
            ->whereHas('commissions', function($query) use ($periodType, $periodDate) {
                $query->where('period_type', $periodType);
                if ($periodType === 'daily') {
                    $query->whereDate('period_date', $periodDate);
                } elseif ($periodType === 'monthly') {
                    $query->whereYear('period_date', Carbon::parse($periodDate)->year)
                          ->whereMonth('period_date', Carbon::parse($periodDate)->month);
                }
            })
            ->get();
            
        return view('admin.commission-summary', compact('usersWithCommissions', 'periodType', 'periodDate'));
    }
}