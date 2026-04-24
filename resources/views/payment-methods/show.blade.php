@extends('layouts.app')

@section('title', 'Detail Metode Pembayaran')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Metode Pembayaran</span>
                    <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $paymentMethod->name }}</p>
                            <p><strong>Tipe:</strong> 
                                <span class="badge bg-{{ $paymentMethod->type === 'bank_account' ? 'info' : 'success' }}">
                                    {{ $paymentMethod->type === 'bank_account' ? 'Rekening Bank' : 'QRIS' }}
                                </span>
                            </p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $paymentMethod->is_active ? 'success' : 'secondary' }}">
                                    {{ $paymentMethod->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                            <p><strong>Dibuat:</strong> {{ $paymentMethod->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Diupdate:</strong> {{ $paymentMethod->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($paymentMethod->type === 'bank_account')
                                <p><strong>Nomor Rekening:</strong> {{ $paymentMethod->account_number }}</p>
                                <p><strong>Nama Pemilik:</strong> {{ $paymentMethod->account_holder }}</p>
                            @else
                                <p><strong>QRIS:</strong></p>
                                @if($paymentMethod->qr_code_path)
                                    <img src="{{ asset($paymentMethod->qr_code_path) }}" alt="QRIS" width="200" height="200">
                                @else
                                    <p>QRIS tidak tersedia</p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection