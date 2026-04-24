<?php

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
        // Change ENUM to include 'process' status (MySQL).
        // SQLite does not support MODIFY COLUMN; in tests we keep the original enum set.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE sale_transactions MODIFY COLUMN status ENUM('pending', 'process', 'success', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Revert to original ENUM (without 'process')
        DB::statement("ALTER TABLE sale_transactions MODIFY COLUMN status ENUM('pending', 'success', 'failed') DEFAULT 'pending'");
    }
};
