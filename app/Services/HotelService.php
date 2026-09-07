<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelService
{
    private $bookFhr;
    private $baseUrl;
    private $token;
    private $source;
    private $requestTimeout = 60;
    private $usesDedicatedHotelEndpoint = false;

    public function __construct(BookFhrService $bookFhr = null)
    {
        $this->bookFhr = $bookFhr ?: app(BookFhrService::class);
        $hotelBase = config('services.bookfhr.hotel_base_url');
        $hotelToken = config('services.bookfhr.hotel_token');

        $this->baseUrl = rtrim($hotelBase ?: config('services.bookfhr.base_url'), '/');
        $this->token = $hotelToken ?: config('services.bookfhr.token');
        $this->source = config('services.bookfhr.source');
        $this->usesDedicatedHotelEndpoint = !empty($hotelBase) || !empty($hotelToken);
    }

    /**
     * Search hotels via BookFHR API.
     *
     * Required params: location, dateFrom, timeFrom, dateTo, timeTo,
     * adults, children, infants, rooms, roomType, currency
     * Optional: radius (default 16000), children_ages[], voucherCode
     */
    public function search(array $params)
    {
        $params['type'] = 'Hotel';
        $params['source'] = $this->source;
        $params['currency'] = $params['currency'] ?? 'GBP';

        if (!isset($params['radius'])) {
            $params['radius'] = 16000;
        }

        $children = (int) ($params['children'] ?? 0);
        if ($children > 0) {
            $params['children_ages'] = $this->normalizeChildrenAges($params['children_ages'] ?? [], $children);
        } else {
            unset($params['children_ages']);
        }

        // Dedicated hotel URL/token (optional override)
        if ($this->usesDedicatedHotelEndpoint) {
            return $this->searchOnDedicatedEndpoint($params);
        }

        return $this->bookFhr->search($params);
    }

    private function normalizeChildrenAges($ages, int $children): array
    {
        if (!is_array($ages)) {
            $ages = [];
        }

        $normalized = [];
        foreach ($ages as $age) {
            if ($age === null || $age === '') {
                continue;
            }
            $normalized[] = max(1, min(17, (int) $age));
        }

        while (count($normalized) < $children) {
            $normalized[] = 8;
        }

        return array_slice($normalized, 0, $children);
    }

    private function searchOnDedicatedEndpoint(array $params)
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/search", $params);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Hotel Search Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function createCart($currency = 'GBP', $locale = 'en', $userId = null)
    {
        return $this->bookFhr->createCart($currency, $locale, $userId);
    }

    public function addToCart($cartId, $searchId, $productId, $optionId, $qty = 1)
    {
        return $this->bookFhr->addToCart($cartId, [
            'searchId' => $searchId,
            'product' => (int) $productId,
            'option' => $optionId,
            'qty' => $qty,
            'type' => 'Hotel',
        ]);
    }

    public function getCartTotals($cartId)
    {
        return $this->bookFhr->getCartTotals($cartId);
    }

    public function submitOrder($cartId, array $data)
    {
        return $this->bookFhr->submitOrder($cartId, $data);
    }

    public function getOrderDetails($orderGuid)
    {
        return $this->bookFhr->getOrderDetails($orderGuid);
    }

    public function confirmOrder($orderGuid, $paymentRef)
    {
        return $this->bookFhr->confirmOrder($orderGuid, $paymentRef);
    }

    public function checkSearch($searchId)
    {
        return $this->bookFhr->checkSearch($searchId);
    }

    /**
     * Try alternate locations / room types until BookFHR returns products.
     */
    public function searchWithFallbacks(array $params, array $locationCandidates = [], array $roomTypeCandidates = [])
    {
        $requestedRoomType = (string) ($params['roomType'] ?? 'Double');
        $roomTypes = $this->uniqueValues(array_merge(
            [$requestedRoomType],
            $roomTypeCandidates ?: ['Double', 'Twin', 'Single', 'Triple', 'Family']
        ));

        $locations = $this->uniqueValues(array_merge(
            $locationCandidates,
            [($params['location'] ?? '')]
        ));

        $mergedResults = [];
        $searchId = null;
        $lastSuccess = null;
        $lastError = 'Unable to fetch hotel results.';
        $attempts = [];

        foreach ($locations as $location) {
            if ($location === '') {
                continue;
            }

            foreach ($roomTypes as $roomType) {
                $attemptParams = array_merge($params, [
                    'location' => $location,
                    'roomType' => $roomType,
                ]);

                $result = $this->search($attemptParams);
                $count = count($result['data']['results'] ?? []);
                $attempts[] = [
                    'location' => $location,
                    'roomType' => $roomType,
                    'success' => (bool) ($result['success'] ?? false),
                    'count' => $count,
                ];

                if (!$result['success']) {
                    $lastError = $result['error'] ?? $lastError;
                    continue;
                }

                $lastSuccess = $result;
                $searchId = $searchId ?: ($result['data']['searchId'] ?? null);

                foreach ($result['data']['results'] ?? [] as $item) {
                    $productId = (string) ($item['product']['id'] ?? '');

                    if ($productId !== '') {
                        $mergedResults[$productId] = $item;
                    }
                }

                if (!empty($mergedResults)) {
                    break 2;
                }
            }
        }

        if ($lastSuccess && empty($mergedResults)) {
            Log::warning('BookFHR hotel search returned no availability', [
                'base_url' => $this->baseUrl,
                'params' => $this->logSafeParams($params),
                'attempts' => $attempts,
            ]);
        }

        if (!$lastSuccess) {
            return [
                'success' => false,
                'error' => $lastError,
                'meta' => [
                    'api_raw_count' => 0,
                    'attempts' => $attempts,
                ],
            ];
        }

        return [
            'success' => true,
            'data' => [
                'searchId' => $searchId,
                'results' => array_values($mergedResults),
            ],
            'meta' => [
                'api_raw_count' => count($mergedResults),
                'attempts' => $attempts,
                'used_room_type' => $attempts[count($attempts) - 1]['roomType'] ?? $requestedRoomType,
            ],
        ];
    }

    private function uniqueValues(array $values): array
    {
        $unique = [];

        foreach ($values as $value) {
            $value = trim((string) $value);

            if ($value === '') {
                continue;
            }

            $unique[$value] = $value;
        }

        return array_values($unique);
    }

    private function logSafeParams(array $params): array
    {
        return [
            'location' => $params['location'] ?? null,
            'dateFrom' => $params['dateFrom'] ?? null,
            'dateTo' => $params['dateTo'] ?? null,
            'adults' => $params['adults'] ?? null,
            'rooms' => $params['rooms'] ?? null,
            'roomType' => $params['roomType'] ?? null,
            'radius' => $params['radius'] ?? null,
        ];
    }
}
