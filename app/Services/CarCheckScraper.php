<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * UK vehicle lookup by registration (make / model / colour).
 *
 * Primary: Carwow public car-check JSON used by
 * https://www.carwow.co.uk/car-tools/check/{vrm}
 *   → GET /car-tools/api/car-check?vrm=...
 *
 * Fallback: Total Car Check FreeCheck HTML scrape
 *   → https://totalcarcheck.co.uk/FreeCheck?regno=...
 */
class CarCheckScraper
{
    protected string $carwowApiUrl = 'https://www.carwow.co.uk/car-tools/api/car-check';

    protected string $totalCarCheckBase = 'https://totalcarcheck.co.uk';

    protected ?string $lastEffectiveUrl = null;

    protected int $lastHttpCode = 0;

    protected string $cookieFile;

    public function __construct()
    {
        $this->cookieFile = storage_path('app/cookies_vehicle_lookup.txt');
    }

    /**
     * Look up make, model, and colour by registration number.
     */
    public function scrape(string $registration): ?array
    {
        $registration = $this->normalizeRegistration($registration);

        // UK private / dateless plates can be short (e.g. 4SMS, A1).
        if ($registration === '' || strlen($registration) < 2) {
            return null;
        }

        try {
            $fromCarwow = $this->fromCarwow($registration);
            if ($this->isValidVehicleResult($fromCarwow)) {
                Log::info("Vehicle lookup succeeded via [carwow] for {$registration}");
                return $fromCarwow;
            }

            $fromTcc = $this->fromTotalCarCheck($registration);
            if ($this->isValidVehicleResult($fromTcc)) {
                Log::info("Vehicle lookup succeeded via [totalcarcheck] for {$registration}");
                return $fromTcc;
            }

            Log::error("Failed to fetch vehicle: {$registration} from all scrapers");
            return null;
        } catch (\Throwable $e) {
            Log::error("Error scraping {$registration}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Carwow JSON endpoint (same call their check page makes in the browser).
     */
    protected function fromCarwow(string $registration): ?array
    {
        $url = $this->carwowApiUrl . '?vrm=' . urlencode($registration);
        Log::info("Trying Carwow URL: {$url}");

        $body = $this->executeCurlRequest($url, 'gzip,deflate', [
            'Accept: application/json,text/plain,*/*',
            'Referer: https://www.carwow.co.uk/car-tools/check/' . strtolower($registration),
            'Origin: https://www.carwow.co.uk',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-origin',
        ]);

        if ($body === null) {
            $body = $this->executeCurlRequest($url, 'identity', [
                'Accept: application/json,text/plain,*/*',
                'Referer: https://www.carwow.co.uk/car-tools/check/' . strtolower($registration),
                'Origin: https://www.carwow.co.uk',
                'Accept-Encoding: identity',
            ]);
        }

        if (!$body) {
            Log::warning("Carwow empty response for {$registration} (HTTP {$this->lastHttpCode})");
            return null;
        }

        if ($this->isBlockedResponse($body)) {
            Log::warning("Carwow blocked for {$registration} (HTTP {$this->lastHttpCode})");
            return null;
        }

        $json = json_decode($body, true);
        if (!is_array($json) || empty($json['success']) || empty($json['data']) || !is_array($json['data'])) {
            $msg = is_array($json) ? (string) data_get($json, 'error.message', 'unknown') : 'invalid json';
            Log::warning("Carwow lookup failed for {$registration}: {$msg}");
            return null;
        }

        $data = $json['data'];

        return $this->buildResult(
            $registration,
            (string) ($data['make'] ?? ''),
            (string) ($data['model'] ?? ''),
            (string) ($data['colour'] ?? $data['color'] ?? '')
        );
    }

    /**
     * Total Car Check FreeCheck HTML (may 403 on some hosting IPs).
     */
    protected function fromTotalCarCheck(string $registration): ?array
    {
        $url = $this->totalCarCheckBase . '/FreeCheck?regno=' . urlencode($registration);
        Log::info("Trying TotalCarCheck URL: {$url}");

        $this->warmUpTotalCarCheck();
        $html = $this->fetchHtml($url, $this->totalCarCheckBase . '/');

        if (!$html) {
            Log::warning("TotalCarCheck fetch failed for {$registration} (HTTP {$this->lastHttpCode})");
            return null;
        }

        if ($this->isBlockedResponse($html) || $this->lastHttpCode === 403) {
            Log::warning("TotalCarCheck access blocked for: {$registration} (HTTP {$this->lastHttpCode})");
            return null;
        }

        if ($this->isNotFoundResponse($html, $this->lastEffectiveUrl)) {
            Log::warning("TotalCarCheck vehicle not found: {$registration}");
            return null;
        }

        $details = $this->extractFromTotalCarCheckHtml($html, $registration);

        return $this->isValidVehicleResult($details) ? $details : null;
    }

    protected function warmUpTotalCarCheck(): void
    {
        $this->fetchHtml($this->totalCarCheckBase . '/', null);
        usleep(150000);
    }

    protected function buildResult(string $registration, string $make, string $model, string $colour): array
    {
        return [
            'registration' => $registration,
            'make' => $this->prettyValue($make) ?: 'TBA',
            'model' => $this->prettyValue($model) ?: 'TBA',
            'color' => $this->prettyValue($colour) ?: 'TBA',
            'scraped_at' => now()->toDateTimeString(),
        ];
    }

    protected function prettyValue(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        // Keep short tokens uppercase (e.g. BMW); title-case longer names.
        if (strlen($value) <= 3 && !str_contains($value, ' ')) {
            return strtoupper($value);
        }

        return ucwords(strtolower($value));
    }

    protected function isBlockedResponse(string $html): bool
    {
        $haystack = strtolower($html);

        if (
            (str_contains($haystack, 'table-freecheck') && str_contains($haystack, 'cert-label'))
            || (str_contains($haystack, '"success":true') && str_contains($haystack, '"make"'))
        ) {
            return false;
        }

        return str_contains($haystack, 'just a moment')
            || str_contains($haystack, '_cf_chl_opt')
            || str_contains($haystack, 'cf-chl')
            || str_contains($haystack, 'challenge-platform')
            || str_contains($haystack, 'challenges.cloudflare.com')
            || str_contains($haystack, 'cdn-cgi/challenge')
            || str_contains($haystack, 'attention required')
            || str_contains($haystack, 'access denied')
            || str_contains($haystack, 'sorry, you have been blocked')
            || str_contains($haystack, 'request blocked')
            || (str_contains($haystack, 'cloudflare') && str_contains($haystack, 'enable javascript'));
    }

    protected function isNotFoundResponse(string $html, ?string $effectiveUrl = null): bool
    {
        if ($effectiveUrl) {
            $url = strtolower($effectiveUrl);
            if (str_contains($url, 'not-found') || str_contains($url, 'exception=')) {
                return true;
            }
        }

        $markers = [
            'car not found',
            'vehicle not found',
            'invalid registration',
            'no results found',
            'no vehicle found',
            'registration not found',
            'we could not find a vehicle',
            'cannot find a vehicle',
            'unable to find',
            'vehicle details are not available',
        ];

        $haystack = strtolower($html);
        foreach ($markers as $marker) {
            if (str_contains($haystack, $marker)) {
                return true;
            }
        }

        return false;
    }

    protected function isValidVehicleResult(?array $result): bool
    {
        if (!$result) {
            return false;
        }

        $make = strtoupper(trim((string) ($result['make'] ?? '')));
        $model = strtoupper(trim((string) ($result['model'] ?? '')));

        $invalidMakes = ['', 'TBA', 'CAR', 'CAR CHECK', 'CARCHECK', 'TOTAL CAR CHECK', 'CARWOW', 'N/A', 'NA', 'UNKNOWN'];
        $invalidModels = ['CAR', 'CAR CHECK', 'CARCHECK', 'TOTAL CAR CHECK', 'N/A', 'NA', 'UNKNOWN'];

        if (in_array($make, $invalidMakes, true)) {
            return false;
        }

        if ($model !== '' && $model !== 'TBA' && (in_array($model, $invalidModels, true) || str_contains($model, 'CAR CHECK'))) {
            return false;
        }

        return true;
    }

    protected function fetchHtml(string $url, ?string $referer = null): ?string
    {
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8',
            'Upgrade-Insecure-Requests: 1',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-User: ?1',
            'sec-ch-ua: "Chromium";v="122", "Not(A:Brand";v="24", "Google Chrome";v="122"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
        ];

        if ($referer) {
            $headers[] = 'Referer: ' . $referer;
            $headers[] = 'Sec-Fetch-Site: same-origin';
        } else {
            $headers[] = 'Sec-Fetch-Site: none';
        }

        $html = $this->executeCurlRequest($url, 'gzip,deflate', $headers);
        if ($html === null && $this->lastHttpCode !== 403) {
            $headers[] = 'Accept-Encoding: identity';
            $html = $this->executeCurlRequest($url, 'identity', $headers);
        }

        return $html;
    }

    protected function executeCurlRequest(string $url, string $encoding, array $headers = []): ?string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_ENCODING, $encoding === 'identity' ? 'identity' : '');

        if (!file_exists($this->cookieFile)) {
            touch($this->cookieFile);
        }
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);

        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
        );

        $baseHeaders = [
            'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8',
            'Connection: keep-alive',
            'Cache-Control: no-cache',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, array_values(array_unique(array_merge($baseHeaders, $headers))));

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

        $this->lastHttpCode = $httpCode;
        $this->lastEffectiveUrl = $effectiveUrl ?: null;

        curl_close($ch);

        Log::info("cURL response: HTTP {$httpCode}, Content-Type: {$contentType}, encoding={$encoding}, url=" . ($effectiveUrl ?: $url));

        if ($errno === 61 || stripos($error, 'content encoding') !== false) {
            Log::warning("cURL encoding error ({$errno}): {$error}");
            return null;
        }

        if ($errno !== 0) {
            Log::warning("cURL failed: HTTP {$httpCode}, Error ({$errno}): {$error}");
            return null;
        }

        if ($httpCode >= 200 && $httpCode < 400 && $body) {
            return $body;
        }

        if ($body && strlen($body) > 200) {
            return $body;
        }

        Log::warning("cURL failed: HTTP {$httpCode}, Error: {$error}");
        return null;
    }

    protected function extractFromTotalCarCheckHtml(string $html, string $registration): array
    {
        $result = [
            'registration' => $registration,
            'make' => 'TBA',
            'model' => 'TBA',
            'color' => 'TBA',
            'scraped_at' => now()->toDateTimeString(),
        ];

        try {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);
            $rows = $xpath->query('//table[contains(@class,"table-freecheck") or contains(@class,"table")]//tr');

            if ($rows) {
                foreach ($rows as $tr) {
                    $labelNode = $xpath->query('.//*[contains(@class,"cert-label")]', $tr)->item(0);
                    if (!$labelNode) {
                        continue;
                    }

                    $valueNode = $xpath->query('.//*[contains(@class,"cert-data-text")]', $tr)->item(0);
                    $label = strtolower(trim($labelNode->textContent ?? ''));
                    $value = $valueNode ? trim($valueNode->textContent ?? '') : '';
                    if ($value === '') {
                        continue;
                    }

                    if ((str_contains($label, 'manufacturer') || $label === 'make') && $result['make'] === 'TBA') {
                        $result['make'] = $value;
                    } elseif (($label === 'model' || (str_contains($label, 'model') && !str_contains($label, 'detail'))) && $result['model'] === 'TBA') {
                        $result['model'] = $value;
                    } elseif ((str_contains($label, 'colour') || str_contains($label, 'color')) && $result['color'] === 'TBA') {
                        $result['color'] = $value;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::debug('extractFromTotalCarCheckHtml failed: ' . $e->getMessage());
        }

        Log::info("Extracted: Make={$result['make']}, Model={$result['model']}, Color={$result['color']}");

        return $result;
    }

    protected function normalizeRegistration(string $registration): string
    {
        return strtoupper(preg_replace('/\s+/', '', $registration) ?? '');
    }

    public function getVehicleDetails(string $registration, int $cacheMinutes = 60): ?array
    {
        $registration = $this->normalizeRegistration($registration);
        $cacheKey = 'vehicle_details_' . $registration;

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if ($this->isValidVehicleResult($cached)) {
                Log::info("Returning cached data for: {$registration}");
                return $cached;
            }
            Cache::forget($cacheKey);
        }

        Log::info("Cache miss for: {$registration}, scraping...");
        $data = $this->scrape($registration);

        if ($this->isValidVehicleResult($data)) {
            Log::info("Caching data for: {$registration}");
            Cache::put($cacheKey, $data, $cacheMinutes * 60);
            return $data;
        }

        Cache::forget($cacheKey);
        return null;
    }

    public function clearCache(string $registration): void
    {
        $cacheKey = 'vehicle_details_' . $this->normalizeRegistration($registration);
        Cache::forget($cacheKey);
        Log::info("Cache cleared for: {$registration}");
    }
}
