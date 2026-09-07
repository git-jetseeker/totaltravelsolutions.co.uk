<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lounges_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('lounges_bookings', 'lounge_name')) {
                $table->string('lounge_name')->nullable()->after('lounge_id');
            }
            if (!Schema::hasColumn('lounges_bookings', 'lounge_code')) {
                $table->string('lounge_code')->nullable()->after('lounge_name');
            }
            if (!Schema::hasColumn('lounges_bookings', 'referenceNo_ext')) {
                $table->string('referenceNo_ext')->nullable()->after('ext_ref');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lounges_bookings', function (Blueprint $table) {
            foreach (['lounge_name', 'lounge_code', 'referenceNo_ext'] as $col) {
                if (Schema::hasColumn('lounges_bookings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
