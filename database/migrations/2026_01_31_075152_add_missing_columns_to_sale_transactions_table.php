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
        Schema::table('sale_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_transactions', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('sale_transactions', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('sale_transactions', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('sale_transactions', 'commission_amount')) {
                $table->decimal('commission_amount', 15, 2)->default(0)->after('commission_rate');
            }
            if (!Schema::hasColumn('sale_transactions', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null')->after('user_id');
            }
            if (!Schema::hasColumn('sale_transactions', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('transaction_date');
            }
            if (!Schema::hasColumn('sale_transactions', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('confirmed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'commission_rate',
                'commission_amount',
                'admin_id',
                'confirmed_at',
                'completed_at',
            ]);
        });
    }
};
