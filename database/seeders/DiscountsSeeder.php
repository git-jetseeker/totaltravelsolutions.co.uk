<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Exact Magr copy of discounts (promo codes) from office dump extract.
 *
 *   php artisan db:seed --class=DiscountsSeeder --force
 *
 * WARNING: DROPs and recreates `discounts`.
 */
class DiscountsSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('sql/companies_pricing/discounts.sql');
        if (! is_file($path)) {
            $this->command?->error('Missing discounts.sql extract');

            return;
        }

        $this->command?->info('Exact Magr copy: DROP + CREATE + INSERT discounts...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement("SET SESSION sql_mode=''");
        DB::statement('SET NAMES utf8mb4');
        DB::statement('DROP TABLE IF EXISTS `discounts`');

        $sql = file_get_contents($path);
        if ($sql === false || trim($sql) === '') {
            throw new \RuntimeException('Empty discounts.sql');
        }
        $sql = str_replace(
            ["'0000-00-00 00:00:00'", "'0000-00-00'"],
            ["'1970-01-01 00:00:01'", "'1970-01-01'"],
            $sql
        );

        $buffer = '';
        $executed = 0;
        foreach (preg_split("/\r\n|\n|\r/", $sql) as $line) {
            if (str_starts_with(ltrim($line), '--')) {
                continue;
            }
            $buffer .= $line."\n";
            if (! preg_match('/;\s*$/', $line)) {
                continue;
            }
            $stmt = trim($buffer);
            $buffer = '';
            $upper = strtoupper(ltrim($stmt));
            if ($stmt === '' || ! (
                str_starts_with($upper, 'INSERT ')
                || str_starts_with($upper, 'CREATE ')
                || str_starts_with($upper, 'ALTER ')
                || str_starts_with($upper, 'DROP ')
                || str_starts_with($upper, 'SET ')
            )) {
                continue;
            }
            try {
                DB::unprepared($stmt);
                $executed++;
            } catch (Throwable $e) {
                throw new \RuntimeException("Failed importing discounts.sql: ".$e->getMessage(), 0, $e);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $rows = Schema::hasTable('discounts') ? DB::table('discounts')->count() : 0;
        $this->command?->info("Done. discounts statements={$executed}; rows={$rows}");
    }
}
