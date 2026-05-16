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
            if (!Schema::hasColumn('sale_transactions', 'user_id_input')) {
                $table->string('user_id_input')->nullable()->after('proof_image');
            }
            if (!Schema::hasColumn('sale_transactions', 'nickname')) {
                $table->string('nickname')->nullable()->after('user_id_input');
            }
            if (!Schema::hasColumn('sale_transactions', 'service_name')) {
                $table->string('service_name')->nullable()->after('nickname');
            }
            if (!Schema::hasColumn('sale_transactions', 'payment_number')) {
                $table->string('payment_number')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropColumn(['user_id_input', 'nickname', 'service_name', 'payment_number']);
        });
    }
};
