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
        // Drop the existing foreign key constraint
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign(['sale_transaction_id']);
        });

        // Update the foreign key to reference the correct table
        Schema::table('commissions', function (Blueprint $table) {
            $table->foreign('sale_transaction_id')->references('id')->on('sale_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign(['sale_transaction_id']);
        });

        // Restore the original foreign key constraint
        Schema::table('commissions', function (Blueprint $table) {
            $table->foreign('sale_transaction_id')->references('id')->on('sales_transactions')->onDelete('cascade');
        });
    }
};
