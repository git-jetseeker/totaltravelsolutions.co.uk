<?php

namespace Tests\Unit;

use App\Support\MailCredentials;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MailCredentialsTest extends TestCase
{
    public function test_environment_mail_settings_are_used_when_database_settings_are_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('settings')
            ->andReturn(false);

        config([
            'mail.mailers.smtp.host' => 'mail.example.com',
            'mail.mailers.smtp.port' => 465,
            'mail.mailers.smtp.encryption' => 'ssl',
            'mail.mailers.smtp.username' => 'bookings@example.com',
            'mail.mailers.smtp.password' => 'test-password',
            'mail.from.address' => 'bookings@example.com',
            'mail.from.name' => 'Example Travel',
        ]);

        $this->assertSame([
            'email_host' => 'mail.example.com',
            'email_port' => 465,
            'email_encryption_type' => 'ssl',
            'email_username' => 'bookings@example.com',
            'email_password' => 'test-password',
            'email_from_address' => 'bookings@example.com',
            'email_from_name' => 'Example Travel',
        ], MailCredentials::all());
    }
}
