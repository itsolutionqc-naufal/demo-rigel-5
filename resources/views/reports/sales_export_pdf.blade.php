<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan & Komisi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 0;
            padding: 20px;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 8px 0;
            color: #111827;
            border-bottom: 2px solid #111827;
            padding-bottom: 8px;
        }

        h2 {
            font-size: 16px;
            margin: 16px 0 8px 0;
            color: #111827;
        }

        .muted {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 16px;
        }

        .section {
            margin-top: 20px;
        }

        /* KPI Cards */
        .kpi-container {
            margin: 20px 0;
        }

        .kpi-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kpi-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            width: 25%;
            background: #f9fafb;
            vertical-align: top;
        }

        .kpi-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .kpi-sub {
            font-size: 9px;
            color: #16a34a;
            margin-top: 4px;
        }

        /* Chart */
        .chart-container {
            text-align: center;
            margin: 20px 0;
            padding: 16px;
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        .chart {
            max-width: 100%;
            height: auto;
        }

        /* Data Table */
        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            border: 1px solid #e5e7eb;
        }

        .grid-fixed {
            table-layout: fixed;
        }

        .grid-ui {
            border: 1px solid #e5e7eb;
        }

        .grid-ui th {
            background: #f9fafb;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 8px;
            padding: 6px;
            text-align: left;
        }

        .grid-ui td {
            font-size: 9px;
            padding: 6px;
            vertical-align: middle;
        }

        .grid-ui tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .grid-ui tfoot tr {
            background: #f3f4f6;
            font-weight: bold;
        }

        .cell-title {
            font-weight: 600;
            color: #111827;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 9999px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
            border: 1px solid transparent;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .badge-failed {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        .badge-process,
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
            border-color: #fcd34d;
        }

        .grid th,
        .grid td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            vertical-align: middle;
            font-size: 9px;
        }

        .grid th {
            background: #4F46E5;
            text-align: center;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grid tr:nth-child(even) {
            background: #f9fafb;
        }

        .grid tfoot tr {
            background: #f3f4f6;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .break {
            word-break: break-word;
        }

        .cell-sub {
            margin-top: 2px;
            font-size: 8px;
            color: #6b7280;
        }

        /* Status Badge */
        .status-success {
            color: #16a34a;
            font-weight: bold;
        }

        .status-failed {
            color: #dc2626;
            font-weight: bold;
        }

        .status-pending {
            color: #d97706;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }

    </style>
</head>
<body>
    <h1>📊 Laporan Penjualan & Komisi</h1>
    <div class="muted">
        <strong>Periode:</strong> {{ ucfirst($period) }}<br>
        <strong>Range Tanggal:</strong> {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}<br>
        <strong>Dicetak:</strong> {{ now()->format('d M Y H:i') }} WIB
    </div>

    {{-- Summary KPI Section --}}
    <div class="section">
        <h2>📈 Ringkasan</h2>
        <table class="kpi-table">
            <tr>
                <td>
                    <div class="kpi-label">💰 Total Penjualan</div>
                    <div class="kpi-value">Rp {{ number_format((float) ($summary->total_sales ?? 0), 0, ',', '.') }}</div>
                    <div class="kpi-sub">Pendapatan kotor</div>
                </td>
                <td>
                    <div class="kpi-label">💵 Total Komisi</div>
                    <div class="kpi-value">Rp {{ number_format((float) ($summary->total_commission ?? 0), 0, ',', '.') }}</div>
                    <div class="kpi-sub">
                        @if($summary->total_sales > 0)
                        {{ number_format(($summary->total_commission / $summary->total_sales) * 100, 2) }}% dari penjualan
                        @endif
                    </div>
                </td>
                <td>
                    <div class="kpi-label">📋 Total Transaksi</div>
                    <div class="kpi-value">{{ number_format((int) ($summary->total_transactions ?? 0)) }}</div>
                    <div class="kpi-sub">Transaksi berhasil</div>
                </td>
                <td>
                    <div class="kpi-label">📊 Rata-rata Komisi</div>
                    <div class="kpi-value">
                        @if($summary->total_sales > 0 && $summary->total_commission)
                        {{ number_format(($summary->total_commission / $summary->total_sales) * 100, 2) }}%
                        @else
                        0.00%
                        @endif
                    </div>
                    <div class="kpi-sub">Rate komisi rata-rata</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Chart Section --}}
    <div class="section">
        <h2>📉 Grafik Penjualan & Komisi</h2>
        @if(!empty($chartImageBase64))
        <div class="chart-container">
            <img class="chart" src="data:image/png;base64,{{ $chartImageBase64 }}" alt="Chart Penjualan dan Komisi">
        </div>
        @else
        @php
            $salesSeries = collect($chartSalesData ?? [])->map(fn ($v) => (float) $v)->values();
            $commissionSeries = collect($chartCommissionData ?? [])->map(fn ($v) => (float) $v)->values();
            $labelsSeries = collect($chartLabels ?? [])->map(fn ($v) => (string) $v)->values();
            $maxValue = max(
                (float) ($salesSeries->max() ?? 0),
                (float) ($commissionSeries->max() ?? 0),
                1.0
            );

            $count = max($labelsSeries->count(), $salesSeries->count(), $commissionSeries->count());
            $step = $count > 24 ? (int) ceil($count / 24) : 1;
        @endphp
        <div class="chart-container" style="text-align:left;">
            <div style="font-size:10px; color:#6b7280; margin-bottom:10px;">
                Grafik gambar tidak tersedia, menampilkan grafik bar sederhana.
            </div>

            <table class="grid" style="margin-top:0;">
                <thead>
                    <tr>
                        <th style="width:16%;">Periode</th>
                        <th>Penjualan</th>
                        <th>Komisi</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < $count; $i += $step)
                        @php
                            $label = $labelsSeries[$i] ?? (string) $i;
                            $salesValue = (float) ($salesSeries[$i] ?? 0);
                            $commissionValue = (float) ($commissionSeries[$i] ?? 0);
                            $salesPct = $maxValue > 0 ? min(100, max(0, ($salesValue / $maxValue) * 100)) : 0;
                            $commissionPct = $maxValue > 0 ? min(100, max(0, ($commissionValue / $maxValue) * 100)) : 0;
                        @endphp
                        <tr>
                            <td>{{ $label }}</td>
                            <td>
                                <div style="display:block; width:100%; background:#f3f4f6; height:10px; border-radius:4px; overflow:hidden;">
                                    <div style="width:{{ (int) $salesPct }}%; height:10px; background:#111827;"></div>
                                </div>
                                <div style="margin-top:4px;" class="text-right">Rp {{ number_format($salesValue, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <div style="display:block; width:100%; background:#f3f4f6; height:10px; border-radius:4px; overflow:hidden;">
                                    <div style="width:{{ (int) $commissionPct }}%; height:10px; background:#16a34a;"></div>
                                </div>
                                <div style="margin-top:4px;" class="text-right">Rp {{ number_format($commissionValue, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Chart Data Breakdown --}}
    <div class="section">
        <h2>📊 Detail Data per Periode</h2>
        <table class="grid">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Periode</th>
                    <th class="text-right">Penjualan</th>
                    <th class="text-right">Komisi</th>
                    <th class="text-center">Jumlah Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @php $hasData = false; @endphp
                @foreach($chartLabels as $index => $label)
                @php
                $salesValue = (float) ($chartSalesData[$index] ?? 0);
                $commissionValue = (float) ($chartCommissionData[$index] ?? 0);
                $txCount = (int) ($chartTransactionsData[$index] ?? 0);
                if ($salesValue > 0 || $commissionValue > 0 || $txCount > 0) $hasData = true;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $label }}</td>
                    <td class="text-right">Rp {{ number_format($salesValue, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($commissionValue, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($txCount) }}</td>
                </tr>
                @endforeach
                @if(!$hasData)
                <tr>
                    <td colspan="5" class="text-center muted">Tidak ada data untuk periode ini</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td colspan="2" class="text-right">TOTAL</td>
                    <td class="text-right">Rp {{ number_format(array_sum($chartSalesData), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format(array_sum($chartCommissionData), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format(array_sum($chartTransactionsData)) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Transactions Table --}}
    @if(isset($transactions) && $transactions->count() > 0)
    <div class="section">
        <h2>📝 Detail Transaksi</h2>
        <table class="grid {{ !empty($isAdmin) ? 'grid-ui grid-fixed' : '' }}">
            <thead>
                <tr>
                    <th class="text-center" style="{{ !empty($isAdmin) ? 'width:6%;' : '' }}">No</th>
                    <th style="{{ !empty($isAdmin) ? 'width:14%;' : '' }}">Tanggal</th>
                    <th class="text-center" style="{{ !empty($isAdmin) ? 'width:10%;' : '' }}">Kode</th>
                    <th style="{{ !empty($isAdmin) ? 'width:18%;' : '' }}">{{ !empty($isAdmin) ? 'User' : 'Customer' }}</th>
                    <th style="{{ !empty($isAdmin) ? 'width:16%;' : '' }}">Layanan</th>
                    <th class="text-right" style="{{ !empty($isAdmin) ? 'width:12%;' : '' }}">Nominal</th>
                    <th class="text-center" style="{{ !empty($isAdmin) ? 'width:7%;' : '' }}">Rate</th>
                    <th class="text-right" style="{{ !empty($isAdmin) ? 'width:12%;' : '' }}">Komisi</th>
                    <th class="text-center" style="{{ !empty($isAdmin) ? 'width:9%;' : '' }}">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $tx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        @php
                            $code = (string) $tx->transaction_code;
                            $shortCode = $code;

                            if (!empty($isAdmin)) {
                                $digits = preg_replace('/\\D+/', '', $code) ?? '';
                                $lastSix = $digits !== '' ? substr($digits, -6) : substr($code, -6);
                                $shortCode = 'TRX-' . ($lastSix !== '' ? strtoupper($lastSix) : '000000');
                            }
                        @endphp
                        {{ $shortCode }}
                    </td>
                    <td>
                        @if(!empty($isAdmin))
                            @php
                                $userLabel = $tx->user?->username ?: ($tx->user?->name ?: ($tx->customer_name ?: '-'));
                                $userEmail = (string) ($tx->user?->email ?? '');

                                $userEmailShort = $userEmail;
                                if ($userEmail !== '' && strlen($userEmail) > 28) {
                                    if (str_contains($userEmail, '@')) {
                                        [$local, $domain] = array_pad(explode('@', $userEmail, 2), 2, '');
                                        $localShort = strlen($local) > 10 ? substr($local, 0, 8) . '…' : $local;
                                        $userEmailShort = $localShort . '@' . $domain;
                                    } else {
                                        $userEmailShort = substr($userEmail, 0, 12) . '…' . substr($userEmail, -10);
                                    }
                                }
                            @endphp
                            <div class="cell-title">{{ $userLabel }}</div>
                            @if($userEmail !== '')
                                <div class="cell-sub">{{ $userEmailShort }}</div>
                            @endif
                        @else
                            {{ $tx->customer_name }}
                        @endif
                    </td>
                    <td>{{ $tx->service_name }}</td>
                    <td class="text-right">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($tx->commission_rate, 1) }}%</td>
                    <td class="text-right">Rp {{ number_format($tx->commission_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php
                            $status = (string) ($tx->status ?? '');
                            $badgeClass = 'badge-pending';
                            if ($status === 'success') {
                                $badgeClass = 'badge-success';
                            } elseif ($status === 'failed') {
                                $badgeClass = 'badge-failed';
                            } elseif ($status === 'process' || $status === 'pending') {
                                $badgeClass = 'badge-process';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL ({{ $transactions->count() }} transaksi)</td>
                    <td class="text-right">Rp {{ number_format($transactions->sum('amount'), 0, ',', '.') }}</td>
                    <td></td>
                    <td class="text-right">Rp {{ number_format($transactions->sum('commission_amount'), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- User Summary Table --}}
    @if(isset($userSummaries) && count($userSummaries) > 0)
    <div class="section">
        <h2>👤 Ringkasan per User</h2>
        <table class="grid grid-fixed">
            <thead>
                <tr>
                    <th class="text-center" style="width:6%;">No</th>
                    <th style="width:18%;">Nama</th>
                    <th style="width:24%;">Email</th>
                    <th class="text-center" style="width:10%;">Role</th>
                    <th class="text-center" style="width:10%;">Transaksi</th>
                    <th class="text-right" style="width:16%;">Total Nominal</th>
                    <th class="text-right" style="width:16%;">Total Komisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userSummaries as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="break">{{ $row['name'] ?? '-' }}</td>
                    <td class="break">{{ $row['email'] ?? '-' }}</td>
                    <td class="text-center">{{ $row['role'] ?? '-' }}</td>
                    <td class="text-center">{{ number_format((int) ($row['tx_count'] ?? 0)) }}</td>
                    <td class="text-right">Rp {{ number_format((float) ($row['total_amount'] ?? 0), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format((float) ($row['total_commission'] ?? 0), 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td colspan="4" class="text-right">TOTAL ({{ number_format((int) ($userSummaryTotals['user_count'] ?? 0)) }} user)</td>
                    <td class="text-center">{{ number_format((int) ($userSummaryTotals['tx_count'] ?? 0)) }}</td>
                    <td class="text-right">Rp {{ number_format((float) ($userSummaryTotals['total_amount'] ?? 0), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format((float) ($userSummaryTotals['total_commission'] ?? 0), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem pada {{ now()->format('d M Y H:i:s') }} WIB
    </div>
</body>
</html>
