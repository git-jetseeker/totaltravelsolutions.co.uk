<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class IncludeSiteName
{
    /**
     * Add the site identity and exception code to Slack log messages.
     * Logging must remain available if Slack context cannot be resolved.
     */
    public function __invoke(Logger $logger): void
    {
        try {
            foreach ($logger->getHandlers() as $handler) {
                $handler->pushProcessor(function ($record) {
                    try {
                        [$siteKey, $siteLabel] = $this->resolveSite();
                        $context = is_array($record)
                            ? (isset($record['context']) && is_array($record['context']) ? $record['context'] : [])
                            : (isset($record->context) && is_array($record->context) ? $record->context : []);
                        $exception = isset($context['exception']) ? $context['exception'] : null;
                        $errorCode = $exception instanceof HttpExceptionInterface
                            ? $exception->getStatusCode()
                            : ($exception instanceof Throwable ? $exception->getCode() : null);
                        $host = '';

                        try {
                            if (function_exists('request') && request()) {
                                $host = (string) (request()->getHost() ?: '');
                            }
                        } catch (Throwable $e) {
                            $host = '';
                        }

                        $prefix = $host !== ''
                            ? sprintf('[%s | %s]', $siteLabel, $host)
                            : sprintf('[%s]', $siteLabel);
                        $message = is_array($record) ? $record['message'] : $record->message;
                        $message = $prefix.($errorCode !== null ? ' [Error code: '.$errorCode.']' : '').' '.$message;
                        $extra = is_array($record)
                            ? (isset($record['extra']) && is_array($record['extra']) ? $record['extra'] : [])
                            : (isset($record->extra) && is_array($record->extra) ? $record->extra : []);
                        $extra = array_merge($extra, [
                            'site' => $siteLabel,
                            'app_name' => (string) config('app.name', 'Laravel'),
                            'app_url' => (string) config('app.url', ''),
                            'host' => $host,
                        ]);

                        if ($siteKey !== '') {
                            $extra['site_key'] = $siteKey;
                        }

                        if ($errorCode !== null) {
                            $extra['error_code'] = $errorCode;
                        }

                        if (is_array($record)) {
                            $record['message'] = $message;
                            $record['extra'] = $extra;

                            return $record;
                        }

                        if (is_object($record) && method_exists($record, 'with')) {
                            return call_user_func_array([$record, 'with'], [
                                'message' => $message,
                                'extra' => $extra,
                            ]);
                        }

                        return $record;
                    } catch (Throwable $e) {
                        return $record;
                    }
                });
            }
        } catch (Throwable $e) {
            // Logging must remain available if processor setup fails.
        }
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function resolveSite(): array
    {
        $siteLabel = trim((string) config('app.name', 'Laravel'));

        return ['', $siteLabel !== '' ? $siteLabel : 'Laravel'];
    }
}
