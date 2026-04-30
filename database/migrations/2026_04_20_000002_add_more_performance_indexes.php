<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'marketing_owner_id') && ! Schema::hasIndex('users', 'idx_users_marketing_owner_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['marketing_owner_id'], 'idx_users_marketing_owner_id');
            });
        }

        if (! Schema::hasIndex('sale_transactions', 'idx_sale_transactions_type_created_at')) {
            Schema::table('sale_transactions', function (Blueprint $table) {
                $table->index(['transaction_type', 'created_at'], 'idx_sale_transactions_type_created_at');
            });
        }

        if (! Schema::hasIndex('sale_transactions', 'idx_sale_transactions_user_type_created_at')) {
            Schema::table('sale_transactions', function (Blueprint $table) {
                $table->index(['user_id', 'transaction_type', 'created_at'], 'idx_sale_transactions_user_type_created_at');
            });
        }

        if (! Schema::hasIndex('commissions', 'idx_commissions_user_withdrawn')) {
            Schema::table('commissions', function (Blueprint $table) {
                $table->index(['user_id', 'withdrawn'], 'idx_commissions_user_withdrawn');
            });
        }

        if (! Schema::hasIndex('commissions', 'idx_commissions_user_created_at')) {
            Schema::table('commissions', function (Blueprint $table) {
                $table->index(['user_id', 'created_at'], 'idx_commissions_user_created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_marketing_owner_id');
        });

        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_sale_transactions_type_created_at');
            $table->dropIndex('idx_sale_transactions_user_type_created_at');
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex('idx_commissions_user_withdrawn');
            $table->dropIndex('idx_commissions_user_created_at');
        });
    }
};

