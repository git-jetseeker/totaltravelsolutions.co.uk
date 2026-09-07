<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lounges', function (Blueprint $table) {
            if (!Schema::hasColumn('lounges', 'lounge_code')) {
                $table->string('lounge_code')->nullable()->after('id');
            }
            if (!Schema::hasColumn('lounges', 'company_code')) {
                $table->string('company_code')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'tripappimages')) {
                $table->text('tripappimages')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'directions')) {
                $table->text('directions')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'facilities')) {
                $table->text('facilities')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'opening_time')) {
                $table->string('opening_time')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'closing_time')) {
                $table->string('closing_time')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'processtime')) {
                $table->string('processtime')->nullable();
            }
        });

        Schema::table('lounges_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('lounges_bookings', 'ext_ref')) {
                $table->string('ext_ref')->nullable();
            }
            if (!Schema::hasColumn('lounges_bookings', 'lounge_api')) {
                $table->string('lounge_api')->nullable();
            }
            if (!Schema::hasColumn('lounges_bookings', 'agentID')) {
                $table->unsignedInteger('agentID')->nullable();
            }
            if (!Schema::hasColumn('lounges_bookings', 'agent_commission')) {
                $table->decimal('agent_commission', 10, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('lounges', function (Blueprint $table) {
            $cols = ['lounge_code', 'company_code', 'tripappimages', 'directions', 'facilities', 'opening_time', 'closing_time', 'processtime'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('lounges', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('lounges_bookings', function (Blueprint $table) {
            $cols = ['ext_ref', 'lounge_api', 'agentID', 'agent_commission'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('lounges_bookings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
