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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User yang mendapatkan komisi
            $table->unsignedBigInteger('sale_transaction_id'); // Transaksi yang menjadi dasar komisi
            $table->decimal('amount', 15, 2); // Jumlah komisi
            $table->date('period_date'); // Tanggal periode komisi
            $table->string('period_type'); // 'daily', 'monthly', dll
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sale_transaction_id')->references('id')->on('sales_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};