<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('email_logs')) {
            Schema::create('email_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('booking_id')->nullable()->index();
                $table->string('booking_ref')->nullable()->index();
                $table->string('to_email')->nullable()->index();
                $table->string('cc')->nullable();
                $table->unsignedBigInteger('template_id')->nullable();
                $table->string('subject')->nullable();
                $table->text('body_excerpt')->nullable();
                $table->string('provider', 50)->default('smtp');
                $table->string('provider_message_id')->nullable()->index();
                $table->string('status', 30)->default('queued')->index();
                $table->text('error_message')->nullable();
                $table->string('triggered_by', 50)->default('system')->index();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('tracking_token', 64)->nullable()->unique();
                $table->timestamp('opened_at')->nullable()->index();
                $table->unsignedInteger('open_count')->default(0);
                $table->timestamp('last_opened_at')->nullable();
                $table->timestamps();
            });

            return;
        }

        $columns = [
            'booking_id' => fn (Blueprint $table) => $table->unsignedBigInteger('booking_id')->nullable(),
            'booking_ref' => fn (Blueprint $table) => $table->string('booking_ref')->nullable(),
            'to_email' => fn (Blueprint $table) => $table->string('to_email')->nullable(),
            'cc' => fn (Blueprint $table) => $table->string('cc')->nullable(),
            'template_id' => fn (Blueprint $table) => $table->unsignedBigInteger('template_id')->nullable(),
            'subject' => fn (Blueprint $table) => $table->string('subject')->nullable(),
            'body_excerpt' => fn (Blueprint $table) => $table->text('body_excerpt')->nullable(),
            'provider' => fn (Blueprint $table) => $table->string('provider', 50)->default('smtp'),
            'provider_message_id' => fn (Blueprint $table) => $table->string('provider_message_id')->nullable(),
            'status' => fn (Blueprint $table) => $table->string('status', 30)->default('queued'),
            'error_message' => fn (Blueprint $table) => $table->text('error_message')->nullable(),
            'triggered_by' => fn (Blueprint $table) => $table->string('triggered_by', 50)->default('system'),
            'user_id' => fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable(),
            'tracking_token' => fn (Blueprint $table) => $table->string('tracking_token', 64)->nullable(),
            'opened_at' => fn (Blueprint $table) => $table->timestamp('opened_at')->nullable(),
            'open_count' => fn (Blueprint $table) => $table->unsignedInteger('open_count')->default(0),
            'last_opened_at' => fn (Blueprint $table) => $table->timestamp('last_opened_at')->nullable(),
            'created_at' => fn (Blueprint $table) => $table->timestamp('created_at')->nullable(),
            'updated_at' => fn (Blueprint $table) => $table->timestamp('updated_at')->nullable(),
        ];

        foreach ($columns as $column => $definition) {
            if (! Schema::hasColumn('email_logs', $column)) {
                Schema::table('email_logs', $definition);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
