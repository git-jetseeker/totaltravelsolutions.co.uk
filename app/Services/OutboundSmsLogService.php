<?php

namespace App\Services;

use App\Models\airports_bookings;
use App\Models\sms_logs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OutboundSmsLogService
{
    public function record(array $data): void
    {
        try {
            if (! Schema::hasTable('sms_logs')) {
                throw new \RuntimeException('The sms_logs table is not available. Run database migrations.');
            }

            $bookingId = $data['booking_id'] ?? null;
            $bookingReference = $data['booking_ref'] ?? null;
            if ($bookingId === null && $bookingReference !== null
                && Schema::hasTable('airports_bookings')
                && Schema::hasColumn('airports_bookings', 'referenceNo')) {
                $bookingId = airports_bookings::query()
                    ->where('referenceNo', $bookingReference)
                    ->value('id');
            }

            sms_logs::query()->create([
                'booking_id' => $bookingId,
                'booking_ref' => $bookingReference,
                'to_number' => $data['to_number'] ?? null,
                'template_id' => $data['template_id'] ?? null,
                'body' => $data['body'] ?? null,
                'provider' => $data['provider'] ?? 'voodoo_sms',
                'provider_message_id' => $data['provider_message_id'] ?? null,
                'status' => $data['status'] ?? 'queued',
                'error_message' => $data['error_message'] ?? null,
                'triggered_by' => $data['triggered_by'] ?? 'system',
                'user_id' => $data['user_id'] ?? Auth::id(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Unable to create sms_logs record', [
                'booking_reference' => $data['booking_ref'] ?? null,
                'exception' => get_class($exception),
                'reason' => $exception->getMessage(),
            ]);
        }
    }
}
