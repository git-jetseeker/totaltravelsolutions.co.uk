<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Align Jetseeker `lounges` table columns with Meetandgreet (MAG) schema
 * so BookFHR lounge code / catalogue fields match.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lounges', function (Blueprint $table) {
            if (!Schema::hasColumn('lounges', 'email')) {
                $table->string('email', 250)->nullable()->after('lounge_code');
            }
            if (!Schema::hasColumn('lounges', 'telephone')) {
                $table->string('telephone', 100)->nullable()->after('post_code');
            }

            if (!Schema::hasColumn('lounges', 'menu_drinks')) {
                $table->longText('menu_drinks')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'menu_extras')) {
                $table->longText('menu_extras')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'menu_food')) {
                $table->longText('menu_food')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'luggageleftonsite')) {
                $table->string('luggageleftonsite', 1000)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'food_beverage')) {
                $table->longText('food_beverage')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'unique_features')) {
                $table->string('unique_features', 1000)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'dresscode')) {
                $table->longText('dresscode')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'facilitiesdisabled')) {
                $table->string('facilitiesdisabled', 500)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'checkintime')) {
                $table->text('checkintime')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'introduction')) {
                $table->string('introduction', 1000)->nullable();
            }

            if (!Schema::hasColumn('lounges', 'why_bookone')) {
                $table->string('why_bookone', 500)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'why_booktwo')) {
                $table->string('why_booktwo', 500)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'why_bookthree')) {
                $table->string('why_bookthree', 500)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'why_bookfour')) {
                $table->string('why_bookfour', 500)->nullable();
            }

            if (!Schema::hasColumn('lounges', 'groups')) {
                $table->string('groups', 500)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'children_permitted')) {
                $table->string('children_permitted', 500)->nullable();
            }

            $flagCols = [
                'free_drinks', 'free_food', 'suitable_for_int', 'landside', 'magazines',
                'noncancellable_nonrefundable', 'personal_lounge', 'phone', 'tv', 'wifi',
                'children_allowed', 'disabled_access', 'flight_announcements', 'snacks',
                'smoking_permitted',
            ];
            foreach ($flagCols as $col) {
                if (!Schema::hasColumn('lounges', $col)) {
                    $table->enum($col, ['0', '1'])->nullable()->default('0');
                }
            }

            if (!Schema::hasColumn('lounges', 'infant_age')) {
                $table->string('infant_age', 50)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'child_age')) {
                $table->string('child_age', 50)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'adult_age')) {
                $table->string('adult_age', 50)->nullable();
            }
            if (!Schema::hasColumn('lounges', 'check_in_up_to')) {
                $table->string('check_in_up_to', 50)->nullable();
            }

            $longTextCols = [
                'upgrade_lounge_text',
                'whats_included_drinks',
                'whats_included_extras',
                'whats_included_food',
                'business_facilities',
                'smoking_permitted_txt',
                'flight_announce',
            ];
            foreach ($longTextCols as $col) {
                if (!Schema::hasColumn('lounges', $col)) {
                    $table->string($col, 1500)->nullable();
                }
            }

            if (!Schema::hasColumn('lounges', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('lounges', 'updated_at')) {
                $table->dateTime('updated_at')->nullable();
            }
        });

        // Widen existing columns to match MAG (no doctrine/dbal required)
        $alters = [
            "ALTER TABLE `lounges` MODIFY `banner_images` TEXT NULL",
            "ALTER TABLE `lounges` MODIFY `address` TEXT NULL",
            "ALTER TABLE `lounges` MODIFY `overview` LONGTEXT NULL",
            "ALTER TABLE `lounges` MODIFY `terms` TEXT NULL",
            "ALTER TABLE `lounges` MODIFY `facilities` VARCHAR(1000) NULL",
            "ALTER TABLE `lounges` MODIFY `directions` VARCHAR(500) NULL",
            "ALTER TABLE `lounges` MODIFY `lounge_code` VARCHAR(50) NULL",
            "ALTER TABLE `lounges` MODIFY `opening_time` VARCHAR(100) NULL",
            "ALTER TABLE `lounges` MODIFY `closing_time` VARCHAR(100) NULL",
            "ALTER TABLE `lounges` MODIFY `processtime` VARCHAR(50) NULL",
        ];

        foreach ($alters as $sql) {
            try {
                DB::statement($sql);
            } catch (\Throwable $e) {
                // Ignore if column type already compatible
            }
        }
    }

    public function down(): void
    {
        $cols = [
            'email', 'telephone', 'menu_drinks', 'menu_extras', 'menu_food',
            'luggageleftonsite', 'food_beverage', 'unique_features', 'dresscode',
            'facilitiesdisabled', 'checkintime', 'introduction',
            'why_bookone', 'why_booktwo', 'why_bookthree', 'why_bookfour',
            'groups', 'children_permitted',
            'free_drinks', 'free_food', 'suitable_for_int', 'landside', 'magazines',
            'noncancellable_nonrefundable', 'personal_lounge', 'phone', 'tv', 'wifi',
            'children_allowed', 'disabled_access', 'flight_announcements', 'snacks',
            'smoking_permitted',
            'infant_age', 'child_age', 'adult_age', 'check_in_up_to',
            'upgrade_lounge_text', 'whats_included_drinks', 'whats_included_extras',
            'whats_included_food', 'business_facilities', 'smoking_permitted_txt',
            'flight_announce', 'created_at', 'updated_at',
        ];

        Schema::table('lounges', function (Blueprint $table) use ($cols) {
            $drop = [];
            foreach ($cols as $col) {
                if (Schema::hasColumn('lounges', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
