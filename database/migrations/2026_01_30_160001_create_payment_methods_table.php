<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama metode pembayaran
            $table->string('type'); // 'bank_account' atau 'qris'
            $table->string('account_number')->nullable(); // Nomor rekening jika bank
            $table->string('account_holder')->nullable(); // Nama pemilik rekening
            $table->text('qr_code_path')->nullable(); // Path ke gambar QRIS
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};