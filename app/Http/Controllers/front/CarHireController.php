<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\airport;
use App\Models\carhire_bookings;
use App\Models\customers;
use App\Models\modules_settings;
use App\Models\pages;
use App\Models\partners;
use App\Services\EnjoyTravelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;

class CarHireController extends Controller
{
    public $_setting = [];

    public $stripeKey = '';

    public function __construct()
    {
        foreach (modules_settings::all() as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }

        // Prefer env (services.stripe.*); fall back to modules_settings used by legacy checkout.
        $this->stripeKey = (string) (config('services.stripe.secret')
            ?: ($this->_setting['stripe_private_key'] ?? ''));
        $this->_setting['stripe_public_key'] = (string) (config('services.stripe.key')
            ?: ($this->_setting['stripe_public_key'] ?? ''));
        $this->_setting['stripe_private_key'] = $this->stripeKey;
    }

    /**
     * Block booking/search when CARHIRE_ENABLED=false.
     */
    protected function guardCarHireEnabled(Request $request = null)
    {
        if (function_exists('carhire_enabled') ? carhire_enabled() : (bool) config('services.enjoytravel.enabled', true)) {
            return null;
        }

        if ($request && ($request->ajax() || $request->wantsJson())) {
            return response()->json([
                'success' => false,
                'message' => 'Car hire is coming soon.',
            ], 503);
        }

        return redirect()->route('carhire')->with('error', 'Car hire is coming soon.')->with('open_booking_tab', 'carhire');
    }

    public function index()
    {
        $page = pages::where('slug', 'car-hire')->where('status', 'Yes')->first()
            ?: (object) [
                'meta_title' => 'Car Hire | ' . config('app.name', 'Total Travel Solutions'),
                'meta_keyword' => 'car hire, airport car rental',
                'meta_description' => 'Compare and book airport car hire.',
            ];
        if (function_exists('apply_crm_meta')) {
            $page = apply_crm_meta($page, 'car-hire');
        }

        return view('frontend.carhire', [
            'airports' => airport::where('status', 'Yes')->get(),
            'page' => $page,
            'activeBookingTab' => 'carhire',
        ]);
    }

    public function searchLocations(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $query = trim((string) $request->input('q', ''));
        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $enjoy = new EnjoyTravelService();
        $result = $enjoy->searchLocations($query);
        EnjoyTravelService::log($result['ok'] ? 'info' : 'error', 'Car hire location lookup', [
            'query' => $query,
            'ok' => !empty($result['ok']),
            'http_code' => $result['http_code'] ?? 0,
            'message' => $result['message'] ?? '',
        ]);
        $rows = [];
        $payload = $result['data'] ?? [];
        if (is_array($payload)) {
            $list = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;
            foreach ($list as $item) {
                if (!is_array($item) || empty($item['id'])) {
                    continue;
                }
                $rows[] = [
                    'id' => (int) $item['id'],
                    'name' => $item['name'] ?? $item['shortName'] ?? ('Location '.$item['id']),
                    'country' => $item['country'] ?? '',
                    'isAirport' => !empty($item['isAirport']),
                ];
            }
        }

        $defaultId = (int) config('services.enjoytravel.location_id', 7857);
        if ($defaultId && stripos($query, 'tirana') !== false) {
            $hasDefault = false;
            foreach ($rows as $row) {
                if ((int) $row['id'] === $defaultId) {
                    $hasDefault = true;
                    break;
                }
            }
            if (!$hasDefault) {
                array_unshift($rows, [
                    'id' => $defaultId,
                    'name' => 'Tirana Airport',
                    'country' => 'AL',
                    'isAirport' => true,
                ]);
            }
        }

        return response()->json([
            'success' => !empty($result['ok']),
            'message' => $result['message'] ?? '',
            'data' => $rows,
        ]);
    }

    public function search(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $pickupId = (int) $request->input('pickup_location_id');
        $dropoffId = (int) $request->input('dropoff_location_id', $pickupId);
        $sameDropoff = $request->boolean('same_dropoff');
        if ($sameDropoff || $dropoffId < 1) {
            $dropoffId = $pickupId;
        }

        $pickupDate = $this->normalizeDate($request->input('pickup_date'));
        $dropoffDate = $this->normalizeDate($request->input('dropoff_date'));
        $pickupTime = $this->normalizeTime($request->input('pickup_time', '12:00'));
        $dropoffTime = $this->normalizeTime($request->input('dropoff_time', '12:00'));
        $standardAge = $request->boolean('driver_age_standard');
        $driverAge = $standardAge ? 30 : (int) $request->input('driver_age', 30);
        if ($driverAge < 21) {
            $driverAge = 30;
        }

        if ($pickupId < 1 || $pickupDate === '' || $dropoffDate === '') {
            EnjoyTravelService::log('warning', 'Car hire search missing required fields', [
                'pickup_location_id' => $pickupId,
                'has_pickup_date' => $pickupDate !== '',
                'has_dropoff_date' => $dropoffDate !== '',
            ]);

            return redirect()->route('carhire')->with('error', 'Please choose a pick-up location from the list, plus dates and times.')->with('open_booking_tab', 'carhire');
        }

        $enjoy = new EnjoyTravelService();
        $result = $enjoy->searchAvailability(
            $pickupId,
            $pickupDate.'T'.$pickupTime,
            $dropoffId,
            $dropoffDate.'T'.$dropoffTime,
            [
                'driverAge' => $driverAge,
                'residenceCountry' => ['code' => strtoupper((string) $request->input('residence_country', 'GB'))],
                'discountCode' => (string) $request->input('promo', ''),
            ]
        );

        $vehicles = [];
        $warnings = [];
        $payload = is_array($result['data'] ?? null) ? $result['data'] : [];
        if (!empty($result['ok'])) {
            if (isset($payload['data']) && is_array($payload['data']) && !isset($payload['rates'])) {
                $payload = $payload['data'];
            }
            $vehicles = $this->flattenRates(is_array($payload) ? $payload : []);
            $warnings = $this->warningMessages($payload);
            EnjoyTravelService::log('info', 'Car hire search results ready', [
                'pickup_location_id' => $pickupId,
                'dropoff_location_id' => $dropoffId,
                'vehicle_count' => count($vehicles),
                'http_code' => $result['http_code'] ?? 0,
                'warnings' => $warnings,
            ]);
        } else {
            EnjoyTravelService::log('error', 'Car hire search failed', [
                'pickup_location_id' => $pickupId,
                'dropoff_location_id' => $dropoffId,
                'http_code' => $result['http_code'] ?? 0,
                'message' => $result['message'] ?? 'unknown',
                'warnings' => $this->warningMessages($payload),
            ]);
        }

        $residenceCode = strtoupper((string) $request->input('residence_country', 'GB'));
        $search = [
            'pickup_location_id' => $pickupId,
            'dropoff_location_id' => $dropoffId,
            'pickup_location_name' => $request->input('pickup_location_name', ''),
            'dropoff_location_name' => $sameDropoff
                ? $request->input('pickup_location_name', '')
                : $request->input('dropoff_location_name', $request->input('pickup_location_name', '')),
            'pickup_date' => $request->input('pickup_date'),
            'dropoff_date' => $request->input('dropoff_date'),
            'pickup_time' => $request->input('pickup_time', '12:00'),
            'dropoff_time' => $request->input('dropoff_time', '12:00'),
            'driver_age' => $driverAge,
            'same_dropoff' => $sameDropoff ? '1' : '0',
            'driver_age_standard' => $standardAge ? '1' : '0',
            'promo' => $request->input('promo', ''),
            'residence_country' => $residenceCode,
            'residence_country_name' => $this->residenceCountryName($residenceCode),
            'query_id' => is_array($result['data'] ?? null) ? ($result['data']['queryId'] ?? '') : '',
        ];
        Session::put('carhire_search', $search);

        $error = null;
        if (empty($result['ok'])) {
            $error = $result['message'] ?? 'No cars found for this search.';
        } elseif (empty($vehicles) && !empty($warnings)) {
            $error = implode(' ', $warnings);
        }

        return view('frontend.carhire_search_results', [
            'airports' => airport::where('status', 'Yes')->get(),
            'vehicles' => $vehicles,
            'search' => $search,
            'error' => $error,
            'warnings' => $warnings,
            'activeBookingTab' => 'carhire',
            'sitePartner' => $this->resolveSitePartner(),
        ]);
    }

    public function viewDeal(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $rateReference = (string) $request->input('rate_reference', Session::get('carhire_deal.rate_reference', ''));
        if ($rateReference === '') {
            return redirect()->route('carhire')->with('error', 'Please select a car first.')->with('open_booking_tab', 'carhire');
        }

        $enjoy = new EnjoyTravelService();
        $rate = $enjoy->getRate($rateReference, true);
        if (empty($rate['ok']) || !is_array($rate['data'] ?? null)) {
            EnjoyTravelService::log('error', 'Car hire get rate failed for deal page', [
                'http_code' => $rate['http_code'] ?? 0,
                'message' => $rate['message'] ?? 'unknown',
            ]);

            return redirect()->route('carhire.search')->with('error', $rate['message'] ?? 'Unable to load this deal. Please search again.');
        }

        $deal = $this->buildDealFromRate($rate['data'], $rateReference);
        Session::put('carhire_deal', $deal);

        EnjoyTravelService::log('info', 'Car hire deal page opened', [
            'rate_reference' => $this->shortRef($rateReference),
            'payment_type' => $deal['payment_type'] ?? '',
            'inclusions' => count($deal['inclusions'] ?? []),
            'extras' => count($deal['extras'] ?? []),
        ]);

        return view('frontend.carhire_deal', [
            'deal' => $deal,
            'search' => Session::get('carhire_search', []),
            'activeBookingTab' => 'carhire',
        ]);
    }

    public function addBookingForm(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $deal = Session::get('carhire_deal', []);
        $rateReference = (string) ($request->input('rate_reference') ?: ($deal['rate_reference'] ?? ''));
        if ($rateReference === '') {
            return redirect()->route('carhire.search')->with('error', 'Please select a car first.');
        }

        if (empty($deal) || ($deal['rate_reference'] ?? '') !== $rateReference) {
            $enjoy = new EnjoyTravelService();
            $rate = $enjoy->getRate($rateReference, true);
            if (empty($rate['ok']) || !is_array($rate['data'] ?? null)) {
                return redirect()->route('carhire.search')->with('error', $rate['message'] ?? 'Unable to open checkout for this deal.');
            }
            $deal = $this->buildDealFromRate($rate['data'], $rateReference);
            Session::put('carhire_deal', $deal);
        }

        $paymentMode = (string) $request->input('payment_mode', $deal['default_payment_mode'] ?? 'deposit');
        if (!in_array($paymentMode, ['deposit', 'full'], true)) {
            $paymentMode = $deal['default_payment_mode'] ?? 'deposit';
        }

        $selectedExtras = $this->selectedExtrasFromRequest($request, $deal['extras'] ?? []);
        $extrasPayNow = 0.0;
        $extrasPayLocal = 0.0;
        $bookingExtras = [];
        foreach ($selectedExtras as $extra) {
            $line = $extra['price'] * $extra['quantity'];
            if (strcasecmp((string) $extra['payment_type'], 'Local') === 0) {
                $extrasPayLocal += $line;
            } else {
                $extrasPayNow += $line;
            }
            $bookingExtras[] = [
                'code' => $extra['code'],
                'quantity' => $extra['quantity'],
                'name' => $extra['name'],
                'price' => $extra['price'],
                'payment_type' => $extra['payment_type'],
            ];
        }

        $carPayNow = $paymentMode === 'full'
            ? (float) ($deal['estimated_total'] ?? 0)
            : (float) ($deal['pay_now'] ?? 0);
        if ($carPayNow <= 0) {
            $carPayNow = (float) ($deal['estimated_total'] ?? $deal['pay_now'] ?? 0);
        }

        $dueAtPickup = $paymentMode === 'full'
            ? $extrasPayLocal
            : ((float) ($deal['pay_local'] ?? 0) + $extrasPayLocal);

        $search = Session::get('carhire_search', []);
        $data = [
            'rate_reference' => $rateReference,
            'vehicle_name' => $deal['vehicle_name'] ?? '',
            'vehicle_type' => $deal['vehicle_type'] ?? '',
            'supplier_name' => $deal['supplier_name'] ?? '',
            'package_name' => $deal['package_name'] ?? '',
            'acriss_code' => $deal['acriss_code'] ?? '',
            'transmission' => $deal['transmission'] ?? '',
            'fuel_type' => $deal['fuel_type'] ?? '',
            'booking_amount' => round($carPayNow + $extrasPayNow, 2),
            'car_amount' => round($carPayNow, 2),
            'extras_amount' => round($extrasPayNow, 2),
            'estimated_total' => (float) ($deal['estimated_total'] ?? 0),
            'pay_local' => round($dueAtPickup, 2),
            'payment_mode' => $paymentMode,
            'selected_extras' => $bookingExtras,
            'inclusions' => $deal['inclusions'] ?? [],
            'pickup_location' => $search['pickup_location_name'] ?? ($deal['pickup_location'] ?? ''),
            'dropoff_location' => $search['dropoff_location_name'] ?? ($deal['dropoff_location'] ?? ''),
            'pickup_date' => $search['pickup_date'] ?? ($deal['pickup_date'] ?? ''),
            'dropoff_date' => $search['dropoff_date'] ?? ($deal['dropoff_date'] ?? ''),
            'pickup_time' => $search['pickup_time'] ?? ($deal['pickup_time'] ?? '12:00'),
            'dropoff_time' => $search['dropoff_time'] ?? ($deal['dropoff_time'] ?? '12:00'),
            'driver_age' => $search['driver_age'] ?? ($deal['driver_age'] ?? 30),
            'promo' => $search['promo'] ?? '',
            'carhire_api' => 'enjoytravel',
            'bookingfor' => 'carhire',
            'rate_json' => $deal['rate_json'] ?? [],
            'image' => $deal['image'] ?? '',
            'supplier_logo' => $deal['supplier_logo'] ?? '',
        ];

        Session::put('carhire_quote', $data);

        // Persist quote to DB immediately so live (multi-server / lost session) still has trip data.
        $draft = $this->upsertDraftBookingFromQuote($data);

        EnjoyTravelService::log('info', 'Car hire checkout opened from deal', [
            'payment_mode' => $paymentMode,
            'booking_amount' => $data['booking_amount'],
            'extras_count' => count($bookingExtras),
            'draft_reference' => $draft['reference_no'] ?? null,
        ]);

        return view('frontend.booking_carhire', [
            'data' => $data,
            'settings' => $this->_setting,
            'airports' => airport::where('status', 'Yes')->get(),
            'stripeKey' => $this->_setting['stripe_public_key'] ?? config('services.stripe.key'),
            'draftBookingId' => $draft['booking_id'] ?? 0,
            'draftReferenceNo' => $draft['reference_no'] ?? '',
            'quoteSnapshot' => $this->quoteSnapshotForClient($data),
        ]);
    }

    public function checkout(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $quote = Session::get('carhire_quote', []);
        $bookingAmount = (float) ($quote['booking_amount'] ?? $request->input('booking_amount', 0));
        $bookingFee = (float) ($this->_setting['booking_fee'] ?? 0);
        $discount = (float) $request->input('discount_amount', 0);
        $extras = $this->extraFees($request);
        $total = max(0, $bookingAmount + $bookingFee + $extras['sms'] + $extras['cancel'] - $discount);

        Stripe::setApiKey($this->stripeKey);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => (int) round($total * 100),
            'currency' => 'gbp',
        ]);
        Session::put('intent_id', $intent->id);
        Session::put('intent_secret', $intent->client_secret);
        EnjoyTravelService::log('info', 'Car hire Stripe payment intent created', [
            'total_amount' => $total,
            'booking_amount' => $bookingAmount,
            'intent_id' => $intent->id,
        ]);

        return response([
            'total_amount' => number_format($total, 2, '.', ''),
            'booking_amount' => $bookingAmount,
            'discount_amount' => number_format($discount, 2, '.', ''),
            'booking_fee' => number_format($bookingFee, 2, '.', ''),
            'intent_id' => $intent->id,
            'intent_secret' => $intent->client_secret,
        ]);
    }

    public function saveDetails(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $existing = $request->input('reference_no');
        $row = $existing ? carhire_bookings::where('referenceNo', $existing)->first() : null;
        $quote = $this->resolveQuote($request, $row);

        $bookingFee = (float) ($this->_setting['booking_fee'] ?? 0);
        $bookingAmount = (float) ($quote['booking_amount'] ?? $request->input('booking_amount', 0));
        $discount = (float) $request->input('discount_amount', 0);
        $extras = $this->extraFees($request);
        $total = max(0, $bookingAmount + $bookingFee + $extras['sms'] + $extras['cancel'] - $discount);

        $now = date('Y-m-d H:i:s');
        $custData = [
            'title' => $request->title,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'phone_number' => $request->contactno,
            'password' => Hash::make(str()->random(8)),
            'address' => '',
            'town' => '',
            'added_on' => $now,
            'update_on' => $now,
        ];
        $customer = customers::where('email', $request->email)->first();
        if ($customer) {
            customers::where('id', $customer->id)->update($custData);
            $customerId = $customer->id;
        } else {
            $customerId = customers::updateOrCreate(
                ['email' => $request->email],
                $custData
            )->id;
        }

        $data = $this->bookingRowFromQuote($quote, [
            'customerId' => $customerId,
            'title' => $request->title,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'phone_number' => $request->contactno,
            'discount_amount' => $discount,
            'booking_amount' => $bookingAmount,
            'booking_fee' => $bookingFee,
            'smsfee' => $extras['sms'],
            'cancelfee' => $extras['cancel'],
            'total_amount' => $total,
            'payment_status' => 'pending',
            'booking_status' => 'Abandon',
            'booking_action' => 'Abandon',
            'traffic_src' => Session::get('bk_src', 'ORG'),
            'agentID' => $this->resolveBookingAgentId(),
            'user_ip' => $request->ip(),
            'browser_data' => substr((string) $request->userAgent(), 0, 250),
            'updated_at' => now(),
        ]);

        // Never wipe trip/vehicle fields already stored on a draft row.
        if ($row) {
            $data = $this->mergeBookingRowPreferFilled($this->bookingArrayFromModel($row), $data);
            carhire_bookings::where('id', $row->id)->update($data);
            $bookingId = $row->id;
            $referenceNo = $row->referenceNo;
        } else {
            $data['created_at'] = now();
            $bookingId = DB::table('carhire_bookings')->insertGetId($data);
            $referenceNo = 'TTS-CH-'.date('ymd').$bookingId;
            carhire_bookings::where('id', $bookingId)->update(['referenceNo' => $referenceNo]);
        }

        Session::put('carhire_quote', $this->mergeQuotePreferFilled(Session::get('carhire_quote', []), $quote));

        EnjoyTravelService::log('info', 'Car hire checkout details saved', [
            'booking_id' => $bookingId,
            'reference_no' => $referenceNo,
            'total_amount' => $total,
            'existing' => (bool) $row,
            'has_vehicle' => !empty($data['vehicle_name']),
            'has_pickup' => !empty($data['pickup_datetime']),
            'rate_reference' => $this->shortRef($data['product_code'] ?? ''),
        ]);

        return response()->json([
            'booking_id' => $bookingId,
            'reference_no' => $referenceNo,
            'available' => 'Yes',
        ]);
    }

    public function payout(Request $request)
    {
        if ($blocked = $this->guardCarHireEnabled($request)) {
            return $blocked;
        }

        $referenceNo = $request->input('reference_no');
        $booking = carhire_bookings::where('referenceNo', $referenceNo)->first();
        if (!$booking) {
            EnjoyTravelService::log('error', 'Car hire payout booking not found', [
                'reference_no' => $referenceNo,
            ]);

            return response()->json(['success' => 0, 'data' => 'Booking not found']);
        }

        if (strcasecmp((string) ($booking->booking_action ?? ''), 'Booked') === 0
            && !empty($booking->ext_ref)) {
            return response()->json(['success' => 1, 'data' => 'payment successfully charged']);
        }

        $quote = $this->resolveQuote($request, $booking);
        $rateReference = trim((string) ($quote['rate_reference'] ?? ($booking->product_code ?? '')));
        $selectedExtras = is_array($quote['selected_extras'] ?? null) ? $quote['selected_extras'] : [];

        $payer = $request->input('result');
        $payerId = '';
        if (is_array($payer)) {
            $payerId = $payer['paymentIntent']['id'] ?? '';
        } elseif (is_object($payer)) {
            $payerId = $payer->paymentIntent->id ?? '';
        }
        if ($payerId === '' && !empty($booking->PayerID)) {
            $payerId = (string) $booking->PayerID;
        }

        if ($rateReference === '') {
            EnjoyTravelService::log('error', 'Car hire payout missing rate reference', [
                'reference_no' => $referenceNo,
                'has_quote' => !empty($quote),
                'has_stored_meta' => !empty($storedMeta),
            ]);
            carhire_bookings::where('id', $booking->id)->update([
                'api_res' => json_encode($request->input('result')),
                'PayerID' => $payerId,
                'payment_method' => 'stripe',
                'payment_status' => 'success',
                'booking_status' => 'pending',
                'booking_action' => 'SupplierFailed',
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => 0,
                'data' => 'This car hire quote has expired. Please search again and select the vehicle before paying.',
            ]);
        }

        // Keep the durable copy in case session is lost on a retry.
        $tripPatch = $this->bookingRowFromQuote($quote, [
            'product_code' => $rateReference,
            'updated_at' => now(),
        ]);
        $tripPatch = $this->mergeBookingRowPreferFilled($this->bookingArrayFromModel($booking), $tripPatch);
        carhire_bookings::where('id', $booking->id)->update($tripPatch);

        $enjoy = new EnjoyTravelService();
        $create = $enjoy->createBooking([
            'rateReference' => $rateReference,
            'lang' => config('services.enjoytravel.lang', 'en-gb'),
            'flightNumber' => (string) $request->input('flight_number', ''),
            'brokerReference' => $referenceNo,
            'customer' => [
                'titleId' => (int) $this->titleId($request->input('title', $booking->title)),
                'firstName' => (string) $request->input('firstname', $booking->first_name),
                'lastName' => (string) $request->input('lastname', $booking->last_name),
                'email' => (string) $request->input('email', $booking->email),
                'phone' => (string) $request->input('contactno', $booking->phone_number),
            ],
            'payment' => [
                'method' => 'AgencyCredit',
                'sendPaymentEmail' => false,
            ],
            'extras' => array_values(array_map(static function ($extra) {
                return [
                    'code' => (string) ($extra['code'] ?? ''),
                    'quantity' => (int) ($extra['quantity'] ?? 1),
                ];
            }, array_filter($selectedExtras, static function ($extra) {
                return !empty($extra['code']) && (int) ($extra['quantity'] ?? 0) > 0;
            }))),
        ]);

        if (empty($create['ok'])) {
            EnjoyTravelService::log('error', 'Car hire supplier create booking failed', [
                'reference_no' => $referenceNo,
                'http_code' => $create['http_code'] ?? 0,
                'message' => $create['message'] ?? 'unknown',
                'rate_reference' => $this->shortRef($rateReference),
            ]);
            carhire_bookings::where('id', $booking->id)->update([
                'api_res' => json_encode($request->input('result')),
                'enjoytravel_api_res' => json_encode($create),
                'PayerID' => $payerId,
                'payment_method' => 'stripe',
                'payment_status' => 'success',
                'booking_status' => 'pending',
                'booking_action' => 'SupplierFailed',
                'updated_at' => now(),
            ]);
            return response()->json([
                'success' => 0,
                'data' => $create['message'] ?? 'Unable to confirm car hire with the supplier.',
            ]);
        }

        $payload = is_array($create['data']) ? $create['data'] : [];

        $update = [
            'ext_ref' => $payload['reservNum'] ?? ($payload['reservationNumber'] ?? ''),
            'supplier_reference' => $payload['supplierReference'] ?? '',
            'enjoytravel_access_token' => $payload['accessToken'] ?? ($payload['access_token'] ?? ''),
            'enjoytravel_api_res' => json_encode($payload),
            'api_res' => json_encode($request->input('result')),
            'PayerID' => $payerId,
            'payment_method' => 'stripe',
            'payment_status' => 'success',
            'booking_status' => 'completed',
            'booking_action' => 'Booked',
            'updated_at' => now(),
        ];
        carhire_bookings::where('id', $booking->id)->update($update);
        EnjoyTravelService::log('info', 'Car hire supplier booking created', [
            'reference_no' => $referenceNo,
            'ext_ref' => $update['ext_ref'] ?? '',
            'supplier_reference' => $update['supplier_reference'] ?? '',
        ]);

        return response()->json(['success' => 1, 'data' => 'payment successfully charged']);
    }

    public function thankyou($id)
    {
        $booking = carhire_bookings::where('referenceNo', $id)->firstOrFail();

        return view('frontend.thankyou_carhire', [
            'airports' => airport::where('status', 'Yes')->get(),
            'booking' => $booking,
        ]);
    }

    protected function warningMessages($payload)
    {
        $out = [];
        if (!is_array($payload) || empty($payload['warnings']) || !is_array($payload['warnings'])) {
            return $out;
        }

        foreach ($payload['warnings'] as $warning) {
            if (is_string($warning) && trim($warning) !== '') {
                $out[] = trim($warning);
                continue;
            }
            if (!is_array($warning)) {
                continue;
            }
            $message = trim((string) ($warning['message'] ?? $warning['code'] ?? ''));
            if ($message !== '') {
                $out[] = $message;
            }
        }

        return array_values(array_unique($out));
    }

    protected function flattenRates($payload)
    {
        $out = [];
        $rates = is_array($payload['rates'] ?? null) ? $payload['rates'] : [];
        $branches = is_array($payload['branches'] ?? null) ? $payload['branches'] : [];

        foreach ($rates as $rate) {
            if (!is_array($rate)) {
                continue;
            }
            $vehicle = is_array($rate['vehicle'] ?? null) ? $rate['vehicle'] : [];
            $packages = is_array($rate['packages'] ?? null) ? $rate['packages'] : [];
            $pickupBranch = $rate['pickupBranch'] ?? ($branches[$rate['pickUpBranchId'] ?? ''] ?? []);
            if (!is_array($pickupBranch)) {
                $pickupBranch = [];
            }
            $supplier = $pickupBranch['supplier']['name'] ?? ($pickupBranch['supplierCode'] ?? '');
            if ($supplier === '') {
                $supplier = $vehicle['supplierCode'] ?? '';
            }
            $supplierLogo = $pickupBranch['supplier']['logoSvgUrl']
                ?? ($pickupBranch['supplier']['logoUrl'] ?? '');
            $image = '';
            if (!empty($vehicle['images'][0]['url'])) {
                $image = $vehicle['images'][0]['url'];
            } elseif (!empty($vehicle['imageTypeUrl'])) {
                $image = $vehicle['imageTypeUrl'];
            }

            $isMeetGreet = !empty($pickupBranch['isMeetAndGreet']);
            $vehicleAtName = '';
            if (is_array($pickupBranch['vehicleAt'] ?? null)) {
                $vehicleAtName = (string) ($pickupBranch['vehicleAt']['name'] ?? '');
            }
            $pickupType = $this->resolvePickupType($isMeetGreet, $vehicleAtName);
            $pickupTypeKey = $isMeetGreet ? 'meet_and_greet' : $this->pickupTypeKey($vehicleAtName);
            $category = $this->mapVehicleCategory((string) ($vehicle['type'] ?? ''), (string) ($vehicle['acrissCode'] ?? ''));
            $seats = (int) preg_replace('/\D+/', '', (string) ($vehicle['seats'] ?? '0'));
            $branchAddress = trim((string) ($pickupBranch['address'] ?? ''));
            if ($branchAddress === '' && is_array($pickupBranch['addressData'] ?? null)) {
                $addr = $pickupBranch['addressData'];
                $branchAddress = trim(implode(', ', array_filter([
                    $addr['line1'] ?? '',
                    $addr['line2'] ?? '',
                    $addr['line3'] ?? '',
                    $addr['postalCode'] ?? '',
                ])));
            }
            $branchInstructions = (string) ($pickupBranch['specialInstructions'] ?? '');
            $lat = $pickupBranch['geoPoint']['latitude'] ?? ($pickupBranch['location']['coords']['latitude'] ?? null);
            $lng = $pickupBranch['geoPoint']['longitude'] ?? ($pickupBranch['location']['coords']['longitude'] ?? null);

            foreach ($packages as $package) {
                if (!is_array($package) || empty($package['rateReference'])) {
                    continue;
                }
                $payNow = EnjoyTravelService::packagePayNowAmount($package);
                $estimated = EnjoyTravelService::packageEstimatedTotal($package);
                $displayPrice = $estimated > 0 ? $estimated : $payNow;
                $paymentType = (string) ($package['paymentType'] ?? '');
                $fuelName = '';
                if (is_array($package['fuelPolicy'] ?? null)) {
                    $fuelName = (string) ($package['fuelPolicy']['name'] ?? $package['fuelPolicy']['code'] ?? '');
                }
                $packageName = trim((string) ($package['name'] ?? ''));
                if ($packageName === '') {
                    $packageName = $paymentType !== '' ? $paymentType : 'Standard';
                }
                // Only labels returned by Enjoy Travel inclusions[].
                $inclusions = array_values(array_map(static function ($row) {
                    return $row['name'];
                }, EnjoyTravelService::includedInPriceItems($package)));

                $out[] = [
                    'rate_reference' => $package['rateReference'],
                    'package_name' => $packageName,
                    'payment_type' => $paymentType,
                    'price' => $payNow,
                    'estimated_total' => $estimated,
                    'display_price' => $displayPrice,
                    'fuel_policy' => $fuelName,
                    'vehicle_name' => $vehicle['name'] ?? 'Car',
                    'vehicle_type' => $vehicle['type'] ?? '',
                    'category' => $category,
                    'acriss_code' => $vehicle['acrissCode'] ?? '',
                    'transmission' => $vehicle['transmissionText'] ?? ($vehicle['transmission'] ?? ''),
                    'fuel_type' => $vehicle['fuelTypeText'] ?? ($vehicle['fuelType'] ?? ''),
                    'seats' => $seats > 0 ? $seats : (string) ($vehicle['seats'] ?? ''),
                    'seats_num' => $seats,
                    'doors' => $vehicle['doors'] ?? '',
                    'aircon' => !empty($vehicle['airco']),
                    'gps' => !empty($vehicle['builtInGps']),
                    'suitcases' => (int) ($vehicle['bigSuitcases'] ?? 0) + (int) ($vehicle['smallSuitcases'] ?? 0),
                    'supplier_name' => $supplier,
                    'supplier_logo' => $supplierLogo,
                    'image' => $image,
                    'pickup_type' => $pickupType,
                    'pickup_type_key' => $pickupTypeKey,
                    'is_meet_and_greet' => $isMeetGreet,
                    'branch_address' => $branchAddress,
                    'branch_instructions' => $branchInstructions,
                    'branch_lat' => is_numeric($lat) ? (float) $lat : null,
                    'branch_lng' => is_numeric($lng) ? (float) $lng : null,
                    'inclusions' => $inclusions,
                    'on_request' => !empty($package['onRequest']),
                ];
            }
        }

        usort($out, function ($a, $b) {
            return ($a['display_price'] ?? $a['price']) <=> ($b['display_price'] ?? $b['price']);
        });

        return $out;
    }

    protected function mapVehicleCategory($type, $acriss = '')
    {
        $hay = strtolower(trim($type.' '.$acriss));
        if ($hay === '') {
            return 'Other';
        }
        if (preg_match('/luxury|premium|executive|prestige/', $hay)) {
            return 'Luxury';
        }
        if (preg_match('/estate|wagon|station/', $hay)) {
            return 'Estate';
        }
        if (preg_match('/suv|crossover|4x4|jeep/', $hay)) {
            return 'SUV';
        }
        if (preg_match('/mini|small|economy|supermini|mdmr|edmr|ecmr/', $hay)) {
            return 'Small';
        }
        if (preg_match('/large|full.?size|people|passenger|van|minibus/', $hay)) {
            return 'Large';
        }
        if (preg_match('/medium|compact|intermediate|standard|cdmr|idmr|sdmr/', $hay)) {
            return 'Medium';
        }
        if ($type !== '') {
            return ucwords(strtolower($type));
        }

        return 'Other';
    }

    protected function resolvePickupType($isMeetGreet, $vehicleAtName)
    {
        if ($isMeetGreet) {
            return 'Meet & Greet';
        }
        $name = trim((string) $vehicleAtName);
        if ($name === '') {
            return 'Airport desk';
        }
        if (stripos($name, 'meet') !== false) {
            return 'Meet & Greet';
        }
        if (stripos($name, 'shuttle') !== false) {
            return 'Shuttle';
        }

        return $name;
    }

    protected function pickupTypeKey($vehicleAtName)
    {
        $name = strtolower(trim((string) $vehicleAtName));
        if ($name === '' || stripos($name, 'airport') !== false || stripos($name, 'desk') !== false) {
            return 'airport_desk';
        }
        if (stripos($name, 'shuttle') !== false) {
            return 'shuttle';
        }
        if (stripos($name, 'meet') !== false) {
            return 'meet_and_greet';
        }

        return preg_replace('/[^a-z0-9]+/', '_', $name) ?: 'other';
    }

    /**
     * Current TTS site partner from partners table (same resolution as parking via current_agent_id).
     *
     * @return array{id:int,name:string,badge:string,title:string}
     */
    protected function resolveSitePartner(): array
    {
        $agentId = function_exists('current_agent_id')
            ? (int) current_agent_id()
            : (int) config('app.agent_id', config('app.default_agent_id', 9));

        $partner = $agentId > 0 ? partners::find($agentId) : null;

        // Host-based fallback when APP_AGENT_ID points at a missing partners row.
        if (!$partner && function_exists('normalize_site_host')) {
            $host = normalize_site_host(request()->getHost());
            if ($host !== '') {
                $partner = partners::query()
                    ->whereNotNull('url')
                    ->where('url', '!=', '')
                    ->get()
                    ->first(function ($row) use ($host) {
                        return normalize_site_host($row->url) === $host;
                    });
            }
        }

        $name = trim((string) ($partner->company ?? ''));
        if ($name === '') {
            $name = trim((string) ($partner->alias ?? ''));
        }
        if ($name === '') {
            $name = (string) config('app.name', 'Total Travel Solutions');
        }

        $badgeSource = trim((string) ($partner->alias ?? ''));
        if ($badgeSource === '') {
            $badgeSource = $name;
        }

        return [
            'id' => (int) ($partner->id ?? $agentId),
            'name' => $name,
            'badge' => strtoupper($badgeSource).' PARTNER',
            'title' => $name.' partner',
        ];
    }

    protected function resolveBookingAgentId(): int
    {
        $partner = $this->resolveSitePartner();

        return max(1, (int) ($partner['id'] ?? 0));
    }

    protected function residenceCountryName($code)
    {
        $map = [
            'GB' => 'United Kingdom',
            'UK' => 'United Kingdom',
            'IE' => 'Ireland',
            'US' => 'United States',
            'PK' => 'Pakistan',
            'AL' => 'Albania',
            'DE' => 'Germany',
            'FR' => 'France',
            'ES' => 'Spain',
            'IT' => 'Italy',
            'NL' => 'Netherlands',
            'AE' => 'United Arab Emirates',
        ];
        $code = strtoupper(trim((string) $code));

        return $map[$code] ?? $code;
    }

    protected function buildDealFromRate(array $rateData, $rateReference)
    {
        $package = EnjoyTravelService::extractPackage($rateData) ?: [];
        $vehicle = is_array($rateData['vehicle'] ?? null) ? $rateData['vehicle'] : [];
        $search = Session::get('carhire_search', []);
        $branches = is_array($rateData['branches'] ?? null) ? $rateData['branches'] : [];
        $pickupBranchId = (string) ($rateData['pickUpBranchId'] ?? '');
        $branch = $branches[$pickupBranchId] ?? (reset($branches) ?: []);
        if (!is_array($branch)) {
            $branch = [];
        }

        $payNow = EnjoyTravelService::packagePayNowAmount($package);
        $payLocal = EnjoyTravelService::packagePayLocalAmount($package);
        $estimated = EnjoyTravelService::packageEstimatedTotal($package);
        $paymentType = (string) ($package['paymentType'] ?? '');
        $extras = EnjoyTravelService::normalizeExtras($package);
        $inclusions = EnjoyTravelService::includedInPriceItems($package);

        $isMeetGreet = !empty($branch['isMeetAndGreet']);
        $vehicleAtName = is_array($branch['vehicleAt'] ?? null) ? (string) ($branch['vehicleAt']['name'] ?? '') : '';
        $pickupType = $this->resolvePickupType($isMeetGreet, $vehicleAtName);

        $image = '';
        if (!empty($vehicle['images'][0]['url'])) {
            $image = $vehicle['images'][0]['url'];
        } elseif (!empty($vehicle['imageTypeUrl'])) {
            $image = $vehicle['imageTypeUrl'];
        }

        $supplierName = $branch['supplier']['name'] ?? ($search['supplier_name'] ?? '');
        $supplierLogo = $branch['supplier']['logoSvgUrl'] ?? ($branch['supplier']['logoUrl'] ?? '');
        $review = is_array($rateData['supplierReviewSummary'] ?? null) ? $rateData['supplierReviewSummary'] : [];

        $residence = is_array($rateData['residenceCountry'] ?? null) ? $rateData['residenceCountry'] : [];
        $fuelPolicy = is_array($package['fuelPolicy'] ?? null) ? $package['fuelPolicy'] : [];

        $hasDepositOption = strcasecmp($paymentType, 'PartialPrePay') === 0 && $payNow > 0 && $payNow < ($estimated - 0.01);
        $defaultPaymentMode = $hasDepositOption ? 'deposit' : 'full';

        $pickupDt = $this->splitApiDateTime($rateData['pickUpDateTime'] ?? null, $search['pickup_date'] ?? '', $search['pickup_time'] ?? '12:00');
        $dropoffDt = $this->splitApiDateTime($rateData['dropOffDateTime'] ?? null, $search['dropoff_date'] ?? '', $search['dropoff_time'] ?? '12:00');

        $branchAddress = trim((string) ($branch['address'] ?? ''));
        if ($branchAddress === '' && is_array($branch['addressData'] ?? null)) {
            $addr = $branch['addressData'];
            $branchAddress = trim(implode(', ', array_filter([
                $addr['line1'] ?? '',
                $addr['line2'] ?? '',
                $addr['line3'] ?? '',
                $addr['postalCode'] ?? '',
            ])));
        }
        $lat = $branch['geoPoint']['latitude'] ?? null;
        $lng = $branch['geoPoint']['longitude'] ?? null;

        return [
            'rate_reference' => $rateReference ?: (string) ($package['rateReference'] ?? ''),
            'rate_json' => $rateData,
            'vehicle_name' => (string) ($vehicle['name'] ?? 'Car'),
            'vehicle_type' => (string) ($vehicle['type'] ?? ''),
            'acriss_code' => (string) ($vehicle['acrissCode'] ?? ''),
            'transmission' => (string) ($vehicle['transmissionText'] ?? ($vehicle['transmission'] ?? '')),
            'fuel_type' => (string) ($vehicle['fuelTypeText'] ?? ($vehicle['fuelType'] ?? '')),
            'seats' => $vehicle['seats'] ?? '',
            'doors' => $vehicle['doors'] ?? '',
            'aircon' => !empty($vehicle['airco']),
            'gps' => !empty($vehicle['builtInGps']),
            'suitcases' => (int) ($vehicle['bigSuitcases'] ?? 0) + (int) ($vehicle['smallSuitcases'] ?? 0),
            'image' => $image,
            'supplier_name' => (string) $supplierName,
            'supplier_logo' => (string) $supplierLogo,
            'review_score' => isset($review['reviewScore']) ? (float) $review['reviewScore'] : null,
            'review_count' => isset($review['reviewCount']) ? (int) $review['reviewCount'] : null,
            'package_name' => (string) ($package['name'] ?? $paymentType),
            'payment_type' => $paymentType,
            'pay_now' => $payNow,
            'pay_local' => $payLocal,
            'estimated_total' => $estimated,
            'currency' => (string) ($rateData['paymentCurrency'] ?? $rateData['displayCurrency'] ?? 'GBP'),
            'has_deposit_option' => $hasDepositOption,
            'default_payment_mode' => $defaultPaymentMode,
            'inclusions' => $inclusions,
            'extras' => $extras,
            'fuel_policy' => (string) ($fuelPolicy['name'] ?? ''),
            'fuel_policy_code' => (string) ($fuelPolicy['code'] ?? ''),
            'unlimited_mileage' => !empty($package['unlimitedMileage']),
            'has_cdw' => !empty($package['hasCDW']),
            'deposit' => EnjoyTravelService::moneyAmount($package['deposit'] ?? null),
            'cdw_excess' => EnjoyTravelService::moneyAmount($package['cdwExcess'] ?? null),
            'pickup_type' => $pickupType,
            'is_meet_and_greet' => $isMeetGreet,
            'pickup_location' => $search['pickup_location_name']
                ?? ($branch['location']['name'] ?? ''),
            'dropoff_location' => $search['dropoff_location_name']
                ?? ($branch['location']['name'] ?? ''),
            'pickup_date' => $pickupDt['date'],
            'pickup_time' => $pickupDt['time'],
            'dropoff_date' => $dropoffDt['date'],
            'dropoff_time' => $dropoffDt['time'],
            'driver_age' => $search['driver_age'] ?? 30,
            'residence_country_name' => $residence['name']
                ?? ($search['residence_country_name'] ?? 'United Kingdom'),
            'branch_address' => $branchAddress,
            'branch_instructions' => (string) ($branch['specialInstructions'] ?? ''),
            'branch_lat' => is_numeric($lat) ? (float) $lat : null,
            'branch_lng' => is_numeric($lng) ? (float) $lng : null,
            'terms' => is_array($rateData['terms'] ?? null) ? $rateData['terms'] : [],
        ];
    }

    protected function selectedExtrasFromRequest(Request $request, array $availableExtras)
    {
        $qtyMap = [];
        $raw = $request->input('extras', []);
        if (is_array($raw)) {
            foreach ($raw as $code => $qty) {
                $qtyMap[(string) $code] = (int) $qty;
            }
        }

        // Support extras[CODE]=qty and extras[][code]/extras[][qty]
        if ($request->has('extra_code') && is_array($request->input('extra_code'))) {
            $codes = $request->input('extra_code', []);
            $qtys = $request->input('extra_qty', []);
            foreach ($codes as $i => $code) {
                $qtyMap[(string) $code] = (int) ($qtys[$i] ?? 0);
            }
        }

        $selected = [];
        foreach ($availableExtras as $extra) {
            $code = (string) ($extra['code'] ?? '');
            if ($code === '') {
                continue;
            }
            $qty = (int) ($qtyMap[$code] ?? 0);
            if (!empty($extra['mandatory'])) {
                $qty = max(1, $qty);
            }
            $qty = max(0, min($qty, (int) ($extra['max_quantity'] ?? 1)));
            if ($qty < 1) {
                continue;
            }
            $selected[] = [
                'code' => $code,
                'name' => (string) ($extra['name'] ?? $code),
                'quantity' => $qty,
                'price' => (float) ($extra['price'] ?? 0),
                'payment_type' => (string) ($extra['payment_type'] ?? ''),
                'group_type' => (string) ($extra['group_type'] ?? ''),
            ];
        }

        return $selected;
    }

    protected function splitApiDateTime($apiValue, $fallbackDate, $fallbackTime)
    {
        $apiValue = trim((string) $apiValue);
        if ($apiValue !== '') {
            try {
                $dt = new \DateTime($apiValue);
                return [
                    'date' => $dt->format('d/m/Y'),
                    'time' => $dt->format('H:i'),
                ];
            } catch (\Throwable $e) {
                // fall through
            }
        }

        return [
            'date' => (string) $fallbackDate,
            'time' => (string) $fallbackTime,
        ];
    }

    protected function shortRef($rateReference)
    {
        $rateReference = (string) $rateReference;
        if (strlen($rateReference) <= 24) {
            return $rateReference;
        }

        return substr($rateReference, 0, 12) . '…' . substr($rateReference, -8);
    }

    protected function extraFees(Request $request)
    {
        $smsOn = in_array($request->input('smsfee'), ['Yes', '1', 1, true], true) || $request->boolean('smsfee');
        $cancelOn = in_array($request->input('canfee'), ['Yes', '1', 1, true], true) || $request->boolean('canfee');

        return [
            'sms' => $smsOn ? (float) ($this->_setting['sms_notification_fee'] ?? 0) : 0,
            'cancel' => $cancelOn ? (float) ($this->_setting['cancellation_fee'] ?? 0) : 0,
        ];
    }

    protected function titleId($title)
    {
        $map = [
            'mr' => 1,
            'mrs' => 2,
            'miss' => 3,
            'ms' => 4,
        ];
        $key = strtolower(trim((string) $title));

        return $map[$key] ?? 1;
    }

    protected function normalizeDate($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        $parts = preg_split('/[\/\.\-]/', $value);
        if (count($parts) === 3) {
            if (strlen($parts[0]) === 4) {
                return sprintf('%04d-%02d-%02d', (int) $parts[0], (int) $parts[1], (int) $parts[2]);
            }
            return sprintf('%04d-%02d-%02d', (int) $parts[2], (int) $parts[1], (int) $parts[0]);
        }
        $ts = strtotime($value);

        return $ts ? date('Y-m-d', $ts) : '';
    }

    protected function normalizeTime($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '10:00:00';
        }
        if (strlen($value) === 5) {
            return $value.':00';
        }
        return $value;
    }

    protected function combineDateTime($date, $time)
    {
        $d = $this->normalizeDate($date);
        if ($d === '') {
            return null;
        }
        return $d.' '.$this->normalizeTime($time);
    }

    /**
     * Map Enjoy Travel pick-up location text to a local airports.id when possible.
     * Non-UK test locations (e.g. Tirana) often won't match — airportID stays null.
     */
    protected function resolveAirportIdFromLocation($locationName)
    {
        $locationName = trim((string) $locationName);
        if ($locationName === '') {
            return null;
        }

        $normalized = preg_replace('/\s+/', ' ', $locationName);
        $withoutAirport = trim(preg_replace('/\bairport\b/i', '', $normalized));

        $match = airport::query()
            ->where('status', 'Yes')
            ->where(function ($q) use ($normalized, $withoutAirport) {
                $q->where('name', $normalized)
                    ->orWhere('name', 'like', $normalized . '%')
                    ->orWhere('name', 'like', '%' . $normalized . '%');
                if ($withoutAirport !== '' && strcasecmp($withoutAirport, $normalized) !== 0) {
                    $q->orWhere('name', $withoutAirport)
                        ->orWhere('name', 'like', $withoutAirport . '%')
                        ->orWhere('name', 'like', '%' . $withoutAirport . '%');
                }
                if (preg_match('/\b([A-Z]{3})\b/', strtoupper($normalized), $m)) {
                    $q->orWhere('iata_code', $m[1]);
                }
            })
            ->orderByRaw('CASE WHEN name = ? THEN 0 WHEN name LIKE ? THEN 1 ELSE 2 END', [$withoutAirport ?: $normalized, ($withoutAirport ?: $normalized) . '%'])
            ->first();

        return $match ? (int) $match->id : null;
    }

    protected function quoteSnapshotForClient(array $quote)
    {
        return [
            'rate_reference' => (string) ($quote['rate_reference'] ?? ''),
            'vehicle_name' => (string) ($quote['vehicle_name'] ?? ''),
            'vehicle_type' => (string) ($quote['vehicle_type'] ?? ''),
            'supplier_name' => (string) ($quote['supplier_name'] ?? ''),
            'package_name' => (string) ($quote['package_name'] ?? ''),
            'acriss_code' => (string) ($quote['acriss_code'] ?? ''),
            'transmission' => (string) ($quote['transmission'] ?? ''),
            'fuel_type' => (string) ($quote['fuel_type'] ?? ''),
            'booking_amount' => (float) ($quote['booking_amount'] ?? 0),
            'payment_mode' => (string) ($quote['payment_mode'] ?? ''),
            'selected_extras' => is_array($quote['selected_extras'] ?? null) ? $quote['selected_extras'] : [],
            'pickup_location' => (string) ($quote['pickup_location'] ?? ''),
            'dropoff_location' => (string) ($quote['dropoff_location'] ?? ''),
            'pickup_date' => (string) ($quote['pickup_date'] ?? ''),
            'dropoff_date' => (string) ($quote['dropoff_date'] ?? ''),
            'pickup_time' => (string) ($quote['pickup_time'] ?? ''),
            'dropoff_time' => (string) ($quote['dropoff_time'] ?? ''),
            'driver_age' => (int) ($quote['driver_age'] ?? 30),
            'promo' => (string) ($quote['promo'] ?? ''),
        ];
    }

    protected function resolveQuote(Request $request, $existing = null)
    {
        $quote = [];
        if ($existing) {
            $quote = $this->mergeQuotePreferFilled($quote, $this->quoteFromBooking($existing));
        }

        $sessionQuote = Session::get('carhire_quote', []);
        if (is_array($sessionQuote)) {
            $quote = $this->mergeQuotePreferFilled($quote, $sessionQuote);
        }

        $fromRequest = $request->input('quote_json');
        if (is_string($fromRequest) && trim($fromRequest) !== '') {
            $decoded = json_decode($fromRequest, true);
            if (is_array($decoded)) {
                $quote = $this->mergeQuotePreferFilled($quote, $decoded);
            }
        }

        $productCode = trim((string) $request->input('product_code', ''));
        if ($productCode !== '') {
            $quote['rate_reference'] = $productCode;
        }

        return $quote;
    }

    protected function quoteFromBooking($booking)
    {
        $meta = [];
        if (!empty($booking->extras_json)) {
            $decoded = json_decode((string) $booking->extras_json, true);
            if (is_array($decoded)) {
                $meta = $decoded;
            }
        }

        $pickupDate = '';
        $pickupTime = '12:00';
        if (!empty($booking->pickup_datetime)) {
            $ts = strtotime((string) $booking->pickup_datetime);
            if ($ts) {
                $pickupDate = date('d/m/Y', $ts);
                $pickupTime = date('H:i', $ts);
            }
        }

        $dropoffDate = '';
        $dropoffTime = '12:00';
        if (!empty($booking->dropoff_datetime)) {
            $ts = strtotime((string) $booking->dropoff_datetime);
            if ($ts) {
                $dropoffDate = date('d/m/Y', $ts);
                $dropoffTime = date('H:i', $ts);
            }
        }

        return $this->mergeQuotePreferFilled([
            'rate_reference' => (string) ($booking->product_code ?? ''),
            'vehicle_name' => (string) ($booking->vehicle_name ?? ''),
            'vehicle_type' => (string) ($booking->vehicle_type ?? ''),
            'supplier_name' => (string) ($booking->supplier_name ?? ''),
            'acriss_code' => (string) ($booking->acriss_code ?? ''),
            'transmission' => (string) ($booking->transmission ?? ''),
            'fuel_type' => (string) ($booking->fuel_type ?? ''),
            'booking_amount' => (float) ($booking->booking_amount ?? 0),
            'pickup_location' => (string) ($booking->pickup_location ?? ''),
            'dropoff_location' => (string) ($booking->dropoff_location ?? ''),
            'pickup_date' => $pickupDate,
            'dropoff_date' => $dropoffDate,
            'pickup_time' => $pickupTime,
            'dropoff_time' => $dropoffTime,
            'driver_age' => (int) ($booking->driver_age ?? 30),
            'promo' => (string) ($booking->discount_code ?? ''),
            'selected_extras' => is_array($meta['selected_extras'] ?? null) ? $meta['selected_extras'] : [],
            'payment_mode' => (string) ($meta['payment_mode'] ?? ''),
            'package_name' => (string) ($meta['package_name'] ?? ''),
        ], $meta);
    }

    protected function mergeQuotePreferFilled(array $base, array $overlay)
    {
        foreach ($overlay as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_string($value) && trim($value) === '') {
                continue;
            }
            if (is_array($value) && $value === []) {
                continue;
            }
            $base[$key] = $value;
        }

        return $base;
    }

    protected function bookingRowFromQuote(array $quote, array $overrides = [])
    {
        $pickupLocation = (string) ($quote['pickup_location'] ?? '');
        $dropoffLocation = (string) ($quote['dropoff_location'] ?? '');
        $pickup = $this->combineDateTime($quote['pickup_date'] ?? '', $quote['pickup_time'] ?? '10:00');
        $dropoff = $this->combineDateTime($quote['dropoff_date'] ?? '', $quote['dropoff_time'] ?? '10:00');
        $duration = 1;
        if ($pickup && $dropoff) {
            $duration = max(1, (int) ceil((strtotime($dropoff) - strtotime($pickup)) / 86400));
        }

        $rateReference = (string) ($quote['rate_reference'] ?? '');
        $row = [
            'airportID' => $this->resolveAirportIdFromLocation($pickupLocation),
            'pickup_datetime' => $pickup,
            'dropoff_datetime' => $dropoff,
            'duration_days' => $duration,
            'driver_age' => (int) ($quote['driver_age'] ?? 30),
            'residence_country_code' => 'GB',
            'vehicle_name' => (string) ($quote['vehicle_name'] ?? ''),
            'vehicle_type' => (string) ($quote['vehicle_type'] ?? ''),
            'acriss_code' => (string) ($quote['acriss_code'] ?? ''),
            'transmission' => (string) ($quote['transmission'] ?? ''),
            'fuel_type' => (string) ($quote['fuel_type'] ?? ''),
            'supplier_name' => (string) ($quote['supplier_name'] ?? ''),
            'pickup_location' => $pickupLocation,
            'dropoff_location' => $dropoffLocation,
            'discount_code' => (string) ($quote['promo'] ?? ''),
            'carhire_api' => 'enjoytravel',
            'product_code' => $rateReference,
            'extras_json' => json_encode([
                'rate_reference' => $rateReference,
                'selected_extras' => is_array($quote['selected_extras'] ?? null) ? $quote['selected_extras'] : [],
                'payment_mode' => $quote['payment_mode'] ?? null,
                'package_name' => $quote['package_name'] ?? null,
                'pickup_date' => $quote['pickup_date'] ?? null,
                'dropoff_date' => $quote['dropoff_date'] ?? null,
                'pickup_time' => $quote['pickup_time'] ?? null,
                'dropoff_time' => $quote['dropoff_time'] ?? null,
                'pickup_location' => $pickupLocation,
                'dropoff_location' => $dropoffLocation,
                'vehicle_name' => $quote['vehicle_name'] ?? null,
                'supplier_name' => $quote['supplier_name'] ?? null,
                'driver_age' => $quote['driver_age'] ?? null,
                'booking_amount' => $quote['booking_amount'] ?? null,
            ]),
        ];

        return array_merge($row, $overrides);
    }

    protected function bookingArrayFromModel($booking)
    {
        return [
            'airportID' => $booking->airportID,
            'pickup_datetime' => $booking->pickup_datetime,
            'dropoff_datetime' => $booking->dropoff_datetime,
            'duration_days' => $booking->duration_days,
            'driver_age' => $booking->driver_age,
            'vehicle_name' => $booking->vehicle_name,
            'vehicle_type' => $booking->vehicle_type,
            'acriss_code' => $booking->acriss_code,
            'transmission' => $booking->transmission,
            'fuel_type' => $booking->fuel_type,
            'supplier_name' => $booking->supplier_name,
            'pickup_location' => $booking->pickup_location,
            'dropoff_location' => $booking->dropoff_location,
            'discount_code' => $booking->discount_code,
            'product_code' => $booking->product_code,
            'extras_json' => $booking->extras_json,
            'booking_amount' => $booking->booking_amount,
        ];
    }

    protected function mergeBookingRowPreferFilled(array $existing, array $incoming)
    {
        foreach ($incoming as $key => $value) {
            if ($value === null || $value === '') {
                if (array_key_exists($key, $existing) && $existing[$key] !== null && $existing[$key] !== '') {
                    $incoming[$key] = $existing[$key];
                }
            }
        }

        return $incoming;
    }

    protected function upsertDraftBookingFromQuote(array $quote)
    {
        $existingRef = Session::get('carhire_draft_reference');
        $row = $existingRef ? carhire_bookings::where('referenceNo', $existingRef)->first() : null;

        $data = $this->bookingRowFromQuote($quote, [
            'booking_amount' => (float) ($quote['booking_amount'] ?? 0),
            'booking_fee' => (float) ($this->_setting['booking_fee'] ?? 0),
            'total_amount' => max(0, (float) ($quote['booking_amount'] ?? 0) + (float) ($this->_setting['booking_fee'] ?? 0)),
            'smsfee' => 0,
            'cancelfee' => 0,
            'discount_amount' => 0,
            'payment_status' => 'pending',
            'booking_status' => 'Abandon',
            'booking_action' => 'Abandon',
            'traffic_src' => Session::get('bk_src', 'ORG'),
            'agentID' => $this->resolveBookingAgentId(),
            'updated_at' => now(),
        ]);

        if ($row && !in_array((string) $row->booking_action, ['Booked', 'Cancelled'], true)) {
            $data = $this->mergeBookingRowPreferFilled($this->bookingArrayFromModel($row), $data);
            carhire_bookings::where('id', $row->id)->update($data);
            Session::put('carhire_draft_reference', $row->referenceNo);

            return [
                'booking_id' => $row->id,
                'reference_no' => $row->referenceNo,
            ];
        }

        $data['created_at'] = now();
        $bookingId = DB::table('carhire_bookings')->insertGetId($data);
        $referenceNo = 'TTS-CH-'.date('ymd').$bookingId;
        carhire_bookings::where('id', $bookingId)->update(['referenceNo' => $referenceNo]);
        Session::put('carhire_draft_reference', $referenceNo);

        return [
            'booking_id' => $bookingId,
            'reference_no' => $referenceNo,
        ];
    }
}
