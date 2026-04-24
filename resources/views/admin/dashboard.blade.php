@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Admin - Laporan Penjualan & Komisi</h4>
                </div>
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Total Penjualan</h5>
                                    <h3>Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Total Komisi</h5>
                                    <h3>Rp {{ number_format($totalCommissions, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">Transaksi Sukses</h5>
                                    <h3>{{ $successfulTransactions }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body">
                                    <h5 class="card-title">Transaksi Gagal</h5>
                                    <h3>{{ $failedTransactions }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Top Performing Users -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Top Performer</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @forelse($topUsers as $user)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $user->name }}
                                                <span class="badge bg-primary rounded-pill">
                                                    Rp {{ number_format($user->commissions_sum_amount, 0, ',', '.') }}
                                                </span>
                                            </li>
                                        @empty
                                            <li class="list-group-item">Tidak ada data komisi dalam periode ini</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Trend Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Grafik Penjualan Harian</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Commission Trend Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Grafik Komisi Harian</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="commissionChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Tautan Cepat</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('sales.index') }}" class="btn btn-primary">Lihat Semua Penjualan</a>
                                        <a href="{{ route('admin.commission.summary') }}" class="btn btn-success">Rekap Komisi per User</a>
                                        <a href="{{ route('users.index') }}" class="btn btn-info">Kelola Tim</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($salesTrend as $trend)
                    '{{ \Carbon\Carbon::parse($trend->date)->format('d/m') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total Penjualan',
                data: [
                    @foreach($salesTrend as $trend)
                        {{ $trend->total_sales }},
                    @endforeach
                ],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Commission Chart
    const commissionCtx = document.getElementById('commissionChart').getContext('2d');
    const commissionChart = new Chart(commissionCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($commissionTrend as $trend)
                    '{{ \Carbon\Carbon::parse($trend->date)->format('d/m') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total Komisi',
                data: [
                    @foreach($commissionTrend as $trend)
                        {{ $trend->total_commission }},
                    @endforeach
                ],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection