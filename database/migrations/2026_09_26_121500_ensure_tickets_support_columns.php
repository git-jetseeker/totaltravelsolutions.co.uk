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

        $add = function (string $column, callable $definition): void {
            if (! Schema::hasColumn('tickets', $column)) {
                Schema::table('tickets', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        };

        $add('ticket_id', fn (Blueprint $t) => $t->string('ticket_id', 64)->nullable());
        $add('agent_id', fn (Blueprint $t) => $t->integer('agent_id')->nullable());
        $add('title', fn (Blueprint $t) => $t->text('title')->nullable());
        $add('booking_ref', fn (Blueprint $t) => $t->string('booking_ref', 64)->nullable());
        $add('user_id', fn (Blueprint $t) => $t->integer('user_id')->nullable());
        $add('company_admin_id', fn (Blueprint $t) => $t->integer('company_admin_id')->nullable());
        $add('name', fn (Blueprint $t) => $t->string('name', 255)->nullable());
        $add('email', fn (Blueprint $t) => $t->string('email', 191)->nullable());
        $add('contact', fn (Blueprint $t) => $t->string('contact', 32)->nullable());
        $add('department', fn (Blueprint $t) => $t->integer('department')->nullable());
        $add('urgency', fn (Blueprint $t) => $t->string('urgency', 20)->nullable());
        $add('date', fn (Blueprint $t) => $t->dateTime('date')->nullable());
        $add('assign_to', fn (Blueprint $t) => $t->integer('assign_to')->nullable());
        $add('assign_date', fn (Blueprint $t) => $t->dateTime('assign_date')->nullable());
        $add('status', fn (Blueprint $t) => $t->string('status', 20)->nullable());

        try {
            DB::statement('ALTER TABLE tickets MODIFY ticket_id VARCHAR(64) NULL');
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function down(): void
    {
        // Keep columns on shared Magr.
    }
};
