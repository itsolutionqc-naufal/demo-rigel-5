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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'marketing_owner_id')) {
                return;
            }

            $table->foreignId('marketing_owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->index()
                ->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'marketing_owner_id')) {
                return;
            }

            $table->dropForeign(['marketing_owner_id']);
            $table->dropColumn('marketing_owner_id');
        });
    }
};

