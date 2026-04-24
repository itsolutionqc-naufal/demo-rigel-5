<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            if (! Schema::hasColumn('commissions', 'withdrawal_transaction_id')) {
                $table->unsignedBigInteger('withdrawal_transaction_id')->nullable()->after('withdrawn');
                $table->index('withdrawal_transaction_id', 'idx_commissions_withdrawal_transaction_id');
            }
        });

        Schema::table('commissions', function (Blueprint $table) {
            if (Schema::hasColumn('commissions', 'withdrawal_transaction_id')) {
                $table->foreign('withdrawal_transaction_id')
                    ->references('id')
                    ->on('sale_transactions')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            if (Schema::hasColumn('commissions', 'withdrawal_transaction_id')) {
                $table->dropForeign(['withdrawal_transaction_id']);
                $table->dropIndex('idx_commissions_withdrawal_transaction_id');
                $table->dropColumn('withdrawal_transaction_id');
            }
        });
    }
};

