<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Library\aph_functions;
use App\Models\airport;
use App\Models\customers;
use App\Models\discounts;
use App\Models\Lounges;
use App\Models\lounges_bookings;
use App\Models\modules_settings;
use App\Models\settings;
use App\Services\BookFhrService;
use App\Services\LoungeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;

class LoungeController extends Controller
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
    }

    public function addBookingFormLounge(Request $request)
    {
        session(['lounge_booking_context' => $this->buildLoungeBookingData($request)]);

        return redirect()->route('booking_lounge.show');
    }

    public function showBookingFormLounge(Request $request)
    {
        $data = session('lounge_booking_context');

        if (!is_array($data) || (empty($data['company_id']) && empty($data['product_code']))) {
            return redirect('/')->with('error', 'Your booking session has expired. Please select a lounge again.');
        }

        $airports = airport::where('status', 'Yes')->get();

        return view('frontend.booking_lounge', [
            'data' => $data,
            'settings' => $this->_setting,
            'airports' => $airports,
        ]);
    }

    private function buildLoungeBookingData(Request $request): array
    {
        $searchData = json_decode($request->input('search_data'), true) ?: [];

        $data = [
            'company_id' => $request->input('company_id', $searchData['lounge_db_id'] ?? $searchData['companyID'] ?? null),
            'product_code' => $request->input('product_code', $searchData['product_id'] ?? $searchData['companyID'] ?? null),
            'booking_amount' => $request->input('booking_amount', $searchData['price'] ?? 0),
            'discount_amount' => $request->input('discount_amount', $searchData['discount_applied'] ?? 0),
            'discount_code' => $request->input('promo', $request->input('discount_code', '')),
            'lounge_name' => $request->input('lounge_name', $searchData['displayName'] ?? $searchData['name'] ?? ''),
            'terminal' => $request->input('terminal', $searchData['terminal'] ?? ''),
            'checkin_date' => $request->input('checkin_date', $request->input('checkIn_date', '')),
            'checkin_time' => $request->input('checkin_time', $request->input('checkIn_time', '')),
            'adults' => $request->input('adults', $request->input('aladults', 0)),
            'children' => $request->input('children', $request->input('alchildren', 0)),
            'infants' => $request->input('infants', $request->input('alinfants', 0)),
            'airport' => $request->input('airport', $request->input('airport_id', '')),
            'pl_id' => $request->input('pl_id', $searchData['option_id'] ?? ''),
            'park_api' => $request->input('park_api', $searchData['park_api'] ?? ''),
            'bookfhrSearchId' => $request->input('bookfhrSearchId', $searchData['searchId'] ?? ''),
            'bookfhrOptionId' => $request->input('bookfhrOptionId', $searchData['option_id'] ?? ''),
            'logobooking' => $request->input('logobooking', '0'),
            'bookingfor' => 'lounge',
            'email' => $request->input('email', ''),
            'aphactive' => $request->input('aphactive', ''),
            'site_codename' => $request->input('site_codename', ''),
        ];

        $loungeRecord = null;
        if (!empty($data['company_id'])) {
            $loungeRecord = Lounges::find($data['company_id']);
        }
        if (!$loungeRecord) {
            $loungeCode = $searchData['product_id'] ?? $data['product_code'] ?? null;
            if ($loungeCode) {
                $loungeRecord = Lounges::where('lounge_code', $loungeCode)
                    ->orWhere('company_code', $loungeCode)
                    ->first();
            }
        }

        $data['logo'] = $this->resolveLoungeBookingImage($searchData, $loungeRecord, $request->input('logo'));
        $data['logobooking2'] = $data['logo'];
        $data['cancellation_label'] = $searchData['cancellation_label'] ?? '';
        $data['location_info'] = $searchData['locationInfo'] ?? '';

        return $data;
    }

    public function checkBookingLounge(Request $request)
    {
        try {
            return $this->processCheckBookingLounge($request);
        } catch (\Throwable $e) {
            Log::error('checkBookingLounge failed', [
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

    private function processCheckBookingLounge(Request $request)
    {
        $lounge_data = $request->all();

        $browser_data = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;

        $discountAmount = $lounge_data['discount_amount'] ?? $lounge_data['discount'] ?? 0;
        $total_amount = ($lounge_data['booking_amount'] * 1) + ($bookingfee * 1) - ($discountAmount * 1);

        $checkin_date = str_replace('/', '-', $lounge_data['checkin_date'] ?? '');

        $lounge = Lounges::where('id', $lounge_data['company_id'] ?? null)->first()
            ?? Lounges::where('lounge_code', $lounge_data['company_id'] ?? null)->first()
            ?? Lounges::where('lounge_code', $lounge_data['product_code'] ?? null)->first()
            ?? Lounges::where('company_code', $lounge_data['product_code'] ?? null)->first();

        $data = [];
        $data['lounge_id'] = $lounge ? $lounge->id : 0;
        $data['lounge_name'] = $lounge_data['lounge_name'] ?? ($lounge->title ?? $lounge->name ?? '');
        $data['lounge_code'] = $lounge_data['product_code'] ?? '';
        $data['terminal'] = $lounge_data['terminal'] ?? '';

        if (!empty($checkin_date) && !empty($lounge_data['checkin_time'])) {
            $data['check_in'] = date('Y-m-d', strtotime($checkin_date)).' '.date('H:i:s', strtotime($lounge_data['checkin_time']));
        } elseif (!empty($checkin_date)) {
            $data['check_in'] = date('Y-m-d', strtotime($checkin_date));
        }

        $data['check_in_time'] = $lounge_data['checkin_time'] ?? '';
        $data['adults'] = $lounge_data['adults'] ?? 1;
        $data['airportID'] = $lounge_data['airport'] ?? 0;
        $data['children'] = $lounge_data['children'] ?? 0;
        $data['infants'] = $lounge_data['infants'] ?? 0;
        $data['booking_fee'] = $bookingfee;
        $data['discount_amount'] = $discountAmount;
        $data['booking_amount'] = $lounge_data['booking_amount'] ?? 0;
        $data['total_amount'] = $total_amount;
        $data['discount_code'] = $lounge_data['promo'] ?? '';
        $data['title'] = $request->title;
        $data['first_name'] = $request->firstname;
        $data['last_name'] = $request->lastname;
        $data['email'] = $request->email;
        $data['phone_number'] = $request->contactno;
        $data['lounge_api'] = $request->park_api;
        $data['browser_data'] = $browser_data;
        $data['booking_action'] = 'Abandon';
        $data['booking_status'] = 'pending';
        $data['payment_status'] = 'pending';
        $data['status'] = 'Yes';
        $data['removed'] = 'No';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $airport = airport::where('id', $data['airportID'])->first();
        if ($airport) {
            if (Schema::hasColumn('lounges_bookings', 'country_code')) {
                $data['country_code'] = $airport->country_code ?? $airport->code ?? 'GB';
            }
            if (Schema::hasColumn('lounges_bookings', 'airport_code')) {
                $data['airport_code'] = $airport->airport_code ?? $airport->code ?? $airport->iata ?? '';
            }
        } elseif (Schema::hasColumn('lounges_bookings', 'country_code')) {
            $data['country_code'] = 'GB';
        }

        $intent_id = $lounge_data['intent_id'] ?? Session::get('intent_id');
        if (!empty($intent_id)) {
            $data['token'] = $intent_id;
            $data['intent_id'] = $intent_id;
        }

        $_current_time = date('Y-m-d H:i:s');
        $days_ago = date('Y-m-d H:i:s', strtotime('-1 days', strtotime($_current_time)));

        $pass = $this->randomPassword();
        $data_cust = [];
        $data_cust['title'] = $request->title;
        $data_cust['first_name'] = $request->firstname;
        $data_cust['last_name'] = $request->lastname;
        $data_cust['email'] = $request->email;
        $data_cust['phone_number'] = $request->contactno;
        $data_cust['password'] = md5($pass);
        $data_cust['address'] = '';
        $data_cust['town'] = '';
        $data_cust['added_on'] = date('Y-m-d H:i:s');
        $data_cust['update_on'] = date('Y-m-d H:i:s');

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

        if (session()->get('bk_src') != '') {
            $data['traffic_src'] = session()->get('bk_src');
        } else {
            $data['traffic_src'] = 'ORG';
        }

        $data['customerId'] = $customer_id;
        $data['agentID'] = 9;

        $referenceNo = lounges_bookings::where('email', $request->input('email'))
            ->where('booking_action', 'Abandon')
            ->where('created_at', '>', $days_ago)
            ->first();

        if ($referenceNo) {
            $referenceNo = $referenceNo->referenceNo;
        }

        // Only insert columns that exist on jetseeker lounges_bookings
        $allowedCols = Schema::getColumnListing('lounges_bookings');
        $data = array_intersect_key($data, array_flip($allowedCols));

        if ($referenceNo == null) {
            $booking_id = DB::table('lounges_bookings')->insertGetId($data);

            $bookingref = 'PZL-';
            $bookingref .= date('y').date('m').date('d');
            $bookingref = $bookingref.$booking_id;
            $data2['referenceNo'] = $bookingref;
            $referenceNo = $bookingref;
            lounges_bookings::where('id', $booking_id)->update($data2);
        } else {
            $booking = lounges_bookings::where('referenceNo', $referenceNo)->first();
            if ($booking) {
                lounges_bookings::where('referenceNo', $referenceNo)->update($data);
                $booking_id = $booking->id;
            } else {
                $booking_id = 0;
            }
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
        ]);
    }

    public function lounge_checkout(Request $request)
    {
        $airport = $request->input('airport') ?? 0;
        $company_id = $request->input('company_id') ?? 0;
        $product_code = $request->input('product_code');
        $checkin_date = $request->input('checkin_date');
        $checkin_time = $request->input('checkin_time');
        $adult = $request->input('adults');
        $children = $request->input('children');
        $infants = $request->input('infants') ?? 0;
        $promo = $request->input('promo');
        $bookingfor = $request->input('bookingfor') ?: 'lounge';
        $park_api = $request->input('park_api');
        $smsfee = $request->input('smsfee') ?? 'No';
        $canfee = $request->input('canfee') ?? 'No';
        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;
        $sms_notification = $this->_setting['sms_notification_fee'] > 0 ? $this->_setting['sms_notification_fee'] : 0;
        $cancellation_fee = $this->_setting['cancellation_fee'] > 0 ? $this->_setting['cancellation_fee'] : 0;

        $booking_amount = (float) $request->input('booking_amount', 0);
        $discount_amount = (float) ($request->input('discount_amount') ?? $request->input('discount') ?? 0);
        $total_amount = 0.00;
        $extra = 0.00;
        $l_fee = 0;
        $output = [];

        if ($park_api == 'holiday') {
            $aph_functions = new aph_functions();
            $booking_amount = $aph_functions->HolidayBookingLoungePrice($product_code, $checkin_date, $checkin_time, $adult, $children);
            $discount_amount = 0;
        }

        if ($park_api == 'bookfhr') {
            $booking_amount = (float) $request->input('booking_amount', 0);
        }

        if ($promo != '' && $park_api != 'bookfhr') {
            $dis = new discounts();
            $discount_amount = $dis->getPromoDiscount($promo, $booking_amount, 'airport_lounges', '');
        }

        $total_amount = ($booking_amount * 1) + ($bookingfee * 1) - ($discount_amount * 1);

        if ($smsfee == 'Yes') {
            $total_amount = $total_amount + ($sms_notification * 1);
            $output['sms_notification'] = $this->priceFormat($sms_notification * 1, false);
        }
        if ($canfee == 'Yes') {
            $total_amount = $total_amount + ($cancellation_fee * 1);
            $output['cancellation_fee'] = $this->priceFormat($cancellation_fee * 1, false);
        }
        if ($l_fee > 0) {
            $total_amount = $total_amount + ($l_fee * 1);
            $output['l_fee'] = $this->priceFormat($l_fee * 1, false);
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

    public function payout_lounge(Request $request)
    {
        $reference_no = $request->input('reference_no');
        $park_api = $request->input('park_api');
        $resp = $request->input('result');
        $resp = json_encode($resp);
        $resp = json_decode($resp);

        try {
            if ($park_api == 'holiday') {
                $holidayorder = $this->bookOnHoliday($request);
                $ext_ref = isset($holidayorder['BookingRef']) ? $holidayorder['BookingRef'] : '';
                $url = isset($holidayorder['MoreInfoURL']) ? $holidayorder['MoreInfoURL'] : '';
                $aphData = ['ext_ref' => $ext_ref];
                if (Schema::hasColumn('lounges_bookings', 'referenceLink_ext')) {
                    $aphData['referenceLink_ext'] = $url;
                }
                lounges_bookings::where('referenceNo', $reference_no)->update($aphData);
            }

            if ($park_api == 'bookfhr') {
                $this->bookOnBookFhr($request, $reference_no, $resp);
            }

            $this->update_booking_payment($request, $resp, 'stripe');

            return response()->json($this->getResponse(1, 'payment successfully charged'));
        } catch (\Throwable $e) {
            Log::error('Lounge payout failed', [
                'reference_no' => $reference_no,
                'message' => $e->getMessage(),
            ]);

            return response()->json($this->getResponse(0, $e->getMessage()));
        }
    }

    public function payout_failed_lounge(Request $request)
    {
        $reference_no = $request->input('reference_no');
        $park_api = $request->input('park_api');
        $resp = $request->input('result');
        $resp = json_encode($resp);
        $resp = json_decode($resp);

        if ($park_api == 'holiday') {
            $holidayorder = $this->bookOnHoliday($request);
            $ext_ref = isset($holidayorder['BookingRef']) ? $holidayorder['BookingRef'] : '';
            $url = isset($holidayorder['MoreInfoURL']) ? $holidayorder['MoreInfoURL'] : '';
            $aphData = ['ext_ref' => $ext_ref];
            if (Schema::hasColumn('lounges_bookings', 'referenceLink_ext')) {
                $aphData['referenceLink_ext'] = $url;
            }
            lounges_bookings::where('referenceNo', $reference_no)->update($aphData);
        }

        $this->update_booking_payment($request, $resp, 'stripe');

        return response()->json($this->getResponse(1, 'payment successfully charged'));
    }

    public function update_booking_payment($request, $paymentresponse, $payment_type)
    {
        $data = [];

        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;
        $smsFeeSetting = (float) ($this->_setting['sms_notification_fee'] ?? 0);
        $cancelFeeSetting = (float) ($this->_setting['cancellation_fee'] ?? 0);

        $smsRaw = $request->input('smsfee');
        $cancelRaw = $request->input('cancelfee');

        // Columns are numeric doubles — never store Yes/No strings
        $smsSelected = $smsRaw === 'Yes' || $smsRaw === 1 || $smsRaw === '1' || (is_numeric($smsRaw) && (float) $smsRaw > 0);
        $cancelSelected = $cancelRaw === 'Yes' || $cancelRaw === 1 || $cancelRaw === '1' || (is_numeric($cancelRaw) && (float) $cancelRaw > 0);

        $data['booking_fee'] = $bookingfee;
        $data['discount_code'] = $request->input('promo');
        $data['cancelfee'] = $cancelSelected ? (is_numeric($cancelRaw) && (float) $cancelRaw > 0 ? (float) $cancelRaw : $cancelFeeSetting) : 0;
        $data['smsfee'] = $smsSelected ? (is_numeric($smsRaw) && (float) $smsRaw > 0 ? (float) $smsRaw : $smsFeeSetting) : 0;
        $data['payment_status'] = 'success';
        $data['payment_method'] = $payment_type;
        $data['booking_status'] = 'completed';
        $data['booking_action'] = 'Booked';

        if ($payment_type == 'stripe') {
            $data['api_res'] = json_encode($paymentresponse);
            $data['PayerID'] = $paymentresponse->paymentIntent->id ?? null;
        }

        DB::table('lounges_bookings')
            ->where('referenceNo', $request->input('reference_no'))
            ->update($data);

        $this->submitTransaction($request->input('reference_no'));

        $row = lounges_bookings::where('referenceNo', $request->input('reference_no'))->first();
        $airport_detail = airport::where('id', $request->input('airport'))->first();

        $loungeTitle = $request->input('lounge_name');
        if ($row && $row->lounge) {
            $loungeTitle = $row->lounge->title ?? $row->lounge->name ?? $loungeTitle;
        }

        $template_data = [];
        $template_data['username'] = $request->input('firstname').' '.$request->input('lastname');
        $template_data['email'] = $request->input('email');
        $template_data['telephone'] = $request->input('contactno');
        $template_data['lounge_name'] = $loungeTitle;
        $template_data['company'] = $loungeTitle;
        $template_data['airport'] = $airport_detail ? $airport_detail->name : '';
        $template_data['start_date'] = $request->input('checkin_date').' '.$request->input('checkin_time');
        $template_data['booktime'] = date('Y-m-d H:i:s');
        $template_data['terminal'] = 'Terminal '.$request->input('terminal');
        $template_data['adults'] = $row->adults ?? '0';
        $template_data['children'] = $row->children ?? '0';
        $template_data['infants'] = $row->infants ?? '0';
        $template_data['payment_gatway'] = $payment_type;
        $template_data['payment_status'] = 'success';
        $template_data['price'] = $row->total_amount ?? 0;
        $template_data['addtionalprice'] = 0;
        $template_data['ref'] = $request->input('reference_no');
        $template_data['ext_ref'] = $row->ext_ref ?? $row->referenceNo_ext ?? '';

        try {
            $email_send = new EmailController();
            $toemails = array_filter([$request->input('email'), 'bookings@totaltravelsolutions.co.uk']);
            $email_send->sendEmail('Client Lounge booking', $toemails, $template_data);

            if (Schema::hasColumn('lounges_bookings', 'customer_email')) {
                DB::table('lounges_bookings')
                    ->where('referenceNo', $request->input('reference_no'))
                    ->update(['customer_email' => '1', 'comp_email' => '1']);
            }
        } catch (\Throwable $e) {
            Log::error('Lounge confirmation email failed', [
                'reference_no' => $request->input('reference_no'),
                'message' => $e->getMessage(),
            ]);
        }

        return true;
    }

    public function submitTransaction($ref_no)
    {
        $order_detail = lounges_bookings::where('referenceNo', $ref_no)->first();
        $d = [];
        $d['orderID'] = $order_detail->id;
        $d['token'] = $order_detail->PayerID ?? '';
        $d['referenceNo'] = $ref_no;
        $d['loungeID'] = $order_detail->lounge_id ?? 0;
        $d['booking_amount'] = $order_detail->booking_amount ?? 0;
        $d['extra_amount'] = $order_detail->extra_amount ?? 0;
        $d['discount_amount'] = $order_detail->discount_amount ?? 0;
        $d['smsfee'] = $order_detail->smsfee ?? 0;
        $d['booking_fee'] = $order_detail->booking_fee ?? 0;
        $d['cancelfee'] = $order_detail->cancelfee ?? 0;
        $d['total_amount'] = $order_detail->total_amount ?? 0;
        $d['payable'] = 0;
        $d['amount_type'] = 'credit';
        $d['payment_method'] = $order_detail->payment_method ?? 'stripe';
        $d['payment_action'] = 'success';
        $d['payment_case'] = 'cancel'; // same convention as parking booking_transaction inserts
        $d['payment_medium'] = '';
        $d['palenty_amount'] = '0';
        $d['palenty_to'] = '';
        $d['comments'] = '';
        $d['modifydate'] = date('Y-m-d H:i:s');
        $d['added_on'] = date('Y-m-d H:i:s');
        $d['booking_status'] = $order_detail->booking_status ?: 'Completed';
        $d['edit_by'] = 0; // frontend/customer booking — no admin editor

        DB::table('lounges_transaction')->insert($d);

        return true;
    }

    public function priceFormat($price, $symbol = true)
    {
        $formated_price = '';
        if ($symbol) {
            $formated_price .= '&pound;';
        }
        $formated_price .= number_format(((float) $price * 1), 2);

        return $formated_price;
    }

    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    public function thankyou($id)
    {
        $airports = airport::all()->where('status', 'Yes');
        $booking = lounges_bookings::with('lounge')->where('referenceNo', $id)->first();

        if (!$booking) {
            return redirect('/')->with('error', 'Booking not found.');
        }

        $airport_detail = airport::where('id', $booking->airportID)->first();
        $lounge_name = optional($booking->lounge)->title
            ?? optional($booking->lounge)->name
            ?? ($booking->lounge_name ?? 'Airport Lounge');

        return view('frontend.thankyou_lounge', [
            'airports' => $airports,
            'booking' => $booking,
            'airport_detail' => $airport_detail,
            'lounge_name' => $lounge_name,
        ]);
    }

    public function bookOnHoliday($request)
    {
        $reference_no = $request->input('reference_no');
        $booking = lounges_bookings::where('referenceNo', $reference_no)->first();
        $product_code = $request->input('product_code');
        $booking_amount = $booking->booking_amount;

        $ArrivalDate = date('Y-m-d', strtotime($booking->check_in));
        $ArrivalTime = date('Hi', strtotime($booking->check_in_time));

        $adults = $booking->adults;
        $children = $booking->children;
        $infants = $booking->infants ?? 0;

        $title = $request->input('title');
        $first_name = $request->input('firstname');
        $last_name = $request->input('lastname');
        $phone_number = $request->input('contactno');
        $email = $request->input('email');

        $aph_functions = new aph_functions();

        $string = '&ArrivalDate='.$ArrivalDate.'&ArrivalTime='.$ArrivalTime.'&Adults='.$adults.'&Children='.$children.'&Infants='.$infants;
        $string .= '&Title='.$title.'&Initial='.$first_name.'&Surname='.$last_name.'&Address=NA&Town=NA&County=NA&PostCode=NA';
        $string .= '&Email='.$email.'&MobileNum='.$phone_number.'&PriceCheckFlag=Y&PriceCheckPrice='.$booking_amount;

        return $aph_functions->HolidayBookingOrderLounge($string, $product_code);
    }

    public function bookOnBookFhr(Request $request, $reference_no, $paymentResponse)
    {
        $BookFhrService = new BookFhrService();
        $apiLog = [];

        $searchId = $request->bookfhrSearchId ?: $request->input('bookfhrSearchId');
        $optionId = $request->bookfhrOptionId
            ?: $request->input('bookfhrOptionId')
            ?: $request->input('pl_id');
        $productId = $request->input('product_code') ?: $request->input('company_id');
        $productId = str_replace('BOOKFHR_', '', (string) $productId);
        $productId = is_numeric($productId) ? (int) $productId : $productId;
        $optionId = is_numeric($optionId) ? (int) $optionId : $optionId;

        $adults = max(1, (int) ($request->adults ?? 1));
        $children = max(0, (int) ($request->children ?? 0));
        $infants = max(0, (int) ($request->infants ?? 0));

        $customerEmail = trim((string) ($request->email ?? ''));
        if ($customerEmail === '') {
            throw new \Exception('Customer email is required for BookFHR lounge booking.');
        }

        $customer = [
            'title' => $request->title ?? 'Mr',
            'firstName' => $request->firstname ?? '',
            'lastName' => $request->lastname ?? '',
            'email' => $customerEmail,
            'phone' => $request->contactno ?? '',
        ];

        $counts = [
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
        ];

        // Keep terminal simple — BookFHR can reprice if terminal text doesn't match catalogue.
        $flight = [
            'inbound_flight' => $request->input('deptFlight') ?: $request->input('flight_number') ?: 'TBA',
            'inbound_terminal' => 'TBA',
            'outbound_flight' => '',
            'outbound_terminal' => '',
        ];

        $submitOrder = $this->submitBookFhrLoungeOrder(
            $BookFhrService,
            $searchId,
            $productId,
            $optionId,
            $customer,
            $counts,
            $flight,
            $reference_no,
            $apiLog
        );

        $orderRef = $submitOrder['data']['order'] ?? null;
        $submitOk = !empty($submitOrder['success']) && (($submitOrder['data']['success'] ?? true) !== false);
        $priceChanged = stripos((string) ($submitOrder['data']['message'] ?? ''), 'price has changed') !== false;

        // Stale search price: refresh search once and retry with the same product.
        if ($priceChanged) {
            Log::warning('BookFHR lounge price changed — refreshing search and retrying', [
                'reference_no' => $reference_no,
                'searchId' => $searchId,
                'productId' => $productId,
                'optionId' => $optionId,
            ]);

            $fresh = $this->refreshBookFhrLoungeSearch($request, $productId, $optionId);
            if (!empty($fresh['searchId']) && !empty($fresh['optionId'])) {
                $submitOrder = $this->submitBookFhrLoungeOrder(
                    $BookFhrService,
                    $fresh['searchId'],
                    $fresh['productId'] ?? $productId,
                    $fresh['optionId'],
                    $customer,
                    $counts,
                    $flight,
                    $reference_no,
                    $apiLog
                );
                $orderRef = $submitOrder['data']['order'] ?? null;
                $submitOk = !empty($submitOrder['success']) && (($submitOrder['data']['success'] ?? true) !== false);
                $priceChanged = stripos((string) ($submitOrder['data']['message'] ?? ''), 'price has changed') !== false;
            }
        }

        if (!$orderRef || !$submitOk) {
            Log::error('BookFHR lounge submitOrder failed', $submitOrder ?? []);
            Log::error('BookFHR lounge API log', $apiLog);
            $message = $submitOrder['data']['message']
                ?? (is_array($submitOrder['error'] ?? null) ? ($submitOrder['error']['message'] ?? null) : ($submitOrder['error'] ?? null))
                ?? 'BookFHR lounge order submission failed';
            throw new \Exception($message);
        }

        $bookFhrUpdate = [
            'ext_ref' => $orderRef,
            'deptFlight' => $flight['inbound_flight'],
            'deprTerminal' => $request->input('terminal') ?: $flight['inbound_terminal'],
            'additional_pass_details' => json_encode($apiLog['lastPassengers'] ?? []),
        ];
        if (Schema::hasColumn('lounges_bookings', 'referenceNo_ext')) {
            $bookFhrUpdate['referenceNo_ext'] = $orderRef;
        }
        lounges_bookings::where('referenceNo', $reference_no)->update($bookFhrUpdate);

        $paymentRef = $paymentResponse->paymentIntent->id ?? '';
        if ($paymentRef) {
            $confirmOrder = $BookFhrService->confirmOrder($orderRef, $paymentRef);
            $apiLog['confirmOrder'] = $confirmOrder;
            if (empty($confirmOrder['success'])) {
                Log::error('BookFHR lounge confirmOrder failed', $confirmOrder ?? []);
            }
        }

        Log::info('BookFHR lounge booking completed', [
            'reference_no' => $reference_no,
            'ext_ref' => $orderRef,
        ]);
    }

    private function submitBookFhrLoungeOrder(
        BookFhrService $BookFhrService,
        $searchId,
        $productId,
        $optionId,
        array $customer,
        array $counts,
        array $flight,
        $reference_no,
        array &$apiLog
    ) {
        if (empty($searchId)) {
            throw new \Exception('BookFHR search ID missing. Please search again.');
        }
        if ($optionId === null || $optionId === '') {
            throw new \Exception('BookFHR lounge option missing. Please search again.');
        }

        $BookFhrService->assertSearchActive($searchId);

        $cartResult = $BookFhrService->createCart('GBP', 'en', null);
        $apiLog['createCart'] = $cartResult;
        $cartId = $cartResult['data']['cartId'] ?? null;

        if (!$cartId) {
            Log::error('BookFHR lounge cart creation failed', $cartResult ?? []);
            throw new \Exception('Unable to create BookFHR cart');
        }

        $cartData = [
            'searchId' => $searchId,
            'product' => $productId,
            'option' => $optionId,
            'qty' => 1,
            'type' => 'Lounge',
        ];

        $addToCartResult = $BookFhrService->addToCart($cartId, $cartData);
        $apiLog['addToCart'] = $addToCartResult;
        Log::info('BookFHR lounge addToCart', $addToCartResult);

        $itemId = $addToCartResult['data']['items'][0]['_id'] ?? null;
        if (!$itemId) {
            Log::error('BookFHR lounge addToCart failed', $addToCartResult ?? []);
            throw new \Exception('Unable to add lounge to BookFHR cart');
        }

        $orderData = [
            'customer' => $customer,
            'products' => [
                $BookFhrService->buildLoungeProduct($itemId, $customer, $counts, $flight),
            ],
            'accountId' => config('services.bookfhr.partner_id'),
            'partnerRef' => $reference_no,
            'reference' => $reference_no,
        ];

        $submitOrder = $BookFhrService->submitOrder($cartId, $orderData);
        $apiLog['submitOrder'] = $submitOrder;
        $apiLog['lastPassengers'] = $orderData['products'][0]['additional_passengers'] ?? [];
        Log::info('BookFHR lounge submitOrder', $submitOrder);

        return $submitOrder;
    }

    private function refreshBookFhrLoungeSearch(Request $request, $productId, $optionId): array
    {
        $airportId = $request->input('airport');
        $airportDetail = airport::find($airportId);
        if (!$airportDetail || empty($airportDetail->iata_code)) {
            return [];
        }

        $checkinDate = str_replace('/', '-', (string) $request->input('checkin_date'));
        $checkinDate = date('Y-m-d', strtotime($checkinDate));
        $checkinTime = date('H:i', strtotime((string) $request->input('checkin_time')));
        $visitEnd = strtotime($checkinDate.' '.$checkinTime.' +4 hours');

        $adults = max(1, (int) ($request->adults ?? 1));
        $children = max(0, (int) ($request->children ?? 0));
        $infants = max(0, (int) ($request->infants ?? 0));

        $loungeService = app(LoungeService::class);
        $result = $loungeService->search([
            'location' => $airportDetail->iata_code,
            'dateFrom' => $checkinDate,
            'timeFrom' => $checkinTime,
            'dateTo' => date('Y-m-d', $visitEnd),
            'timeTo' => date('H:i', $visitEnd),
            'type' => 'Lounge',
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'currency' => 'GBP',
        ]);

        if (empty($result['success'])) {
            Log::error('BookFHR lounge refresh search failed', $result);
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
            // Same product, take first available option if exact option gone.
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

    private function resolveLoungeBookingImage(array $searchData, $loungeRecord, $requestLogo = null): string
    {
        $apiImages = Lounges::normalizeImageList(array_merge(
            !empty($searchData['image']) ? [$searchData['image']] : [],
            $searchData['images'] ?? []
        ));

        if (!empty($apiImages[0])) {
            return $apiImages[0];
        }

        $requestImage = Lounges::formatImageUrl($requestLogo ?: ($searchData['image'] ?? ''));
        if ($requestImage !== '') {
            return $requestImage;
        }

        if ($loungeRecord) {
            return $loungeRecord->getImageUrl($searchData['image'] ?? null);
        }

        return asset('favicon.ico');
    }

    public function getResponse($success, $data)
    {
        return [
            'success' => $success,
            'data' => $data,
        ];
    }
}
