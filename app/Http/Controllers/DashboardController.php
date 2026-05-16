<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\SaleTransaction;
use App\Models\Commission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request, default to current month
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Parse dates
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Get summary data for sales transactions
        $totalSales = $this->calculateTotalSales($start, $end);
        $totalCommissions = $this->calculateTotalCommissions($start, $end);
        $successfulTransactions = $this->calculateSuccessfulTransactions($start, $end);

        // Get user-specific data if authenticated user is not admin
        $userId = auth()->user()->isAdmin() ? null : auth()->id();

        // Get article statistics (dynamically from database)
        $articleStats = $this->getArticleStats();

        // Get user's balance (unpaid commissions)
        $userBalance = $this->calculateUserBalance();

        // Get recent transactions
        $recentTransactions = $this->getRecentTransactions(5);

        // Get recent articles
        $recentArticles = $this->getRecentArticles(5);

        return view('dashboard', compact(
            'totalSales',
            'totalCommissions',
            'successfulTransactions',
            'articleStats',
            'recentArticles',
            'recentTransactions',
            'startDate',
            'endDate',
            'userBalance'
        ));
    }

    private function calculateTotalSales(Carbon $start, Carbon $end)
    {
        if (!$this->dbHasTable('sale_transactions')) {
            return 0;
        }

        try {
            // Calculate total sales from successful transactions
            $query = SaleTransaction::whereBetween('created_at', [$start, $end])
                ->where('status', 'success');

            // If not admin, only show user's transactions
            if (!auth()->user()->isAdmin()) {
                $query->where('user_id', auth()->id());
            }

            $totalSales = $query->sum('amount');

            return $totalSales ?: 0;
        } catch (\Throwable $e) {
            Log::warning('Dashboard: calculateTotalSales failed', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    private function calculateTotalCommissions(Carbon $start, Carbon $end)
    {
        if (!$this->dbHasTable('commissions')) {
            return 0;
        }

        try {
            // Calculate total commissions from successful transactions
            $query = Commission::whereBetween('created_at', [$start, $end]);

            // If not admin, only show user's commissions
            if (!auth()->user()->isAdmin()) {
                $query->where('user_id', auth()->id());
            }

            $totalCommissions = $query->sum('amount');

            return $totalCommissions ?: 0;
        } catch (\Throwable $e) {
            Log::warning('Dashboard: calculateTotalCommissions failed', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    private function calculateSuccessfulTransactions(Carbon $start, Carbon $end)
    {
        if (!$this->dbHasTable('sale_transactions')) {
            return 0;
        }

        try {
            // Calculate number of successful transactions
            $query = SaleTransaction::whereBetween('created_at', [$start, $end])
                ->where('status', 'success');

            // If not admin, only count user's transactions
            if (!auth()->user()->isAdmin()) {
                $query->where('user_id', auth()->id());
            }

            return $query->count();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: calculateSuccessfulTransactions failed', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    private function getArticleStats()
    {
        if (!$this->dbHasTable('articles')) {
            return [
                'total' => 0,
                'published' => 0,
                'draft' => 0,
                'total_views' => 0,
                'this_month' => 0,
                'published_this_month' => 0,
            ];
        }

        // Single query with conditional aggregation (DB-agnostic: avoid MONTH()/YEAR()).
        $monthStart = Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString();
        $monthEnd = Carbon::now()->endOfMonth()->endOfDay()->toDateTimeString();

        try {
            $stats = Article::selectRaw(
                '
                    COUNT(*) as total,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as published,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as draft,
                    COALESCE(SUM(views), 0) as total_views,
                    SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as this_month,
                    SUM(CASE WHEN status = ? AND created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as published_this_month
                ',
                ['published', 'draft', $monthStart, $monthEnd, 'published', $monthStart, $monthEnd]
            )->first();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: getArticleStats failed', [
                'message' => $e->getMessage(),
            ]);

            return [
                'total' => 0,
                'published' => 0,
                'draft' => 0,
                'total_views' => 0,
                'this_month' => 0,
                'published_this_month' => 0,
            ];
        }

        return [
            'total' => $stats->total ?? 0,
            'published' => $stats->published ?? 0,
            'draft' => $stats->draft ?? 0,
            'total_views' => $stats->total_views ?? 0,
            'this_month' => $stats->this_month ?? 0,
            'published_this_month' => $stats->published_this_month ?? 0,
        ];
    }

    private function getRecentArticles($limit = 5)
    {
        if (!$this->dbHasTable('articles')) {
            return collect();
        }

        // OPTIMIZED: Add eager loading and select only needed columns
        return Article::with(['user' => function($q) {
                $q->select('id', 'name', 'email');
            }])
            ->select('id', 'title', 'excerpt', 'content', 'category', 'image', 'status', 'views', 'user_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    private function calculateUserBalance()
    {
        if (!$this->dbHasTable('commissions')) {
            return 0;
        }

        try {
            // Calculate user's balance (unpaid commissions)
            $query = Commission::where('user_id', auth()->id());

            // Avoid 500 if production DB hasn't migrated `withdrawn` yet.
            if ($this->dbHasColumn('commissions', 'withdrawn')) {
                $query->where('withdrawn', false);
            }

            $balance = $query->sum('amount');

            return $balance ?: 0;
        } catch (\Throwable $e) {
            Log::warning('Dashboard: calculateUserBalance failed', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    private function getRecentTransactions($limit = 5)
    {
        if (!$this->dbHasTable('sale_transactions')) {
            return collect();
        }

        // Get recent transactions from database
        $query = SaleTransaction::with('user', 'admin')
            ->orderBy('created_at', 'desc');

        // If not admin, only show user's transactions
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        try {
            return $query->limit($limit)->get();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: getRecentTransactions failed', [
                'message' => $e->getMessage(),
            ]);
            return collect();
        }
    }

    private function dbHasTable(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Schema::hasTable failed', [
                'table' => $table,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function dbHasColumn(string $table, string $column): bool
    {
        try {
            return Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Schema::hasColumn failed', [
                'table' => $table,
                'column' => $column,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
