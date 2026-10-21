<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carhire_bookings')) {
            return;
        }

        Schema::create('carhire_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('airportID')->nullable();
            $table->unsignedInteger('customerId')->nullable();
            $table->string('product_code', 255)->nullable();
            $table->string('option_id', 100)->nullable();
            $table->string('bookfhr_search_id', 191)->nullable();
            $table->string('bookfhr_cart_id', 191)->nullable();
            $table->string('bookfhr_item_id', 191)->nullable();
            $table->string('title', 20)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('referenceNo', 100)->nullable()->index();
            $table->string('ext_ref', 191)->nullable();
            $table->string('supplier_reference', 191)->nullable();
            $table->dateTime('pickup_datetime')->nullable();
            $table->dateTime('dropoff_datetime')->nullable();
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->unsignedTinyInteger('driver_age')->nullable();
            $table->string('residence_country_code', 5)->nullable();
            $table->string('vehicle_name', 191)->nullable();
            $table->string('vehicle_type', 100)->nullable();
            $table->string('acriss_code', 20)->nullable();
            $table->string('transmission', 50)->nullable();
            $table->string('fuel_type', 50)->nullable();
            $table->unsignedTinyInteger('seats')->nullable();
            $table->unsignedTinyInteger('doors')->nullable();
            $table->string('supplier_name', 191)->nullable();
            $table->string('pickup_location', 191)->nullable();
            $table->string('dropoff_location', 191)->nullable();
            $table->string('pickup_address', 255)->nullable();
            $table->string('dropoff_address', 255)->nullable();
            $table->string('discount_code', 100)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('booking_amount', 10, 2)->default(0);
            $table->decimal('extra_amount', 10, 2)->default(0);
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->decimal('cancelfee', 10, 2)->default(0);
            $table->decimal('smsfee', 10, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('pay_later', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('carhire_api', 50)->nullable();
            $table->string('booked_type', 50)->nullable();
            $table->string('payment_status', 50)->nullable();
            $table->string('booking_status', 50)->nullable();
            $table->string('booking_action', 50)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('PayerID', 191)->nullable();
            $table->string('intent_id', 191)->nullable();
            $table->string('token', 191)->nullable();
            $table->string('enjoytravel_access_token', 191)->nullable();
            $table->longText('api_res')->nullable();
            $table->longText('bookfhr_api_res')->nullable();
            $table->longText('enjoytravel_api_res')->nullable();
            $table->longText('extras_json')->nullable();
            $table->text('browser_data')->nullable();
            $table->string('user_ip', 64)->nullable();
            $table->string('traffic_src', 50)->nullable();
            $table->unsignedInteger('agentID')->nullable();
            $table->string('removed', 10)->nullable()->default('No');
            $table->string('email_check', 10)->nullable()->default('0');
            $table->string('status', 20)->nullable()->default('1');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carhire_bookings');
    }
};
