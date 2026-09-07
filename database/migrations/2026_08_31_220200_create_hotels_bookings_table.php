<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hotels_bookings')) {
            return;
        }

        Schema::create('hotels_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airportID')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('referenceNo')->nullable()->index();
            $table->string('ext_ref')->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->string('check_in_time')->nullable();
            $table->string('check_out_time')->nullable();
            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);
            $table->unsignedInteger('infants')->default(0);
            $table->unsignedInteger('rooms')->default(1);
            $table->string('room_type')->nullable();
            $table->string('room_title')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('booking_amount', 10, 2)->default(0);
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->decimal('cancelfee', 10, 2)->default(0);
            $table->decimal('smsfee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('hotel_api')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('booking_status')->nullable();
            $table->string('booking_action')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('PayerID')->nullable();
            $table->longText('api_res')->nullable();
            $table->string('browser_data')->nullable();
            $table->string('traffic_src')->nullable();
            $table->unsignedBigInteger('agentID')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels_bookings');
    }
};
