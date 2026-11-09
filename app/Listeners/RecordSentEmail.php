<?php

namespace App\Listeners;

use App\Services\OutboundEmailLogService;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Email;

class RecordSentEmail
{
    public function handle(MessageSent $event): void
    {
        try {
            $sentMessage = $event->sent;
            $message = $sentMessage->getOriginalMessage();
            if (! $message instanceof Email) {
                return;
            }

            $body = $message->getHtmlBody();
            if (! is_string($body)) {
                $body = is_string($message->getTextBody()) ? $message->getTextBody() : '';
            }

            $reference = $message->getHeaders()->has('X-Booking-Reference')
                ? (string) $message->getHeaders()->get('X-Booking-Reference')->getBodyAsString()
                : null;
            if ($reference === null && preg_match('/\bTTS-[A-Z0-9-]+\b/i', strip_tags($body), $matches)) {
                $reference = $matches[0];
            }

            $template = $message->getHeaders()->has('X-Email-Template')
                ? (string) $message->getHeaders()->get('X-Email-Template')->getBodyAsString()
                : null;

            $cc = array_map(
                static fn ($address) => $address->getAddress(),
                $message->getCc()
            );

            app(OutboundEmailLogService::class)->record([
                'booking_ref' => $reference,
                'to_email' => array_map(
                    static fn ($address) => $address->getAddress(),
                    $message->getTo()
                ),
                'cc' => $cc === [] ? null : implode(',', $cc),
                'template_title' => $template,
                'subject' => $message->getSubject(),
                'body' => $body,
                'provider' => 'laravel_mailer',
                'provider_message_id' => $sentMessage->getMessageId(),
                'status' => 'sent',
                'triggered_by' => 'system',
            ]);
        } catch (\Throwable $exception) {
            Log::error('Unable to record globally sent email', [
                'exception' => get_class($exception),
                'reason' => $exception->getMessage(),
            ]);
        }
    }
}
