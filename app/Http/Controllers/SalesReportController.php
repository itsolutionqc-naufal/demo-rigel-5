<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\PaymentMethod;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    /**
     * Display the sales report page.
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 'daily');
        $date = $request->input('date', now()->format('Y-m-d'));
        $week = $request->input('week', now()->format('o-\WW'));

        $customStartDate = $request->input('start_date');
        $customEndDate = $request->input('end_date');

        $actor = $request->user();

        $report = $this->buildReportMetrics($period, $date, $week, $customStartDate, $customEndDate, $actor);
        $summary = $report['summary'];
        $chartLabels = $report['chartLabels'];
        $chartSalesData = $report['chartSalesData'];
        $chartCommissionData = $report['chartCommissionData'];
        $chartTransactionsData = $report['chartTransactionsData'];

        // Get payment methods for dynamic display
        $paymentMethods = Cache::remember('payment_methods.active', now()->addMinutes(5), static function () {
            return PaymentMethod::query()
                ->where('is_active', true)
                ->get();
        });

        return view('reports.sales', compact(
            'summary',
            'period',
            'date',
            'chartLabels',
            'chartSalesData',
            'chartCommissionData',
            'chartTransactionsData',
            'customStartDate',
            'customEndDate',
            'paymentMethods'
        ));
    }

    public function export(Request $request, string $format)
    {
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(404);
        }

        $period = $request->input('period', 'daily');
        if (! in_array($period, ['daily', 'weekly', 'monthly'], true)) {
            $period = 'daily';
        }

        $date = $request->input('date', now()->format('Y-m-d'));
        $week = $request->input('week', now()->format('o-\WW'));
        $customStartDate = $request->input('start_date');
        $customEndDate = $request->input('end_date');
        $actor = $request->user();

        $report = $this->buildReportMetrics($period, $date, $week, $customStartDate, $customEndDate, $actor);

        $startDate = $report['startDate'];
        $endDate = $report['endDate'];
        $summary = $report['summary'];
        $chartLabels = $report['chartLabels'];
        $chartSalesData = $report['chartSalesData'];
        $chartCommissionData = $report['chartCommissionData'];
        $chartTransactionsData = $report['chartTransactionsData'];

        $transactionsQuery = SaleTransaction::query()
            ->with(['user:id,username,name,email,role'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->orderBy('created_at');

        if ($actor && $actor->isMarketing()) {
            $transactionsQuery->visibleToMarketing($actor);
        }

        $chartImageBase64 = $this->quickChartImageBase64($chartLabels, $chartSalesData, $chartCommissionData);

        // If QuickChart fails, generate fallback chart
        if (! $chartImageBase64) {
            $chartImageBase64 = $this->generateFallbackChart($chartLabels, $chartSalesData, $chartCommissionData);
        }

        $rangeLabel = $startDate->format('Y-m-d').'_to_'.$endDate->format('Y-m-d');
        $filenameBase = "sales-report-{$period}-{$rangeLabel}";

        if ($format === 'xlsx') {
            $export = new SalesReportExport(
                period: $period,
                startDate: $startDate,
                endDate: $endDate,
                summary: $summary,
                chartLabels: $chartLabels,
                chartSalesData: $chartSalesData,
                chartCommissionData: $chartCommissionData,
                chartTransactionsData: $chartTransactionsData,
                transactionsQuery: $transactionsQuery,
                chartImageBase64: $chartImageBase64
            );

            return Excel::download($export, "{$filenameBase}.xlsx");
        }

        // Get transactions for PDF display
        $transactions = $transactionsQuery->limit(100)->get();

        $isAdmin = (bool) ($actor && method_exists($actor, 'isAdmin') && $actor->isAdmin());

        $userSummariesAll = $transactions
            ->groupBy('user_id')
            ->map(function ($items, $userId) {
                $first = $items->first();
                $user = $first?->user;

                return [
                    'user_id' => $userId,
                    'name' => (string) ($user?->name ?? '-'),
                    'email' => (string) ($user?->email ?? '-'),
                    'role' => (string) ($user?->role ?? '-'),
                    'tx_count' => (int) $items->count(),
                    'total_amount' => (float) $items->sum('amount'),
                    'total_commission' => (float) $items->sum('commission_amount'),
                ];
            })
            ->values()
            ->filter(function (array $row) {
                return in_array($row['role'], [User::ROLE_USER, User::ROLE_MARKETING], true);
            })
            ->values();

        $userSummaryTotals = [
            'user_count' => (int) $userSummariesAll->count(),
            'tx_count' => (int) $userSummariesAll->sum('tx_count'),
            'total_amount' => (float) $userSummariesAll->sum('total_amount'),
            'total_commission' => (float) $userSummariesAll->sum('total_commission'),
        ];

        $userSummaries = $userSummariesAll
            ->sortByDesc('total_amount')
            ->take(10)
            ->values();

        $pdfHtml = view('reports.sales_export_pdf', [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => $summary,
            'chartLabels' => $chartLabels,
            'chartSalesData' => $chartSalesData,
            'chartCommissionData' => $chartCommissionData,
            'chartTransactionsData' => $chartTransactionsData,
            'chartImageBase64' => $chartImageBase64,
            'transactions' => $transactions,
            'userSummaries' => $userSummaries,
            'userSummaryTotals' => $userSummaryTotals,
            'isAdmin' => $isAdmin,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($pdfHtml, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filenameBase}.pdf\"",
        ]);
    }

    private function buildReportMetrics(string $period, string $date, string $week, ?string $customStartDate, ?string $customEndDate, $actor): array
    {
        $cacheKey = 'reports.sales.metrics:' . sha1(json_encode([
            'actor_id' => $actor?->id,
            'actor_role' => $actor?->role,
            'period' => $period,
            'date' => $date,
            'week' => $week,
            'custom_start_date' => $customStartDate,
            'custom_end_date' => $customEndDate,
        ]));

        return Cache::remember($cacheKey, now()->addSeconds(30), function () use ($period, $date, $week, $customStartDate, $customEndDate, $actor) {
        if ($period === 'weekly') {
            [$weekYear, $weekNumber] = array_pad(explode('-W', (string) $week, 2), 2, null);
            $weekYear = is_numeric($weekYear) ? (int) $weekYear : (int) now()->format('o');
            $weekNumber = is_numeric($weekNumber) ? (int) $weekNumber : (int) now()->format('W');

            $startDate = Carbon::now()->setISODate($weekYear, $weekNumber)->startOfWeek(Carbon::MONDAY)->startOfDay();
            $endDate = $startDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

            $summaryQuery = SaleTransaction::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'success');

            if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
                $summaryQuery->visibleToMarketing($actor);
            }

            $summary = $summaryQuery
                ->toBase()
                ->selectRaw('SUM(amount) as total_sales, SUM(commission_amount) as total_commission, COUNT(*) as total_transactions')
                ->first();

            $dailyBreakdownQuery = SaleTransaction::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'success');

            if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
                $dailyBreakdownQuery->visibleToMarketing($actor);
            }

            $dailyBreakdown = $dailyBreakdownQuery
                ->toBase()
                ->selectRaw('DATE(created_at) as date, SUM(amount) as daily_sales, SUM(commission_amount) as daily_commission, COUNT(*) as daily_transactions')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $chartLabels = [];
            $chartSalesData = [];
            $chartCommissionData = [];
            $chartTransactionsData = [];

            $currentDate = $startDate->copy();
            $dateRange = [];

            while ($currentDate->lte($endDate)) {
                $dateRange[] = $currentDate->format('Y-m-d');
                $chartLabels[] = $currentDate->format('d M');
                $currentDate->addDay();
            }

            foreach ($dateRange as $dayDate) {
                $dailyData = $dailyBreakdown->firstWhere('date', $dayDate);
                $chartSalesData[] = $dailyData ? $dailyData->daily_sales : 0;
                $chartCommissionData[] = $dailyData ? $dailyData->daily_commission : 0;
                $chartTransactionsData[] = $dailyData ? $dailyData->daily_transactions : 0;
            }

            return compact(
                'startDate',
                'endDate',
                'summary',
                'chartLabels',
                'chartSalesData',
                'chartCommissionData',
                'chartTransactionsData',
            );
        }

        if ($period === 'monthly') {
            if ($customStartDate && $customEndDate) {
                $startDate = Carbon::parse($customStartDate)->startOfDay();
                $endDate = Carbon::parse($customEndDate)->endOfDay();
            } else {
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = Carbon::parse($date)->endOfMonth();
            }

            $summaryQuery = SaleTransaction::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'success');

            if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
                $summaryQuery->visibleToMarketing($actor);
            }

            $summary = $summaryQuery
                ->toBase()
                ->selectRaw('SUM(amount) as total_sales, SUM(commission_amount) as total_commission, COUNT(*) as total_transactions')
                ->first();

            $dailyBreakdownQuery = SaleTransaction::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'success');

            if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
                $dailyBreakdownQuery->visibleToMarketing($actor);
            }

            $dailyBreakdown = $dailyBreakdownQuery
                ->toBase()
                ->selectRaw('DATE(created_at) as date, SUM(amount) as daily_sales, SUM(commission_amount) as daily_commission, COUNT(*) as daily_transactions')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $chartLabels = [];
            $chartSalesData = [];
            $chartCommissionData = [];
            $chartTransactionsData = [];

            $currentDate = $startDate->copy();
            $dateRange = [];

            while ($currentDate->lte($endDate)) {
                $dateRange[] = $currentDate->format('Y-m-d');
                $chartLabels[] = $currentDate->format('d M');
                $currentDate->addDay();
            }

            foreach ($dateRange as $dayDate) {
                $dailyData = $dailyBreakdown->firstWhere('date', $dayDate);
                $chartSalesData[] = $dailyData ? $dailyData->daily_sales : 0;
                $chartCommissionData[] = $dailyData ? $dailyData->daily_commission : 0;
                $chartTransactionsData[] = $dailyData ? $dailyData->daily_transactions : 0;
            }

            return compact(
                'startDate',
                'endDate',
                'summary',
                'chartLabels',
                'chartSalesData',
                'chartCommissionData',
                'chartTransactionsData',
            );
        }

        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        $summaryQuery = SaleTransaction::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success');

        if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
            $summaryQuery->visibleToMarketing($actor);
        }

        $summary = $summaryQuery
            ->toBase()
            ->selectRaw('SUM(amount) as total_sales, SUM(commission_amount) as total_commission, COUNT(*) as total_transactions')
            ->first();

        $driver = DB::connection()->getDriverName();
        $hourExpression = $driver === 'sqlite'
            ? "CAST(strftime('%H', created_at) AS INTEGER)"
            : 'HOUR(created_at)';

        $hourlyQuery = SaleTransaction::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success');

        if ($actor && method_exists($actor, 'isMarketing') && $actor->isMarketing()) {
            $hourlyQuery->visibleToMarketing($actor);
        }

        $hourlyRows = $hourlyQuery
            ->toBase()
            ->selectRaw($hourExpression.' as hour, SUM(amount) as hourly_sales, SUM(commission_amount) as hourly_commission, COUNT(*) as hourly_transactions')
            ->groupBy(DB::raw($hourExpression))
            ->orderBy('hour')
            ->get();
        $hourlyMap = $hourlyRows->keyBy('hour');

        $chartLabels = [];
        $chartSalesData = [];
        $chartCommissionData = [];
        $chartTransactionsData = [];

        for ($i = 0; $i < 24; $i++) {
            $chartLabels[] = $i.':00';

            $row = $hourlyMap->get($i);
            $chartSalesData[] = $row ? (float) ($row->hourly_sales ?? 0) : 0;
            $chartCommissionData[] = $row ? (float) ($row->hourly_commission ?? 0) : 0;
            $chartTransactionsData[] = $row ? (int) ($row->hourly_transactions ?? 0) : 0;
        }

        return compact(
            'startDate',
            'endDate',
            'summary',
            'chartLabels',
            'chartSalesData',
            'chartCommissionData',
            'chartTransactionsData',
        );
        });
    }

    private function quickChartImageBase64(array $labels, array $salesData, array $commissionData): ?string
    {
        if (! config('reports.quickchart_enabled', true)) {
            return null;
        }

        $config = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Penjualan',
                        'data' => $salesData,
                        'borderColor' => '#111827',
                        'backgroundColor' => 'rgba(17, 24, 39, 0.08)',
                        'borderWidth' => 2,
                        'tension' => 0.35,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Komisi',
                        'data' => $commissionData,
                        'borderColor' => '#16a34a',
                        'backgroundColor' => 'rgba(22, 163, 74, 0.08)',
                        'borderWidth' => 2,
                        'tension' => 0.35,
                        'fill' => true,
                    ],
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];

        try {
            $response = Http::timeout((int) config('reports.quickchart_timeout', 5))
                ->get((string) config('reports.quickchart_url', 'https://quickchart.io/chart'), [
                    'c' => json_encode($config),
                    'format' => 'png',
                    'width' => 1000,
                    'height' => 400,
                    'backgroundColor' => 'white',
                ]);

            if (! $response->successful()) {
                return null;
            }

            return base64_encode($response->body());
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Generate a fallback chart image using GD library when QuickChart is unavailable.
     */
    private function generateFallbackChart(array $labels, array $salesData, array $commissionData): ?string
    {
        try {
            $width = 1000;
            $height = 400;
            $padding = ['top' => 60, 'right' => 40, 'bottom' => 80, 'left' => 100];
            $chartWidth = $width - $padding['left'] - $padding['right'];
            $chartHeight = $height - $padding['top'] - $padding['bottom'];

            $image = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 17, 24, 39);
            $green = imagecolorallocate($image, 22, 163, 74);
            $grayLight = imagecolorallocate($image, 229, 231, 235);
            $grayText = imagecolorallocate($image, 107, 114, 128);

            imagefill($image, 0, 0, $white);

            $maxValue = max(array_merge($salesData, $commissionData), [1]);

            // Draw grid lines
            for ($i = 0; $i <= 5; $i++) {
                $y = $padding['top'] + ($chartHeight / 5) * $i;
                imageline($image, $padding['left'], $y, $width - $padding['right'], $y, $grayLight);

                $value = $maxValue - ($maxValue / 5) * $i;
                imagestring($image, 2, 5, $y - 7, 'Rp ' . number_format($value / 1000, 0) . 'K', $grayText);
            }

            // Draw sales line
            if (count($salesData) > 1) {
                $points = [];
                foreach ($salesData as $index => $value) {
                    $x = $padding['left'] + ($chartWidth / max(count($labels) - 1, 1)) * $index;
                    $y = $padding['top'] + $chartHeight - ($value / $maxValue) * $chartHeight;
                    $points[] = [$x, $y];
                }

                for ($i = 0; $i < count($points) - 1; $i++) {
                    imageline($image, $points[$i][0], $points[$i][1], $points[$i + 1][0], $points[$i + 1][1], $black);
                }

                // Draw points
                foreach ($points as $point) {
                    imagefilledellipse($image, $point[0], $point[1], 6, 6, $black);
                }
            }

            // Draw commission line
            if (count($commissionData) > 1) {
                $points = [];
                foreach ($commissionData as $index => $value) {
                    $x = $padding['left'] + ($chartWidth / max(count($labels) - 1, 1)) * $index;
                    $y = $padding['top'] + $chartHeight - ($value / $maxValue) * $chartHeight;
                    $points[] = [$x, $y];
                }

                for ($i = 0; $i < count($points) - 1; $i++) {
                    imageline($image, $points[$i][0], $points[$i][1], $points[$i + 1][0], $points[$i + 1][1], $green);
                }

                // Draw points
                foreach ($points as $point) {
                    imagefilledellipse($image, $point[0], $point[1], 6, 6, $green);
                }
            }

            // Draw X-axis labels (show every nth label to avoid overlap)
            $labelCount = count($labels);
            $step = max(1, floor($labelCount / 12));
            for ($i = 0; $i < $labelCount; $i += $step) {
                $x = $padding['left'] + ($chartWidth / max($labelCount - 1, 1)) * $i;
                imagestring($image, 2, $x - 15, $height - $padding['bottom'] + 10, $labels[$i], $grayText);
            }

            // Draw title and legend
            imagestring($image, 4, $padding['left'], 10, 'Laporan Penjualan & Komisi', $black);
            imagefilledrectangle($image, $padding['left'], 30, $padding['left'] + 15, 42, $black);
            imagestring($image, 2, $padding['left'] + 20, 30, 'Penjualan', $black);
            imagefilledrectangle($image, $padding['left'] + 120, 30, $padding['left'] + 135, 42, $green);
            imagestring($image, 2, $padding['left'] + 140, 30, 'Komisi', $green);

            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            return base64_encode($imageData);
        } catch (\Throwable) {
            return null;
        }
    }
}
