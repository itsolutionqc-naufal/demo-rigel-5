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
            if (!Schema::hasColumn('sale_transactions', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('sale_transactions', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('sale_transactions', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('sale_transactions', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('account_name');
            }
            if (!Schema::hasColumn('sale_transactions', 'address')) {
                $table->text('address')->nullable()->after('whatsapp_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name',
                'account_number',
                'account_name',
                'whatsapp_number',
                'address'
            ]);
        });
    }
};
