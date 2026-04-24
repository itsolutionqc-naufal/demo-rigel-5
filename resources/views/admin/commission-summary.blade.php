@extends('layouts.app')

@section('title', 'Rekap Komisi per User')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Rekap Komisi per User</h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
                <div class="card-body">
                    <!-- Period Filter -->
                    <form method="GET" action="{{ route('admin.commission.summary') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="period_type" class="form-label">Periode</label>
                                <select class="form-select" id="period_type" name="period_type">
                                    <option value="daily" {{ $periodType === 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="monthly" {{ $periodType === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="period_date" class="form-label">Tanggal/Bulan</label>
                                <input type="date" class="form-control" id="period_date" name="period_date" value="{{ $periodDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.commission.summary') }}" class="btn btn-secondary ms-2">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Commission Summary Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Total Komisi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usersWithCommissions as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <strong>
                                                Rp {{ number_format($user->commissions_sum_amount, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('sales.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                                                Lihat Penjualan
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data komisi dalam periode ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Card -->
                    @if(count($usersWithCommissions) > 0)
                        <div class="card bg-light mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Total Keseluruhan Komisi dalam Periode Ini</h5>
                                <h3>
                                    Rp {{ number_format($usersWithCommissions->sum('commissions_sum_amount'), 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection