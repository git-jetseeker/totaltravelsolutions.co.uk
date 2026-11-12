<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Seeds ALL BookFHR parking products (company_code LIKE BOOKFHR_%) into companies.
 *
 * Target mapping:
 * - admin_id = 225 for all
 * - airport_id kept exactly as in source database
 *
 * Usage:
 *   1. Copy this file to database/seeders/
 *   2. Copy database/seeders/data/bookfhr_all_products.json
 *   3. php artisan db:seed --class=BookFhrAllProductsSeeder --force
 *
 * Matching key: company_code + airport_id
 * Schema-safe: skips missing columns, and coerces nulls for NOT NULL columns.
 */
class BookFhrAllProductsSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/bookfhr_all_products.json');

        if (! is_file($path)) {
            $this->command?->error("Missing data file: {$path}");

            return;
        }

        if (! Schema::hasTable('companies')) {
            $this->command?->error('Table companies does not exist.');

            return;
        }

        $payload = json_decode((string) file_get_contents($path), true);
        if (! is_array($payload) || empty($payload['products'])) {
            $this->command?->error('Invalid or empty bookfhr_all_products.json');

            return;
        }

        $adminId = (int) ($payload['target_admin_id'] ?? 225);
        $now = now()->format('Y-m-d H:i:s');
        $companyMeta = $this->columnMeta('companies');
        $hasFacilities = Schema::hasTable('facilities');
        $facilityMeta = $hasFacilities ? $this->columnMeta('facilities') : [];
        $hasAwardsAssign = Schema::hasTable('companies_assign_awards');
        $hasAwards = Schema::hasTable('awards');

        $skippedCompanyCols = [];
        $inserted = 0;
        $updated = 0;

        DB::transaction(function () use (
            $payload,
            $adminId,
            $now,
            $companyMeta,
            $hasFacilities,
            $facilityMeta,
            $hasAwardsAssign,
            $hasAwards,
            &$skippedCompanyCols,
            &$inserted,
            &$updated
        ) {
            foreach ($payload['products'] as $product) {
                $company = $product['company'] ?? null;
                if (! is_array($company) || empty($company['company_code'])) {
                    continue;
                }

                $company['admin_id'] = $adminId;
                $company['airport_id'] = (int) ($company['airport_id'] ?? 0);
                $company['updated_at'] = $now;

                // Common CDC NOT NULL fields that are often null in source export.
                if (! array_key_exists('parent_id', $company) || $company['parent_id'] === null || $company['parent_id'] === '') {
                    $company['parent_id'] = 0;
                }
                if (! array_key_exists('edin_id', $company) || $company['edin_id'] === null) {
                    $company['edin_id'] = '';
                }
                if (! array_key_exists('aph_id', $company) || $company['aph_id'] === null) {
                    $company['aph_id'] = '';
                }

                [$company, $skipped] = $this->prepareRow($company, $companyMeta);
                foreach ($skipped as $col) {
                    $skippedCompanyCols[$col] = true;
                }

                if (empty($company['company_code']) || ! array_key_exists('airport_id', $company)) {
                    $this->command?->error('Required columns company_code/airport_id missing on target companies table.');

                    return;
                }

                $existing = DB::table('companies')
                    ->where('company_code', $company['company_code'])
                    ->where('airport_id', $company['airport_id'])
                    ->first();

                if ($existing) {
                    $update = $company;
                    unset($update['created_at'], $update['id']);
                    DB::table('companies')->where('id', $existing->id)->update($update);
                    $companyId = (int) $existing->id;
                    $updated++;
                    $this->command?->info("Updated {$company['company_code']} (id={$companyId}) [{$product['group']}]");
                } else {
                    if (isset($companyMeta['created_at']) && empty($company['created_at'])) {
                        $company['created_at'] = $now;
                    }
                    unset($company['id']);
                    $companyId = (int) DB::table('companies')->insertGetId($company);
                    $inserted++;
                    $this->command?->info("Inserted {$company['company_code']} (id={$companyId}) [{$product['group']}]");
                }

                if ($hasFacilities && isset($product['facilities']) && is_array($product['facilities'])) {
                    DB::table('facilities')->where('company_id', $companyId)->delete();
                    foreach ($product['facilities'] as $facility) {
                        if (! is_array($facility)) {
                            continue;
                        }
                        $facility['company_id'] = $companyId;
                        if (isset($facilityMeta['created_at']) && empty($facility['created_at'])) {
                            $facility['created_at'] = $now;
                        }
                        if (isset($facilityMeta['updated_at'])) {
                            $facility['updated_at'] = $now;
                        }
                        [$facility] = $this->prepareRow($facility, $facilityMeta);
                        if (! empty($facility)) {
                            DB::table('facilities')->insert($facility);
                        }
                    }
                }

                if ($hasAwardsAssign && $hasAwards && ! empty($product['award_ids']) && is_array($product['award_ids'])) {
                    DB::table('companies_assign_awards')->where('cid', $companyId)->delete();
                    foreach ($product['award_ids'] as $awardId) {
                        $awardId = (int) $awardId;
                        if ($awardId <= 0 || ! DB::table('awards')->where('id', $awardId)->exists()) {
                            continue;
                        }
                        DB::table('companies_assign_awards')->insert([
                            'cid' => $companyId,
                            'award_id' => $awardId,
                        ]);
                    }
                }
            }
        });

        if ($skippedCompanyCols) {
            $this->command?->warn(
                'Skipped columns not on target companies table: ' . implode(', ', array_keys($skippedCompanyCols))
            );
        }

        $this->command?->info("BookFHR ALL products seed complete. inserted={$inserted}, updated={$updated}");
    }

    /**
     * @return array<string, array{nullable: bool, type: string, default: mixed}>
     */
    protected function columnMeta(string $table): array
    {
        $meta = [];
        foreach (DB::select("SHOW COLUMNS FROM `{$table}`") as $col) {
            $field = $col->Field;
            $meta[$field] = [
                'nullable' => strtoupper((string) $col->Null) === 'YES',
                'type' => strtolower((string) $col->Type),
                'default' => $col->Default,
            ];
        }

        return $meta;
    }

    /**
     * Keep existing columns only, and coerce null/empty values for target column types.
     *
     * @param  array<string, mixed>  $row
     * @param  array<string, array{nullable: bool, type: string, default: mixed}>  $meta
     * @return array{0: array<string, mixed>, 1: array<int, string>}
     */
    protected function prepareRow(array $row, array $meta): array
    {
        $filtered = [];
        $skipped = [];

        foreach ($row as $key => $value) {
            if (! isset($meta[$key])) {
                $skipped[] = $key;
                continue;
            }

            $filtered[$key] = $this->coerceValue($value, $meta[$key]);
        }

        // Ensure every NOT NULL column without DB default is present on insert rows.
        foreach ($meta as $column => $info) {
            if (array_key_exists($column, $filtered)) {
                continue;
            }
            if ($info['nullable'] || $info['default'] !== null) {
                continue;
            }
            if ($column === 'id') {
                continue;
            }
            $filtered[$column] = $this->fallbackForType($info['type']);
        }

        return [$filtered, $skipped];
    }

    /**
     * @param  array{nullable: bool, type: string, default: mixed}  $info
     */
    protected function coerceValue(mixed $value, array $info): mixed
    {
        $type = $info['type'];
        $isEmpty = $value === null || $value === '';

        if ($isEmpty) {
            if ($info['nullable'] && $value === null) {
                return null;
            }
            if ($info['default'] !== null) {
                return $info['default'];
            }

            return $this->fallbackForType($type);
        }

        // Numeric-ish columns: cast empty-like / boolean strings cleanly.
        if ($this->isNumericType($type)) {
            if (is_bool($value)) {
                return $value ? 1 : 0;
            }
            if (is_numeric($value)) {
                return str_contains($type, 'int') ? (int) $value : $value;
            }

            return $this->fallbackForType($type);
        }

        // enum('0','1') style columns (common for is_flex on some DBs).
        if (str_starts_with($type, 'enum(') && (str_contains($type, "'0'") || str_contains($type, "'1'"))) {
            if ($value === true || $value === 1 || $value === '1') {
                return '1';
            }
            if ($value === false || $value === 0 || $value === '0') {
                return '0';
            }
            if ($value === '' || $value === null) {
                return $info['default'] ?? '0';
            }
        }

        return $value;
    }

    protected function isNumericType(string $type): bool
    {
        return str_contains($type, 'int')
            || str_contains($type, 'decimal')
            || str_contains($type, 'float')
            || str_contains($type, 'double')
            || str_contains($type, 'numeric');
    }

    protected function fallbackForType(string $type): mixed
    {
        if ($this->isNumericType($type)) {
            return 0;
        }

        if (str_starts_with($type, 'enum(')) {
            if (preg_match("/enum\\('([^']*)'/i", $type, $m)) {
                return $m[1];
            }

            return '0';
        }

        if (str_contains($type, 'datetime') || str_contains($type, 'timestamp')) {
            return now()->format('Y-m-d H:i:s');
        }

        if (str_contains($type, 'date')) {
            return now()->format('Y-m-d');
        }

        return '';
    }
}
