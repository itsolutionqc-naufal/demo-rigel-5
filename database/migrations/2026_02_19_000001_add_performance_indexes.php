<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better query performance
        
        // Sale Transactions table indexes
        if (!Schema::hasIndex('sale_transactions', 'idx_user_id_status')) {
            Schema::table('sale_transactions', function (Blueprint $table) {
                $table->index(['user_id', 'status'], 'idx_user_id_status');
                $table->index(['status', 'completed_at'], 'idx_status_completed_at');
                $table->index(['created_at'], 'idx_sale_transactions_created_at');
            });
        }
        
        // Commissions table indexes
        if (!Schema::hasIndex('commissions', 'idx_user_id_period')) {
            Schema::table('commissions', function (Blueprint $table) {
                $table->index(['user_id', 'period_date'], 'idx_user_id_period');
                $table->index(['sale_transaction_id'], 'idx_sale_transaction_id');
                $table->index(['withdrawn'], 'idx_withdrawn');
            });
        }
        
        // Users table indexes
        if (!Schema::hasIndex('users', 'idx_email_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['email_verified_at'], 'idx_email_verified');
                $table->index(['created_at'], 'idx_users_created_at');
            });
        }
        
        // Services table indexes
        if (Schema::hasColumn('services', 'is_active') && !Schema::hasIndex('services', 'idx_is_active')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index(['is_active'], 'idx_is_active');
            });
        }
        if (Schema::hasColumn('services', 'commission_rate') && !Schema::hasIndex('services', 'idx_commission_rate')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index(['commission_rate'], 'idx_commission_rate');
            });
        }
        
        // Payment methods table indexes
        if (Schema::hasColumn('payment_methods', 'service_id') && !Schema::hasIndex('payment_methods', 'idx_service_id')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->index(['service_id'], 'idx_service_id');
            });
        }
        if (Schema::hasColumn('payment_methods', 'is_active') && !Schema::hasIndex('payment_methods', 'idx_is_active')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->index(['is_active'], 'idx_is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes if they exist
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_user_id_status');
            $table->dropIndex('idx_status_completed_at');
            $table->dropIndex('idx_sale_transactions_created_at');
        });
        
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex('idx_user_id_period');
            $table->dropIndex('idx_sale_transaction_id');
            $table->dropIndex('idx_withdrawn');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_email_verified');
            $table->dropIndex('idx_users_created_at');
        });
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('idx_is_active');
            $table->dropIndex('idx_commission_rate');
        });
        
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropIndex('idx_service_id');
            $table->dropIndex('idx_is_active');
        });
    }
};
