<?php

namespace App\Services;

class LoungeService
{
    private $bookFhr;

    public function __construct(BookFhrService $bookFhr = null)
    {
        $this->bookFhr = $bookFhr ?: app(BookFhrService::class);
    }

    /**
     * Search Lounges via BookFHR.
     * Required: location, dateFrom, timeFrom, dateTo, timeTo, adults, children, infants
     */
    public function search(array $params)
    {
        $params['type'] = 'Lounge';
        $params['currency'] = $params['currency'] ?? 'GBP';
        $params['adults'] = max(1, (int) ($params['adults'] ?? 1));
        $params['children'] = max(0, (int) ($params['children'] ?? 0));
        $params['infants'] = max(0, (int) ($params['infants'] ?? 0));

        return $this->bookFhr->search($params);
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
            'type' => 'Lounge',
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
}
