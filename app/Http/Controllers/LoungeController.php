<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\airport;
use App\partners;
use App\users_roles;
use App\airports_terminals;
use App\airports_bookings;
use App\booking_transaction;
use App\lounges_bookings;
use App\lounges_transaction;

use App\Company;
use App\User;
use App\Hotel;
use App\customers;
use App\hotel_bookings;
use App\modules_settings;
use App\lounge_penalty_details;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Elibyy\TCPDF\Facades\TCPDF;
use PDF;
use View;
use aph_functions;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\front\HotelController as FrontHotelController;
use functions;

class LoungeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $modules_settings = modules_settings::all();
        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role_nam = users_roles::get_user_role(Auth::user()->id)->name;

        set_time_limit(0);
        $bookings = new lounges_bookings();
        $bookings =  $bookings->select("lounges_bookings.*");
        //$bookings = $bookings->leftJoin('companies as c', 'lounges_bookings.companyId', '=', 'c.id');
         
        $bookings_count = new lounges_bookings();
        $bookings_count =  $bookings_count->select("lounges_bookings.*");
        //$bookings_count = $bookings_count->leftJoin('companies as c', 'lounges_bookings.companyId', '=', 'c.id');
        $data = $request->all();
        $admins = User::all();
        $airports = airport::all();
        $companies_dlist = Company::all();
        $agent=partners::all();

        $show = 0;
        
        if ($request->has("search")) {
            $name = $request->input("search");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where('referenceNo','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('first_name','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('last_name','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('email','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('phone_number','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('postal_code','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('checkindate','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('checkoutdate','LIKE',"%{$name}%");


                $bookings_count = $bookings_count->where('referenceNo','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('first_name','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('last_name','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('email','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('phone_number','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('postal_code','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('checkindate','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('checkoutdate','LIKE',"%{$name}%");
            }
        }
        
        if ($request->has("filter") && $request->has("start_date") && $request->has("end_date")) {

            $name = $request->input("filter");
           // dd($name);
            if ($name != "" && $name != "all") {
                 $start_date = date("Y-m-d", strtotime($request->input("start_date")));
                 $end_date = date("Y-m-d", strtotime($request->input("end_date")));
                
                $bookings->whereDate($name, '>=', $start_date);
                $bookings->whereDate($name, '<=', $end_date);


                $bookings_count->whereDate($name, '>=', $start_date);
                $bookings_count->whereDate($name, '<=', $end_date);

            }
        } else {

            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "createdate";

            $bookings->whereDate($name, '>=', $start_date);
            $bookings->whereDate($name, '<=', $end_date);

            $bookings_count->whereDate($name, '>=', $start_date);
            $bookings_count->whereDate($name, '<=', $end_date);

            Input::merge(['start_date' => date("d-M-Y",strtotime($start_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
            
        }
        if ($request->has("booking_source")) {
            $name = $request->input("booking_source");

            if ($name != "" && $name != "all") {
                //  dd($name);

				if ($role_nam == "Marketing" && $name=='paid') 
				{

					$bookings = $bookings->where("traffic_src",'=', 'PPC');
					$bookings = $bookings->orWhere("traffic_src", 'BING');
					$bookings = $bookings->orWhere("traffic_src", 'ORG');
					$bookings = $bookings->orWhere("traffic_src", 'EM');
					$bookings = $bookings->orWhere("traffic_src", 'POR');
					$bookings_count = $bookings_count->where("traffic_src",'=', 'PPC');
					$bookings_count = $bookings_count->orWhere("traffic_src", 'BING');
					$bookings_count = $bookings_count->orWhere("traffic_src", 'ORG');
					$bookings_count = $bookings_count->orWhere("traffic_src", 'EM');
					$bookings_count = $bookings_count->orWhere("traffic_src", 'POR');
					
				}
				else
				{
					$bookings = $bookings->where("traffic_src", $name);
                	$bookings_count = $bookings_count->where("traffic_src", $name);
				}
            }
			
        }
        

        if ($request->has("airport") ) {
            $name = $request->input("airport");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("airportID", $name);
                $bookings_count = $bookings_count->where("airportID", $name);
            }
        }
        
        if ($request->has("status")) {
            $name = $request->input("status");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("booking_status", $name);
                $bookings_count = $bookings_count->where("booking_status", $name);
            }
        }
        
        
        $bookings = $bookings->orderBy('id', 'DESC');
        
        $bookings_count = $bookings_count->orderBy('id', 'DESC')->get();
        
        $bookings = $bookings->paginate(20);
        
        return view("admin.lounge_bookings.booking_list", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings, "bookings_count" => $bookings_count, "role_name" => $role_nam]);
    }
    
    public function incomplete_Booking(Request $request)
    {
        //


        set_time_limit(0);
        $bookings = new lounges_bookings();
        $data = $request->all();
        $admins = User::all();
        $airports = airport::all();
        $companies_dlist = Company::all();


        $show = 0;

        if ($request->has("search")) {
            $name = $request->input("search");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where('referenceNo','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('first_name','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('last_name','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('email','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('phone_number','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('lounge_name','LIKE',"%{$name}%");
            }
        }
        if ($request->has("airport")) {
            $airport = $request->input("airport");
            if ($airport != "" && $airport != "all") {
                $bookings = $bookings->where("airportID", $airport);
            }
        }
		if ($request->has("filter") && $request->has("start_date") && $request->has("end_date")) {
            $name = $request->input("filter");

            if ($name != "" && $name != "all") {
				                
				$start_date = date("Y-m-d", strtotime($request->input("start_date")));
				$end_date = date("Y-m-d", strtotime($request->input("end_date")));
                
                $bookings = $bookings->whereDate($name, '>=', $start_date);
                $bookings = $bookings->whereDate($name, '<=', $end_date);
            }
			else {
				$start_date = date("Y-m-d", strtotime($request->input("start_date")));
				$end_date = date("Y-m-d", strtotime($request->input("end_date")));
				$name = "createdate";

				$bookings = $bookings->whereDate($name, '>=', $start_date);
				$bookings = $bookings->whereDate($name, '<=', $end_date);
			}
        } else {
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "createdate";
        }



        // $bookings = airports_bookings::where("booking_status", "Abandon");
        $bookings = $bookings->where("booking_status", "Abandon");
        $bookings = $bookings->orderBy('id', 'DESC');
        $bookings = $bookings->paginate(20);
        // return view("admin.booking.incomplete_list", ["bookings" => $bookings]);
        return view("admin.lounge_bookings.incomplete_list", ["companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings]);
    }
    public function bookinghistory(Request $request)
    {     


        $bookings = new lounges_transaction();
       
       // $bookings = $bookings->leftjoin('booking_transaction as t', 't.orderID', '=', 'booking_transaction.orderID');
        if ($request->has("name")) {
            $name = $request->input("name");
            $bookings = $bookings->where("lounges_transaction.referenceNo", $name);
        }

        //  $bookings = airports_bookings::where("booking_status", "Abandon")->paginate(20);
  
       /// $bookings = $bookings->where("booking_transaction.booking_status", "Abandon");
        //$bookings= $bookings->where("booking_transaction.id", "t.id");
        //$bookings= $bookings->where("booking_transaction.id","<", "t.id");
        


		$bookings = $bookings->orderBy('id', 'DESC');

        $bookings = $bookings->paginate(20);
              //    dd( $bookings);
        return view("admin.lounge_bookings.bookinghistroy_list", ["bookings" => $bookings]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function show(Hotel $hotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $booking_detail = lounges_bookings::findOrFail($id);
       // dd($booking_detail);
        $edit = 1;
        $airports = airport::All();

        return view("admin.lounge_bookings.update_booking", ["airports" => $airports, "type" => "edit", "id" => $id, "settings" => $this->_setting, "edit" => $edit,"booking_detail"=>$booking_detail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
          //
        //dd($request->all());

        $id = $request->input("id");
        $airport_id = $request->input("airportid");

        $check_in = $request->input("check_in");
        $check_in_time = $request->input("check_in_time");


        $checkin_date = date('Y-m-d H:i:s', strtotime($check_in . " " . $check_in_time));
		
		
        $fulladdress= $request->input("fulladdress");

        $promo = $request->input("discount_code");
        $first_name = $request->input("first_name");
        $title = $request->input("title");
        $last_name = $request->input("last_name");
        $email = $request->input("email");
        $contact = $request->input("contact");

        $adults = $request->input("adults");
        $children = $request->input("children");
        
        $address = $request->input("address");
        $post_code = $request->input("post_code");
        $town = $request->input("town");

        $transaction_id = $request->input("transaction_id");

        $payment_method = $request->input("payment_method");

        
        $parking_type = $request->input("parking_type");
        $discount_amount = $request->input("discount_amount");
        $p_booking_amount = $request->input("p_booking_amount");

        $postalFEE = $request->input("postalFEE");
        $bookingFEE = $request->input("bookingFEE");
        $add_extra = $request->input("add_extra");
        $totalAMOUNT = $request->input("totalAMOUNT");
        $h_totalAMOUNT = $request->input("h_totalAMOUNT");


		
		
        $smsFEE = $request->input("smsFEE");
        $cancelFEE = $request->input("cancelFEE");
        

//creating customer
        $pass = $this->randomPassword();
        $data = array();
        $data["title"] = $title;
        $data["first_name"] = $first_name;
        $data["last_name"] = $last_name;
        $data["phone_number"] = $contact;
        $data["password"] = md5($pass);
        $data["address"] = $address;
        $data["town"] = $town;
        
        //$data["added_on"] = date("Y-m-d H:i:s");
        //$data["update_on"] = date("Y-m-d H:i:s");
        $data["email"] = $email;

        $customer_exist = customers::where('email', $request->input('email'))->first();
        if ($customer_exist) {
            $customerData = customers::where('email', $email)
                ->update($data);
            $customer_id = $customer_exist->id;

        } else {

            $customerData = customers::updateOrCreate($data);
            $customer_id = $customerData->id;

        }
//creating customer end
        $ip = request()->ip();
        $data = [];
        $data["airportID"] = $airport_id;
        $data["customerId"] = $customer_id;

        $data["title"] = $title;
        $data["first_name"] = $first_name;
        $data["last_name"] = $last_name;
        $data["email"] = $email;
        $data["phone_number"] = $contact;
        $data["adults"] = $adults;
        $data["children"] = $children;
        
        $data["fulladdress"] = $fulladdress;
        $data["address"] = $address;
        $data["town"] = $town;
        $data["postal_code"] = $post_code;
        $data["check_in"] = $checkin_date;
        $data["check_in_time"] = $check_in_time;
        
        $data["discount_code"] = $promo;
        $data["discount_amount"] = $discount_amount;
        $data["booking_amount"] = $p_booking_amount;
        $data["extra_amount"] = $add_extra;
        $data["smsfee"] = $smsFEE;

        $data["booking_fee"] = $bookingFEE;
        $data["cancelfee"] = $cancelFEE;
        $data["total_amount"] = $totalAMOUNT;
        
        $data["booked_type"] = $parking_type;



        //$data["user_ip"] = $ip;
        $data["booking_status"] = "completed";

        //dd($data);
  



        lounges_bookings::where("id", $id)->update($data);
        $row = DB::table('lounges_bookings')->where("id", $id)->first();

        $airport_detail = airport::where("id", $row->airportID)->first();
        
        $template_data = [];
        $template_data["username"] = $row->first_name . " " . $row->last_name;
        $template_data["email"] = $row->email;
        $template_data["telephone"] = $row->phone_number;
        $template_data["lounge_name"] = $row->lounge_name;
        $template_data["company"] = 'Holiday Extra';
        $template_data["airport"] = $airport_detail->name;
        
        $template_data["start_date"] = date("d-m-Y", strtotime($row->check_in)) .' '. $row->check_in_time;
        $template_data["booktime"] = date("d-m-Y H:i:s", strtotime($row->createdate));
        $template_data["terminal"] = 'Terminal '.$row->terminal;
        
        $template_data["payment_gatway"] = $row->payment_method;
        $template_data["payment_status"] = $row->payment_status;
        $template_data["price"] = $row->total_amount;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] = $row->referenceNo;
        
        $template_data["ext_ref"] = $row->referenceNo_ext;
        
        // $email_send = new EmailController();
        // $email_send->sendEmail("Update Booking Lounge", $row->email, $template_data);

        return redirect("admin/booking_lounge/edit/".$id)->with("success", "Booking updated Successfully");
       // return back();


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        //
    }
    
    
    public function showdetail(Request $request)
    {
        //
        $discount_amount = 0;
        // dd($request->input("id"));
        $id = $request->input("id");
        $row = lounges_bookings::getSingleRowById($id);
        $airports = airport::where('id', $row->airportID)->first();
        if ($row->booking_amount > $row->discount_amount) {
            $discount_amount = ((float)$row->total_amount + (float)$row->discount_amount);
        }

        $html = "<div><h2>Booking Detail</h2></div><div style=\"overflow-y: scroll; margin-top: 30px\"> 
        <div class=\"row\" style=\"margin-bottom: 10px;\">
            <div class=\"col-md-6\">
            <table class=\"table table-bordered responsive\">
            <tr>
            <td>Booking Reference:</td>
            <td>" . $row->referenceNo . "</td>
            </tr>
            
            <tr>
            <td>Checkin Date:</td>
            <td>" . date("d-m-Y", strtotime($row->check_in)) .' '. $row->check_in_time. "</td>
            </tr>
            
            
            <tr>
            <td>Lounge Name:</td>
            <td>" . $row->lounge_name . "</td>
            </tr>
            
            
            <tr>
            <td>Lounge Code:</td>
            <td>" . $row->lounge_code . "</td>
            </tr>
            
            <tr>
            <td>Airport:</td>
            <td>" . $airports->name . "</td>
            </tr>
            <tr>
            <td>Terminal:</td>
            <td>" . $row->terminal . "</td>
            </tr>
            
            
            <tr>
            <td>Country Code:</td>
            <td>GB</td>
            </tr>
            
            </table>
                
            </div>
            <div class=\"col-md-6\">
                <table class=\"table table-bordered responsive\">
            <tr>
            <td>Name:</td>
            <td>" . $row->first_name . " " . $row->last_name . "</td>
            </tr>
            
            <tr>
            <td>Contact Number:</td>
            <td>" . $row->phone_number . "</td>
            </tr>
            
            
            <tr>
            <td>Email Address:</td>
            <td>" . $row->email . "</td>
            </tr>
            
            
            
            <tr>
            <td>Total Adults:</td>
            <td>" . $row->adults . "</td>
            </tr>
            
            
            <tr>
            <td>Total Children:</td>
            <td>" . $row->children . "</td>
            </tr>
            
             <tr>
            <td>API  Order No:</td>
            <td>" . $row->referenceNo_ext . "</td>
            </tr>
            
            <tr>
            <td>Booked with:</td>
            <td>Holiday Extra</td>
            </tr>
            
            
            
            
            </table>
            </div>
            </div>
            <table class=\"table table-bordered responsive\">
    			<thead>
    			<tr>
    				<th>Booking Amount</th>
    				<th>Extra Amount</th>
    				<th>Sms Fee</th>
    				<th>Booking Fee</th>
    				<th>Cancel Fee</th>
    				<th>Levy Fee</th>
    				<th>T Amount without Discount</th>
    				<th>Discount Code</th>
    				<th>Discount Amount</th>
    				<th>Net Amount to Pay</th>
    			</tr>
    			</thead>
    			<tbody><tr>
    				<td><stron class=\"badge badge-success badge-roundless\">" . $this->priceFormat($row->booking_amount) . "</stron></td>
    				<td>" . $this->priceFormat($row->extra_amount) . "</td>
    				<td>" . $this->priceFormat($row->smsfee) . "</td>
    				<td>" . $this->priceFormat($row->booking_fee) . "</td>
    				<td>" . $this->priceFormat($row->cancelfee) . "</td>
    				
    				<td>" . $this->priceFormat($row->leavy_fee) . "</td>
    				<td>" . $this->priceFormat($row->booking_amount+$row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee) . "</td>
    				<td>" . $row->discount_code . "</td>
    				<td>" . $this->priceFormat($row->booking_amount-$row->total_amount+$row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee) . "</td>
    				<td><stron class=\"badge badge-success badge-roundless\">" . $this->priceFormat($row->total_amount) . "</stron></td>
    			</tr>
    			</tbody><tbody>
    			</tbody></table>
    			<table class=\"table table-bordered responsive\">
    			<thead>
    			<tr>
    			    <th>Payer ID</th>
    				<th>Token</th>
    				<th>Payment Method</th>
    				<th>Payment Status</th>
    				<th>Booking Status</th>
    			</tr>
    			</thead>
    			<tbody><tr>
    			    <td>" . $row->PayerID . "</td>
    				<td></td>
    				<td>" . $row->payment_method . "</td>
    				<td>" . $row->payment_status . "</td>
    				<td>" . $row->booking_status . "</td>
    			</tr>
    			</tbody><tbody>
    			</tbody></table></div>";
        return $html;

    }
   
    
    public function sendEmailBooking(Request $request)
    {
        $id = $request->input("id");
        $cid = $request->input("cid");
        $type = $request->input("type");

        $row = lounges_bookings::getSingleRowById($id);

        $airport_detail = airport::where("id",$row->airportID)->first();
        $template_data = [];
        
        
            	
        $template_data["username"] = $row->first_name . " " . $row->last_name;
        $template_data["email"] = $row->email;
        $template_data["telephone"] = $row->phone_number;
        $template_data["lounge_name"] = $row->lounge_name;
        $template_data["company"] = 'Holiday Extra';
        $template_data["airport"] = $airport_detail->name;
        
        $template_data["start_date"] = date("d-m-Y", strtotime($row->check_in)) .' '. $row->check_in_time;
        $template_data["booktime"] = date("d-m-Y H:i:s", strtotime($row->createdate));
        $template_data["terminal"] = 'Terminal '.$row->terminal;
        
        $template_data["payment_gatway"] = $row->payment_method;
        $template_data["payment_status"] = $row->payment_status;
        $template_data["price"] = $row->total_amount;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] = $row->referenceNo;
        
        $template_data["ext_ref"] = $row->referenceNo_ext;
        
        $email_send = new EmailController();
        if ($type == "client" || $type == "all") {

            //dd($row->email);
            $email_send->sendEmail("Update Booking Lounge", $row->email, $template_data);
        }
        if ($type == "company" || $type == "all") {
            //$email_send->sendEmail("Update Booking Hotel", $row->email, $template_data);
            //$email_send->sendEmail("Add Booking Admin Hotel", 'bookings@parkingzone.co.uk', $template_data);
        }

        return "<h2>Email send successfully</h2>";
    }
    
    public function cancelForm(Request $request)
    {

        //
        $id = $request->input("id");
        $row = lounges_bookings::getSingleRowById($id);

        $discount_amount = 0;

        if ($row->booking_amount > $row->discount_amount) {
            $discount_amount = ((float)$row->total_amount + (float)$row->discount_amount);
        }
//        $company_name = "";
//        if ($row->company) {
//            $company_name = $row->company->name;
//        }
//        $airport_name = "";
//        if ($row->airport) {
//            $airport_name = $row->airport->name;
//        }

        $html = "<div><h2>Booking Detail</h2></div><div style=\"overflow-y: scroll; text-align: left; margin-top: 30px\"> 
    
            <table class=\"table table-bordered responsive\">
			<thead>
			<tr>
				<th>Booking Amount</th>
				<th>Extra Amount</th>
				<th>Sms Fee</th>
				<th>Booking Fee</th>
				<th>Cancel Fee</th>
				<th>Levy Fee</th>
				<th>T Amount without Discount</th>
				<th>Discount Code</th>
				<th>Discount Amount</th>
				<th>Net Amount to Pay</th>
			</tr>
			</thead>
			<tbody><tr>
				<td><stron class=\"badge badge-success badge-roundless\">" .  $this->priceFormat($row->booking_amount) . "</stron></td>
				<td>" . $this->priceFormat($row->extra_amount) . "</td>
				<td>" . $this->priceFormat($row->smsfee) . "</td>
				<td>" . $this->priceFormat($row->booking_fee) . "</td>
				<td>" . $this->priceFormat($row->cancelfee) . "</td>
				
				<td>" . $row->leavy_fee . "</td>
				<td>" . $this->priceFormat($row->booking_amount+$row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee) . "</td>
				<td>" . $row->discount_code . "</td>
				<td>" . $row->discount_amount. "</td>
				<td><stron class=\"badge badge-success badge-roundless\">" . $row->total_amount . "</stron></td>
			</tr>
			</tbody><tbody>
			</tbody></table>
			<table class=\"table table-bordered responsive\">
			<thead>
			<tr>
			    <th>Payer ID</th>
				<th>Token</th>
				<th>Payment Method</th>
				<th>Payment Status</th>
				<th>Booking Status</th>
			</tr>
			</thead>
			<tbody><tr>
			    <td>" . $row->PayerID . "</td>
				<td></td>
				<td>" . $row->payment_method . "</td>
				<td>" . $row->payment_status . "</td>
				<td>" . $row->booking_status . "</td>
			</tr>
			</tbody><tbody>
			</tbody></table>
			
			<!--<p class=\"alert alert-warning\"><b>This product is cancelable product but user did not pay cancelation fee..!!!</b></p>-->
			<div id='response'></div>
			<h2>Cancel</h2>
	        <form class=\"form-horizontal\" action=\"\" method=\"post\" id=\"form1\">
		    <table class=\"table table-bordered table-striped responsive\">
			<thead>
			<tr>
				<th></th>
				<th>Enter the Booking amount to be Cancelled</th>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input class=\"radio-resend\" type=\"checkbox\" value=\"client\" name=\"client\"> Send Email to Client <br>
				<input class=\"radio-resend\" type=\"checkbox\" value=\"company\" name=\"company\"> Send Email to Company <br>
				<input class=\"radio-resend\" type=\"checkbox\" value=\"admin\" name=\"admin\"> Send Email to Admin</td>
				<td><input type=\"text\" value=\"".($row->total_amount-$row->booking_fee)."\" name=\"amount\" class=\"form-control\" placeholder=\"Enter booking Amount to be Cancelled\"><br>Suggested Amount ".($row->total_amount-$row->booking_fee)."</td>
				
				<td><input type=\"checkbox\" name=\"booking_payment_medium\" value=\"Cheque\"> Refund Via Cheque<br>
				<input type=\"checkbox\" name=\"booking_payment_medium\" value=\"Charge back\"> Refund Via Charge back<br>
				<input type=\"checkbox\" name=\"booking_payment_medium\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br>
				<input type=\"checkbox\" checked=\"\" name=\"booking_payment_medium\" value=\"Payment Gateway\"> Refund Via Payment Gateway
				</td>
				<td><input type=\"submit\" id=\"button1\" name=\"refund\" class=\"btn btn-info\" value=\"Save\"></td>
			</tr>
			<tr>
				<td colspan=\"4\">
					<table style=\"width: 680px;\" class=\"cancelchild1\">
						
					</table>
				</td>
			
			
			</tr>
			
			<tr id=\"cancelparent\">
				
				<td><input type=\"text\" value=\"\" name=\"palenty_amount[]\" class=\"form-control\" placeholder=\"Enter Palenty Amount to be refunded\"><br>
				<input type=\"checkbox\" name=\"palenty_to[]\" value=\"Company\"> To Company<br>
				<input type=\"checkbox\" checked=\"\" name=\"palenty_to[]\" value=\"JETSEEKER\"> To JETSEEKER
				</td>
				<td><input type=\"checkbox\" name=\"payment_medium[]\" value=\"Cheque\"> Refund Via Cheque<br>
				<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Charge back\"> Refund Via Charge back<br>
				<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br>
				<input type=\"checkbox\" checked=\"\" name=\"payment_medium[]\" value=\"Payment Gateway\"> Refund Via Payment Gateway
				</td>				
				<td><input type=\"button\" id=\"cancelbutton3\" class=\"btn btn-info\" value=\"Add more\"></td>	
			</tr>
			</tbody>
		</table>
		<textarea class=\"col-md-12\" placeholder=\"Enter Reason Here....\" name=\"reason\" id=\"reason1\">Customer Emailed</textarea>
		<input type=\"hidden\" name=\"id\" value=\"$row->id\">
		<input type=\"hidden\" name=\"mode\" value=\"cancel\">
		<input type=\"hidden\" name=\"t_amount\" value=\"$row->total_amount\">
	</form>
			<script type=\"text/javascript\">




$('#button3').click(function () {
	
    var div = $('.child1').length;
   
    $('.child1').append('<tr class=\"childn\"><td><input type=\"text\" value=\"\" name=\"palenty_amount[]\" class=\"form-control\" placeholder=\"Enter Palenty Amount to be refunded\"/><br/>'+
				'<input type=\"checkbox\" checked name=\"palenty_to[]\" value=\"Company\"> To Company<br/>'+
				'<input type=\"checkbox\"  name=\"palenty_to[]\" value=\"JETSEEKER\"> To JETSEEKER'+
				'</td>'+
				'<td><input type=\"checkbox\" name=\"payment_medium[]\" value=\"Cheque\"> Refund Via Cheque<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Charge back\"> Refund Via Charge back<br/>'+
				
				'<input type=\"checkbox\" checked name=\"payment_medium[]\" value=\"Payment Gateway\"> Refund Via Payment Gateway'+
				'</td>'+				
				'<td><a class=\"btn btn-red\" id=\"Remove_product\">Remove</a></td></tr>');
});
$(document).on(\"click\", \"#Remove_product\", function() {
	
    $(this).closest('.childn').remove();
});
$('#cancelbutton3').click(function () {
	
    var div = $('.cancelchild1').length;
   
    $('.cancelchild1').append('<tr class=\"cancelchildn\"><td><input type=\"text\" value=\"\" name=\"palenty_amount[]\" class=\"form-control\" placeholder=\"Enter Palenty Amount to be refunded\"/><br/>'+
				'<input type=\"checkbox\" checked name=\"palenty_to[]\" value=\"Company\"> To Company<br/>'+
				'<input type=\"checkbox\"  name=\"palenty_to[]\" value=\"JETSEEKER\"> To JETSEEKER'+
				'</td>'+
				'<td><input type=\"checkbox\" name=\"payment_medium[]\" value=\"Cheque\"> Refund Via Cheque<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Charge back\"> Refund Via Charge back<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br/>'+
				'<input type=\"checkbox\" checked name=\"payment_medium[]\" value=\"Payment Gateway\"> Refund Via Payment Gateway'+
				'</td>'+				
				'<td><a class=\"btn btn-red\" id=\"cancelRemove_product\">Remove</a></td></tr>');
});
$(document).on(\"click\", \"#cancelRemove_product\", function() {
	
    $(this).closest('.cancelchildn').remove();
});






$('#form1').on('submit', function(event) {
	if($('#form1 input[name=\"amount\"]').val()==''){
		alert('Amount is required.....!!');
		return false;
	}
    else if($('#form1 textarea[name=\"reason\"]').val()==''){
		alert('Reason is required.....!!');
		return false;
	}else{
		  event.preventDefault();
		  $.ajax({
                   url:'" . route('cancelFormActionLounge') . "',
                    type:'POST',
                    data:$(this).serialize(),
                    
                    success:function(result){
                    $('#response').addClass('alert alert-danger');
                         $(\"#response\").text(result.message+' '+'System will redirect you in a moment'); 
                        if(result.success==1){ 
                            $('#response').addClass('alert alert-success');
                            setTimeout(function(){
                                location.reload();
                                $('.bootbox.modal').modal('hide');
                            },5000);
                         }

                    }

            });
	}
       
});
</script>
			</div>";
        return $html;

    }
    
    public function cancelFormActionLounge(Request $request)
    {

        $id = $request->input("id");

        $already_booking_refunds = 0;
        $refund_trans = lounges_transaction::all()->where("orderID", $id);


        foreach ($refund_trans as $refund_tran) {
            $already_booking_refunds += (float)$refund_tran->payable;

        }

        $type = $request->input("mode");
        $reason = $request->input("reason");
        $t_amount = $request->input("t_amount");
        $booking_payment_medium = $request->input("booking_payment_medium");
        $payment_medium = $request->input('payment_medium');
        $palenty_amount = $request->input('palenty_amount');
        $palenty_to = $request->input('palenty_to');
        $amt = !empty($request->input('amount')) ? number_format($request->input('amount'), 2, '.', '') : $request->input('full_r');
        $total_refunded_booking = ($already_booking_refunds + $amt);


        if ($total_refunded_booking > $t_amount) {

            $data["success"] = 0;
            $data["message"] = "Alert !!!! already paid Booking Refunded Amount";

            return response()->json($data);


        } else {

            if ($amt > $t_amount) {
                $data["success"] = 0;
                $data["message"] = "Alert !!!! Booking Refunded Amount Must Be Less Then Customer Paid Booking Amount";
                return response()->json($data);
            } else {
                if ($type == 'cancel') {
                    $email_to = array('client' => $request->input('client'), 'company' => $request->input('company'), 'admin' => $request->input('admin'));
                    $res = $this->refunds($id, $payment_medium, $palenty_amount, $palenty_to, 'cancel', $amt, $reason, "", $email_to, $booking_payment_medium);

                    return response()->json($res);
                }
                if ($type == 'refund') {

                    $email_to = array('client' => $request->input('client'), 'company' => $request->input('company'), 'admin' => $request->input('admin'));

                    $res = $this->refunds($id, $payment_medium, $palenty_amount, $palenty_to, 'refund', $amt, $reason, "", $email_to, $booking_payment_medium);
                    return response()->json($res);
                }
            }
        }

    }
    public function refundForm(Request $request)
    {
        //
        $id = $request->input("id");
        $row = lounges_bookings::getSingleRowById($id);
//        $company_name = "";
//        if ($row->company) {
//            $company_name = $row->company->name;
//        }
//        $airport_name = "";
//        if ($row->airport) {
//            $airport_name = $row->airport->name;
//        }

        $html = "<div><h2>Booking Detail</h2></div><div style=\"overflow-y: scroll; text-align: left; margin-top: 30px\"> 
    
            <table class=\"table table-bordered responsive\">
			<thead>
			<tr>
				<th>Booking Amount</th>
				<th>Extra Amount</th>
				<th>Sms Fee</th>
				<th>Booking Fee</th>
				<th>Cancel Fee</th>
				<th>Levy Fee</th>
				<th>T Amount without Discount</th>
				<th>Discount Code</th>
				<th>Discount Amount</th>
				<th>Net Amount to Pay</th>
			</tr>
			</thead>
			<tbody><tr>
				<td><stron class=\"badge badge-success badge-roundless\">" .  $this->priceFormat($row->booking_amount) . "</stron></td>
				<td>" . $row->extra_amount . "</td>
				<td>" . $row->smsfee . "</td>
				<td>" . $row->booking_fee . "</td>
				<td>" . $row->cancelfee . "</td>
				
				<td>" . $row->leavy_fee . "</td>
				<td>" . $this->priceFormat($row->booking_amount+$row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee) . "</td>
				<td>" . $row->discount_code . "</td>
				<td>" . $row->discount_amount . "</td>
				<td><stron class=\"badge badge-success badge-roundless\">" . $row->total_amount . "</stron></td>
			</tr>
			</tbody><tbody>
			</tbody></table>
			<table class=\"table table-bordered responsive\">
			<thead>
			<tr>
			    <th>Payer ID</th>
				<th>Token</th>
				<th>Payment Method</th>
				<th>Payment Status</th>
				<th>Booking Status</th>
			</tr>
			</thead>
			<tbody><tr>
			    <td>" . $row->PayerID . "</td>
				<td></td>
				<td>" . $row->payment_method . "</td>
				<td>" . $row->payment_status . "</td>
				<td>" . $row->booking_status . "</td>
			</tr>
			</tbody><tbody>
			</tbody></table>
			
			<p class=\"alert alert-warning\"><b>This product is cancelable product but user did not pay cancelation fee..!!!</b></p>
			<div id='response'></div>
			<h2>Refund</h2>
	        <form class=\"form-horizontal\" action=\"\" method=\"post\" id=\"form1\">
		    <table class=\"table table-bordered table-striped responsive\">
			<thead>
			<tr>
				<th></th>
				<th>Enter Amount to be refunded	</th>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<tr >
				<td><input class=\"radio-resend\" type=\"checkbox\" value=\"client\" name=\"client\"> Send Email to Client <br>
				<input class=\"radio-resend\" type=\"checkbox\" value=\"company\" name=\"company\"> Send Email to Company <br>
				<input class=\"radio-resend\" type=\"checkbox\" value=\"admin\" name=\"admin\"> Send Email to Admin</td>
				<td style=\"display:none;\"><input type=\"text\" value=\"$row->total_amount\" name=\"amount\" class=\"form-control\" placeholder=\"Enter booking Amount to be Cancelled\"><br>Suggested Amount $row->total_amount</td>
				
				<td style=\"display:none;\"><input type=\"checkbox\" name=\"booking_payment_medium\" value=\"Cheque\"> Refund Via Cheque<br>
				<input type=\"checkbox\" name=\"booking_payment_medium\" value=\"Charge back\"> Refund Via Charge back<br>
				<input type=\"checkbox\" name=\"booking_payment_medium\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br>
				<input type=\"checkbox\" checked=\"\" name=\"booking_payment_medium\" value=\"Payment Gateway\"> Refund Via Payment Gateway
				</td>
				<td><input type=\"submit\" id=\"button1\" name=\"refund\" class=\"btn btn-info\" value=\"Save\"></td>
			</tr>
			<tr>
				<td colspan=\"4\">
					<table style=\"width: 680px;\" class=\"cancelchild1\">
						
					</table>
				</td>
			
			
			</tr>
			
			<tr id=\"cancelparent\">
				
				<td><input type=\"text\" value=\"\" name=\"palenty_amount[]\" class=\"form-control\" placeholder=\"Enter Palenty Amount to be refunded\"><br>
				<input type=\"checkbox\" name=\"palenty_to[]\" value=\"Company\"> To Company<br>
				<input type=\"checkbox\" checked=\"\" name=\"palenty_to[]\" value=\"JETSEEKER\"> To JETSEEKER
				</td>
				<td><input type=\"checkbox\" name=\"payment_medium[]\" value=\"Cheque\"> Refund Via Cheque<br>
				<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Charge back\"> Refund Via Charge back<br>
				<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br>
				<input type=\"checkbox\" checked=\"\" name=\"payment_medium[]\" value=\"Payment Gateway\"> Refund Via Payment Gateway
				</td>				
				<td><input type=\"button\" id=\"cancelbutton3\" class=\"btn btn-info\" value=\"Add more\"></td>	
			</tr>
			</tbody>
		</table>
		<textarea class=\"col-md-12\" placeholder=\"Enter Reason Here....\" name=\"reason\" id=\"reason1\">Customer Emailed</textarea>
		<input type=\"hidden\" name=\"id\" value=\"$row->id\">
		  <input type=\"hidden\" name=\"mode\" value=\"refund\">
		<input type=\"hidden\" name=\"t_amount\" value=\"$row->total_amount\">
	</form>
			<script type=\"text/javascript\">




$('#button3').click(function () {
	
    var div = $('.child1').length;
   
    $('.child1').append('<tr class=\"childn\"><td><input type=\"text\" value=\"\" name=\"palenty_amount[]\" class=\"form-control\" placeholder=\"Enter Palenty Amount to be refunded\"/><br/>'+
				'<input type=\"checkbox\" checked name=\"palenty_to[]\" value=\"Company\"> To Company<br/>'+
				'<input type=\"checkbox\"  name=\"palenty_to[]\" value=\"JETSEEKER\"> To JETSEEKER'+
				'</td>'+
				'<td><input type=\"checkbox\" name=\"payment_medium[]\" value=\"Cheque\"> Refund Via Cheque<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Charge back\"> Refund Via Charge back<br/>'+
				
				'<input type=\"checkbox\" checked name=\"payment_medium[]\" value=\"Payment Gateway\"> Refund Via Payment Gateway'+
				'</td>'+				
				'<td><a class=\"btn btn-red\" id=\"Remove_product\">Remove</a></td></tr>');
});
$(document).on(\"click\", \"#Remove_product\", function() {
	
    $(this).closest('.childn').remove();
});
$('#cancelbutton3').click(function () {
	
    var div = $('.cancelchild1').length;
   
    $('.cancelchild1').append('<tr class=\"cancelchildn\"><td><input type=\"text\" value=\"\" name=\"palenty_amount[]\" class=\"form-control\" placeholder=\"Enter Palenty Amount to be refunded\"/><br/>'+
				'<input type=\"checkbox\" checked name=\"palenty_to[]\" value=\"Company\"> To Company<br/>'+
				'<input type=\"checkbox\"  name=\"palenty_to[]\" value=\"JETSEEKER\"> To JETSEEKER'+
				'</td>'+
				'<td><input type=\"checkbox\" name=\"payment_medium[]\" value=\"Cheque\"> Refund Via Cheque<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Charge back\"> Refund Via Charge back<br/>'+
				'<input type=\"checkbox\" name=\"payment_medium[]\" value=\"Bank Transfer\"> Refund Via Bank Transfer<br/>'+
				'<input type=\"checkbox\" checked name=\"payment_medium[]\" value=\"Payment Gateway\"> Refund Via Payment Gateway'+
				'</td>'+				
				'<td><a class=\"btn btn-red\" id=\"cancelRemove_product\">Remove</a></td></tr>');
});
$(document).on(\"click\", \"#cancelRemove_product\", function() {
	
    $(this).closest('.cancelchildn').remove();
});






$('#form1').on('submit', function(event) {
	if($('#form1 input[name=\"amount\"]').val()==''){
		alert('Amount is required.....!!');
		return false;
	}
    else if($('#form1 textarea[name=\"reason\"]').val()==''){
		alert('Reason is required.....!!');
		return false;
	}else{
		 
		  event.preventDefault();
		  $.ajax({
                    url:'" . route('cancelFormActionLounge') . "',
                    type:'POST',
                    data:$(this).serialize(),
                    success:function(result){
                       // console.log(result);
                       $('#response').addClass('alert alert-danger');
                        $(\"#response\").text(result.message); 
                        if(result.success==1){ 
                        $('#response').addClass('alert alert-success');
                        location.reload();
                             $('.bootbox.modal').modal('hide');
                         }
                        

                    }

            });
	}
       
});
</script>
</script>
			</div>";
        return $html;

    }
    
    public function refunds($id, $payment_medium = '', $palenty_amount = '', $palenty_to = '', $mode, $amt, $reason, $type = "", $email_to, $booking_payment_medium = '')
    {        
    	
        $response = [];
        $response["success"] = 0;
        $response["message"] = 'Technical Error!!';

        $clientemail = isset($email_to['client']) ? $email_to['client'] : '';
        $companyemail = isset($email_to['company']) ? $email_to['company'] : '';
        $adminemail = isset($email_to['admin']) ? $email_to['admin'] : '';
        $admin_id = 0;
        if (Auth::check()) {
            $admin_id = Auth::user()->id;
        }

        $payment_action = $mode == 'cancel' ? 'Refund' : 'Refund';
        $payment_case = $mode == 'cancel' ? 'cancel' : 'Refund';
        $booking_status = $mode == 'cancel' ? 'cancelled' : 'Refund';

        //if ($type == '') {
        //$booking = $db->get_row("SELECT * FROM " . $db->prefix . "booking WHERE id='" . $id . "' ");
        $booking = lounges_bookings::where("id", $id)->first();


        $companyID = '';
        //$company = "companyId = '" . $companyID . "'";

        $status = $mode == 'cancel' ? 'Cancel Booking Lounge' : 'Refund Booking Lounge';
        $cstatus = $mode == 'cancel' ? 'Cancel Booking Company' : 'Refund';
        $getstatus = 1;


        if ($booking) {


            if ($amt == 'full') {
                $refund = ($booking->booking_amount * 1) - ($booking->discount_amount * 1);
            } else {
                $refund = $amt;
            }
            if ($reason == '00') {
                $reason = 'Not Show';
                $status = 'Cancel Booking Not Show';
                $booking_status = 'Noshow';
                //$booking_action = ", booking_action = 'Noshow'";
            }
            if ($payment_medium == "Payment Gateway") {
                $refundStatus = $this->refundPayment($refund, $booking->referenceNo, $booking->PayerID);
            } else {
                $refundStatus = [];
                $refundStatus["StatusCode"] = 0;
            }


            if ($refundStatus["StatusCode"] == 0) {
                $getstatus = 1;

                if ($getstatus == 1) {


                    $data = [];
                    $data["orderID"] = $booking->id;
                    $data["referenceNo"] = $booking->referenceNo;
                    $data["loungeID"] = $booking->lounge_id;
                    $data["booking_amount"] = $booking->booking_amount;
                    $data["extra_amount"] = $booking->extra_amount;
                    $data["discount_amount"] = $booking->discount_amount;
                    $data["smsfee"] = $booking->smsfee;
                    $data["booking_fee"] = $booking->booking_fee;
                    $data["cancelfee"] = $booking->cancelfee;
                    $data["payable"] = $refund;
                    $data["amount_type"] = 'credit';
                    $data["payment_method"] = $booking->payment_method;
                    $data["payment_action"] = $payment_action;
                    $data["payment_case"] = $payment_case;
                    $data["payment_medium"] = $booking_payment_medium;
                    $data["comments"] = $reason;
                    $data["edit_by"] = $admin_id;
                    $data["modifydate"] = date("Y-m-d H:i:s");
                    $data["total_amount"] = $booking->total_amount;

                    $transaction = DB::table('lounges_transaction')->insertGetId($data);


                    $trans_id = $transaction;
                    $i = 0;
                    if (!empty($palenty_amount)) {
                        foreach ($palenty_amount as $penalty) {
                            if ($penalty == "") {
                                $penalty = 0;
                            }
                            $d = [];
                            $d["trans_id"] = $trans_id;
                            $d["payment_medium"] = $payment_medium[$i];
                            $d["penalty_amount"] = $penalty;
                            $d["penalty_to"] = $palenty_to[$i];


                            $inserted = DB::table('lounge_penalty_details')->insert($d);

                            $i++;
                        }
                    }else {
                        $inserted = 0;
                    }

                    if ($inserted || $reason == 'Not Show') {
                        // $query = $db->update("UPDATE $btbl SET booking_status = '" . $booking_status . "' $booking_action WHERE id = '" . $id . "' ");

                        $dd = [];
                        $dd["booking_status"] = $booking_status;

                        $dd["booking_action"] = "Refund";
                        if ($mode == 'cancel') {
                            $dd["booking_action"] = "Cancelled";
                        }


                        if ($reason == '00') {
                            $dd["booking_action"] = "Noshow";
                        }


                        $query = DB::table('lounges_bookings')
                            ->where("id", $id)
                            ->update($dd);



                        $query = 1;
                        if ($query) {
                            $company_email = is_numeric($companyID) ? 'company' : 'info@parkingzone.co.uk';


                            $bookData = lounges_bookings::getSingleRowById($id);

                            $template_data = [];
                            $template_data["username"] = $bookData->first_name . " " . $bookData->last_name;

                            $template_data["airport"] = $bookData->airport->name;
                            
                            
							$template_data["email"] = $bookData->email;
                            $template_data["start_date"] = date("d-m-Y", strtotime($bookData->check_in)) .' '. $bookData->check_in_time;;
                            
                            $template_data["lounge_name"] = $bookData->lounge_name;
                            
                            $template_data["ref"] = $bookData->referenceNo;
                            $template_data["refund"] = $refund;
                            $template_data["terminal"] = $bookData->terminal;
                            
    
                            file_put_contents("cancel_email_data.txt",print_r($template_data,true));

                            if ($mode == 'cancel' && !empty($companyemail)) {
                                //notifications($id, $cstatus, $company_email);

                                $email_send = new EmailController();
                                // $company_data = DB::table('companies')->where('id', $bookData->companyId)->first();
                                // if ($company_data->company_email) {
                                //     $company_email = $company_data->company_email;
                                //     $email_send->sendEmail($cstatus, $company_email, $template_data);
                                // }
                                
                                // notifications($id, $cstatus,'ubaid@zafftech.com','company');
                            }
                            if (!empty($adminemail)) {
                                
                                if ($bookData->admin) {
                                    $adminemail = $bookData->admin->email;
                                     $email_send = new EmailController();
                                   
                                }
                                //dd($bookData."====000");
                                // notifications($id, $status, 'admin', '', '', $type);

                            }
                            if (!empty($clientemail)) {
                                // notifications($id, $status, $booking['email'], '', '', $type);
                                // notifications($id, $status,'ubaid@zafftech.com','','',$type);
                                $email_send = new EmailController();
                                $email_send->sendEmail($status, $booking['email'], $template_data);
                            }
                            $response["success"] = 1;
                            $response["message"] = 'Booking Successfully ' . ucwords($mode) . '..!!';
                            //$msg = base64_encode('success:Booking Successfully ' . ucwords($mode) . '..!!');
                           
                        } else {
                            //$msg = base64_encode('error:Try again..!!');
                            $response["success"] = 0;
                            $response["message"] = 'Try again..!!';

                        }
                    }
                } else {
                    //$msg = base64_encode('error:' . $getstatus);
                    $response["success"] = 0;
                    $response["message"] = 'Error! Try again..!!';
                }

            } else {
                $response["success"] = 0;
                $response["message"] = $refundStatus["Message"];
            }
            return $response;
        } else {
            //$msg = base64_encode('error:Record not found..!!');
            $response["success"] = 0;
            $response["message"] = 'Record not found..!!';

            return $response;
        }


        return $response;

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
    
    function priceFormat($price, $symbol = true)
    {
        $formated_price = '';
        if ($symbol) {
            $formated_price .= '&pound;';
        }
        $formated_price .= number_format(((float)$price * 1), 2);
        return $formated_price;
    }
    
}
