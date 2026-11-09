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
            Log::warning('Unable to load mail settings from the database; trying the credential-file fallback.', [
                'exception' => get_class($exception),
            ]);
        }

        $file = self::fromFile();
        foreach ($file as $key => $value) {
            $databaseValue = $database[$key] ?? null;
            if ($databaseValue === null || $databaseValue === '' || $databaseValue === []) {
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

    private static function fromFile(): array
    {
        $path = config('mail.credentials_path', storage_path('app/email_credentials.json'));
        if (! is_file($path) || ! is_readable($path)) {
            return [];
        }

        try {
            $credentials = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            Log::warning('Mail credentials fallback file contains invalid JSON.');
            return [];
        }

        if (! is_array($credentials)) {
            Log::warning('Mail credentials fallback file must contain a JSON object.');
            return [];
        }

        if (array_key_exists('gmail_service_account', $credentials)) {
            $credentials['gmail_service_account_json'] = $credentials['gmail_service_account'];
            unset($credentials['gmail_service_account']);
        }

        return array_intersect_key($credentials, array_fill_keys(self::SETTING_KEYS, true));
    }
}
