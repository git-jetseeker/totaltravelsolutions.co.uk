<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class MailSettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('settings')) {
            throw new RuntimeException('The settings table must exist before mail settings can be seeded.');
        }

        $settings = [
            'email_host' => config('mail.mailers.smtp.host'),
            'email_port' => config('mail.mailers.smtp.port'),
            'email_encryption_type' => config('mail.mailers.smtp.encryption'),
            'email_username' => config('mail.mailers.smtp.username'),
            'email_password' => config('mail.mailers.smtp.password'),
            'email_from_address' => config('mail.from.address'),
            'email_from_name' => config('mail.from.name'),
        ];

        foreach (['email_host', 'email_port', 'email_username', 'email_password'] as $requiredSetting) {
            if ($settings[$requiredSetting] === null || $settings[$requiredSetting] === '') {
                throw new RuntimeException("The {$requiredSetting} mail setting must be configured before seeding.");
            }
        }

        foreach ($settings as $fieldName => $fieldValue) {
            if ($fieldValue === null || $fieldValue === '') {
                continue;
            }

            DB::table('settings')->updateOrInsert(
                ['agent_id' => 1, 'field_name' => $fieldName],
                ['field_value' => (string) $fieldValue]
            );
        }
    }
}
