@extends('layouts.app')

@section('title', 'Tambah Metode Pembayaran')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tambah Metode Pembayaran') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('payment-methods.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Metode Pembayaran</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe Metode Pembayaran</label>
                            <select id="type" class="form-select @error('type') is-invalid @enderror" name="type" required>
                                <option value="">Pilih Tipe</option>
                                <option value="bank_account" {{ old('type') === 'bank_account' ? 'selected' : '' }}>Rekening Bank</option>
                                <option value="qris" {{ old('type') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 bank-account-fields" style="display: none;">
                            <label for="account_number" class="form-label">Nomor Rekening</label>
                            <input id="account_number" type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{ old('account_number') }}" autocomplete="account_number">
                            
                            @error('account_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 bank-account-fields" style="display: none;">
                            <label for="account_holder" class="form-label">Nama Pemilik Rekening</label>
                            <input id="account_holder" type="text" class="form-control @error('account_holder') is-invalid @enderror" name="account_holder" value="{{ old('account_holder') }}" autocomplete="account_holder">
                            
                            @error('account_holder')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 qris-fields" style="display: none;">
                            <label for="qr_code" class="form-label">Gambar QRIS</label>
                            <input id="qr_code" type="file" class="form-control @error('qr_code') is-invalid @enderror" name="qr_code" accept="image/*">
                            
                            @error('qr_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                            <div class="form-text">Format: JPEG, PNG. Ukuran maksimal: 2MB</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Simpan Metode Pembayaran') }}
                            </button>
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const bankFields = document.querySelectorAll('.bank-account-fields');
    const qrisFields = document.querySelectorAll('.qris-fields');
    
    function toggleFields() {
        const selectedValue = typeSelect.value;
        
        bankFields.forEach(field => {
            field.style.display = selectedValue === 'bank_account' ? 'block' : 'none';
        });
        
        qrisFields.forEach(field => {
            field.style.display = selectedValue === 'qris' ? 'block' : 'none';
        });
    }
    
    typeSelect.addEventListener('change', toggleFields);
    
    // Initialize fields based on current selection
    toggleFields();
});
</script>
@endsection