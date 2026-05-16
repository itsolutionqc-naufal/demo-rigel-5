<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('host_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_transaction_id')
                ->constrained('sale_transactions')
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();
            $table->string('host_id');
            $table->string('nickname');
            $table->string('whatsapp_number');
            $table->boolean('form_filled')->default(false);
            $table->timestamps();

            $table->unique('sale_transaction_id');
            $table->index('service_id');
        });

        // Backfill: migrate existing sale_transactions (transaction_type=host_submit) into host_submissions.
        // service_id is best-effort (matched by service_name).
        DB::statement(<<<'SQL'
            INSERT INTO host_submissions (
                sale_transaction_id,
                service_id,
                host_id,
                nickname,
                whatsapp_number,
                form_filled,
                created_at,
                updated_at
            )
            SELECT
                st.id AS sale_transaction_id,
                s.id AS service_id,
                COALESCE(st.user_id_input, '') AS host_id,
                COALESCE(st.nickname, '') AS nickname,
                COALESCE(st.whatsapp_number, '') AS whatsapp_number,
                CASE
                    WHEN LOWER(COALESCE(st.description, '')) LIKE '%formulir:%sudah%' THEN 1
                    ELSE 0
                END AS form_filled,
                st.created_at,
                st.updated_at
            FROM sale_transactions st
            LEFT JOIN host_submissions hs ON hs.sale_transaction_id = st.id
            LEFT JOIN services s ON s.name = st.service_name
            WHERE st.transaction_type = 'host_submit'
              AND hs.sale_transaction_id IS NULL
        SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('host_submissions');
    }
};

