<?php

namespace App\Support;

use App\Models\settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use JsonException;

class MailCredentials
{
    private const SETTING_KEYS = [
        'email_host',
        'email_port',
        'email_encryption_type',
        'email_username',
        'email_password',
        'email_from_address',
        'email_from_name',
        'gmail_from_email',
        'gmail_from_name',
        'gmail_service_account_json',
    ];

    public static function all(): array
    {
        $database = [];
        try {
            if (Schema::hasTable('settings')) {
                $database = settings::query()
                    ->where('agent_id', 1)
                    ->whereIn('field_name', self::SETTING_KEYS)
                    ->pluck('field_value', 'field_name')
                    ->toArray();
            }
        } catch (\Throwable $exception) {
            Log::warning('Unable to load mail settings from the database; using environment configuration.', [
                'exception' => get_class($exception),
            ]);
        }

        $environment = [
            'email_host' => config('mail.mailers.smtp.host'),
            'email_port' => config('mail.mailers.smtp.port'),
            'email_encryption_type' => config('mail.mailers.smtp.encryption'),
            'email_username' => config('mail.mailers.smtp.username'),
            'email_password' => config('mail.mailers.smtp.password'),
            'email_from_address' => config('mail.from.address'),
            'email_from_name' => config('mail.from.name'),
        ];

        foreach ($environment as $key => $value) {
            $databaseValue = $database[$key] ?? null;
            if (($databaseValue === null || $databaseValue === '' || $databaseValue === []) && $value !== null) {
                $database[$key] = $value;
            }
        }

        return $database;
    }

    public static function googleServiceAccount(): ?array
    {
        $value = self::all()['gmail_service_account_json'] ?? null;
        if (is_array($value) && ($value['type'] ?? null) === 'service_account') {
            return $value;
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                if (is_array($decoded) && ($decoded['type'] ?? null) === 'service_account') {
                    return $decoded;
                }
            } catch (JsonException $exception) {
                Log::warning('Gmail service-account settings contain invalid JSON.');
            }
        }

        return null;
    }
}
