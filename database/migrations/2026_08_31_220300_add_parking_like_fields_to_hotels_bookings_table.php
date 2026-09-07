<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hotels_bookings')) {
            return;
        }

        $place = function (ColumnDefinition $column, string $anchor): void {
            if (Schema::hasColumn('hotels_bookings', $anchor)) {
                $column->after($anchor);
            }
        };

        Schema::table('hotels_bookings', function (Blueprint $table) use ($place) {
            if (!Schema::hasColumn('hotels_bookings', 'product_code')) {
                $place($table->string('product_code')->nullable(), 'hotel_id');
            }
            if (!Schema::hasColumn('hotels_bookings', 'option_id')) {
                $place($table->string('option_id')->nullable(), 'product_code');
            }
            if (!Schema::hasColumn('hotels_bookings', 'bookfhr_search_id')) {
                $place($table->string('bookfhr_search_id')->nullable(), 'option_id');
            }
            if (!Schema::hasColumn('hotels_bookings', 'bookfhr_option_id')) {
                $place($table->string('bookfhr_option_id')->nullable(), 'bookfhr_search_id');
            }
            if (!Schema::hasColumn('hotels_bookings', 'bookfhr_cart_id')) {
                $place($table->string('bookfhr_cart_id')->nullable(), 'bookfhr_search_id');
            }
            if (!Schema::hasColumn('hotels_bookings', 'bookfhr_item_id')) {
                $place($table->string('bookfhr_item_id')->nullable(), 'bookfhr_cart_id');
            }
            if (!Schema::hasColumn('hotels_bookings', 'bookfhr_api_res')) {
                $place($table->longText('bookfhr_api_res')->nullable(), 'api_res');
            }
            if (!Schema::hasColumn('hotels_bookings', 'referenceLink_ext')) {
                $place($table->string('referenceLink_ext')->nullable(), 'ext_ref');
            }
            if (!Schema::hasColumn('hotels_bookings', 'user_ip')) {
                $place($table->string('user_ip')->nullable(), 'browser_data');
            }
            if (!Schema::hasColumn('hotels_bookings', 'intent_id')) {
                $place($table->string('intent_id')->nullable(), 'PayerID');
            }
            if (!Schema::hasColumn('hotels_bookings', 'token')) {
                $place($table->string('token')->nullable(), 'PayerID');
            }
            if (!Schema::hasColumn('hotels_bookings', 'no_of_nights')) {
                $place($table->unsignedInteger('no_of_nights')->nullable(), 'rooms');
            }
            if (!Schema::hasColumn('hotels_bookings', 'booked_type')) {
                $place($table->string('booked_type')->default('hotel'), 'hotel_api');
            }
            if (!Schema::hasColumn('hotels_bookings', 'park_api')) {
                $place($table->string('park_api')->nullable(), 'hotel_api');
            }
            if (!Schema::hasColumn('hotels_bookings', 'booking_extra')) {
                $place($table->decimal('booking_extra', 10, 2)->default(0), 'booking_amount');
            }
            if (!Schema::hasColumn('hotels_bookings', 'status')) {
                $place($table->string('status')->default('Yes'), 'booking_action');
            }
            if (!Schema::hasColumn('hotels_bookings', 'removed')) {
                $place($table->string('removed')->default('No'), 'status');
            }
            if (!Schema::hasColumn('hotels_bookings', 'email_check')) {
                $place($table->string('email_check')->nullable(), 'removed');
            }
        });

        if (!Schema::hasTable('hotel_booking_transaction')) {
            Schema::create('hotel_booking_transaction', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('orderID')->nullable();
                $table->string('token')->nullable();
                $table->string('referenceNo')->nullable()->index();
                $table->unsignedBigInteger('hotelId')->nullable();
                $table->decimal('booking_amount', 10, 2)->default(0);
                $table->decimal('extra_amount', 10, 2)->default(0);
                $table->decimal('discount_amount', 10, 2)->default(0);
                $table->decimal('smsfee', 10, 2)->default(0);
                $table->decimal('booking_fee', 10, 2)->default(0);
                $table->decimal('cancelfee', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('payable', 10, 2)->default(0);
                $table->string('amount_type')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('payment_action')->nullable();
                $table->string('booking_status')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_booking_transaction');

        if (!Schema::hasTable('hotels_bookings')) {
            return;
        }

        Schema::table('hotels_bookings', function (Blueprint $table) {
            foreach ([
                'product_code', 'option_id', 'bookfhr_search_id', 'bookfhr_option_id', 'bookfhr_cart_id',
                'bookfhr_item_id', 'bookfhr_api_res', 'referenceLink_ext', 'user_ip', 'intent_id', 'token',
                'no_of_nights', 'booked_type', 'park_api', 'booking_extra', 'status', 'removed', 'email_check',
            ] as $column) {
                if (Schema::hasColumn('hotels_bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
