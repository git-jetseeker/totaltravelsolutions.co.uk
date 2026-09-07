<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;

use App\Models\airports_bookings;
use App\Models\customers;
use App\Models\discounts;
use App\Models\Company;
use App\Models\companies_special_features;
use App\Models\airport;
use App\Models\OffDays; 
use App\Models\settings;
use App\Models\AppImage;
use App\Models\modules_settings;
use App\Models\airports_terminals;

use App\Library\aph_functions;
use App\Library\functions;
use App\Library\api;

use DateTime;

use Illuminate\Support\Facades\Password; // Import the Password facade
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ApiCusomterController extends Controller
{
    public $_settings = [];
    public $_module_setting = [];
    public function __construct()
    {
        $modules_settings = modules_settings::all();
        foreach ($modules_settings as $setting) {
            $this->_module_setting[$setting->name] = $setting->value;
        }
        $settings = settings::all();
        foreach ($settings as $setting) {
            $this->_settings[$setting->field_name] = $setting->field_value;
        }
    }
    public function PromoDiscount(Request $request)
    {
        $request->validate([
            "promo" => "required|string",
        ]);
        //Retrieve the discount code from the database
        $discount = discounts::where(
            "promo",
            $request->input("promo")
        )->first();
        return response()->json([
            "message" => "Discount code is valid.",
            "discount" => $discount, // You can return discount details here
        ]);
    }
    
     public function receiveFile(Request $request)
    {
        // Validate the request
        $request->validate([
            'attachment' => 'required|file',
        ]);
        $file = $request->file('attachment');
        $publicPath = public_path('supports');
        // Move the uploaded file to the public uploads directory
        $fileName = time() . '_' . $file->getClientOriginalName(); // You can customize the filename as needed
        $file->move($publicPath, $fileName);

        // Return the full URL of the uploaded file
        $fileUrl = 'supports/' . $fileName;
        return response()->json(['path' => $fileUrl], 200);
    }
    
    public function bookinglist(Request $request)
    {
        $request->validate([
            "referenceNo" => "required|string",
        ]);
        $booking = airports_bookings::where(
            "referenceNo",
            $request->input("referenceNo")
        )
            ->join(
                "airports_terminals as dep",
                "airports_bookings.deprTerminal",
                "=",
                "dep.id"
            )
            ->join(
                "airports_terminals as ret",
                "airports_bookings.returnTerminal",
                "=",
                "ret.id"
            )
            ->join(
                "airports as ap",
                "airports_bookings.airportID",
                "=",
                "ap.id"
            )
            ->select(
                "ap.name as aiportName",
                "airports_bookings.referenceNo",
                "airports_bookings.first_name",
                "airports_bookings.last_name",
                "airports_bookings.no_of_days",
                "airports_bookings.total_amount",
                "airports_bookings.make",
                "airports_bookings.color",
                "airports_bookings.model",
                "airports_bookings.registration",
                "airports_bookings.departDate",
                "dep.name as deprTerminalName", // Terminal name for departure
                "airports_bookings.deptFlight",
                "airports_bookings.returnDate",
                "ret.name as returnTerminalName", // Terminal name for return
                "airports_bookings.returnFlight"
            )
            ->first();
        if (!$booking) {
            return response()->json(
                [
                    "message" =>
                        "No booking found for the given reference number.",
                ],
                404
            );
        }
        return response()->json(
            [
                "booking" => $booking,
            ],
            200
        );
    }
    public function airportsTerminals(Request $request)
    {
        $aid = $request->id;
        $terminals = airports_terminals::all()->where("aid", $aid);
        $airportName = airport::where("id", $aid)->first();
        $airportName = $airportName->name;
        return response()->json(
            [
                "Airport" => $airportName,
                "terminals" => $terminals,
            ],
            200
        );
    }
    public function airports()
    {
        $airports = airport::all()->where("status", "Yes");
        return response()->json(
            [
                "airports" => $airports,
            ],
            200
        );
    }
    public function booking(Request $request)
    {
        $data = $request->all();
        //Validate the incoming request data
        $validator = Validator::make($data, [
            "title" => "required|string|max:5",
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|string|email",
            "phone_number" => "required|string|max:255",
            "airport_code" => "required|string|max:5",
            "product_code" => "required|string|max:10",
            "dropoff_date" => "required|string|max:255",
            "dropoff_time" => "required|string|max:255",
            "dep_terminal" => "required|string|max:255",
            "dep_flight" => "required|string|max:255",
            "pickup_date" => "required|string|max:255",
            "pickup_time" => "required|string|max:255",
            "ret_terminal" => "required|string|max:255",
            "ret_flight" => "required|string|max:255",
            "make" => "required|string|max:255",
            "model" => "required|string|max:255",
            "color" => "required|string|max:255",
            "registration" => "required|string|max:15",
            "passengers" => "required|string|max:3",
            "user_ip" => "required|string|max:30",
            "booking_amt" => "required|string|max:10",
            "sms_fee" => "required|string|max:10",
            "cancel_fee" => "required|string|max:10",
            "discount_amt" => "required|string|max:10",
            "total_amt" => "required|string|max:10",
            "pmt_token" => "required|string|max:255",
            "promo" => "required|string|max:25",
            "park_api" => "required|string|max:10",
        ]);
        //Check if validation fails
        if ($validator->fails()) {
            //Return the error messages with a 422 Unprocessable Entity status
            // file_put_contents(
            //     "app_requests_errors.txt",
            //     "Date: " .
            //         date("Y-m-d H:i:s") .
            //         '\r\n' .
            //         'Errors: \r\n' .
            //         print_r($validator->errors(), true) .
            //         "\r\n",
            //     FILE_APPEND
            // );
            return response()->json(
                [
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        $airport = airport::where("iata_code", $request->airport_code)->first();
        $airportId = $airport->id;
        $customer_id = customers::where("email", $request->email)->first();
        $customer_id = $customer_id->id;
        $company = Company::where(
            "company_code",
            $request->product_code
        )->first();
        $company_id = $company->id;
        $booked_type = $company->parking_type;
        $park_api = $data["park_api"];
        $data = [];
        $data["airportID"] = $airportId;
        $data["customerId"] = $customer_id;
        $data["companyId"] = $company_id;
        $data["product_code"] = $request->product_code;
        $data["title"] = $request->title;
        $data["first_name"] = $request->first_name;
        $data["last_name"] = $request->last_name;
        $data["email"] = $request->email;
        $data["phone_number"] = $request->phone_number;
        $data["passenger"] = $request->passengers;
        $data["departDate"] =
            $request->dropoff_date . " " . $request->dropoff_time;
        $data["deprTerminal"] = $request->dep_terminal;
        $data["deptFlight"] = $request->dep_flight;
        $data["returnDate"] =
            $request->pickup_date . " " . $request->pickup_time;
        $data["returnFlight"] = $request->ret_flight;
        $data["returnTerminal"] = $request->ret_terminal;
        $data["no_of_days"] = $request->no_of_days;
        $data["discount_code"] = $request->promo;
        $data["discount_amount"] = $request->discount_amt;
        $data["booking_amount"] = $request->booking_amt;
        $data["smsfee"] = $request->sms_fee;
        $data["booking_fee"] = "1.99";
        $data["cancelfee"] = $request->cancel_fee;
        $data["total_amount"] = $request->total_amt;
        $data["booked_type"] = $booked_type;
        $data["payment_method"] = "stripe";
        $data["booking_status"] = "Completed";
        $data["booking_action"] = "Booked";
        $data["payment_status"] = "success";
        $data["PayerID"] = $request->pmt_token;
        $data["user_ip"] = $request->user_ip ?? "TBA";
        $data["make"] = $request->make ?? "TBA";
        $data["model"] = $request->model ?? "TBA";
        $data["color"] = $request->color ?? "TBA";
        $data["registration"] = $request->registration ?? "TBA";
        $data["park_api"] = $request->park_api;
        $data["traffic_src"] = "App";
        $data["agentID"] = 20;
        $data["booking_extra"] = 0;
        $data["browser_data"] = '';
        $booking_id = airports_bookings::insertGetId($data);
        $bookingref = "MG-20";
        $bookingref .= date("y") . date("m") . date("d");
        $bookingref = $bookingref . $booking_id;
        $ref = [];
        $ref["referenceNo"] = $bookingref;
        $update = airports_bookings::where("id", $booking_id)->update($ref);
        if ($update && $park_api == "DB") {
            $this->notification(
                $request,
                $bookingref,
                $airportId,
                $company_id,
                $booked_type,
                $booking_id
            );
        }
        if ($park_api == "a2z") {
            $terminal = $request->dep_terminal;
            $rterminal = $request->ret_terminal;
            if (
                $request->dep_terminal != "TBA" &&
                $request->dep_terminal != ""
            ) {
                $terminaldb = airports_terminals::where(
                    "id",
                    $request->dep_terminal
                )->first();
                $terminal = $terminaldb->name;
            } else {
                $terminal = "NA";
            }
            if (
                $request->ret_terminal != "TBA" &&
                $request->ret_terminal != ""
            ) {
                $rterminaldb = airports_terminals::where(
                    "id",
                    $request->ret_terminal
                )->first();
                $rterminal = $rterminaldb->name;
            } else {
                $rterminal = "NA";
            }
            $bookingAmt = $request->booking_amt;
            $data = [
                "user" => $this->_settings["a2z_user"],
                "key" => $this->_settings["a2z_key"],
                "action" => "payment",
                "booking" => [
                    "airport" => $request->airport_code,
                    "productsku" => $request->product_code,
                    "bookreference" => $bookingref,
                    "depdate" => date(
                        "Y-m-d",
                        strtotime($request->dropoff_date)
                    ),
                    "deptime" => $request->dropoff_time,
                    "depterminal" => $terminal ?? "TBA",
                    "depflight" => $request->dep_flight ?? "TBA",
                    "returndate" => date(
                        "Y-m-d",
                        strtotime($request->pickup_date)
                    ),
                    "returntime" => $request->pickup_time,
                    "returnterminal" => $rterminal ?? "TBA",
                    "returnflight" => $request->ret_flight ?? "TBA",
                    "parkingdays" => $request->no_of_days,
                    "make" => $request->make ?? "TBA",
                    "model" => $request->model ?? "TBA",
                    "colour" => $request->color ?? "TBA",
                    "regnumber" => $request->reg_number ?? "TBA",
                    "passengers" => $request->passengers ?? "1",
                    "ipaddress" => "192.168.1.1",
                    "title" => $request->title,
                    "firstname" => $request->first_name,
                    "lastname" => $request->last_name,
                    "emailaddress" => $request->email,
                    "mobile" => $request->phone_number,
                    "quoteamount" => "$bookingAmt",
                    "bookcharge" => "0.00",
                    "discountamount" => "0.00",
                    "totalamount" => "$bookingAmt",
                    "paymentgateway" => "Invoice",
                    "paymenttoken" => $request->pmt_token,
                ],
            ];
            $aph_functions = new aph_functions();
            $a2zOrder = $aph_functions->a2zBookingOrder($data);
            if (isset($a2zOrder) && $a2zOrder->status == "success") {
                $a2zData["ext_ref"] = $a2zOrder->reference;
                airports_bookings::where("referenceNo", $bookingref)->update(
                    $a2zData
                );
                $ext_ref = $a2zData["ext_ref"];
                $this->notification(
                    $request,
                    $ext_ref,
                    $airportId,
                    $company_id,
                    $booked_type,
                    $booking_id
                );
            }
        }
        return response()->json(
            [
                "Message" => "Booking successful",
                "referenceNo" => $bookingref,
            ],
            200
        );
    }

    public function apiRegister(Request $request)
    {
        $data = $request->all();
        // dd($data);
        //Validate the incoming request data
        $validator = Validator::make($data, [
            "title" => "required|string|max:255",
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:customers",
            "phone_number" => "required|string|max:255",
            "password" => "required|string|min:6|",

        ]);
        //Check if validation fails
        if ($validator->fails()) {
            //Return the error messages with a 422 Unprocessable Entity status
            // file_put_contents(
            //     "app_requests_errors.txt",
            //     "Date: " .
            //         date("Y-m-d H:i:s") .
            //         '\r\n' .
            //         'Errors: \r\n' .
            //         print_r($validator->errors(), true) .
            //         "\r\n",
            //     FILE_APPEND
            // );
            return response()->json(
                [
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        //Continue with user creation if validation passes
        $user = customers::create([
            "first_name" => $data["first_name"],
            "last_name" => $data["last_name"],
            "email" => $data["email"],
            "title" => $data["title"],
            "phone_number" => $data["phone_number"],
            "password" => Hash::make($data["password"]),
        ]);

        return response()->json($user, 201); // Return the created user data with a 201 Created status
    }
    public function apiLogin(Request $request)
    {
        $data = $request->all();
        //Validate the incoming request data
        $validator = Validator::make($data, [
            "email" => "required|string|email",
            "password" => "required|string",
        ]);
        //Check if validation fails
        if ($validator->fails()) {
            //Return the error messages with a 422 Unprocessable Entity status
            // file_put_contents(
            //     "app_requests_errors.txt",
            //     "Date: " .
            //         date("Y-m-d H:i:s") .
            //         '\r\n' .
            //         'Errors: \r\n' .
            //         print_r($validator->errors(), true) .
            //         "\r\n",
            //     FILE_APPEND
            // );
            return response()->json(
                [
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        $credentials = $request->only("email", "password");
        if (Auth::guard("customer")->attempt($credentials)) {
            //Authentication passed
            $customer = Auth::guard("customer")->user();
            return response()->json(
                ["message" => "Login successful", "customer" => $customer],
                200
            );
        } else {
            //Authentication failed
            return response()->json(["message" => "Invalid credentials"], 401);
        }
    }
    public function forgotPassword(Request $request)
    {
        //Validate the email input
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email|exists:customers,email",
        ]);
        //Check if validation fails
        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        
        
        //Send password reset link
        $status = Password::broker("customer")->sendResetLink(
            $request->only("email")
        );
        //Check the status of the password reset link request
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(
                ["message" => "Password reset link sent"],
                200
            );
        } else {
            return response()->json(
                ["message" => "Failed to send reset link"],
                500
            );
        }
    }
    public function resetPassword(Request $request)
    {
        //Validate the incoming request data
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email|exists:customers,email",
            "current_password" => "required|string",
            "password" => "required|string|min:8|confirmed", // This automatically checks for password_confirmation
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(
                [
                    "errors" => $validator->errors(),
                ],
                422
            );
        }
        try {
            //Retrieve the customer by email
            $customer = customers::where("email", $request->email)->first(); // Use singular model name
            if (!$customer) {
                return response()->json(
                    ["message" => "Customer not found"],
                    404
                );
            }
            //Check if the current password matches
            if (!Hash::check($request->current_password, $customer->password)) {
                return response()->json(
                    ["message" => "Current password is incorrect"],
                    401
                );
            }
            //Update the customer's password
            $customer->password = Hash::make($request->password);
            $customer->save();
            //Trigger the password reset event
            event(new PasswordReset($customer)); // Use the correct event
            return response()->json(
                ["message" => "Password reset successful"],
                200
            );
        } catch (\Exception $e) {
            \Log::error("Password reset error: " . $e->getMessage());
            return response()->json(
                ["message" => "Server error, please try again later"],
                500
            );
        }
    }
    public function searchResults(Request $request)
    {
        $data = $request->all();
        // file_put_contents(
        //     "app_requests_errors.txt",
        //     "Date: " .
        //         date("Y-m-d H:i:s") .
        //         '\r\n' .
        //         'RequestApi: \r\n' .
        //         print_r($data, true) .
        //         "\r\n",
        //     FILE_APPEND
        // );
        //Validate the incoming request data
        $validator = Validator::make($data, [
            "airport_id" => "required|string|max:255",
            "dropoff_date" => "required|string|max:255",
            "dropoff_time" => [
                "required",
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/',
            ],
            "pickup_date" => "required|string|max:255",
            "pickup_time" => [
                "required",
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/',
            ],
            "email" => "required|string|email|max:255",
        ]);
        //Check if validation fails
        if ($validator->fails()) {
            // Return the error messages with a 422 Unprocessable Entity status
            file_put_contents(
                "app_requests_errors.txt",
                "Date: " .
                    date("Y-m-d H:i:s") .
                    '\r\n' .
                    'Errors: \r\n' .
                    print_r($validator->errors(), true) .
                    "\r\n",
                FILE_APPEND
            );
            return response()->json(
                [
                    "errors" => $validator->errors(),
                ],
                422
            );
        } else {
            $products = $this->ajaxSearchResults($request);
            return response()->json(
                [
                    "Products" => $products,
                ],
                200
            );
        }
    }
    public function ajaxSearchResults($request)
    {
        $promo_error_message = "";
        $airport_id = $request->input("airport_id");
        $dropdate = $request->input("dropoff_date");
        $dropoftime = $request->input("dropoff_time");
        $pickdate = $request->input("pickup_date");
        $pickuptime = $request->input("pickup_time");
        $no_of_days = $request->input("no_of_days");
        $dropdate = str_replace("/", "-", $dropdate);
        $pickdate = str_replace("/", "-", $pickdate);
        $email = $request->input("email");
        $src = "APP";
        $dropDateTime = date(
            "Y-m-d H:i",
            strtotime($dropdate . " " . $dropoftime)
        );
        $pickDateTime = date(
            "Y-m-d H:i",
            strtotime($pickdate . " " . $pickuptime)
        );
        //print_r($email);
        $bookingfor = "airport_parking";
        $promo = $request->input("promo");
        $filter1 = $request->input("filter1");
        $filter2 =
            $request->input("filter2") != ""
                ? $request->input("filter2")
                : "low-to-high";
        $filter3 = $request->input("filter3");
        $search_filter = "";
        $search_filter3 = "";
        $search_filter2 =
            'ORDER BY (recommended = "Yes") DESC, recommended DESC';
        if ($filter1 != "" && $filter1 != "All") {
            $search_filter .= "and parking_type = '" . $filter1 . "'";
        }
        if ($filter2 == "Recommended" && $filter1 != "All") {
            $search_filter .= "and recommended = 'Yes' ";
        }
        if ($filter2 == "Featured" && $filter1 != "All") {
            $search_filter .= "and featured = 'Yes' ";
        }
        if ($filter3 != "") {
            $search_filter3 .= "and terminal = '" . $filter3 . "'";
        }
        if ($promo != "") {
            $discount = new discounts();
            $promo_verify = $discount->varifyPromoCode($promo);
            if ($promo_verify != "Verify") {
                $validatedData->getMessageBag()->add("promo", $promo_verify);
            }
        }
        $selected_date = strtotime($dropdate);
        $year = date("Y", $selected_date);
        $month = date("n", $selected_date);
        $day = date("j", $selected_date);
        $dropofdate = date("Y-m-d", strtotime($dropdate));
        $pickupdate = date("Y-m-d", strtotime($pickdate));
        $dStart = new DateTime($dropofdate);
        $dEnd = new DateTime($pickupdate);
        $dDiff = $dStart->diff($dEnd);
        $dDiff->format("%R");
        $no_of_days = $dDiff->days;
        $total_days = $no_of_days + 1;
        if ($no_of_days > 30) {
            $total_days = "30";
        } else {
            $total_days = $no_of_days + 1;
        }
        if ($total_days <= 0) {
            $total_days = 1;
        }
        $data = [];
        $data["email"] = $email;
        $data["aid"] = $airport_id;
        $data["traffic_src"] = $src;
        $data["dropoff_date"] = $dropDateTime;
        $data["pickup_date"] = $pickDateTime;
        $data["discount_code"] = $promo;
        $query =
            "SELECT  distinct fapp.id, fc.company_code as product_code, fc.admin_id, fc.opening_time, fc.closing_time, fc.id as companyID, fc.aph_id, fc.name, fc.processtime, fc.awards, fc.featured, fc.recommended, fc.special_features, fc.overview, IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc, IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival, fc.terms, fc.address, fc.town, fc.post_code, fc.message, fc.extra_charges, fc.parking_type, fc.logo, fc.travel_time, fc.miles_from_airport, fc.cancelable, fc.editable,fc.is_flex, fc.bookingspace, fasb.brand_name, fapb.after_30_days, fapp.id as pl_id, IF( fapb.day_" .
            $total_days .
            " >0, fapb.day_" .
            $total_days .
            "+fapp.extra, 0.00) AS price FROM companies as fc
             left join companies_set_price_plans as fapp on fc.id = fapp.cid
             left join companies_set_assign_price_plans  as fasb on fapp.id = fasb.plan_id and fasb.day_no = 'day_" .
            $day .
            "'
             left join companies_product_prices as fapb on fapb.cid = fc.id and fapb.brand_name = fasb.brand_name
             WHERE is_active = 'Yes' and removed != 'Yes'  and airport_id = '" .
            $airport_id .
            "' and aph_id is null and fapp.cmp_month = '" .
            $month .
            "'  and fapp.cmp_year = '" .
            $year .
            "'  $search_filter $search_filter3 $search_filter2
             ";
        $companies = DB::select($query);
        $offDaysAdmin = [];
        $offAdmins = [];
        $offDaysComp = [];
        $offComp = [];
        $offDayEntriesAdmin = OffDays::where("off_type", "Admin")->get();
        foreach ($offDayEntriesAdmin as $offDayEntryAdmin) {
            $admin_id = $offDayEntryAdmin->admin_id;
            $offDays = explode(",", $offDayEntryAdmin->off_days);
            if (!isset($offDaysAdmin[$admin_id])) {
                $offDaysAdmin[$admin_id] = [];
            }
            $offDaysAdmin[$admin_id] = array_merge(
                $offDaysAdmin[$admin_id],
                $offDays
            );
            $offAdmins[] = $admin_id;
        }
        $offDayEntriesComp = OffDays::where("off_type", "Company")->get();
        foreach ($offDayEntriesComp as $offDayEntryComp) {
            $company_id = $offDayEntryComp->company_id;
            $offDays = explode(",", $offDayEntryComp->off_days);
            if (!isset($offDaysComp[$company_id])) {
                $offDaysComp[$company_id] = [];
            }
            $offDaysComp[$company_id] = array_merge(
                $offDaysComp[$company_id],
                $offDays
            );
            $offComp[] = $company_id;
        }
        if ($companies !== false) {
            $array = [];
            foreach ($companies as $company) {
                $company = (array) $company;
                if (
                    isset($offDaysAdmin[$company["admin_id"]]) &&
                    (in_array(
                        $dropofdate,
                        $offDaysAdmin[$company["admin_id"]]
                    ) ||
                        in_array(
                            $pickupdate,
                            $offDaysAdmin[$company["admin_id"]]
                        ))
                ) {
                    continue;
                }
                if (
                    isset($offDaysComp[$company["companyID"]]) &&
                    (in_array(
                        $dropofdate,
                        $offDaysComp[$company["companyID"]]
                    ) ||
                        in_array(
                            $pickupdate,
                            $offDaysComp[$company["companyID"]]
                        ))
                ) {
                    continue;
                }
                if ($no_of_days > 30) {
                    $after30Days = $company["after_30_days"];
                    $booking_price = number_format(
                        $company["price"],
                        2,
                        ".",
                        ""
                    );
                    $booking_price =
                        $booking_price + $after30Days * ($no_of_days + 1 - 30);
                    $company["price"] = number_format(
                        $booking_price,
                        2,
                        ".",
                        ""
                    );
                } else {
                    $company["price"] = number_format(
                        $company["price"],
                        2,
                        ".",
                        ""
                    );
                }
                $company["park_api"] = "DB";
                $array[] = $this->array_flatten($company);
            }
        }
        $dbcompany = json_decode(json_encode((array) $array), false);
        $companies = ["original" => $companies];
        $dbcompany = ["merged" => $dbcompany];
        $result = array_merge($companies, $dbcompany);
        $companies = $result["merged"];
        $airports = airport::all()->where("status", "Yes");
        $companies_special_features = companies_special_features::all();
        $airport_detail = airport::where("id", $airport_id)->first();
        $apiairports = airport::all()
            ->where("id", $airport_id)
            ->toArray();
        foreach ($apiairports as $apiairport) {
            $airport_name = $apiairport["name"];
            $airport_code = $apiairport["iata_code"];
            $airport_post_code = $apiairport["post_code"];
            $airport_address = $apiairport["address"];
            $airport_town = $apiairport["city"];
        }
        $ArrivalDate = date("dMy", strtotime($dropdate));
        $DepartDate = date("dMy", strtotime($pickdate));
        $ArrivalTime = date("Hi", strtotime($dropoftime));
        $DepartTime = date("Hi", strtotime($pickuptime));
        $aph_functions = new aph_functions();
        $api = new api();
        if ($airport_id != 20) {
            if ($this->_settings["holiday_api"] != "Inactive") {
                $holidaycompanies = @$aph_functions->HolidayExtraBooking(
                    $airport_code,
                    $dropdate,
                    $dropoftime,
                    $pickdate,
                    $pickuptime
                );
               // dd($holidaycompanies);
                $holiday_record = @$api->holiday_record(
                    $holidaycompanies,
                    $airport_id,
                    $search_filter
                );
                if (!empty($holiday_record)) {
                    $holiday_record = json_decode(json_encode($holiday_record));
                    $companies = array_merge(
                        (array) $companies,
                        (array) $holiday_record
                    );
                }
            }
            if ($this->_settings["a2z_api"] != "Inactive") {
                $a2zCompanies = @$aph_functions->a2zListings(
                    $airport_code,
                    $dropdate,
                    $dropoftime,
                    $pickdate,
                    $pickuptime
                );
                $a2zRecord = @$api->a2z_record(
                    $a2zCompanies,
                    $airport_id,
                    $search_filter
                );
                if (!empty($a2zRecord)) {
                    $a2zRecord = json_decode(json_encode($a2zRecord));
                    $companies = array_merge(
                        (array) $companies,
                        (array) $a2zRecord
                    );
                }
            }
        }
        usort($companies, function ($a, $b) {
            //Prioritize by recommended
            if ($a->recommended === "Yes" && $b->recommended !== "Yes") {
                return -1;
            } elseif ($a->recommended !== "Yes" && $b->recommended === "Yes") {
                return 1;
            }
            //Prioritize by featured
            if ($a->featured === "Yes" && $b->featured !== "Yes") {
                return -1;
            } elseif ($a->featured !== "Yes" && $b->featured === "Yes") {
                return 1;
            }
            //Prioritize by park_api
            if ($a->park_api === "DB" && $b->park_api !== "DB") {
                return -1;
            } elseif ($a->park_api !== "DB" && $b->park_api === "DB") {
                return 1;
            }
            //Sort by price in ascending order
            return $a->price <=> $b->price;
        });
        return response([
            "companies" => $companies,
            "companies_special_features" => $companies_special_features,
            "no_of_days" => $no_of_days,
        ]);
    }
    public function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $arrayList = array_flatten($value);
                foreach ($arrayList as $listItem) {
                    $result[$key] = $listItem;
                }
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    public function notification(
        $request,
        $bookingref,
        $airportId,
        $company_id,
        $booked_type,
        $booking_id
    ) {
        ob_start();
        $airport_detail = airport::where("id", $airportId)->first();
        $company_data = DB::table("companies")
            ->where("id", $company_id)
            ->first();
        $overview = $company_data->overview . "<br>";
        $companyemail = $company_data->company_email;
        $directions =
            "<strong>Arrival:</strong><br>" .
            $company_data->arival .
            "<br>" .
            "<strong>Return:</strong><br>" .
            $company_data->return_proc .
            "<br>";
            
            
        $template_data = [];
        $template_data["guidence"] = $overview . " " . $directions;
        $template_data["username"] =
            $request->first_name . " " . $request->last_name;
        $template_data["email"] = $request->email;
        $template_data["telephone"] = $request->phone_number;
        
        $template_data["carpark"] = "Car Park";
        $template_data["c_parent"] = $company_data->name;
        $template_data["ptype"] = $booked_type;
        $template_data["airport"] = $airport_detail->name;
        if ($request->dep_terminal != "TBA" && $request->dep_terminal != "") {
            $terminal = airports_terminals::where(
                "id",
                $request->dep_terminal
            )->first();
     
            $template_data["terminal"] = $terminal->name;
        } else {
            $template_data["terminal"] = "TBA";
        }
        if ($request->ret_terminal != "TBA" && $request->ret_terminal != "") {
            $terminal = airports_terminals::where(
                "id",
                $request->ret_terminal
            )->first();
            $template_data["rterminal"] = $terminal->name;
        } else {
            $template_data["rterminal"] = "TBA";
        }
        $template_data["days"] = $request->no_of_days;
        $template_data["end_date"] = date(
            "Y-m-d H:i:s",
            strtotime($request->pickup_date . " " . $request->pickup_time)
        );
        $template_data["start_date"] = date(
            "Y-m-d H:i:s",
            strtotime($request->dropoff_date . " " . $request->dropoff_time)
        );
        $template_data["booktime"] = date("Y-m-d H:i:s");
        $template_data["r_flight_no"] = $request->ret_flight;
        $template_data["make"] = $request->make;
        $template_data["model"] = $request->model;
        $template_data["color"] = $request->color;
        $template_data["reg"] = $request->reg_number;
        $template_data["payment_gatway"] = "stripe";
        $template_data["payment_status"] = "success";
        $template_data["price"] = $request->total_amt;
        $template_data["c_price"] = $request->booking_amt;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] = $bookingref ?? "NA";
        $template_data["ext_ref"] = $ext_ref ?? "NA";
        $template_data["company"] = $company_data->name;
        $template_data["c_code"] = $request->product_code;
       
        $email_send = new EmailController();
        $toemails = [
            $request->email,
            "fullstack@seedanalytica.com",
        ];
        foreach ($toemails as $email) {
            $emailcheck = $email_send->sendGmail(
                "Add Booking",
                $email,
                $template_data
            );
            if ($emailcheck == "0") {
                $data["email_check"] = "0";
            } else {
                $data["email_check"] = "1";
            }
            $update = DB::table("airports_bookings")
                ->where("referenceNo", $bookingref)
                ->update($data);
        }
        if ($company_data->id == 207 || $company_data->id == 208) {
            $filePath = $this->create_csv_air($booking_id, "Next");
            $toemails = explode(",", $company_data->company_email);
            foreach ($toemails as $email) {
                $cmpCheck = $email_send->sendGmailWithAttachment(
                    "Add Booking Company",
                    'fullstack@seedanalytica.com',
                    $template_data,
                    $filePath
                );
                if ($cmpCheck == "0") {
                    $data["comp_email_check"] = "0";
                } else {
                    $data["comp_email_check"] = "1";
                }
                $update = DB::table("airports_bookings")
                    ->where("referenceNo", $bookingref)
                    ->update($data);
            }
        } else {
            $filePath = $this->create_csv($booking_id, "Next");
            $cmpCheck = $email_send->sendGmailWithAttachment(
                "Add Booking Company",
                'fullstack@seedanalytica.com',
                $template_data,
                $filePath
            );
            if ($cmpCheck == "0") {
                $data["comp_email_check"] = "0";
            } else {
                $data["comp_email_check"] = "1";
            }
            $update = DB::table("airports_bookings")
                ->where("referenceNo", $bookingref)
                ->update($data);
        }
        // $smsfee = $request->sms_fee;
        // if ($smsfee > 0) {
        //     $functions = new functions();
        //     $functions->send_sms($request->phone_number, $bookingref);
        // }
        ob_end_clean();
    }
    public function create_csv_air($token, $status)
    {
        $query =
            "select
 		            ap.name AS Airport,
 					c.parking_type AS ProductType,
 					c.name AS ProductName,
 					c.id AS ProductID,
 					b.referenceNo AS ReferenceNumber,
 					b.booking_status AS BookingStatus,
 					CONCAT(b.first_name, ' ', b.last_name) AS CustomerName,
 					DATE_FORMAT(b.departDate, '%Y-%m-%d %H:%i:%s') AS DepartureDate,
 					DATE_FORMAT(b.returnDate, '%Y-%m-%d %H:%i:%s') AS ArrivalDate,
 					IF(b.deprTerminal > 0, (select airports_terminals.name from airports_terminals where airports_terminals.id= b.deprTerminal), 'TBA') As DepartureTerminal,
 					IF(b.returnTerminal > 0, (select airports_terminals.name from airports_terminals where airports_terminals.id= b.returnTerminal), 'TBA') As ArrivalTerminal,
 					b.deptFlight AS DepartureFlightNo,
 					b.returnFlight AS ReturnFlightNo,
 					b.registration AS Regno,
 					b.make AS Make,
 					b.model AS Model,
 					b.color AS CarColor,
 					b.passenger AS Passengers,
 					b.phone_number AS Mobile,
 					b.booking_amount AS ListPrice,
 					(b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As AmountPrice,
 					(c.share_percentage/100*(b.booking_amount)) As SupplierCost
         			from airports_bookings as b
         			join companies as c on c.id = b.companyId
         			join airports as ap on ap.id = b.airportID
         			left join airports_terminals as tr on tr.id = b.deprTerminal
                     WHERE b.id =" . $token;
        $results = DB::select($query);
        if ($results > 0) {
            $datenow = date("dmYhms");
            $name = "MAGR_$datenow.csv";
            $csvpath = public_path("csv/");
            $filepath = $csvpath . $name;
            $outstream = fopen($filepath, "w");
            if ($status != "") {
                $results[0]->BookingStatus = "BookingStatus";
            }
            fputcsv($outstream, array_keys((array) $results[0]));
            foreach ($results as $result) {
                if ($status != "") {
                    $result->BookingStatus = $status;
                }
                fputcsv($outstream, (array) $result);
            }
            fclose($outstream);
            rewind($outstream);
            fclose($outstream);
        }
        return $filepath;
    }
    public function create_csv($token, $status)
    {
        $query =
            "select
 					c.company_code AS ProductCode,
 					b.referenceNo AS Refno,
 					ap.name AS Airport,
 					DATE_FORMAT(b.created_at, '%Y-%m-%d') AS BookingDate,
 					TIME_FORMAT(b.created_at, '%H:%i:%s') AS BookingTime,
 					DATE_FORMAT(b.departDate, '%Y-%m-%d') AS DepartureDate,
 					TIME_FORMAT(b.departDate, '%H:%i:%s') AS DepartureTime,
 					DATE_FORMAT(b.returnDate, '%Y-%m-%d') AS ReturnDate,
 					TIME_FORMAT(b.returnDate, '%H:%i:%s') AS ReturnTime,
 					IF(b.deprTerminal > 0, (select airports_terminals.name from airports_terminals where airports_terminals.id= b.deprTerminal), 'TBA') As DepartureTerminal,
 					IF(b.returnTerminal > 0, (select airports_terminals.name from airports_terminals where airports_terminals.id= b.returnTerminal), 'TBA') As ReturnTerminal,
 					b.no_of_days AS TotalDays,
 					b.title AS Title,
 					b.first_name AS FirstName,
 					b.last_name AS LastName,
 					b.phone_number AS Telephone,
 					b.booking_amount AS BookingPrice,
 					(b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As CompanyShare,
 				    (c.share_percentage/100*(b.booking_amount)) As AgentShare,
 					b.deptFlight AS DeptFlight,
 					b.returnFlight AS ReturnFlight,
 					b.leavy_fee AS  AirportLevyFee,
 					b.make AS CarMake,
 					b.model AS CarModel,
 					b.color AS CarColor,
 					b.registration AS CarRegistration
         			from airports_bookings as b
         			join companies as c on c.id = b.companyId
         			join airports as ap on ap.id = b.airportID
         			left join airports_terminals as tr on tr.id = b.deprTerminal
                     WHERE b.id =" . $token;
        $results = DB::select($query);
        if ($results > 0) {
            $datenow = date("dmYhms");
            $name = "MAGR_$datenow.csv";
            $csvpath = public_path("csv/");
            $filepath = $csvpath . $name;
            $outstream = fopen($filepath, "w");
            if ($status != "") {
                $results[0]->BookingStatus = "BookingStatus";
            }
            fputcsv($outstream, array_keys((array) $results[0]));
            foreach ($results as $result) {
                if ($status != "") {
                    $result->BookingStatus = $status;
                }
                fputcsv($outstream, (array) $result);
            }
            //dd($outstream);
            fclose($outstream);
            //rewind($outstream);
            //fclose($outstream);
        }
        return $filepath;
    }
    public function appImage()
    {
        $images = AppImage::all(["image"]); // Fetch only the 'image' column
        //Add the full URL to each image path
        $images->transform(function ($image) {
            $image->image = url(
                "https://dashboard.jetseekergroup.com/storage/app/" . $image->image
            );
            return $image;
        });
        return response()->json([
            "success" => true,
            "data" => $images,
        ]);
    }
}
