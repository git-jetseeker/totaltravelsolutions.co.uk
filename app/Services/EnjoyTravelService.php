<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EnjoyTravelService
{
    protected $baseUrl;
    protected $authUrl;
    protected $username;
    protected $password;
    protected $clientId;
    protected $clientSecret;
    protected $source;
    protected $scope;
    protected $lang;
    protected $currency;
    protected $locationId;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.enjoytravel.base_url'), '/');
        $this->authUrl = rtrim((string) config('services.enjoytravel.auth_url'), '/');
        $this->username = (string) config('services.enjoytravel.username');
        $this->password = (string) config('services.enjoytravel.password');
        $this->clientId = (string) config('services.enjoytravel.client_id');
        $this->clientSecret = (string) config('services.enjoytravel.client_secret');
        $this->source = (string) config('services.enjoytravel.source', $this->clientId);
        $this->scope = (string) config('services.enjoytravel.scope', 'Enjoy.API');
        $this->lang = (string) config('services.enjoytravel.lang', 'en-gb');
        $this->currency = (string) config('services.enjoytravel.currency', 'GBP');
        $this->locationId = (int) config('services.enjoytravel.location_id', 7857);
        if ($this->source === '') {
            $this->source = $this->clientId;
        }
    }

    public static function isEnjoyTravelApi($api)
    {
        if (!is_string($api)) {
            return false;
        }

        $api = strtolower(trim($api));
        return in_array($api, ['enjoytravel', 'enjoy travel', 'enjoy', 'enj'], true);
    }

    public static function isEnjoyTravelBooking($booking)
    {
        if (!$booking) {
            return false;
        }

        if (self::isEnjoyTravelApi($booking->carhire_api ?? null)) {
            return true;
        }

        $code = (string) ($booking->product_code ?? '');
        if ($code !== '' && stripos($code, 'ENJ') === 0) {
            return true;
        }

        $extRef = (string) ($booking->ext_ref ?? '');
        if ($extRef !== '' && preg_match('/^ENJ-?\d+/i', $extRef)) {
            return true;
        }

        if (!empty($booking->enjoytravel_access_token)) {
            return true;
        }

        $enjoyPayload = trim((string) ($booking->enjoytravel_api_res ?? ''));
        return $enjoyPayload !== '' && $enjoyPayload !== 'null' && $enjoyPayload !== '[]' && $enjoyPayload !== '{}';
    }

    /**
     * Numeric reservation id used by GET/DELETE /bookings.
     * Docs: path is the number without the ENJ- prefix.
     */
    public static function resolveReservationNumber($booking)
    {
        $candidates = [
            $booking->ext_ref ?? '',
            $booking->supplier_reference ?? '',
            $booking->referenceNo ?? '',
        ];

        $payload = self::decodeStoredPayload($booking);
        foreach (['reservNum', 'reservationNumber', 'reservation_number'] as $key) {
            if (!empty($payload[$key])) {
                array_unshift($candidates, $payload[$key]);
            }
        }

        foreach ($candidates as $candidate) {
            $number = self::normalizeReservationNumber($candidate);
            if ($number !== '') {
                return $number;
            }
        }

        return '';
    }

    public static function normalizeReservationNumber($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/(?:ENJ-?)?(\d{5,})/i', $value, $m)) {
            return $m[1];
        }

        return ctype_digit($value) ? $value : '';
    }

    /**
     * Per-booking access token required by GET /bookings/{id} and DELETE /bookings.
     * This is not the OAuth token.
     */
    public static function resolveBookingAccessToken($booking)
    {
        if (!$booking) {
            return '';
        }

        $direct = trim((string) ($booking->enjoytravel_access_token ?? ''));
        if ($direct !== '') {
            return $direct;
        }

        $payload = self::decodeStoredPayload($booking);
        foreach (['accessToken', 'access_token', 'AccessToken'] as $key) {
            if (!empty($payload[$key]) && is_string($payload[$key])) {
                return trim($payload[$key]);
            }
        }

        if (self::isEnjoyTravelApi($booking->carhire_api ?? null)) {
            $token = trim((string) ($booking->token ?? ''));
            if ($token !== '') {
                return $token;
            }
        }

        return '';
    }

    public static function log($level, $message, array $context = [])
    {
        $allowed = ['debug', 'info', 'notice', 'warning', 'error', 'critical'];
        $level = in_array($level, $allowed, true) ? $level : 'info';
        Log::channel('carhire')->{$level}($message, self::safeContext($context));
    }

    public function ping()
    {
        self::log('info', 'Enjoy Travel ping started');
        $result = $this->request('GET', '/api/Diagnostics/ping');
        self::log($result['ok'] ? 'info' : 'error', 'Enjoy Travel ping finished', [
            'ok' => $result['ok'] ?? false,
            'http_code' => $result['http_code'] ?? 0,
            'message' => $result['message'] ?? '',
        ]);

        return $result;
    }

    public function searchLocations($query, $lang = null)
    {
        self::log('info', 'Enjoy Travel location search', ['query' => $query, 'lang' => $lang ?: $this->lang]);

        return $this->request('GET', '/locations/location-search', [], [
            'query' => $query,
            'lang' => $lang ?: $this->lang,
        ]);
    }

    public function getCountries($lang = null)
    {
        return $this->request('GET', '/locations/countries', [], [
            'language' => $lang ?: $this->lang,
        ]);
    }

    /**
     * Search live car-hire rates.
     * locationId is Enjoy Travel location.id from /locations/*.
     */
    public function searchAvailability($pickupLocationId, $pickupDateTime, $dropoffLocationId, $dropoffDateTime, array $extra = [])
    {
        $body = array_merge([
            'lang' => $this->lang,
            'source' => $this->source,
            'pickUp' => [
                'dateTime' => $pickupDateTime,
                'location' => ['id' => (int) $pickupLocationId],
            ],
            'dropOff' => [
                'dateTime' => $dropoffDateTime,
                'location' => ['id' => (int) $dropoffLocationId],
            ],
            'driverAge' => 33,
            'residenceCountry' => ['code' => 'GB'],
            'displayCurrency' => $this->currency,
        ], $extra);

        if (array_key_exists('discountCode', $body) && trim((string) $body['discountCode']) === '') {
            unset($body['discountCode']);
        }

        self::log('info', 'Enjoy Travel availability search', [
            'pickup_location_id' => (int) $pickupLocationId,
            'dropoff_location_id' => (int) $dropoffLocationId,
            'pickup_datetime' => $pickupDateTime,
            'dropoff_datetime' => $dropoffDateTime,
            'driver_age' => $body['driverAge'] ?? null,
            'currency' => $this->currency,
            'has_discount' => !empty($body['discountCode']),
        ]);

        return $this->request('POST', '/availability', $body);
    }

    public function getRate($rateReference, $getTerms = true)
    {
        self::log('info', 'Enjoy Travel get rate', [
            'rate_reference' => $this->shortRef($rateReference),
            'get_terms' => (bool) $getTerms,
        ]);

        return $this->request('POST', '/rate', [
            'rateReference' => $rateReference,
            'lang' => $this->lang,
            'getTerms' => (bool) $getTerms,
            'displayCurrency' => $this->currency,
        ]);
    }

    public function createBooking(array $payload)
    {
        if (array_key_exists('flightNumber', $payload) && trim((string) $payload['flightNumber']) === '') {
            unset($payload['flightNumber']);
        }
        if (array_key_exists('extras', $payload) && empty($payload['extras'])) {
            unset($payload['extras']);
        }

        self::log('info', 'Enjoy Travel create booking', [
            'rate_reference' => $this->shortRef($payload['rateReference'] ?? ''),
            'broker_reference' => $payload['brokerReference'] ?? null,
            'has_customer' => !empty($payload['customer']),
            'payment_method' => $payload['payment']['method'] ?? null,
        ]);

        return $this->request('POST', '/bookings', $payload);
    }

    public function defaultLocationId()
    {
        return $this->locationId ?: 7857;
    }

    public static function packagePayNowAmount(array $package)
    {
        // Prefer pay-now (online) amount; Enjoy Travel often only populates `payment`, not `display`.
        $paths = [
            ['payments', 'payNow', 'total', 'payment', 'amount'],
            ['payments', 'payNow', 'total', 'display', 'amount'],
            ['payments', 'payNow', 'vehicle', 'payment', 'amount'],
            ['payments', 'payNow', 'vehicle', 'display', 'amount'],
            ['payments', 'estimatedTotal', 'total', 'payment', 'amount'],
            ['payments', 'estimatedTotal', 'total', 'display', 'amount'],
            ['vehiclePrice', 'total', 'payment', 'amount'],
            ['vehiclePrice', 'total', 'display', 'amount'],
            ['vehiclePrice', 'payment', 'amount'],
            ['vehiclePrice', 'display', 'amount'],
        ];

        return self::firstNumericPath($package, $paths);
    }

    public static function packageEstimatedTotal(array $package)
    {
        $paths = [
            ['payments', 'estimatedTotal', 'total', 'payment', 'amount'],
            ['payments', 'estimatedTotal', 'total', 'display', 'amount'],
            ['vehiclePrice', 'total', 'payment', 'amount'],
            ['vehiclePrice', 'total', 'display', 'amount'],
            ['vehiclePrice', 'payment', 'amount'],
        ];
        $total = self::firstNumericPath($package, $paths);
        if ($total > 0) {
            return $total;
        }

        return self::packagePayNowAmount($package);
    }

    public static function packagePayLocalAmount(array $package)
    {
        $paths = [
            ['payments', 'payLocal', 'total', 'payment', 'amount'],
            ['payments', 'payLocal', 'total', 'display', 'amount'],
            ['payments', 'payLocal', 'vehicle', 'payment', 'amount'],
            ['payments', 'payLocal', 'vehicle', 'display', 'amount'],
        ];

        return self::firstNumericPath($package, $paths);
    }

    public static function extractPackage(array $rateData)
    {
        if (!empty($rateData['package']) && is_array($rateData['package'])) {
            return $rateData['package'];
        }
        if (!empty($rateData['packages'][0]) && is_array($rateData['packages'][0])) {
            return $rateData['packages'][0];
        }

        return null;
    }

    /**
     * "Included in the price" labels — only names returned by Enjoy Travel.
     */
    public static function includedInPriceItems(array $package)
    {
        $items = [];
        foreach (($package['inclusions'] ?? []) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                $name = trim((string) ($row['description'] ?? ''));
            }
            if ($name === '') {
                continue;
            }
            $items[] = [
                'code' => (string) ($row['code'] ?? ''),
                'name' => $name,
            ];
        }

        return $items;
    }

    public static function moneyAmount($node)
    {
        if (is_numeric($node)) {
            return round((float) $node, 2);
        }
        if (!is_array($node)) {
            return 0.0;
        }
        foreach (['payment', 'display', 'local'] as $bucket) {
            if (isset($node[$bucket]['amount']) && is_numeric($node[$bucket]['amount'])) {
                return round((float) $node[$bucket]['amount'], 2);
            }
        }
        if (isset($node['amount']) && is_numeric($node['amount'])) {
            return round((float) $node['amount'], 2);
        }

        return 0.0;
    }

    /**
     * Normalise package extras from /rate for the deal page UI.
     */
    public static function normalizeExtras(array $package)
    {
        $out = [];
        foreach (($package['extras'] ?? []) as $extra) {
            if (!is_array($extra) || empty($extra['code'])) {
                continue;
            }
            $price = self::moneyAmount($extra['rentalPrice'] ?? null);
            $group = (string) ($extra['groupType'] ?? '');
            $out[] = [
                'code' => (string) $extra['code'],
                'name' => (string) ($extra['name'] ?? $extra['code']),
                'description' => (string) ($extra['description'] ?? ''),
                'max_quantity' => max(1, (int) ($extra['maxQuantity'] ?? 1)),
                'selected_quantity' => (int) ($extra['selectedQuantity'] ?? 0),
                'mandatory' => !empty($extra['mandatory']),
                'included_in_vehicle_price' => !empty($extra['includedInVehiclePrice']),
                'payment_type' => (string) ($extra['paymentType'] ?? ''),
                'price' => $price,
                'currency' => (string) (($extra['rentalPrice']['payment']['currency']
                    ?? $extra['rentalPrice']['display']['currency']
                    ?? 'GBP')),
                'group_type' => $group,
                'product_id' => $extra['productId'] ?? null,
                'dynamic' => is_array($extra['dynamicProductAttributes'] ?? null)
                    ? $extra['dynamicProductAttributes']
                    : [],
            ];
        }

        return $out;
    }

    protected static function firstNumericPath(array $source, array $paths)
    {
        foreach ($paths as $path) {
            $cursor = $source;
            foreach ($path as $key) {
                if (!is_array($cursor) || !array_key_exists($key, $cursor)) {
                    $cursor = null;
                    break;
                }
                $cursor = $cursor[$key];
            }
            if (is_numeric($cursor)) {
                return round((float) $cursor, 2);
            }
        }

        return 0.0;
    }

    public function getBooking($reservationNumber, $accessToken, $displayCurrency = null, $lang = null)
    {
        $number = self::normalizeReservationNumber($reservationNumber);
        if ($number === '' || trim((string) $accessToken) === '') {
            self::log('error', 'Enjoy Travel get booking missing reservation or access token', [
                'has_reservation' => $number !== '',
                'has_access_token' => trim((string) $accessToken) !== '',
            ]);

            return [
                'ok' => false,
                'http_code' => 0,
                'message' => 'Enjoy Travel booking lookup needs reservation number and booking access token.',
                'data' => null,
            ];
        }

        self::log('info', 'Enjoy Travel get booking', ['reservation_number' => $number]);

        return $this->request('GET', '/bookings/' . rawurlencode($number), [], [
            'accessToken' => $accessToken,
            'displayCurrency' => $displayCurrency ?: $this->currency,
            'lang' => $lang ?: $this->lang,
        ]);
    }

    public function getBookingForRecord($booking)
    {
        return $this->getBooking(
            self::resolveReservationNumber($booking),
            self::resolveBookingAccessToken($booking)
        );
    }

    public function cancelBooking($reservationNumber, $accessToken, $cancelReason = null)
    {
        $number = self::normalizeReservationNumber($reservationNumber);
        if ($number === '' || trim((string) $accessToken) === '') {
            self::log('error', 'Enjoy Travel cancel missing reservation or access token', [
                'has_reservation' => $number !== '',
                'has_access_token' => trim((string) $accessToken) !== '',
            ]);

            return [
                'ok' => false,
                'http_code' => 0,
                'message' => 'Enjoy Travel cancel needs reservation number and booking access token.',
                'data' => null,
            ];
        }

        self::log('info', 'Enjoy Travel cancel booking', [
            'reservation_number' => $number,
            'has_reason' => $cancelReason !== null && $cancelReason !== '',
        ]);

        $body = [
            'reservationNumber' => (int) $number,
            'accessToken' => $accessToken,
        ];
        if ($cancelReason !== null && $cancelReason !== '') {
            $body['cancelReason'] = $cancelReason;
        }

        $result = $this->request('DELETE', '/bookings', $body);

        if (!$result['ok'] && is_array($result['data'] ?? null)) {
            $status = (string) ($result['data']['status'] ?? '');
            if (strcasecmp($status, 'Cancelled') === 0) {
                $result['ok'] = true;
                $result['message'] = 'OK';
                self::log('info', 'Enjoy Travel cancel treated as success (status Cancelled)', [
                    'reservation_number' => $number,
                ]);
            } else {
                $result['message'] = $this->cancelFailureMessage($result['data'], $result['message'] ?? '');
            }
        }

        return $result;
    }

    public function cancelForBooking($booking, $cancelReason = null)
    {
        $reservation = self::resolveReservationNumber($booking);
        $accessToken = self::resolveBookingAccessToken($booking);
        $result = $this->cancelBooking($reservation, $accessToken, $cancelReason);

        if (!$result['ok'] && $reservation !== '' && $accessToken !== '') {
            $live = $this->getBooking($reservation, $accessToken);
            if ($live['ok'] && strcasecmp((string) ($live['data']['status'] ?? ''), 'Cancelled') === 0) {
                self::log('info', 'Enjoy Travel cancel confirmed via follow-up get booking', [
                    'reservation_number' => $reservation,
                ]);

                return [
                    'ok' => true,
                    'http_code' => $live['http_code'],
                    'message' => 'OK',
                    'data' => $live['data'],
                ];
            }
            if ($live['ok'] && is_array($live['data'] ?? null)) {
                $result['message'] = $this->cancelFailureMessage($live['data'], $result['message'] ?? '');
            }
        }

        return $result;
    }

    protected function cancelFailureMessage(array $data, $fallback = '')
    {
        $status = trim((string) ($data['status'] ?? ''));
        $apiMessage = trim((string) ($data['errorMessage'] ?? ''));
        if ($apiMessage === '' && !empty($data['error']) && is_string($data['error'])) {
            $apiMessage = trim($data['error']);
        }

        $parts = ['Enjoy Travel cancel failed'];
        if ($status !== '') {
            $parts[] = 'status: ' . $status;
        }
        if ($apiMessage !== '') {
            $parts[] = $apiMessage;
        } elseif (is_string($fallback) && $fallback !== '' && stripos($fallback, 'Enjoy Travel returned an error') === false) {
            $parts[] = $fallback;
        }

        return implode(' — ', $parts);
    }

    /**
     * @return array{ok:bool,http_code:int,message:string,data:mixed}
     */
    protected function getAccessToken($forceRefresh = false)
    {
        $cacheKey = 'enjoytravel.oauth_token.' . md5($this->clientId . '|' . $this->baseUrl);
        if (!$forceRefresh) {
            $cached = Cache::get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                return [
                    'ok' => true,
                    'http_code' => 200,
                    'message' => 'OK',
                    'data' => $cached,
                ];
            }
        }

        if ($this->clientId === '' || $this->clientSecret === '') {
            self::log('error', 'Enjoy Travel OAuth config missing', [
                'has_client_id' => $this->clientId !== '',
                'has_client_secret' => $this->clientSecret !== '',
            ]);

            return [
                'ok' => false,
                'http_code' => 0,
                'message' => 'Enjoy Travel config missing. Check ENJOYTRAVEL_CLIENT_ID and ENJOYTRAVEL_CLIENT_SECRET in .env.',
                'data' => null,
            ];
        }

        self::log('info', 'Enjoy Travel OAuth token request started', [
            'auth_url' => $this->authUrl,
            'scope' => $this->scope,
            'force_refresh' => $forceRefresh,
        ]);

        $tokenUrl = $this->authUrl . '/connect/token';
        $form = http_build_query([
            'grant_type' => 'client_credentials',
            'scope' => $this->scope,
        ]);

        $result = $this->tokenRequest($tokenUrl, $form, true);
        if (!$result['ok']) {
            $formWithClient = http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => $this->scope,
            ]);
            $result = $this->tokenRequest($tokenUrl, $formWithClient, false);
        }

        if (!$result['ok']) {
            return $result;
        }

        $token = is_array($result['data']) ? (string) ($result['data']['access_token'] ?? '') : '';
        if ($token === '') {
            self::log('error', 'Enjoy Travel OAuth response missing access_token', [
                'http_code' => (int) $result['http_code'],
            ]);

            return [
                'ok' => false,
                'http_code' => (int) $result['http_code'],
                'message' => 'Enjoy Travel OAuth response did not include access_token.',
                'data' => $result['data'],
            ];
        }

        $ttl = 3000;
        if (is_array($result['data']) && !empty($result['data']['expires_in'])) {
            $ttl = max(60, ((int) $result['data']['expires_in']) - 60);
        }
        Cache::put($cacheKey, $token, $ttl);
        self::log('info', 'Enjoy Travel OAuth token cached', ['ttl' => $ttl]);

        $result['data'] = $token;
        return $result;
    }

    protected function tokenRequest($url, $formBody, $useBasicAuth)
    {
        $headers = [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        ];
        if ($useBasicAuth) {
            $headers[] = 'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $formBody,
        ]);

        $raw = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            self::log('error', 'Enjoy Travel OAuth connection failed', [
                'url' => $url,
                'curl_error' => $curlError,
            ]);

            return [
                'ok' => false,
                'http_code' => 0,
                'message' => 'Enjoy Travel auth connection failed: ' . $curlError,
                'data' => null,
            ];
        }

        $data = json_decode($raw, true);
        if ($httpCode >= 200 && $httpCode < 300 && is_array($data) && !empty($data['access_token'])) {
            return [
                'ok' => true,
                'http_code' => $httpCode,
                'message' => 'OK',
                'data' => $data,
            ];
        }

        $message = 'Enjoy Travel OAuth request failed';
        if (is_array($data)) {
            $message = $data['error_description'] ?? $data['error'] ?? $data['message'] ?? $message;
        }

        self::log('error', 'Enjoy Travel OAuth request failed', [
            'url' => $url,
            'http_code' => $httpCode,
            'message' => is_string($message) ? $message : json_encode($message),
            'used_basic_auth' => $useBasicAuth,
        ]);

        return [
            'ok' => false,
            'http_code' => $httpCode,
            'message' => is_string($message) ? $message : json_encode($message),
            'data' => $data,
        ];
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $body
     * @param array $query
     * @return array{ok:bool,http_code:int,message:string,data:mixed}
     */
    protected function request($method, $path, array $body = [], array $query = [])
    {
        if ($this->baseUrl === '') {
            self::log('error', 'Enjoy Travel API config missing', ['path' => $path]);

            return [
                'ok' => false,
                'http_code' => 0,
                'message' => 'Enjoy Travel config missing. Check ENJOYTRAVEL_BASE_URL in .env.',
                'data' => null,
            ];
        }

        $tokenResult = $this->getAccessToken();
        if (!$tokenResult['ok']) {
            return $tokenResult;
        }

        $url = $this->baseUrl . $path;
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $tokenResult['data'],
        ];

        $ch = curl_init($url);
        $options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($method !== 'GET' && !empty($body)) {
            $headers[] = 'Content-Type: application/json';
            $options[CURLOPT_HTTPHEADER] = $headers;
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        }

        curl_setopt_array($ch, $options);

        $started = microtime(true);
        self::log('info', 'Enjoy Travel API request', [
            'method' => $method,
            'path' => $path,
            'query' => $query,
            'has_body' => $method !== 'GET' && !empty($body),
        ]);

        $raw = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        $elapsedMs = (int) round((microtime(true) - $started) * 1000);

        if ($raw === false) {
            $detail = $curlError !== '' ? $curlError : ('curl errno ' . $curlErrno);
            self::log('error', 'Enjoy Travel API connection failed', [
                'method' => $method,
                'path' => $path,
                'curl_errno' => $curlErrno,
                'curl_error' => $detail,
                'elapsed_ms' => $elapsedMs,
            ]);

            return [
                'ok' => false,
                'http_code' => 0,
                'message' => 'Enjoy Travel API connection failed: ' . $detail,
                'data' => null,
            ];
        }

        $data = json_decode($raw, true);
        if ($data === null && $raw !== '' && $httpCode !== 204) {
            $data = ['raw' => $raw];
        }

        if ($httpCode === 204) {
            self::log('info', 'Enjoy Travel API response', [
                'method' => $method,
                'path' => $path,
                'http_code' => $httpCode,
                'ok' => true,
                'elapsed_ms' => $elapsedMs,
            ]);

            return [
                'ok' => true,
                'http_code' => $httpCode,
                'message' => 'OK',
                'data' => ['success' => true],
            ];
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            $message = 'OK';
            $ok = true;
            if (is_array($data)) {
                $hasErrorFlag = array_key_exists('error', $data) && (
                    $data['error'] === true
                    || $data['error'] === 1
                    || $data['error'] === 'true'
                    || $data['error'] === '1'
                );
                $cancelled = strcasecmp((string) ($data['status'] ?? ''), 'Cancelled') === 0;

                if ($hasErrorFlag && !$cancelled) {
                    $ok = false;
                    $message = (string) ($data['errorMessage'] ?? 'Enjoy Travel returned an error');
                }
                if (isset($data['errorMessage']) && $data['errorMessage'] && $ok === false) {
                    $message = (string) $data['errorMessage'];
                }
            }

            self::log($ok ? 'info' : 'error', 'Enjoy Travel API response', [
                'method' => $method,
                'path' => $path,
                'http_code' => $httpCode,
                'ok' => $ok,
                'message' => $message,
                'elapsed_ms' => $elapsedMs,
                'summary' => $this->responseSummary($data),
            ]);

            return [
                'ok' => $ok,
                'http_code' => $httpCode,
                'message' => $message,
                'data' => $data,
            ];
        }

        $message = $this->formatApiErrorMessage($data, 'Enjoy Travel API request failed');

        self::log('error', 'Enjoy Travel API request failed', [
            'method' => $method,
            'path' => $path,
            'http_code' => $httpCode,
            'message' => $message,
            'elapsed_ms' => $elapsedMs,
            'summary' => $this->responseSummary($data),
        ]);

        return [
            'ok' => false,
            'http_code' => $httpCode,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Prefer ASP.NET ProblemDetails field errors over the generic title
     * ("One or more validation errors occurred.").
     */
    protected function formatApiErrorMessage($data, $fallback = 'Enjoy Travel API request failed')
    {
        if (!is_array($data)) {
            return $fallback;
        }

        $fieldErrors = $this->flattenValidationErrors($data['errors'] ?? null);
        if ($fieldErrors !== []) {
            return implode(' ', $fieldErrors);
        }

        foreach (['detail', 'title', 'errorMessage', 'message', 'error'] as $key) {
            if (!empty($data[$key]) && is_string($data[$key])) {
                return $data[$key];
            }
        }

        return $fallback;
    }

    protected function flattenValidationErrors($errors)
    {
        if (!is_array($errors) || $errors === []) {
            return [];
        }

        $out = [];
        foreach ($errors as $field => $messages) {
            if (is_string($messages) && trim($messages) !== '') {
                $out[] = trim($messages);
                continue;
            }
            if (!is_array($messages)) {
                continue;
            }
            foreach ($messages as $message) {
                if (is_string($message) && trim($message) !== '') {
                    $out[] = trim($message);
                }
            }
        }

        return array_values(array_unique($out));
    }

    protected function responseSummary($data)
    {
        if (!is_array($data)) {
            return ['type' => gettype($data)];
        }

        $warnings = [];
        if (is_array($data['warnings'] ?? null)) {
            foreach (array_slice($data['warnings'], 0, 5) as $warning) {
                if (!is_array($warning)) {
                    $warnings[] = (string) $warning;
                    continue;
                }
                $warnings[] = trim(($warning['code'] ?? '') . ': ' . ($warning['message'] ?? ''), ': ');
            }
        }

        return [
            'keys' => array_slice(array_keys($data), 0, 12),
            'rates' => is_array($data['rates'] ?? null) ? count($data['rates']) : null,
            'packages' => is_array($data['packages'] ?? null) ? count($data['packages']) : null,
            'reservNum' => $data['reservNum'] ?? ($data['reservationNumber'] ?? null),
            'status' => $data['status'] ?? null,
            'error' => array_key_exists('error', $data) ? $data['error'] : null,
            'cancellationFee' => $data['cancellationFee'] ?? null,
            'errorMessage' => $data['errorMessage'] ?? ($data['detail'] ?? ($data['message'] ?? null)),
            'validation_errors' => $this->flattenValidationErrors($data['errors'] ?? null) ?: null,
            'warnings' => $warnings ?: null,
        ];
    }

    protected function shortRef($value)
    {
        $value = (string) $value;
        if (strlen($value) <= 24) {
            return $value;
        }

        return substr($value, 0, 12) . '...' . substr($value, -8);
    }

    protected static function safeContext(array $context)
    {
        foreach (['access_token', 'accessToken', 'client_secret', 'password', 'Authorization'] as $secret) {
            if (array_key_exists($secret, $context)) {
                $context[$secret] = '[redacted]';
            }
        }

        return $context;
    }

    protected static function decodeStoredPayload($booking)
    {
        $blobs = [
            $booking->enjoytravel_api_res ?? null,
            $booking->api_res ?? null,
        ];

        foreach ($blobs as $blob) {
            if (is_array($blob)) {
                return $blob;
            }
            if (!is_string($blob) || trim($blob) === '') {
                continue;
            }
            $decoded = json_decode($blob, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}
