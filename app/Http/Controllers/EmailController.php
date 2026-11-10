<?php



namespace App\Http\Controllers;

use App\Models\email_templates;
use App\Models\modules_settings;
use App\Models\settings;
use App\Support\MailCredentials;
use App\Services\OutboundEmailLogService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Google\Client as GoogleClient;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;

use Illuminate\Support\Facades\Config;
 
class EmailController extends Controller

{
    public $_setting = [];
    private bool $useGoogleApi = false;



  public function __construct()
{
    $this->_setting = MailCredentials::all();

    $encryption = strtolower((string) $this->credentialValue('email_encryption_type', env('MAIL_ENCRYPTION', 'tls')));
    $scheme = in_array($encryption, ['ssl', 'smtps'], true) ? 'smtps' : 'smtp';
    $fromAddress = $this->credentialValue(
        'email_from_address',
        $this->credentialValue('email_username', env('MAIL_FROM_ADDRESS', env('MAIL_USERNAME')))
    );
    $fromName = $this->credentialValue('email_from_name', env('MAIL_FROM_NAME', env('APP_NAME', 'Total Travel Solutions')));

    config([
        'mail.default' => 'smtp',
        'mail.mailers.smtp.transport' => 'smtp',
        'mail.mailers.smtp.scheme' => $scheme,
        'mail.mailers.smtp.host' => $this->credentialValue('email_host', env('MAIL_HOST')),
        'mail.mailers.smtp.port' => $this->credentialValue('email_port', env('MAIL_PORT', 587)),
        'mail.mailers.smtp.username' => $this->credentialValue('email_username', env('MAIL_USERNAME')),
        'mail.mailers.smtp.password' => $this->credentialValue('email_password', env('MAIL_PASSWORD')),
        'mail.from.address' => $fromAddress,
        'mail.from.name' => $fromName,
    ]);

    $hasSmtpCredentials = $this->hasSmtpCredentials($this->_setting);
    $this->useGoogleApi = ! $hasSmtpCredentials
        && MailCredentials::googleServiceAccount() !== null;

    Mail::purge('smtp');
    }

    private function hasSmtpCredentials(array $credentials): bool
    {
        return ! empty($credentials['email_host'])
            && ! empty($credentials['email_username'])
            && ! empty($credentials['email_password']);
    }

    private function credentialValue(string $key, $fallback)
    {
        $value = $this->_setting[$key] ?? null;

        return $value === null || $value === '' ? $fallback : $value;
    }

 public function testEmail()
{
   $toEmail = "mzt646@qmechanicspk.com";
    $subject = "Test Email from Laravel";
    $body = "This is a test email to check SMTP settings in Laravel.";

    try {
        if ($this->useGoogleApi) {
            $serviceAccount = MailCredentials::googleServiceAccount();
            $fromEmail = $this->credentialValue(
                'gmail_from_email',
                $this->credentialValue('email_from_address', $this->credentialValue('email_username', ''))
            );
            $fromName = $this->credentialValue(
                'gmail_from_name',
                $this->credentialValue('email_from_name', config('mail.from.name'))
            );

            if ($serviceAccount === null || trim((string) $fromEmail) === '') {
                throw new \RuntimeException('Gmail API credentials or sender address are not configured.');
            }

            $client = new GoogleClient();
            $client->setAuthConfig($serviceAccount);
            $client->setSubject((string) $fromEmail);
            $client->addScope(Gmail::GMAIL_SEND);

            $mimeMessage = (new MimeEmail())
                ->from(new Address((string) $fromEmail, (string) $fromName))
                ->to($toEmail)
                ->subject($subject)
                ->text($body);

            $gmailMessage = new Message();
            $gmailMessage->setRaw(rtrim(strtr(base64_encode($mimeMessage->toString()), '+/', '-_'), '='));

            $gmailService = new Gmail($client);
            $sentMessage = $gmailService->users_messages->send('me', $gmailMessage);
            $provider = 'gmail_api';
            $messageId = $sentMessage->getId();
        } else {
            $sentMessage = Mail::mailer('smtp')->raw($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
            $provider = 'smtp';
            $messageId = is_object($sentMessage) && method_exists($sentMessage, 'getMessageId')
                ? $sentMessage->getMessageId()
                : null;
        }

        return response()->json([
            'status' => 'sent',
            'message' => 'Test email was accepted by the mail provider.',
            'recipient' => $toEmail,
            'provider' => $provider,
            'message_id' => $messageId,
        ]);
    } catch (\Throwable $e) {
        Log::error('Test email delivery failed', [
            'recipient' => $toEmail,
            'exception' => get_class($e),
            'reason' => $e->getMessage(),
        ]);

        return response()->json([
            'status' => 'failed',
            'message' => 'Test email could not be sent. Check the application mail logs and configuration.',
            'recipient' => $toEmail,
        ], 500);
    }
}

 





    public function get_template($template_title, $template_data = [])

    {

        $template = email_templates::where("title", $template_title)->first();
        if (! $template) {
            Log::error('Email template not found', ['title' => $template_title]);
            return null;
        }

        $data = $template["description"];

        if (count($template_data) > 0) {

            foreach ($template_data as $key => $val) {

                $data = str_replace('[' . $key . ']', $val, $data);
            }
        }

        return ["data" => $data, "subject" => $template["subject"]];
    }



    public function send()

    {

        $template_data = [];

        $template_data["username"] = "Ali";

        $template_data["ref"] = "1000022222000";

        //$this->sendEmail("Update Booking", "pakingzone@gmail.com", "pakingzone@gmail.com", $template_data);
        $this->sendEmail("Update Booking", "helpdesk@quickparkgatwick.co.uk", "helpdesk@quickparkgatwick.co.uk", $template_data);
    }



public function sendEmail($template_title, $to, $template_data)
{
    try {
        $delivery = $this->deliverConfiguredEmail($template_title, $to, $template_data);

        $this->logNotificationEmail($template_title, $to, $template_data, $this->deliveryStatus(), null, false, $delivery);
        return response()->json(['message' => 'Email sent successfully']);
    } catch (\Throwable $e) {
        $this->logNotificationEmail($template_title, $to, $template_data, 'failed', $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


 



  public function sendEmailWithFile($template_title, $to, $template_data, $pathToFile)
{
    set_time_limit(0);
    try {
        $delivery = $this->deliverConfiguredEmail($template_title, $to, $template_data, $pathToFile);

        $this->logNotificationEmail($template_title, $to, $template_data, $this->deliveryStatus(), null, true, $delivery);
        return response()->json(['message' => 'Email sent successfully']);
    } catch (\Throwable $e) {
        $this->logNotificationEmail($template_title, $to, $template_data, 'failed', $e->getMessage(), true);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    
public function sendGmail($template_title, $to, $template_data): bool
{
    try {
        $delivery = $this->deliverConfiguredEmail($template_title, $to, $template_data);

        $this->logNotificationEmail($template_title, $to, $template_data, $this->deliveryStatus(), null, false, $delivery);
        return true;
    } catch (\Throwable $e) {
        $this->logNotificationEmail($template_title, $to, $template_data, 'failed', $e->getMessage());
        return false;
    }
}


public function sendGmailWithAttachment($template_title, $to, $template_data, $attachmentPath): bool
{
    try {
        $delivery = $this->deliverConfiguredEmail($template_title, $to, $template_data, $attachmentPath);

        $this->logNotificationEmail($template_title, $to, $template_data, $this->deliveryStatus(), null, true, $delivery);
        return true;
    } catch (\Throwable $e) {
        $this->logNotificationEmail($template_title, $to, $template_data, 'failed', $e->getMessage(), true);
        return false;
    }
}

private function deliverConfiguredEmail($templateTitle, $recipients, array $templateData, ?string $attachmentPath = null): array
{
    $template = $this->get_template($templateTitle, $templateData);
    if (! is_array($template) || ! isset($template['subject'], $template['data'])) {
        throw new \RuntimeException("Email template [{$templateTitle}] is missing or incomplete.");
    }

    if ($attachmentPath !== null && (! is_file($attachmentPath) || ! is_readable($attachmentPath))) {
        throw new \RuntimeException('Email attachment is missing or unreadable.');
    }

    $recipients = is_array($recipients) ? $recipients : [$recipients];
    $recipients = array_values(array_filter(array_map('trim', array_map('strval', $recipients))));
    if ($recipients === []) {
        throw new \InvalidArgumentException('At least one email recipient is required.');
    }

    if (! $this->useGoogleApi) {
        $sentMessage = Mail::mailer('smtp')->send([], [], function ($message) use (
            $template,
            $recipients,
            $attachmentPath,
            $templateTitle,
            $templateData
        ) {
            $message->to($recipients)
                ->subject($template['subject'])
                ->html($template['data'])
                ->from(config('mail.from.address'), config('mail.from.name'));

            $headers = $message->getSymfonyMessage()->getHeaders();
            $headers->addTextHeader('X-Email-Template', (string) $templateTitle);
            if (! empty($templateData['ref'])) {
                $headers->addTextHeader('X-Booking-Reference', (string) $templateData['ref']);
            }

            if ($attachmentPath !== null) {
                $message->attach($attachmentPath);
            }
        });

        return [
            'provider' => 'smtp',
            'provider_message_id' => is_object($sentMessage) && method_exists($sentMessage, 'getMessageId')
                ? $sentMessage->getMessageId()
                : null,
        ];
    }

    $serviceAccount = MailCredentials::googleServiceAccount();
    $fromEmail = $this->credentialValue(
        'gmail_from_email',
        $this->credentialValue('email_from_address', $this->credentialValue('email_username', ''))
    );
    $fromName = $this->credentialValue(
        'gmail_from_name',
        $this->credentialValue('email_from_name', config('mail.from.name'))
    );

    if ($serviceAccount === null || trim((string) $fromEmail) === '') {
        throw new \RuntimeException('Gmail API credentials or sender address are not configured.');
    }

    $client = new GoogleClient();
    $client->setAuthConfig($serviceAccount);
    $client->setSubject((string) $fromEmail);
    $client->addScope(Gmail::GMAIL_SEND);

    $mimeMessage = (new MimeEmail())
        ->from(new Address((string) $fromEmail, (string) $fromName))
        ->to(...$recipients)
        ->subject((string) $template['subject'])
        ->html((string) $template['data']);

    if ($attachmentPath !== null) {
        $mimeMessage->attachFromPath($attachmentPath);
    }

    $gmailMessage = new Message();
    $gmailMessage->setRaw(rtrim(strtr(base64_encode($mimeMessage->toString()), '+/', '-_'), '='));

    $gmailService = new Gmail($client);
    $sentMessage = $gmailService->users_messages->send('me', $gmailMessage);

    return [
        'provider' => 'gmail_api',
        'provider_message_id' => is_object($sentMessage) && method_exists($sentMessage, 'getId')
            ? $sentMessage->getId()
            : null,
    ];
}

private function deliveryStatus(): string
{
    return $this->useGoogleApi ? 'accepted_by_gmail_api' : 'accepted_by_mail_transport';
}


private function logNotificationEmail(
    string $templateTitle,
    $recipients,
    array $templateData,
    string $status,
    ?string $reason = null,
    bool $hasAttachment = false,
    array $delivery = []
): void {
    $recipients = is_array($recipients) ? $recipients : [$recipients];
    $maskedRecipients = array_map(function ($recipient) {
        $recipient = trim((string) $recipient);
        if (strpos($recipient, '@') === false) {
            return $recipient === '' ? '[empty]' : substr($recipient, 0, 1) . '***';
        }

        [$localPart, $domain] = explode('@', $recipient, 2);
        return substr($localPart, 0, 1) . '***@' . $domain;
    }, $recipients);

    $context = [
        'channel' => 'email',
        'status' => $status,
        'booking_reference' => $templateData['ref'] ?? null,
        'template' => $templateTitle,
        'recipients' => $maskedRecipients,
        'attachment' => $hasAttachment,
    ];

    if ($reason !== null) {
        $context['reason'] = $reason;
    }

    Log::channel('notifications')->log(
        $status === 'failed' ? 'error' : 'info',
        'Outbound email notification',
        $context
    );

    try {
        if ($status !== 'failed' && ($delivery['provider'] ?? null) !== 'gmail_api') {
            return;
        }

        $template = $this->get_template($templateTitle, $templateData);
        app(OutboundEmailLogService::class)->record([
            'booking_ref' => $templateData['ref'] ?? null,
            'to_email' => $recipients,
            'template_title' => $templateTitle,
            'subject' => is_array($template) ? ($template['subject'] ?? null) : null,
            'body' => is_array($template) ? ($template['data'] ?? '') : '',
            'provider' => $delivery['provider'] ?? ($this->useGoogleApi ? 'gmail_api' : 'smtp'),
            'provider_message_id' => $delivery['provider_message_id'] ?? null,
            'status' => $status === 'failed' ? 'failed' : 'sent',
            'error_message' => $reason,
            'triggered_by' => 'system',
        ]);
    } catch (\Throwable $e) {
        Log::error('Unable to create email_logs record', [
            'booking_reference' => $templateData['ref'] ?? null,
            'template' => $templateTitle,
            'exception' => get_class($e),
            'reason' => $e->getMessage(),
        ]);
    }
}

    
    
    private function createMessage($fromName, $fromEmail, $to, $subject, $body)
    {
        $email = new Message();
        $rawMessage = $this->createRawMessage($fromName, $fromEmail, $to, $subject, $body);
    
        $email->setRaw($rawMessage);
        $email->setLabelIds(['SENT']);
    
        return $email;
    }
    
    private function createRawMessage($fromName, $fromEmail, $to, $subject, $body)
    {
        $message = "From: \"$fromName\" <$fromEmail>\r\n";
        $message .= "To: $to\r\n";
        $message .= "Subject: $subject\r\n";
        $message .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
        $message .= $body;
    
        return base64_encode($message);
    }

     
    private function createMessageWithAttachment($fromName, $fromEmail, $to, $subject, $body, $attachmentPath)
    {
        $email = new Message();
        $rawMessage = $this->createRawMessageWithAttachment($fromName, $fromEmail, $to, $subject, $body, $attachmentPath);
        
        $email->setRaw($rawMessage);
        $email->setLabelIds(['SENT']);
        
        return $email;
    }
    
    private function createRawMessageWithAttachment($fromName, $fromEmail, $to, $subject, $body, $attachmentPath)
    {
        $boundary = uniqid(rand(), true);
        
        // Create the main part of the message
        $message = "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=utf-8\r\n";
        $message .= "\r\n";
        $message .= $body . "\r\n";
        
        // Add the attachment
        $attachmentContent = file_get_contents($attachmentPath);
        $base64Attachment = base64_encode($attachmentContent);
        $attachmentName = pathinfo($attachmentPath, PATHINFO_BASENAME);
        
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: application/octet-stream; name=\"{$attachmentName}\"\r\n";
        $message .= "Content-Disposition: attachment; filename=\"{$attachmentName}\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "\r\n";
        $message .= $base64Attachment . "\r\n";
        
        $message .= "--{$boundary}--\r\n";
        
        // Construct the raw message
        $rawMessage = "From: \"$fromName\" <$fromEmail>\r\n";
        $rawMessage .= "To: $to\r\n";
        $rawMessage .= "Subject: $subject\r\n";
        $rawMessage .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n\r\n";
        $rawMessage .= $message;
        
        return base64_encode($rawMessage);
    }
}
