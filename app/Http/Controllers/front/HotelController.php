<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\airport;
use App\Models\customers;
use App\Models\email_templates;
use App\Models\Hotel;
use App\Models\hotels_bookings;
use App\Models\modules_settings;
use App\Models\settings;
use App\Services\BookFhrService;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;

class HotelController extends Controller
{
    public $_setting = [];

    public $stripeKey;

    public $currency = 'GBP';

    public $_mysetting = [];

    public function __construct()
    {
        $this->stripeKey = config('services.stripe.secret');

        $modules_settings = modules_settings::all();
        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }

        $my_settings = settings::all();
        foreach ($my_settings as $my_setting) {
            $this->_mysetting[$my_setting->field_name] = $my_setting->field_value;
        }

        $this->_setting['stripe_public_key'] = config('services.stripe.key');
        $this->_setting['stripe_private_key'] = config('services.stripe.secret');
    }

    public function addBookingFormHotel(Request $request)
    {
        session(['hotel_booking_context' => $this->buildHotelBookingData($request)]);

        return redirect()->route('booking_hotel.show');
    }

    public function showBookingFormHotel(Request $request)
    {
        $data = session('hotel_booking_context');

        if (!is_array($data) || (empty($data['company_id']) && empty($data['product_code']))) {
            return redirect('/')->with('error', 'Your booking session has expired. Please select a hotel again.');
        }

        $airports = airport::where('status', 'Yes')->get();

        return view('frontend.booking_hotel', [
            'data' => $data,
            'settings' => $this->_setting,
            'airports' => $airports,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    private function buildHotelBookingData(Request $request): array
    {
        $searchData = json_decode($request->input('search_data'), true) ?: [];

        $data = [
            'company_id' => $request->input('company_id', $searchData['hotel_db_id'] ?? $searchData['companyID'] ?? null),
            'product_code' => $request->input('product_code', $searchData['product_id'] ?? ''),
            'booking_amount' => $request->input('booking_amount', $searchData['price'] ?? 0),
            'discount_amount' => $request->input('discount_amount', $searchData['discount_applied'] ?? 0),
            'discount_code' => $request->input('promo', $request->input('discount_code', '')),
            'hotel_name' => $request->input('hotel_name', $searchData['displayName'] ?? $searchData['name'] ?? ''),
            'room_title' => $request->input('room_title', $searchData['room_title'] ?? ''),
            'room_type' => $request->input('room_type', $searchData['plan_type'] ?? $searchData['room_type'] ?? 'Double'),
            'checkin_date' => $request->input('checkin_date', $request->input('hotel_checkin_date', '')),
            'checkout_date' => $request->input('checkout_date', $request->input('hotel_checkout_date', '')),
            'checkin_time' => $request->input('checkin_time', $request->input('hotel_checkin_time', '14:00')),
            'checkout_time' => $request->input('checkout_time', $request->input('hotel_checkout_time', '11:00')),
            'adults' => $request->input('adults', $request->input('hotel_adults', 1)),
            'children' => $request->input('children', $request->input('hotel_children', 0)),
            'infants' => $request->input('infants', $request->input('hotel_infants', 0)),
            'rooms' => $request->input('rooms', $request->input('hotel_rooms', 1)),
            'airport' => $request->input('airport', $request->input('airport_id', '')),
            'pl_id' => $request->input('pl_id', $searchData['option_id'] ?? ''),
            'park_api' => $request->input('park_api', 'bookfhr'),
            'bookfhrSearchId' => $request->input('bookfhrSearchId', $searchData['searchId'] ?? ''),
            'bookfhrOptionId' => $request->input('bookfhrOptionId', $searchData['option_id'] ?? ''),
            'logobooking' => $request->input('logobooking', '0'),
            'bookingfor' => 'hotels',
            'email' => $request->input('email', ''),
            'site_codename' => $request->input('site_codename', ''),
        ];

        $hotelRecord = null;
        if (!empty($data['company_id'])) {
            $hotelRecord = Hotel::find($data['company_id']);
        }
        if (!$hotelRecord && !empty($data['product_code'])) {
            $hotelRecord = Hotel::where('company_code', $data['product_code'])->first();
        }

        $data['logo'] = $this->resolveHotelBookingImage($searchData, $hotelRecord, $request->input('logo'));
        $data['logobooking2'] = $data['logo'];
        $data['cancellation_label'] = $searchData['cancellation_label'] ?? '';

        return $data;
    }

    public function checkBookingHotel(Request $request)
    {
        try {
            return $this->processCheckBookingHotel($request);
        } catch (\Throwable $e) {
            Log::error('checkBookingHotel failed', [
                'message' => $e->getMessage(),
                'email' => $request->email,
            ]);

            return response()->json([
                'booking_id' => 0,
                'reference_no' => '',
                'available' => 'No',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function processCheckBookingHotel(Request $request)
    {
        if ($request->input('incomplete') !== 'yes') {
            return response()->json([
                'booking_id' => $request->input('booking_id'),
                'reference_no' => $request->input('reference_no'),
                'available' => 'Yes',
            ]);
        }

        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;
        $sms_notification = $this->_setting['sms_notification_fee'] > 0 ? $this->_setting['sms_notification_fee'] : 0;
        $cancellation_fee = $this->_setting['cancellation_fee'] > 0 ? $this->_setting['cancellation_fee'] : 0;

        $booking_amount = (float) $request->input('booking_amount', 0);
        $discount_amount = (float) $request->input('discount_amount', 0);
        $smsfee_charged = 0;
        $cancelfee_charged = 0;

        $total_amount = $booking_amount + $bookingfee - $discount_amount;

        if ($request->input('smsfee') === 'Yes') {
            $total_amount += $sms_notification;
            $smsfee_charged = $sms_notification;
        }
        if ($request->input('canfee') === 'Yes') {
            $total_amount += $cancellation_fee;
            $cancelfee_charged = $cancellation_fee;
        }

        $checkin_date = str_replace('/', '-', $request->checkin_date ?? '');
        $checkout_date = str_replace('/', '-', $request->checkout_date ?? '');

        $hotel = Hotel::find($request->company_id)
            ?? Hotel::where('company_code', $request->product_code)->first();

        $checkInTs = !empty($checkin_date) ? strtotime($checkin_date) : false;
        $checkOutTs = !empty($checkout_date) ? strtotime($checkout_date) : false;
        $no_of_nights = ($checkInTs && $checkOutTs && $checkOutTs > $checkInTs)
            ? max(1, (int) ceil(($checkOutTs - $checkInTs) / 86400))
            : 1;

        $data = [
            'hotel_id' => $hotel ? $hotel->id : 0,
            'product_code' => (string) ($request->product_code ?? ''),
            'option_id' => (string) ($request->bookfhrOptionId ?? $request->pl_id ?? ''),
            'bookfhr_search_id' => (string) ($request->bookfhrSearchId ?? ''),
            'hotel_name' => $request->hotel_name ?? ($hotel->display_name ?? ''),
            'room_title' => $request->room_title ?? '',
            'room_type' => $request->room_type ?? '',
            'airportID' => $request->airport ?: 0,
            'adults' => (int) $request->adults,
            'children' => (int) $request->children,
            'infants' => (int) $request->infants,
            'rooms' => (int) ($request->rooms ?? 1),
            'no_of_nights' => $no_of_nights,
            'booking_fee' => $bookingfee,
            'discount_amount' => $discount_amount,
            'booking_amount' => $booking_amount,
            'total_amount' => $total_amount,
            'discount_code' => $request->promo,
            'smsfee' => $smsfee_charged,
            'cancelfee' => $cancelfee_charged,
            'title' => $request->title,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'phone_number' => $request->contactno,
            'hotel_api' => $request->park_api,
            'booked_type' => 'hotel',
            'booking_action' => 'Abandon',
            'booking_status' => 'pending',
            'payment_status' => 'pending',
            'status' => 'Yes',
            'removed' => 'No',
            'browser_data' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'user_ip' => request()->ip(),
            'traffic_src' => session()->get('bk_src') ?: 'ORG',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($checkin_date) && !empty($request->checkin_time)) {
            $data['check_in'] = date('Y-m-d', strtotime($checkin_date)) . ' ' . date('H:i:s', strtotime($request->checkin_time));
        } elseif (!empty($checkin_date)) {
            $data['check_in'] = date('Y-m-d', strtotime($checkin_date));
        }
        if (!empty($checkout_date) && !empty($request->checkout_time)) {
            $data['check_out'] = date('Y-m-d', strtotime($checkout_date)) . ' ' . date('H:i:s', strtotime($request->checkout_time));
        } elseif (!empty($checkout_date)) {
            $data['check_out'] = date('Y-m-d', strtotime($checkout_date));
        }
        $data['check_in_time'] = $request->checkin_time ?? '';
        $data['check_out_time'] = $request->checkout_time ?? '';

        $intent_id = $request->input('intent_id') ?: Session::get('intent_id');
        if (!empty($intent_id)) {
            $data['token'] = $intent_id;
            $data['intent_id'] = $intent_id;
        }

        $pass = $this->randomPassword();
        $data_cust = [
            'title' => $request->title,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'phone_number' => $request->contactno,
            'password' => md5($pass),
            'address' => '',
            'town' => '',
            'added_on' => date('Y-m-d H:i:s'),
            'update_on' => date('Y-m-d H:i:s'),
        ];

        $customer_exist = customers::where('email', $request->email)->first();
        if ($customer_exist) {
            customers::where('email', $request->email)->update($data_cust);
            $customer_id = $customer_exist->id;
        } else {
            $customerData = customers::updateOrCreate(
                ['email' => $request->email],
                $data_cust
            );
            $customer_id = $customerData->id;
        }

        $data['customerId'] = $customer_id;
        $data['agentID'] = 9;

        $_current_time = date('Y-m-d H:i:s');
        $days_ago = date('Y-m-d H:i:s', strtotime('-1 days', strtotime($_current_time)));

        $data = $this->onlyBookingColumns($data);

        $existing = hotels_bookings::where('email', $request->input('email'))
            ->where('booking_action', 'Abandon')
            ->where('created_at', '>', $days_ago)
            ->first();

        if (!$existing) {
            $booking_id = DB::table('hotels_bookings')->insertGetId($data);

            $bookingref = 'PZH-';
            $bookingref .= date('y') . date('m') . date('d');
            $referenceNo = $bookingref . $booking_id;
            hotels_bookings::where('id', $booking_id)->update(['referenceNo' => $referenceNo]);
        } else {
            $referenceNo = $existing->referenceNo;
            hotels_bookings::where('referenceNo', $referenceNo)->update($data);
            $booking_id = $existing->id;
        }

        if (!empty($intent_id)) {
            Stripe::setApiKey($this->stripeKey);
            \Stripe\PaymentIntent::update(
                $intent_id,
                [
                    'amount' => (int) round($total_amount * 100),
                ]
            );
        }

        return response()->json([
            'booking_id' => $booking_id,
            'reference_no' => $referenceNo,
            'available' => 'Yes',
            'price' => round($booking_amount, 2),
            'booking_amount' => round($booking_amount, 2),
            'total_amount' => round($total_amount, 2),
        ]);
    }

    public function hotel_checkout(Request $request)
    {
        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;
        $sms_notification = $this->_setting['sms_notification_fee'] > 0 ? $this->_setting['sms_notification_fee'] : 0;
        $cancellation_fee = $this->_setting['cancellation_fee'] > 0 ? $this->_setting['cancellation_fee'] : 0;

        $booking_amount = (float) $request->input('booking_amount', 0);
        $discount_amount = (float) ($request->input('discount_amount') ?? $request->input('discount') ?? 0);
        $total_amount = $booking_amount + $bookingfee - $discount_amount;

        $output = [];

        if ($request->input('smsfee') === 'Yes') {
            $total_amount += $sms_notification;
            $output['sms_notification'] = $this->priceFormat($sms_notification, false);
        }
        if ($request->input('canfee') === 'Yes') {
            $total_amount += $cancellation_fee;
            $output['cancellation_fee'] = $this->priceFormat($cancellation_fee, false);
        }

        Stripe::setApiKey($this->stripeKey);
        $intent = \Stripe\PaymentIntent::create([
            'amount' => (int) round($total_amount * 100),
            'currency' => 'gbp',
        ]);
        Session::put('intent_id', $intent->id);
        Session::put('intent_secret', $intent->client_secret);

        $output['total_amount'] = $this->priceFormat($total_amount, false);
        $output['booking_amount'] = $booking_amount;
        $output['discount_amount'] = $this->priceFormat($discount_amount, false);
        $output['booking_fee'] = $this->priceFormat($bookingfee, false);
        $output['company_name'] = '';
        $output['intent_id'] = $intent->id;
        $output['intent_secret'] = $intent->client_secret;

        return response($output);
    }

    public function payout_hotel(Request $request)
    {
        $reference_no = $request->input('reference_no');
        $resp = json_decode(json_encode($request->input('result')));

        try {
            if ($request->input('park_api') === 'bookfhr') {
                $this->bookOnBookFhr($request, $reference_no);
            }

            $this->update_booking_payment($request, $resp, 'stripe');
            $this->submitHotelTransaction($reference_no);

            return response()->json($this->getResponse(1, 'payment successfully charged'));
        } catch (\Throwable $e) {
            Log::error('Hotel payout failed', [
                'reference_no' => $reference_no,
                'message' => $e->getMessage(),
            ]);

            return response()->json($this->getResponse(0, $e->getMessage()));
        }
    }

    public function bookOnBookFhr(Request $request, $reference_no)
    {
        $booking = hotels_bookings::where('referenceNo', $reference_no)->first();
        if (!$booking) {
            throw new \Exception('Hotel booking record not found');
        }

        $BookFhrService = new BookFhrService();
        $apiLog = [];

        $searchId = $request->bookfhrSearchId ?: $booking->bookfhr_search_id;
        $BookFhrService->assertSearchActive($searchId);

        $cartResult = $BookFhrService->createCart('GBP', 'en', null);
        $apiLog['createCart'] = $cartResult;
        $cartId = $cartResult['data']['cartId'] ?? null;

        if (!$cartId) {
            Log::error('BookFHR hotel cart creation failed', $cartResult ?? []);
            $this->saveBookFhrApiLog($reference_no, $apiLog);
            throw new \Exception('Unable to create BookFHR cart');
        }

        $productCode = str_replace('BOOKFHR_', '', (string) ($request->product_code ?: $booking->product_code));
        $productCode = is_numeric($productCode) ? (int) $productCode : $productCode;

        $cartData = [
            'searchId' => $searchId,
            'product' => $productCode,
            'option' => $request->bookfhrOptionId ?: $booking->option_id,
            'qty' => 1,
            'type' => 'Hotel',
        ];

        $addToCartResult = $BookFhrService->addToCart($cartId, $cartData);
        $apiLog['addToCart'] = $addToCartResult;
        $itemId = $addToCartResult['data']['items'][0]['_id'] ?? null;

        if (!$itemId) {
            Log::error('BookFHR hotel addToCart failed', $addToCartResult ?? []);
            $this->saveBookFhrApiLog($reference_no, $apiLog, $cartId, null);
            throw new \Exception('Unable to add hotel to BookFHR cart');
        }

        $customer = [
            'title' => $request->title ?? $booking->title ?? 'Mr',
            'firstName' => $request->firstname ?? $booking->first_name ?? '',
            'lastName' => $request->lastname ?? $booking->last_name ?? '',
            'email' => $request->email ?? $booking->email ?? '',
            'phone' => $request->contactno ?? $booking->phone_number ?? '',
        ];

        $counts = [
            'adults' => (int) ($request->adults ?? $booking->adults ?? 1),
            'children' => (int) ($request->children ?? $booking->children ?? 0),
            'infants' => (int) ($request->infants ?? $booking->infants ?? 0),
        ];

        $flight = [
            'inbound_flight' => $request->input('deptFlight') ?: $request->input('flight_number') ?: 'TBA',
            'inbound_terminal' => $request->input('departterminal') ?: 'TBA',
            'outbound_flight' => $request->input('returnflight') ?: 'TBA',
            'outbound_terminal' => $request->input('arrivalterminal') ?: 'TBA',
        ];

        $orderData = [
            'customer' => $customer,
            'products' => [
                $BookFhrService->buildHotelProduct($itemId, $customer, $counts, $flight),
            ],
            'accountId' => config('services.bookfhr.partner_id'),
            'partnerRef' => $reference_no,
            'reference' => $reference_no,
        ];

        $submitOrder = $BookFhrService->submitOrder($cartId, $orderData);
        $apiLog['submitOrder'] = $submitOrder;
        Log::info('BookFHR hotel submitOrder', $submitOrder ?? []);

        $orderRef = $submitOrder['data']['order'] ?? null;

        if (!$orderRef || empty($submitOrder['success']) || ($submitOrder['data']['success'] ?? true) === false) {
            Log::error('BookFHR hotel submitOrder failed', $submitOrder ?? []);
            $this->saveBookFhrApiLog($reference_no, $apiLog, $cartId, $itemId);
            $message = $submitOrder['data']['message']
                ?? (is_array($submitOrder['error'] ?? null) ? ($submitOrder['error']['message'] ?? null) : ($submitOrder['error'] ?? null))
                ?? 'BookFHR hotel order submission failed';
            throw new \Exception($message);
        }

        $paymentRef = $booking->PayerID;
        if ($paymentRef) {
            $confirmOrder = $BookFhrService->confirmOrder($orderRef, $paymentRef);
            $apiLog['confirmOrder'] = $confirmOrder;
            Log::info('BookFHR hotel confirmOrder', $confirmOrder ?? []);
            if (empty($confirmOrder['success'])) {
                Log::error('BookFHR hotel confirmOrder failed', $confirmOrder ?? []);
            }
        }

        $update = [
            'ext_ref' => $orderRef,
            'bookfhr_cart_id' => $cartId,
            'bookfhr_item_id' => $itemId,
            'bookfhr_api_res' => json_encode($apiLog),
        ];
        if (Schema::hasColumn('hotels_bookings', 'referenceNo_ext')) {
            $update['referenceNo_ext'] = $orderRef;
        }

        hotels_bookings::where('referenceNo', $reference_no)->update($this->onlyBookingColumns($update));
    }

    public function update_booking_payment($request, $paymentresponse, $payment_type)
    {
        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;
        $smsFeeSetting = (float) ($this->_setting['sms_notification_fee'] ?? 0);
        $cancelFeeSetting = (float) ($this->_setting['cancellation_fee'] ?? 0);

        $smsRaw = $request->input('smsfee');
        $cancelRaw = $request->input('cancelfee');

        // Columns are numeric doubles — never store Yes/No strings
        $smsSelected = $smsRaw === 'Yes' || $smsRaw === 1 || $smsRaw === '1' || (is_numeric($smsRaw) && (float) $smsRaw > 0);
        $cancelSelected = $cancelRaw === 'Yes' || $cancelRaw === 1 || $cancelRaw === '1' || (is_numeric($cancelRaw) && (float) $cancelRaw > 0);

        $data = [
            'booking_fee' => $bookingfee,
            'discount_code' => $request->input('promo'),
            'cancelfee' => $cancelSelected ? (is_numeric($cancelRaw) && (float) $cancelRaw > 0 ? (float) $cancelRaw : $cancelFeeSetting) : 0,
            'smsfee' => $smsSelected ? (is_numeric($smsRaw) && (float) $smsRaw > 0 ? (float) $smsRaw : $smsFeeSetting) : 0,
            'payment_status' => 'success',
            'payment_method' => $payment_type,
            'booking_status' => 'completed',
            'booking_action' => 'Booked',
        ];

        $allTotal = (float) $request->input('alltotal', 0);
        if ($allTotal > 0) {
            $data['total_amount'] = $allTotal;
        }

        if ($payment_type === 'stripe') {
            $data['api_res'] = json_encode($paymentresponse);
            $data['PayerID'] = $paymentresponse->paymentIntent->id ?? null;
            $data['intent_id'] = $paymentresponse->paymentIntent->id ?? null;
        }

        DB::table('hotels_bookings')
            ->where('referenceNo', $request->input('reference_no'))
            ->update($this->onlyBookingColumns($data));

        if (!empty($data['discount_code']) && Schema::hasTable('discounts')) {
            DB::table('discounts')
                ->where('promo', $data['discount_code'])
                ->update(['used_at' => date('Y-m-d H:i:s')]);
        }

        $row = hotels_bookings::where('referenceNo', $request->input('reference_no'))->first();
        $airport_detail = airport::find($request->input('airport'));

        $template_data = [
            'username' => $request->input('firstname') . ' ' . $request->input('lastname'),
            'email' => $request->input('email'),
            'telephone' => $row->phone_number ?? $request->input('contactno'),
            'company' => $row->hotel_name ?? $request->input('hotel_name'),
            'hotel_name' => $row->hotel_name ?? $request->input('hotel_name'),
            'carpark' => 'Hotel',
            'c_parent' => $row->hotel_name ?? $request->input('hotel_name'),
            'ptype' => $row->room_title ?: ($row->room_type ?? 'Hotel Room'),
            'airport' => $airport_detail ? $airport_detail->name : '',
            'days' => $row->no_of_nights ?? 1,
            'start_date' => $row->check_in ?? '',
            'end_date' => $row->check_out ?? '',
            'booktime' => date('Y-m-d H:i:s'),
            'adults' => $row->adults ?? '0',
            'children' => $row->children ?? '0',
            'infants' => $row->infants ?? '0',
            'payment_gatway' => $payment_type,
            'payment_status' => 'success',
            'price' => $row->total_amount ?? 0,
            'c_price' => $row->booking_amount ?? 0,
            'addtionalprice' => 0,
            'ref' => $row->referenceNo ?? '',
            'ext_ref' => $row->ext_ref ?? '',
            'c_code' => $row->product_code ?? '',
        ];

        try {
            $email_send = new EmailController();
            $toemails = array_filter([$request->input('email'), 'bookings@totaltravelsolutions.co.uk']);
            $email_send->sendEmail($this->bookingEmailTemplate(), $toemails, $template_data);

            if (Schema::hasColumn('hotels_bookings', 'email_check')) {
                DB::table('hotels_bookings')
                    ->where('referenceNo', $request->input('reference_no'))
                    ->update(['email_check' => '1']);
            }
        } catch (\Throwable $e) {
            Log::error('Hotel confirmation email failed', [
                'reference_no' => $request->input('reference_no'),
                'message' => $e->getMessage(),
            ]);
        }

        return true;
    }

    public function submitHotelTransaction($ref_no)
    {
        if (!Schema::hasTable('hotel_booking_transaction')) {
            return true;
        }

        $order_detail = hotels_bookings::where('referenceNo', $ref_no)->first();
        if (!$order_detail) {
            return false;
        }

        $d = [
            'orderID' => $order_detail->id,
            'token' => $order_detail->PayerID ?? '',
            'referenceNo' => $ref_no,
            'hotelId' => $order_detail->hotel_id ?? 0,
            'booking_amount' => $order_detail->booking_amount ?? 0,
            'extra_amount' => 0,
            'discount_amount' => $order_detail->discount_amount ?? 0,
            'smsfee' => $order_detail->smsfee ?? 0,
            'booking_fee' => $order_detail->booking_fee ?? 0,
            'cancelfee' => $order_detail->cancelfee ?? 0,
            'total_amount' => $order_detail->total_amount ?? 0,
            'payable' => 0,
            'amount_type' => 'credit',
            'payment_method' => $order_detail->payment_method ?? 'stripe',
            'payment_action' => 'success',
            'payment_case' => 'cancel', // same convention as parking booking_transaction inserts
            'payment_medium' => '',
            'palenty_amount' => '0',
            'palenty_to' => '',
            'comments' => '',
            'modifydate' => date('Y-m-d H:i:s'),
            'added_on' => date('Y-m-d H:i:s'),
            'booking_status' => $order_detail->booking_status ?: 'Completed',
            'edit_by' => 0, // frontend/customer booking — no admin editor
        ];

        DB::table('hotel_booking_transaction')->insert(
            array_intersect_key($d, array_flip(Schema::getColumnListing('hotel_booking_transaction')))
        );

        return true;
    }

    public function thankyou($id)
    {
        $airports = airport::all()->where('status', 'Yes');
        $booking = hotels_bookings::where('referenceNo', $id)->first();

        if (!$booking) {
            return redirect('/')->with('error', 'Booking not found.');
        }

        $airport_detail = airport::where('id', $booking->airportID)->first();

        return view('frontend.thankyou_hotel', [
            'airports' => $airports,
            'booking' => $booking,
            'airport_detail' => $airport_detail,
            'hotel_name' => $booking->hotel_name ?: 'Airport Hotel',
        ]);
    }

    /**
     * Refresh a stale BookFHR hotel search so a re-submit can use live pricing.
     */
    public function refreshBookFhrHotelSearch(Request $request, $productId, $optionId): array
    {
        $airportDetail = airport::find($request->input('airport'));
        if (!$airportDetail || empty($airportDetail->iata_code)) {
            return [];
        }

        $checkinDate = date('Y-m-d', strtotime(str_replace('/', '-', (string) $request->input('checkin_date'))));
        $checkoutDate = date('Y-m-d', strtotime(str_replace('/', '-', (string) $request->input('checkout_date'))));

        $hotelService = app(HotelService::class);
        $result = $hotelService->search([
            'location' => $airportDetail->iata_code,
            'dateFrom' => $checkinDate,
            'timeFrom' => date('H:i', strtotime((string) ($request->input('checkin_time') ?: '14:00'))),
            'dateTo' => $checkoutDate,
            'timeTo' => date('H:i', strtotime((string) ($request->input('checkout_time') ?: '11:00'))),
            'adults' => max(1, (int) $request->input('adults', 1)),
            'children' => max(0, (int) $request->input('children', 0)),
            'infants' => max(0, (int) $request->input('infants', 0)),
            'rooms' => max(1, (int) $request->input('rooms', 1)),
            'roomType' => $request->input('room_type') ?: 'Double',
            'currency' => 'GBP',
        ]);

        if (empty($result['success'])) {
            Log::error('BookFHR hotel refresh search failed', $result);

            return [];
        }

        $searchId = $result['data']['searchId'] ?? null;
        $matchedOption = null;
        $matchedProduct = null;

        foreach (($result['data']['results'] ?? []) as $item) {
            $product = $item['product'] ?? [];
            if ((string) ($product['id'] ?? '') !== (string) $productId) {
                continue;
            }
            $matchedProduct = $product['id'];
            foreach (($item['options'] ?? []) as $option) {
                if ((string) ($option['id'] ?? '') === (string) $optionId) {
                    $matchedOption = $option['id'];
                    break 2;
                }
            }
            // Same hotel, take the first available room option if the exact one is gone.
            if (!empty($item['options'][0]['id'])) {
                $matchedOption = $item['options'][0]['id'];
                break;
            }
        }

        if (!$searchId || $matchedOption === null) {
            return [];
        }

        return [
            'searchId' => $searchId,
            'productId' => is_numeric($matchedProduct) ? (int) $matchedProduct : $productId,
            'optionId' => is_numeric($matchedOption) ? (int) $matchedOption : $matchedOption,
        ];
    }

    private function saveBookFhrApiLog(string $referenceNo, array $apiLog, ?string $cartId = null, ?string $itemId = null): void
    {
        $update = ['bookfhr_api_res' => json_encode($apiLog)];
        if ($cartId) {
            $update['bookfhr_cart_id'] = $cartId;
        }
        if ($itemId) {
            $update['bookfhr_item_id'] = $itemId;
        }

        hotels_bookings::where('referenceNo', $referenceNo)->update($this->onlyBookingColumns($update));
    }

    /**
     * hotels_bookings carries a few more columns on the CRM than on the mainsite,
     * so drop anything this database does not know about. An unknown table is left
     * untouched so the query itself surfaces the real error.
     */
    private function onlyBookingColumns(array $data): array
    {
        $columns = Schema::getColumnListing('hotels_bookings');

        return $columns ? array_intersect_key($data, array_flip($columns)) : $data;
    }

    /**
     * Hotel-specific template if the CRM has one, otherwise reuse the lounge layout.
     */
    private function bookingEmailTemplate(): string
    {
        foreach (['Client Hotel booking', 'Add Booking Hotel'] as $title) {
            if (email_templates::where('title', $title)->exists()) {
                return $title;
            }
        }

        return 'Client Lounge booking';
    }

    private function resolveHotelBookingImage(array $searchData, $hotelRecord, $requestLogo = null): string
    {
        $apiImages = Hotel::normalizeImageList(array_merge(
            !empty($searchData['image']) ? [$searchData['image']] : [],
            $searchData['images'] ?? []
        ));

        if (!empty($apiImages[0])) {
            return $apiImages[0];
        }

        $requestImage = Hotel::formatImageUrl($requestLogo ?: ($searchData['image'] ?? ''));
        if ($requestImage !== '') {
            return $requestImage;
        }

        if ($hotelRecord) {
            return $hotelRecord->getImageUrl($searchData['image'] ?? null);
        }

        return asset('favicon.ico');
    }

    public function priceFormat($price, $symbol = true)
    {
        // Hotel stays can exceed £1,000 — the raw form is consumed by JS parseFloat,
        // so it must never carry a thousands separator.
        if (!$symbol) {
            return number_format((float) $price, 2, '.', '');
        }

        return '&pound;' . number_format((float) $price, 2);
    }

    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $pass[] = $alphabet[random_int(0, $alphaLength)];
        }

        return implode($pass);
    }

    public function getResponse($success, $data)
    {
        return [
            'success' => $success,
            'data' => $data,
        ];
    }
}
