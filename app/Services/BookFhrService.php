<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookFhrService
{
    private $baseUrl;
    private $token;
    private $source;
    private $booking_secret;
    private $requestTimeout = 60;

    public function setRequestTimeout(int $seconds): self
    {
        $this->requestTimeout = max(3, $seconds);

        return $this;
    }

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.bookfhr.base_url'), '/');
        $this->token = config('services.bookfhr.token');
        $this->source = config('services.bookfhr.source');
        $this->booking_secret = config('services.bookfhr.booking_secret');
    }

    private function origin(): string
    {
        return rtrim((string) (config('services.bookfhr.origin') ?: config('app.url')), '/');
    }

    private function httpClient(bool $withToken = false)
    {
        $client = Http::timeout($this->requestTimeout)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'Origin' => $this->origin(),
            ]);

        if ($withToken) {
            $client = $client->withToken($this->token);
        }

        return $client;
    }

    /**
     * Search for products (Parking, Lounge, Hotel, Carhire).
     */
    public function search(array $params)
    {
        try {
            $params['source'] = $this->source;

            $response = $this->httpClient(true)->get("{$this->baseUrl}/search", $params);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Search Error', $e);
        }
    }

    /**
     * Check whether a search result is still active.
     * GET /api/search-check/{searchId} — no auth required.
     */
    public function checkSearch($searchId)
    {
        try {
            $response = $this->httpClient()->get("{$this->baseUrl}/search-check/{$searchId}");

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Search Check Error', $e);
        }
    }

    /**
     * Create a new cart.
     */
    public function createCart($currency = 'GBP', $locale = 'en', $userId = null)
    {
        try {
            $data = [
                'currency' => $currency,
                'locale' => $locale,
            ];

            if ($userId) {
                $data['userId'] = $userId;
            }

            $response = $this->httpClient()->post("{$this->baseUrl}/cart/create", $data);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Create Cart Error', $e);
        }
    }

    /**
     * Add product to cart.
     */
    public function addToCart($cartId, array $data)
    {
        try {
            $response = $this->httpClient()->post("{$this->baseUrl}/cart/{$cartId}", $data);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Add to Cart Error', $e);
        }
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart($cartId, $itemId)
    {
        try {
            $response = Http::timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/cart/{$cartId}/remove/{$itemId}");

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Remove from Cart Error', $e);
        }
    }

    /**
     * Get cart totals.
     */
    public function getCartTotals($cartId)
    {
        try {
            $response = Http::timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/cart/{$cartId}/totals");

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Get Cart Totals Error', $e);
        }
    }

    /**
     * Associate a user ID with an existing cart (rewards).
     */
    public function setCartUser($cartId, $userId)
    {
        try {
            $response = Http::timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/cart/{$cartId}/user", [
                    'userId' => $userId,
                ]);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Set Cart User Error', $e);
        }
    }

    /**
     * Submit order.
     */
    public function submitOrder($cartId, array $orderData)
    {
        try {
            $response = $this->httpClient()->post("{$this->baseUrl}/cart/{$cartId}/order/submit", $orderData);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Submit Order Error', $e);
        }
    }

    /**
     * Get order details (public).
     */
    public function getOrderDetails($orderGuid)
    {
        try {
            $response = Http::timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/order/details/{$orderGuid}");

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Get Order Details Error', $e);
        }
    }

    /**
     * Get full order details (authenticated).
     */
    public function getOrderDetailsAll($orderGuid)
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/order/details/{$orderGuid}/all");

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Get Order Details All Error', $e);
        }
    }

    /**
     * Confirm partner booking after payment.
     * checksum = MD5(orderId + paymentRef + bookingSecret)
     */
    public function confirmOrder($orderGuid, $paymentRef)
    {
        try {
            $checksum = md5($orderGuid . $paymentRef . $this->booking_secret);

            $orderData = [
                'orderId' => $orderGuid,
                'paymentRef' => $paymentRef,
                'checksum' => $checksum,
            ];

            Log::info('Confirming BookFHR order', [
                'orderGuid' => $orderGuid,
                'paymentRef' => $paymentRef,
            ]);

            $response = Http::withToken($this->token)
                ->timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/partner/booking/confirm", $orderData);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Confirm Order Error', $e);
        }
    }

    /**
     * Partner booking details.
     */
    public function getPartnerBookingDetails(array $payload)
    {
        return $this->partnerPost('partner/booking/details', $payload, 'BookFHR Partner Booking Details Error');
    }

    /**
     * Search partner bookings by email.
     */
    public function searchPartnerBookings(array $payload)
    {
        return $this->partnerPost('partner/bookings', $payload, 'BookFHR Partner Bookings Error');
    }

    /**
     * Check if a booking can be cancelled.
     */
    public function canCancelBooking(array $payload)
    {
        return $this->partnerPost('partner/booking/can-cancel', $payload, 'BookFHR Can Cancel Error');
    }

    /**
     * Cancel a partner booking (POST simplified).
     */
    public function cancelBooking(array $payload)
    {
        return $this->partnerPost('partner/booking/cancel', $payload, 'BookFHR Cancel Booking Error');
    }

    /**
     * Cancel a partner booking (DELETE).
     */
    public function deleteCancelBooking(array $payload)
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->delete("{$this->baseUrl}/partner/booking/cancel", $payload);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse('BookFHR Delete Cancel Booking Error', $e);
        }
    }

    /**
     * Partner booking financial / commission info.
     */
    public function getPartnerBookingFinancial(array $payload)
    {
        return $this->partnerPost('partner/booking/financial', $payload, 'BookFHR Partner Financial Error');
    }

    /**
     * Ensure search is still valid before adding to cart.
     */
    public function assertSearchActive($searchId)
    {
        if (empty($searchId)) {
            throw new \Exception('BookFHR search ID is missing. Please search again.');
        }

        $check = $this->checkSearch($searchId);
        $payload = is_array($check['data'] ?? null)
            ? $check['data']
            : (is_array($check['error'] ?? null) ? $check['error'] : []);

        $valid = $payload['valid'] ?? null;
        $status = strtolower((string) ($payload['status'] ?? ''));

        if ($check['success'] && ($valid === true || $status === 'active')) {
            return $check;
        }

        if ($valid === false || $status === 'expired' || ($check['status'] ?? null) === 410) {
            throw new \Exception('BookFHR search results have expired. Please search again.');
        }

        if ($check['success'] === false) {
            $message = is_array($check['error'])
                ? ($check['error']['message'] ?? json_encode($check['error']))
                : (string) $check['error'];
            throw new \Exception('BookFHR search expired or invalid: ' . $message);
        }

        return $check;
    }

    /**
     * Build Lounge product block for POST /cart/{cartId}/order/submit
     */
    public function buildLoungeProduct($itemId, array $customer, array $counts, array $flight = [])
    {
        return [
            '_id' => $itemId,
            'type' => 'Lounge',
            'flight' => [
                'inbound_flight' => $flight['inbound_flight'] ?? ($flight['deptFlight'] ?? 'TBA'),
                'inbound_terminal' => $flight['inbound_terminal'] ?? ($flight['terminal'] ?? 'TBA'),
                'outbound_flight' => $flight['outbound_flight'] ?? '',
                'outbound_terminal' => $flight['outbound_terminal'] ?? '',
            ],
            'additional_passengers' => $this->buildAdditionalPassengers($customer, $counts),
        ];
    }

    /**
     * Build Hotel product block for POST /cart/{cartId}/order/submit
     */
    public function buildHotelProduct($itemId, array $customer, array $counts, array $flight = [])
    {
        return [
            '_id' => $itemId,
            'type' => 'Hotel',
            'flight' => [
                'inbound_flight' => $flight['inbound_flight'] ?? 'TBA',
                'inbound_terminal' => $flight['inbound_terminal'] ?? 'TBA',
                'outbound_flight' => $flight['outbound_flight'] ?? 'TBA',
                'outbound_terminal' => $flight['outbound_terminal'] ?? 'TBA',
            ],
            'guests' => $this->buildHotelGuests($customer, $counts),
        ];
    }

    /**
     * Extra lounge guests only (lead adult = order customer).
     * Sending the lead again makes BookFHR treat party size as +1 → "price has changed".
     */
    private function buildAdditionalPassengers(array $customer, array $counts)
    {
        $adults = max(1, (int) ($counts['adults'] ?? 1));
        $children = max(0, (int) ($counts['children'] ?? 0));
        $infants = max(0, (int) ($counts['infants'] ?? 0));

        $passengers = [];
        $typeNo = 1;

        // Skip first adult — they are already in orderData.customer
        for ($i = 1; $i < $adults; $i++) {
            $passengers[] = [
                'typeNo' => $typeNo++,
                'type' => 'Adult',
                'title' => 'Mr',
                'firstName' => 'Guest',
                'lastName' => (string) ($i + 1),
            ];
        }

        for ($i = 0; $i < $children; $i++) {
            $passengers[] = [
                'typeNo' => $typeNo++,
                'type' => 'Child',
                'title' => 'Miss',
                'firstName' => 'Child',
                'lastName' => (string) ($i + 1),
            ];
        }

        for ($i = 0; $i < $infants; $i++) {
            $passengers[] = [
                'typeNo' => $typeNo++,
                'type' => 'Infant',
                'title' => 'Master',
                'firstName' => 'Infant',
                'lastName' => (string) ($i + 1),
            ];
        }

        return $passengers;
    }

    /**
     * Hotels expect the full occupancy list, including the lead guest.
     */
    private function buildHotelGuests(array $customer, array $counts)
    {
        $adults = max(1, (int) ($counts['adults'] ?? 1));
        $children = max(0, (int) ($counts['children'] ?? 0));
        $infants = max(0, (int) ($counts['infants'] ?? 0));

        $title = $customer['title'] ?? 'Mr';
        $firstName = $customer['firstName'] ?? 'Guest';
        $lastName = $customer['lastName'] ?? 'Guest';

        $guests = [];

        for ($i = 0; $i < $adults; $i++) {
            $guests[] = [
                'title' => $i === 0 ? $title : 'Mr',
                'firstName' => $i === 0 ? $firstName : 'Guest',
                'lastName' => $i === 0 ? $lastName : (string) ($i + 1),
                'type' => 'Adult',
            ];
        }

        for ($i = 0; $i < $children; $i++) {
            $guests[] = [
                'title' => 'Miss',
                'firstName' => 'Child',
                'lastName' => (string) ($i + 1),
                'type' => 'Child',
            ];
        }

        for ($i = 0; $i < $infants; $i++) {
            $guests[] = [
                'title' => 'Master',
                'firstName' => 'Infant',
                'lastName' => (string) ($i + 1),
                'type' => 'Infant',
            ];
        }

        return $guests;
    }

    private function partnerPost($path, array $payload, $errorLabel)
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout($this->requestTimeout)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/{$path}", $payload);

            return $this->formatResponse($response);
        } catch (\Exception $e) {
            return $this->exceptionResponse($errorLabel, $e);
        }
    }

    private function formatResponse($response)
    {
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'error' => $response->json() ?: $response->body(),
            'status' => $response->status(),
        ];
    }

    private function exceptionResponse($label, \Exception $e)
    {
        Log::error($label . ': ' . $e->getMessage());

        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}
