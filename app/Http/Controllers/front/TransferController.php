<?php

namespace App\Http\Controllers\front;

use App\Models\airports_bookings;
use App\Models\airports_terminals;
use App\Models\companies_set_price_plan;
use App\Models\customers;
use App\Models\discounts;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\modules_settings;
use App\Models\ref_tracking;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\airport;
use App\Models\transfer_bookings;

use App\Models\lounges_bookings;

use DateTime;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

//use App\library\stripephp\StripPayment;
//use App\library\payzone\includes\PayzoneGateway;
use App\Library\payzone\includes\helpers;
use PharIo\Manifest\Email;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Refund;
use Stripe\Stripe;
use Illuminate\Support\Facades\URL;
use App\Library\aph_functions;
use App\Models\Company;
use App\Library\functions;
use App\Models\settings; 
use Illuminate\Support\Facades\Session;

class TransferController extends Controller
{

    public $_setting = [];
    
    public $stripeKey;
    public $currency = "GBP"; 
    
    public $_mysetting = [];

    function __construct()
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
    
    
    public function addBookingFormTransfer(Request $request)
    {
        session(['transfer_booking_context' => $request->except(['_token'])]);

        return redirect()->route('booking_transfer.show');
    }

    public function showBookingFormTransfer(Request $request)
    {
        $stored = session('transfer_booking_context');

        if (!is_array($stored) || empty($stored['company_id'])) {
            return redirect('/')->with('error', 'Your booking session has expired. Please select a transfer again.');
        }

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.booking_transfer', [
            'data' => new Request($stored),
            'settings' => $this->_setting,
            'airports' => $airports,
        ]);
    }
    
    public function checkBookingTransfer(Request $request)
    {
        $transfer_data = $request->all();
        
    	$browser_data = $_SERVER['HTTP_USER_AGENT'];
	    $ip = request()->ip();
	    
        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;
        
        $total_amount = ($transfer_data["booking_amount"]*1) + ($bookingfee*1);
        
        $arrival_date = str_replace('/', '-', $transfer_data['arrival_date']);	
        $return_date = str_replace('/', '-', $transfer_data['return_date']);	
        
        $intent_id = $transfer_data["intent_id"];
        
        $data['transfer_id'] = 0;
        $data['transfer_name'] = $transfer_data["transfer_name"];
        $data['transfer_code'] = $transfer_data["product_code"];
        
        $data['arrival_date'] = date('Y-m-d', strtotime($arrival_date));
        $data['arrival_time'] = $transfer_data["arrival_time"];
        $data['return_date'] = date('Y-m-d', strtotime($return_date));
        $data['return_time'] = $transfer_data["return_time"];
        $data['adults'] = $transfer_data["adults"];
        $data['children'] = $transfer_data["children"];
        $data['infants'] = $transfer_data["infants"];
        
        $data['booking_fee'] = $bookingfee;
        
        $data['discount_amount'] = 0.00;
        $data['booking_amount'] = $transfer_data["booking_amount"];
        $data['total_amount'] = $total_amount;
        
        
        
        $data['loc_type'] = $transfer_data["loc_type"];
        $data['loc_code'] = $transfer_data["loc_code"];
        $data['loc_name'] = $transfer_data["loc_name"];
        $data['loc_lat'] = $transfer_data["loc_lat"];
        $data['loc_long'] = $transfer_data["loc_long"];
        $data['loc_id'] = $transfer_data["loc_id"];
        $data['loc_country'] = $transfer_data["loc_country"];
        
        
        $data['loc_type_drop'] = $transfer_data["loc_type_drop"];
        $data['loc_code_drop'] = $transfer_data["loc_code_drop"];
        $data['loc_name_drop'] = $transfer_data["loc_name_drop"];
        $data['loc_lat_drop'] = $transfer_data["loc_lat_drop"];
        $data['loc_long_drop'] = $transfer_data["loc_long_drop"];
        $data['loc_id_drop'] = $transfer_data["loc_id_drop"];
        $data['loc_country_drop'] = $transfer_data["loc_country_drop"];
        
        
        $data['title'] = $request->title;
        $data['first_name'] = $request->firstname;
        $data['last_name'] = $request->lastname;
        $data['email'] = $request->email;
        $data['phone_number'] = $request->contactno;
        
        $data['token'] = $intent_id;
        $data['intent_id'] = $intent_id;
        $data['browser_data'] = $browser_data;
        //$data['user_ip'] = $ip;
        
            
        $data["createdate"] = date("Y-m-d H:i:s");
        $data["modifydate"] = date("Y-m-d H:i:s");
        
       // dd($data);
        $incomplete = $request->incomplete;
        
        $pass = $this->randomPassword();
        $data_cust = array();
        $data_cust['title'] = $request->title;
        $data_cust['first_name'] = $request->firstname;
        $data_cust['last_name'] = $request->lastname;
        $data_cust['email'] = $request->email;
        $data_cust['phone_number'] = $request->contactno;
        
        $data_cust["password"] = md5($pass);
        $data_cust['address'] = '';
        $data_cust['town'] = '';
        
        $data_cust["added_on"] = date("Y-m-d H:i:s");
        $data_cust["update_on"] = date("Y-m-d H:i:s");
            

        $customer_exist = customers::where('email', $request->email)->first();
        if ($customer_exist) {
            $customerData = customers::where('email', $request->email)
                ->update($data_cust);
            $customer_id = $customer_exist->id;

        } else {

            $customerData = customers::updateOrCreate($data_cust);
            $customer_id = $customerData->id;

        }
        
        
        $data['customerId'] = $customer_id;
        
        $referenceNo = $request->reference_no;
        //dd($data);
        if ($referenceNo == '') {
            
            $booking_id = DB::table("transfer_bookings")->insertGetId($data);
            $bookingref = 'PZT-';
            $bookingref .= date("y") . date("m") . date("d");
            
    
            $bookingref = $bookingref . $booking_id;
            $data2["referenceNo"] = $bookingref;
            $referenceNo = $bookingref;
            transfer_bookings::where("id", $booking_id)->update($data2);

        } else {
            $booking = transfer_bookings::where('referenceNo', $referenceNo)->first();
            if ($booking) {
                transfer_bookings::where("referenceNo", $referenceNo)->update($data);
                $booking_id = $booking->id;
            } else {
                $booking_id = 0;
            }
        }
        
        Stripe::setApiKey($this->stripeKey);
        \Stripe\PaymentIntent::update(
            $intent_id,
            [
                'amount' => ($total_amount*100),
            ]
        );
        
        
    	return json_encode(array('booking_id' => $booking_id, 'reference_no' => $referenceNo, 'available' => 'Yes'));
    }
    
    function transfer_checkout(Request $request)
    {
        $company_id = $request->input('company_id');
        if ($company_id == "") {
            $company_id = 0;
        }
		
		$product_code = $request->input('product_code');
        
        $bookingfor = $request->input('bookingfor');
        $totalamount = $request->input('total_amount');
        $sku = $request->input('sku');
        $park_api = $request->input('park_api');

        $smsfee = $request->input('smsfee');
        if ($smsfee == "") {
            $smsfee = "No";
        }

        $canfee = $request->input('canfee');
        if ($canfee == "") {
            $canfee = "No";
        }

        $settings =
        $l_fee = 0;

        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;

        $sms_notification = $this->_setting['sms_notification_fee'] > 0 ? $this->_setting['sms_notification_fee'] : 0;
        $cancellation_fee = $this->_setting['cancellation_fee'] > 0 ? $this->_setting['cancellation_fee'] : 0;
        $booking_amount = 0.00;
        $discount_amount = 0.00;

        $total_amount = 0.00;

        
        if($park_api == 'holiday'){     
            $booking_amount = $totalamount;
        }

        //$booking_amount = $this->check_extra($company_id, $pl_id, $booking_amount);
        
        $total_amount = ($booking_amount * 1) + ($bookingfee * 1);


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
        
        $intent_secret='';
        if($intent_secret=='')
        {      
            
            Stripe::setApiKey($this->stripeKey);
            $intent = \Stripe\PaymentIntent::create([
                'amount' => ($total_amount*100),
                'currency' => 'gbp',
            ]);
            Session::put('intent_id',$intent->id);
             Session::put('intent_secret',$intent->client_secret);
    
           
            $intent_secret = $intent->client_secret;
            $intent_id = $intent->id;
        }
        else
        {
            \Stripe\PaymentIntent::update(
                $intent_id,
                [
                    'amount' => ($total_amount*100),
                ]
            );
        }

		
        $output['total_amount'] = $this->priceFormat($total_amount, false);
        $output['booking_amount'] = $booking_amount;
        $output['discount_amount'] = $this->priceFormat($discount_amount, false);
        $output['booking_fee'] = $this->priceFormat($bookingfee, false);
		$output['company_name'] = '';
        $output['intent_id'] =$intent->id;
        $output['intent_secret'] = $intent->client_secret;
        return response($output);

    }
    
    
    public function payout_transfer(Request $request)
    {

        $booking_id = $request->input("booking_id");
        $reference_no = $request->input("reference_no");
		$park_api = $request->input('park_api');
        $resp=$request->input('result');
        $resp=json_encode($resp);
        $resp=json_decode($resp);
		//$booking = lounges_bookings::where("id",$booking_id)->first();

        if($park_api == 'holiday'){
            $holidayorder = $this->bookOnHoliday($request);
            $ext_ref = isset($holidayorder['BookingRef']) ? $holidayorder['BookingRef'] : '';
            $url = isset($holidayorder['MoreInfoURL']) ? $holidayorder['MoreInfoURL'] : '';
            $aphData['referenceNo_ext'] = $ext_ref;
            $aphData['referenceLink_ext'] = $url;
            lounges_bookings::where("referenceNo", $reference_no)->update($aphData);
        }
        
        $this->update_booking_payment($request, $resp, "stripe");
        echo json_encode($this->getResponse(1, "payment successfully charged"));


    }
    
    public function payout_failed_transfer(Request $request)
    {

        $booking_id = $request->input("booking_id");
        $reference_no = $request->input("reference_no");
		$park_api = $request->input('park_api');
        $resp=$request->input('result');
        $resp=json_encode($resp);
        $resp=json_decode($resp);
        

        if($park_api == 'holiday'){
            $holidayorder = $this->bookOnHoliday($request);
            $ext_ref = isset($holidayorder['BookingRef']) ? $holidayorder['BookingRef'] : '';
            $url = isset($holidayorder['MoreInfoURL']) ? $holidayorder['MoreInfoURL'] : '';
            $aphData['referenceNo_ext'] = $ext_ref;
            $aphData['referenceLink_ext'] = $url;
            transfer_bookings::where("referenceNo", $reference_no)->update($aphData);
        }
        
        //$this->update_booking_payment($request, $resp, "stripe");
        //echo json_encode($this->getResponse(1, "payment successfully charged"));
    }
    
    public function update_booking_payment($request, $paymentresponse, $payment_type)
    {
        $data = [];

        $bookingfee = $this->_setting['booking_fee'] > 0 ? $this->_setting['booking_fee'] : 0;

        $data["booking_fee"] = $bookingfee;
        $data["discount_code"] = $request->input("promo");


        $data["cancelfee"] = $request->input("cancelfee");
        $data["smsfee"] = $request->input("smsfee");
        $data["payment_status"] = "success";
        $data["payment_method"] = $payment_type;
        
        $data["booking_status"] = "completed";
        $data["booking_action"] = "Booked";
         
        if ($payment_type == "stripe") {
            $data["api_res"] =json_encode($paymentresponse);
            $data["PayerID"] = $paymentresponse->paymentIntent->id;
        }

        DB::table('lounges_bookings')
            ->where('referenceNo', $request->input("reference_no"))
            ->update($data);

        $this->submitTransaction($request->input("reference_no"));
        
		$row = DB::table('lounges_bookings')->where('referenceNo', $request->input("reference_no"))->first();
       
        $airport_detail = airport::where("id",$request->input("airport"))->first();
        
						

        $template_data = [];
		//$template_data["guidence"] = $directions;
        $template_data["username"] = $request->input("firstname") . " " . $request->input("lastname");
        $template_data["email"] = $request->input("email");
        $template_data["telephone"] = $request->input("contactno");
        $template_data["lounge_name"] = $request->input("lounge_name");
        $template_data["company"] = 'Holiday Extra';
        $template_data["airport"] = $airport_detail->name;
        
        $template_data["start_date"] = $request->input("checkin_date") . " " . $request->input("checkin_time");
        $template_data["booktime"] = date("Y-m-d H:i:s");
        $template_data["terminal"] = 'Terminal '.$request->input("terminal");
        $template_data["ext_ref"] = $request->input("registration");
        $template_data["model"] = $request->input("model");
        $template_data["make"] = $request->input("make");
        $template_data["color"] = $request->input("color");
        $template_data["payment_gatway"] = $payment_type;
        $template_data["payment_status"] = "success";
        $template_data["price"] = $row->total_amount;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] = $request->input("reference_no");
        
        $template_data["ext_ref"] = $row->referenceNo_ext;
        
        $email_send = new EmailController(); 
        $toemails = [$request->input("email"),'bookings@parkingzone.co.uk'];  
        
        $email_send->sendEmail("Client Lounge booking", $toemails, $template_data);
            
        //$email_send->sendEmail("Admin Lounge Booking",'bookings@parkingzone.co.uk', $template_data);
		
        //$email_send->sendEmail("Add Booking Company", $company_data->company_email, $template_data);  
        
        
        $smsfee = $request->input("smsfee");
        
        //if ($smsfee>0) {
           //$functions = new functions();
           //$functions->send_sms($request->input("contactno"), $request->input("reference_no"));
         //}

        return true;
        //return redirect()->route('thankyou');


    }
    public function submitTransaction($ref_no)
    {
       // echo $ref_no;
        $order_detail = lounges_bookings::where("referenceNo",$ref_no)->first();
        //dd($order_detail);
        $d = [];
        $d["orderID"] = $order_detail->id;
        $d["token"] = $order_detail->PayerID;
        $d["referenceNo"] = $ref_no;
        $d["loungeID"] = 0;
        $d["booking_amount"] =$order_detail->booking_amount;
        $d["extra_amount"]=$order_detail->extra_amount;
        $d["discount_amount"] = $order_detail->discount_amount;
        $d["smsfee"] = $order_detail->smsfee;
        $d["booking_fee"] = $order_detail->booking_fee;
        $d["cancelfee"] = $order_detail->cancelfee;
        $d["total_amount"] = $order_detail->total_amount;
        $d["payable"] = 0;
        $d["amount_type"] = "credit";
        $d["payment_method"] =$order_detail->payment_method;
        $d["payment_action"] = $order_detail->payment_status;
        $d["booking_status"] = $order_detail->booking_status;

        DB::table('lounges_transaction')
            ->insert($d);
        return true;
    }
    function priceFormat($price, $symbol = true)
    {
        $formated_price = '';
        if ($symbol) {
            $formated_price .= '&pound;';
        }
        $formated_price .= number_format(((float)$price * 1), 2);
        return $formated_price;
    }
    function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public function thankyou($id)
    {
        $airports = airport::all()->where("status", "Yes");

        $booking =  lounges_bookings::where("referenceNo",$id)->first();
        
        $airport_detail = airport::where("id", $booking->airportID)->first();

        return view("frontend.thankyou_lounge", ["airports" => $airports, "booking"=>$booking, "airport_detail"=>$airport_detail]);

    }
    public function bookOnHoliday($request) {

        $reference_no = $request->input("reference_no");
        //dd($reference_no);
        $booking =  transfer_bookings::where("referenceNo",$reference_no)->first();
        
		$product_code = $request->input("product_code");
        
        $booking_amount = $request->input("booking_amount");
        $booking_url = $request->input("booking_url");


        $loc_code = $request->input("loc_code");
        $loc_type = $request->input("loc_type");
        $loc_code_drop = $request->input("loc_code_drop");
        $loc_type_drop = $request->input("loc_type_drop");
        if($loc_type == 'AP'){
            $loc_type = 'IATA';
        }
        
        $ArrivalDate = date('Y-m-d', strtotime($booking->arrival_date));
        $ArrivalTime = date("Hi",strtotime($booking->arrival_time)); 

        $ReturnDate = date('Y-m-d', strtotime($booking->return_date));
        $ReturnTime = date("Hi",strtotime($booking->return_time)); 
        
        $adults = $booking->adults;
        $children = $booking->children;
        
        $flight_time = $request->input("flight_time");
        $arrival_flight = $request->input("arrival_flight");
        $return_flight = $request->input("return_flight");

        $title = $request->input("title");
        $first_name = $request->input("firstname");
        $last_name = $request->input("lastname");
        $phone_number = $request->input("contactno");
        $email = $request->input("email");

        $aph_functions = new aph_functions();

        $string = "&FromDate=".$ArrivalDate."&FromTime=".$ArrivalTime;
        //$string .= "&ReturnDate=".$ReturnDate."&ReturnTime=".$ReturnTime;
        $string .= "&PickUp=".$loc_code."&PickUpType=".$loc_type."&DropOff=".$loc_code_drop."&DropOffType=".$loc_type_drop."&Adults=".$adults;
        $string .= "&Title=".$title."&FirstName=".$first_name."&LastName=".$last_name;
        $string .= "&Email=".$email."&DayPhone=".$phone_number."&Price=".$booking_amount;
        $string .= "&OutFlight=".$arrival_flight."&ReturnFlight=".$return_flight;

        $aphorder = $aph_functions->HolidayBookingOrderTransfer($string, $booking_url);
        return $aphorder;
    }
    public function getResponse($success, $data)
    {
        $res = array();
        $res['success'] = $success;
        $res['data'] = $data;
        return $res;
    }
    
    function get_location_suggestion(Request $request){
        
        $query = trim($request->keyword);
        if($query!='')
        {
           
                                                                                  
            $api_url_ap = 'https://v2.holidayextras.co.uk/search/api/resort-transfers-destination?query='.$query;     
            $output_ap = $this->transfer_curl_call($api_url_ap);
            
    	    $result = json_decode($output_ap, true);
            //echo "<pre>"; print_r($result_ap); echo "</pre>"; exit;
            $i=0;
            $result_ap = array();
            $result_hotel = array();
            foreach($result as $row)
        	{
        	    if($row['locationType'] == 'AP'){
        	        $result_ap[] = $row;
        	    }
        	    if($row['locationType'] == 'TTI'){
        	        $result_hotel[] = $row;
        	        $i++;
                    // if($i > 5){
                    //     break;
                    // }
        	    }
        	    
                
                
                
        	}
            // echo "<pre>"; print_r($result_ap); echo "</pre>"; 
            // echo "<pre>"; print_r($result_hotel); echo "</pre>"; 
            // exit;
            
            $html ='<ul id="hotels-list">
                    <li class="searchregions">Airport</li>';
            $i=0;
            if(!empty($result_ap)){
                foreach($result_ap as $region)
            	{
            	    $value = "'".$region["locationType"]."', '".$region["name"]."', '".$region["code"]."',  '".$region["lat"]."', '".$region["lng"]."', '".$region["country"]."', '".$region["id"]."'";
            	    $html .='<li onClick="selectRegion('.$value.');">'.$region["label"].'</li>';
                    $i++;
                    // if($i > 2){
                    //     break;
                    // }
            	}
            }
            
        	
        	$html .='<li class="searchhotels">HOTELS</li>';
        	$i=0;
        	if(!empty($result_hotel)){
                foreach($result_hotel as $hotel)
                {
                    $value = "'".$hotel["locationType"]."', '".$hotel["name"]."', '".$hotel["code"]."',  '".$hotel["lat"]."', '".$hotel["lng"]."'";
                    $html .='<li onClick="selectHotel('.$value.');">'.$hotel["name"].'</li>';
                    $i++;
                    // if($i > 2){
                    //     break;
                    // }
                }
        	}
            echo $html .='</ul>';
            
        }
    }
    function get_location_suggestion_drop(Request $request){
        
        $query = trim($request->keyword);
        
        $loc_type = isset($request->loc_type) ? $request->loc_type : '';
        if($query!='')
        {
           
                                                                                  
            $api_url_ap = 'https://v2.holidayextras.co.uk/search/api/resort-transfers-destination?query='.$query;     
            $output_ap = $this->transfer_curl_call($api_url_ap);
            
    	    $result = json_decode($output_ap, true);
            //echo "<pre>"; print_r($result_ap); echo "</pre>"; exit;
            $i=0;
            $result_ap = array();
            $result_hotel = array();
            foreach($result as $row)
        	{
        	    if($row['locationType'] == 'AP'){
        	        $result_ap[] = $row;
        	    }
        	    if($row['locationType'] == 'TTI'){
        	        $result_hotel[] = $row;
        	        $i++;
                    // if($i > 5){
                    //     break;
                    // }
        	    }
        	}
            
            $i=0;
            if($loc_type == 'AP'){
                $html ='<ul id="hotels-list">
                    <li class="searchhotels">HOTELS</li>';
            	$i=0;
            	if(!empty($result_hotel)){
                    foreach($result_hotel as $hotel)
                    {
                        $value = "'".$hotel["locationType"]."', '".$hotel["name"]."', '".$hotel["code"]."',  '".$hotel["lat"]."', '".$hotel["lng"]."'";
                        $html .='<li onClick="selectHotelDrop('.$value.');">'.$hotel["name"].'</li>';
                        $i++;
                        // if($i > 2){
                        //     break;
                        // }
                    }
            	}
            	echo $html .='</ul>';
            }
            else{
            	$html ='<ul id="hotels-list">
                    <li class="searchregions">Airport</li>';
                if(!empty($result_ap)){
                    foreach($result_ap as $region)
                	{
                	    $value = "'".$region["locationType"]."', '".$region["name"]."', '".$region["code"]."',  '".$region["lat"]."', '".$region["lng"]."', '".$region["country"]."', '".$region["id"]."'";
                	    $html .='<li onClick="selectRegionDrop('.$value.');">'.$region["label"].'</li>';
                        $i++;
                        // if($i > 2){
                        //     break;
                        // }
                	}
                }
                
            	
            	$html .='<li class="searchhotels">HOTELS</li>';
            	$i=0;
            	if(!empty($result_hotel)){
                    foreach($result_hotel as $hotel)
                    {
                        $value = "'".$hotel["locationType"]."', '".$hotel["name"]."', '".$hotel["code"]."',  '".$hotel["lat"]."', '".$hotel["lng"]."'";
                        $html .='<li onClick="selectHotelDrop('.$value.');">'.$hotel["name"].'</li>';
                        $i++;
                        // if($i > 2){
                        //     break;
                        // }
                    }
            	}
            	echo $html .='</ul>';
            }
            
            
        }
    }
    function transfer_curl_call($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

?>