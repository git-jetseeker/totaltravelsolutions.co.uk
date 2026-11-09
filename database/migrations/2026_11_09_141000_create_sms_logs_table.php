<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sms_logs')) {
            return;
        }

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id')->nullable()->index();
            $table->string('booking_ref')->nullable()->index();
            $table->string('to_number', 50)->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->text('body')->nullable();
            $table->string('provider', 50)->default('voodoo_sms');
            $table->string('provider_message_id')->nullable();
            $table->string('status', 30)->default('queued')->index();
            $table->text('error_message')->nullable();
            $table->string('triggered_by', 50)->default('system');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
