<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        // Anchors are resolved against the table as it exists right now, so a
        // missing anchor simply appends the column instead of failing the ALTER.
        $place = function (ColumnDefinition $column, string $anchor): void {
            if (Schema::hasColumn('hotels', $anchor)) {
                $column->after($anchor);
            }
        };

        Schema::table('hotels', function (Blueprint $table) use ($place) {
            if (!Schema::hasColumn('hotels', 'company_code')) {
                $place($table->string('company_code', 100)->nullable(), 'id');
            }

            if (!Schema::hasColumn('hotels', 'status')) {
                $table->enum('status', ['Yes', 'No'])->default('Yes');
            }

            if (!Schema::hasColumn('hotels', 'is_active')) {
                $place($table->enum('is_active', ['Yes', 'No', 'Old'])->default('No'), 'status');
            }

            if (!Schema::hasColumn('hotels', 'removed')) {
                $table->enum('removed', ['Yes', 'No'])->default('No');
            }

            if (!Schema::hasColumn('hotels', 'share_percentage')) {
                $table->double('share_percentage', 4, 2)->nullable();
            }

            if (!Schema::hasColumn('hotels', 'max_discount')) {
                $place($table->string('max_discount', 11)->nullable(), 'share_percentage');
            }

            if (!Schema::hasColumn('hotels', 'featured')) {
                $table->enum('featured', ['Yes', 'No'])->default('No');
            }

            if (!Schema::hasColumn('hotels', 'recommended')) {
                $table->enum('recommended', ['Yes', 'No'])->default('No');
            }

            if (!Schema::hasColumn('hotels', 'airport_id')) {
                $table->integer('airport_id')->default(0);
            }

            if (!Schema::hasColumn('hotels', 'address')) {
                $table->string('address', 500)->nullable();
            }

            if (!Schema::hasColumn('hotels', 'hotel_name')) {
                $table->string('hotel_name', 255)->nullable();
            }

            if (!Schema::hasColumn('hotels', 'name')) {
                $table->string('name', 100)->nullable();
            }

            if (!Schema::hasColumn('hotels', 'logo')) {
                $table->text('logo')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        Schema::table('hotels', function (Blueprint $table) {
            foreach (['company_code', 'max_discount', 'is_active'] as $column) {
                if (Schema::hasColumn('hotels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
