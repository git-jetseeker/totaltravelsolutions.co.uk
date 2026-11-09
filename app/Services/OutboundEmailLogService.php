<?php

namespace App\Services;

use App\Models\airports_bookings;
use App\Models\email_logs;
use App\Models\email_templates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OutboundEmailLogService
{
    public function record(array $data): email_logs
    {
        if (! Schema::hasTable('email_logs')) {
            throw new \RuntimeException('The email_logs table is not available. Run database migrations.');
        }

        $recipients = $data['to_email'] ?? '';
        if (is_array($recipients)) {
            $recipients = implode(',', $recipients);
        }

        $template = ! empty($data['template_title'])
            ? email_templates::query()->where('title', $data['template_title'])->first()
            : null;
        $bookingReference = $data['booking_ref'] ?? null;
        $bookingId = $data['booking_id'] ?? null;

        if ($bookingId === null && $bookingReference !== null
            && Schema::hasTable('airports_bookings')
            && Schema::hasColumn('airports_bookings', 'referenceNo')) {
            $bookingId = airports_bookings::query()
                ->where('referenceNo', $bookingReference)
                ->value('id');
        }

        $payload = [
            'booking_id' => $bookingId,
            'booking_ref' => $bookingReference,
            'to_email' => $recipients,
            'cc' => $data['cc'] ?? null,
            'template_id' => $data['template_id'] ?? $template?->id,
            'subject' => $data['subject'] ?? $template?->subject,
            'body_excerpt' => Str::limit(strip_tags((string) ($data['body'] ?? '')), 500),
            'provider' => $data['provider'] ?? 'smtp',
            'provider_message_id' => $data['provider_message_id'] ?? null,
            'status' => $data['status'] ?? 'queued',
            'error_message' => $data['error_message'] ?? null,
            'triggered_by' => $data['triggered_by'] ?? 'system',
            'user_id' => $data['user_id'] ?? Auth::id(),
        ];

        if (Schema::hasColumn('email_logs', 'tracking_token')) {
            $payload['tracking_token'] = null;
        }
        if (Schema::hasColumn('email_logs', 'open_count')) {
            $payload['open_count'] = 0;
        }

        return email_logs::query()->create($payload);
    }
}
