<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hotels')) {
            return;
        }

        Schema::create('hotels', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('company_code', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('hotel_name', 255)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->string('banner_image', 255)->nullable();
            $table->text('logo')->nullable();
            $table->integer('airport_id')->default(0);
            $table->integer('admin_id')->default(0);
            $table->string('address', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('post_code', 100)->nullable();
            $table->text('information')->nullable();
            $table->text('restaurant_overview')->nullable();
            $table->longText('terms')->nullable();
            $table->string('miles_from_airport', 50)->nullable();
            $table->string('transfer_time', 50)->nullable();
            $table->text('special_features')->nullable();
            $table->enum('featured', ['Yes', 'No'])->default('No');
            $table->enum('recommended', ['Yes', 'No'])->default('No');
            $table->enum('cancelable', ['Yes', 'No'])->default('No');
            $table->enum('editable', ['Yes', 'No'])->default('No');
            $table->double('share_percentage', 4, 2)->nullable();
            $table->string('max_discount', 11)->nullable();
            $table->string('awards', 100)->nullable();
            $table->string('check_in', 100)->nullable();
            $table->string('check_out', 100)->nullable();
            $table->dateTime('added_on')->nullable();
            $table->dateTime('update_on')->nullable();
            $table->integer('added_by')->nullable();
            $table->enum('status', ['Yes', 'No'])->default('Yes');
            $table->enum('is_active', ['Yes', 'No', 'Old'])->default('No');
            $table->enum('removed', ['Yes', 'No'])->default('No');
            $table->integer('removed_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
