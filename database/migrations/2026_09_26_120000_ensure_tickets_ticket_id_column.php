<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tickets')) {
            return;
        }

        if (! Schema::hasColumn('tickets', 'ticket_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('ticket_id', 64)->nullable()->after('id');
            });
            return;
        }

        // Widen legacy varchar(21) without doctrine/dbal change()
        try {
            DB::statement('ALTER TABLE tickets MODIFY ticket_id VARCHAR(64) NULL');
        } catch (\Throwable $e) {
            // Ignore if DB user cannot ALTER or already correct.
        }
    }

    public function down(): void
    {
        // Keep column — unsafe to drop on shared Magr.
    }
};
