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
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->decimal('amount', 15, 2); // Nominal pesanan
            $table->decimal('commission_rate', 5, 2)->default(1.00); // Persentase komisi (default 1%)
            $table->decimal('commission_amount', 15, 2); // Jumlah komisi
            $table->enum('status', ['process', 'success', 'failed'])->default('process'); // Status pesanan
            $table->unsignedBigInteger('user_id'); // User yang membuat pesanan
            $table->unsignedBigInteger('admin_id')->nullable(); // Admin yang menyetujui
            $table->timestamp('confirmed_at')->nullable(); // Waktu konfirmasi
            $table->timestamp('completed_at')->nullable(); // Waktu selesai
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_transactions');
    }
};