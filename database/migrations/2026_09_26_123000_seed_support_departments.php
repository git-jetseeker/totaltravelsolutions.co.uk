<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('support_departments')) {
            Schema::create('support_departments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->nullable();
                $table->string('email', 191)->nullable();
            });
        } else {
            if (! Schema::hasColumn('support_departments', 'name')) {
                Schema::table('support_departments', function (Blueprint $table) {
                    $table->string('name', 100)->nullable();
                });
            }
            if (! Schema::hasColumn('support_departments', 'email')) {
                Schema::table('support_departments', function (Blueprint $table) {
                    $table->string('email', 191)->nullable();
                });
            }
        }

        $count = DB::table('support_departments')->count();
        if ($count > 0) {
            return;
        }

        $email = 'support@example.com';
        try {
            if (function_exists('site_settings')) {
                $settings = site_settings();
                if (! empty($settings['footer_email'])) {
                    $email = $settings['footer_email'];
                }
            }
        } catch (\Throwable $e) {
            // keep default
        }

        DB::table('support_departments')->insert([
            ['name' => 'Booking', 'email' => $email],
            ['name' => 'Complaint', 'email' => $email],
            ['name' => 'Amendment', 'email' => $email],
            ['name' => 'Cancellation', 'email' => $email],
        ]);
    }

    public function down(): void
    {
        // Keep seeded departments on shared Magr.
    }
};
