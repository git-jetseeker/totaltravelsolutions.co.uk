<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Live SiteGround DB may only have stub discounts (id + timestamps).
 * Magr promo lookup needs promo/status/start_date/end_date/...
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('discounts')) {
            DB::statement("CREATE TABLE `discounts` (
              `id` int NOT NULL AUTO_INCREMENT,
              `discount_campaign` varchar(255) NOT NULL DEFAULT '',
              `promo` varchar(30) NOT NULL DEFAULT '',
              `status` varchar(8) NOT NULL DEFAULT 'Yes',
              `agent_id` int DEFAULT NULL,
              `start_date` date DEFAULT NULL,
              `end_date` date DEFAULT NULL,
              `discount_value` double(4,2) NOT NULL DEFAULT 0,
              `discount_type` varchar(255) NOT NULL DEFAULT '',
              `parking_type` varchar(255) NOT NULL DEFAULT '',
              `discount_for` varchar(255) NOT NULL DEFAULT '',
              `admin_id` int NOT NULL DEFAULT 0,
              `added_on` datetime DEFAULT NULL,
              `updated_on` datetime DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            return;
        }

        $cols = [
            'discount_campaign' => "VARCHAR(255) NOT NULL DEFAULT ''",
            'promo' => "VARCHAR(30) NOT NULL DEFAULT ''",
            'status' => "VARCHAR(8) NOT NULL DEFAULT 'Yes'",
            'agent_id' => 'INT NULL',
            'start_date' => 'DATE NULL',
            'end_date' => 'DATE NULL',
            'discount_value' => 'DOUBLE(4,2) NOT NULL DEFAULT 0',
            'discount_type' => "VARCHAR(255) NOT NULL DEFAULT ''",
            'parking_type' => "VARCHAR(255) NOT NULL DEFAULT ''",
            'discount_for' => "VARCHAR(255) NOT NULL DEFAULT ''",
            'admin_id' => 'INT NOT NULL DEFAULT 0',
            'added_on' => 'DATETIME NULL',
            'updated_on' => 'DATETIME NULL',
        ];

        foreach ($cols as $col => $def) {
            if (! Schema::hasColumn('discounts', $col)) {
                DB::statement("ALTER TABLE `discounts` ADD COLUMN `{$col}` {$def}");
            }
        }
    }

    public function down(): void
    {
        // Non-destructive: Magr columns stay.
    }
};
