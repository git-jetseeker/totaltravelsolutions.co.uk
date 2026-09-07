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
use App\Company;
use App\User;
use App\customers;
use App\modules_settings;
use App\penalty_details;
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
use functions;
use Carbon\Carbon;

class BookingController extends Controller
{
    public $_setting = [];

    public function __construct()
    {
        $this->middleware('auth');
        $modules_settings = modules_settings::all();
        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }
    }


    /******Dashboard***/

    function get_sales_count($_email_booking_source)
    {

        $count_email_booking_source = new airports_bookings();
        $count_email_booking_source = $count_email_booking_source->where("booking_status", "'Completed'");
        $count_email_booking_source = $count_email_booking_source->where("payment_status", "'success'");
        $count_email_booking_source = $count_email_booking_source->where("removed",  "'No'");
        $count_email_booking_source = $count_email_booking_source->where("status", "'Yes'");


        if ($_email_booking_source == "paid") {

            $count_email_booking_source =$count_email_booking_source->where(function ($count_email_booking_source) {

                $count_email_booking_source = $count_email_booking_source->where("traffic_src", "=", "'ppc'");
                $count_email_booking_source =  $count_email_booking_source->orWhere("traffic_src", "'BING'");
               // dd("paid");
            });

        }

        if ($_email_booking_source == "org") {
            $count_email_booking_source = $count_email_booking_source->where("traffic_src", "'ORG'");
        }

        if ($_email_booking_source == "em") {
            $count_email_booking_source = $count_email_booking_source->where("traffic_src", "'EM'");
        }

        if ($_email_booking_source == "ppc") {
            $count_email_booking_source = $count_email_booking_source->where("traffic_src", "'PPC'");
        }

        if ($_email_booking_source == "BING") {
            $count_email_booking_source = $count_email_booking_source->where("traffic_src", "'BING'");
        }




        $count_email_booking_source = $count_email_booking_source->where( DB::raw('DATE_FORMAT("createdate", "%Y-%m-%d")'), "CURDATE()");
        $count_email_booking_source = $count_email_booking_source->count();
        return $count_email_booking_source;

    }


    function get_count_bookings($filter_count_get)
    {
        $booking = new airports_bookings();
        $booking = $booking->where("status", "'Yes'");
        $booking = $booking->where("removed", "'No'");

        if($filter_count_get == "complete"){
            $booking = $booking->where("booking_status", "'Completed'");
            $booking = $booking->where("payment_status", "'success'");
            //$filter_count = "b.booking_status = 'Completed' and b.payment_status = 'Successful' and b.removed = 'No' and b.status = 'Yes'";
        }
        else if($filter_count_get == "incomplete"){
            $booking = $booking->where("booking_status", "'Abandon'");
            $booking = $booking->where("payment_status", "'Pending'");
            //$filter_count = "b.booking_status = 'Abandon' and b.payment_status = 'Pending' and b.removed = 'No' ";
        }

        $booking = $booking->where( DB::raw('DATE_FORMAT("b.createdate", "%Y-%m-%d")'), "CURDATE()");
        $booking = $booking->count();
        return $booking;
    }


    function sales_amount($_filter){

        $booking = new airports_bookings();
        $booking = $booking->select(DB::raw("SUM(airports_bookings.total_amount) as stotal"),DB::raw("(SUM((c.share_percentage/100)*airports_bookings.booking_amount)- SUM(airports_bookings.discount_amount)) AS total_share "));
        $booking = $booking  ->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $booking = $booking->where("airports_bookings.booking_status", "'Completed'");
        $booking = $booking->where("airports_bookings.payment_status", "'success'");
        $booking = $booking->where("airports_bookings.removed", "'No'");
        $booking = $booking->where("airports_bookings.status", "'Yes'");


        if($_filter == "today"){
            $booking = $booking->where( DB::raw('DATE_FORMAT("airports_bookings.createdate", "%Y-%m-%d")'), "CURDATE()");

            //$_filter_count = "and DATE_FORMAT(b.createdate, '%Y-%m-%d') = CURDATE()";
        }
        else if($_filter == "monthly"){
            $booking = $booking->where( DB::raw('airports_bookings.createdate'),">=", "LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 1 MONTH");
            $booking = $booking->where( DB::raw('airports_bookings.createdate'),"<", "LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY");

            //$_filter_count = "and b.createdate >= LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 1 MONTH
		//AND b.createdate < LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY";
        }
        $_booking_source = "";
//        if($_SESSION['admin_admintype']=='Marketing'){
//            $_booking_source = " AND (b.traffic_src = 'ppc' || b.traffic_src = 'BING')";
//
//        }

//        $_filter_count_sales = $db->get_row("SELECT SUM(total_amount) AS stotal ,(SUM((c.share_percentage/100)*b.booking_amount)- SUM(b.discount_amount)) AS total_share FROM " . $db->prefix . "booking as b
//			left join " . $db->prefix . "companies as c on (b.companyId = c.id OR b.companyId = c.aph_id)
//			where b.booking_status = 'Completed' and b.payment_status = 'Successful' and b.removed = 'No' and b.status = 'Yes' $_booking_source $_filter_count
//			ORDER BY b.id DESC");

        // $_filter_count_sales = $db->get_row("SELECT SUM(total_amount) AS stotal FROM " . $db->prefix . "booking as b
        // where b.booking_status = 'Completed' and b.payment_status = 'Successful' and b.removed = 'No' and b.status = 'Yes' $_filter_count
        // ORDER BY b.id DESC");
        $booking = $booking->first();

        $result = array("share"=>$booking->total_share,"total"=>$booking->stotal);

        return $result;
        //return $_filter_count_sales['stotal'];
    }



    /******* Dashboard end ************/
    function admin_booking_report_pdf(Request $request)
    {
        // dd($request);
         $adminName = '';
        $user_permissions = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        
        if($user_permissions){
            $user_permissions =explode(",",$user_permissions["permissions"]);   
        }

        $data = $request->all();
        $total_planty_to_company = 0;
        $total_planty_to_fly = 0;
        $total = 0;
        $lprice = 0;
        $aprice = 0;
        $commission_price = 0;
        $allrefund = 0;
        $cancelled = 0;
        $discount = 0;
        $can_count = 0;
        $ref_count = 0;
        $abd_count = 0;
        $comp_count = 0 ;

        $filter_search = "";
        $planty_refund = " ";
        $b_t_table = " ";
        $_refund_via_s = "";
        $palenty_to = "";
        $search = "";
        $join = "";
        $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";
        $b_t_table = "bt.*, ";
        if ($data != null && count($data) > 0) {

            if (!empty($data['search'])) {
                $search = trim(preg_replace('/\s+/', ' ', $data['search']));
                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";
            }

            if (!empty($data['airport']) && $data['airport'] == "all" || $data['airport'] == null) {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all"||  $data['companies'] == null) {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            }

            if (!empty($data['admins']) && $data['admins'] == "all"|| $data['admins'] == null) {
                $_admins = "";
               
            } else {
                  $aid =  $data['admins'];
                  $ads = DB::select("Select name from users where id = '$aid'");
           
                  $adminName = $ads[0]->name;
                 
                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";
            }

            if (!empty($data['payment']) && $data['payment'] == "all"|| $data['payment'] == null) {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }

            if (!empty($data['status']) && $data['status'] == "all"|| $data['status'] == null) {
                $_status = "";
            } else {
                if ($data['status'] == 'Booked') {
                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";
                } elseif ($data['status'] == 'Refund') {

                    if ($data['refund_via'] == 'all') {
                        $_refund_via_s = "";
                    } else {
                        $_refund_via_s = " AND bt.payment_medium = '" . $data['refund_via'] . "'";
                    }
                    if ($data['palenty_to'] == 'all') {
                        $palenty_to = "";
                    } else {
                        $palenty_to = " AND bt.palenty_to = '" . $data['palenty_to'] . "'";
                    }


                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                } else {
                    if ($data["status"] != "") {
                        if( $data["status"] == 'Completed')
                        {
                            $data['status'] = 'Booked';
                        }
                        $_status = " AND b.booking_action = '" . $data['status'] . "'";
                    } else {
                        $_status = "";
                    }
                }
            }
            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }
            if ($data['filter'] != "" && $data['filter'] != "all") {
                if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }

                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }  else if ($data['filter'] == null) {
                  if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }
               $data['filter']="created_at";
                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;


                  }else {
                $_filter = "";
            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
        } else {
            $_current = " and b.created_at  >= CURDATE()";
            $filter_search = $_current;
        }


        $companies = "SELECT $b_t_table b.id as booking_id,b.model,b.make,b.color,b.registration ,b.referenceNo,b.booking_amount, b.booking_status, b.payment_method,b.email, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.first_name as firstname , b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
	 b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
	(c.share_percentage/100*(b.booking_amount)) As fly_commission 		
	FROM airports_bookings as b 
	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
	join airports as a on a.id = b.airportID
	$planty_refund
	WHERE  b.removed = 'No' and b.status = 'Yes' $join $filter_search GROUP BY b.referenceNo ORDER BY b.id DESC";

  
        $companies = DB::select(DB::raw($companies));
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();


        //$html = "<table><thead><tr><th>Sr#</th></tr></thead>";
        $html = "<table border=\"1\" cellspacing='0' ><thead><tr><th><b>Sr# </b></th><th><b> Reference No </b></th><th><b> Carpark Name </b></th><th><b> Name </b></th><th><b> Status </b></th>
        <th><b> Payment Method </b></th><th><b> Booked Date </b></th><th><b> Departures Date </b></th><th><b> Arrival Date </b></th>";
        if (in_array("Amounts", $user_permissions)){
            $html .= "<th> <b> Booking Amount </b></th>";
        }
        $html .= "<th><b> Booking Agent Share </b></th><th> <b> Service Provider share </b></th></tr></thead><tbody>";
        // echo "Sr# \t Reference No \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departure Date \t Arrival Date \t Total Days  \t Cancel Amount \t Total Amount \t Vehicle make \t Model \t Color \t Registration ";

        // $html .="\n";
        $i = 1;
        $comp_share = 0 ;
        $pz_share = 0;
        foreach ($companies as $company) {

            $cancel = 0;
            $adjust = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);

            $transaction = (array)$transactions;
            foreach ($transactions as $transaction) {
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                    $cancel += $transaction['payable'];
                }
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {
                    $adjust += $transaction['payable'];
                }
            }

            // $refund = 0;
            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){
            // $refund  = $company['booking_amount'];
            // }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){
            // $refund  = $company['booking_amount'];
            // }

            $booking_amount = $company['booking_amount'];
            $share_percentage = $company['share_percentage'];

            $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));
            $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));
            $company_commission_after_refund = $company_commission_before_refund;

            if ($adjust != $company['booking_amount'] && $adjust > 0) {
                //$booking_amount = $booking_amount - $adjust;
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }
            if ($adjust == $company['booking_amount']) {
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }

            $company_commission = $company_commission_after_refund;
            $fly_commission = $fly_commission_before_refund;

            // $explode  = explode("FP",$company['company_code']);
            // $cname = $explode[1];
            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");
            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];
            $cname = $company['company_name'];

            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));
            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));
            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));
            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));

            $booking_amount = $company['booking_amount'];
            $totals = $company['total_amount'];

            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){
            $cancel_remaining_amount = 0;
            if ($cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
                $cancel_remaining_amount = $booking_amount - $cancel;
            }

            $output = "<tr><td>" . $i . "</td>";
            $output .= "<td>" . $company['referenceNo'] . "</td>";
            $output .= "<td>" . $cname . "</td>";
            // $output .= $company['airport_name'] . "\t";
            $output .= "<td>" . $company['firstname']." ".$company['Surname'] . "</td>";
            $output .= "<td>" . $company['booking_status'] . "</td>";
            $output .= "<td>" . $company['payment_method'] . "</td>";
            $output .= "<td>" . date("d/m/Y", strtotime($company['createdate'])) . "</td>";
            $output .= "<td>" . date("d/m/Y", strtotime($company['start_date'])) . "</td>";
            $output .= "<td>" . date("d/m/Y", strtotime($company['end_date'])) . "</td>";
            // $output .= "<td>" . $company['no_of_days'] . "</td>";
            //$output .="<td>". $cancel . "</td>";
            // $output .= $adjust . "\t";
            if (in_array("Amounts", $user_permissions)){
            $output .= "<td>" . $company['booking_amount'] . "</td>";
            }
             $c_share =  (100 - $company['share_percentage'])/100 * $company['booking_amount'];
             $p_share =  ($company['share_percentage']/100) * $company['booking_amount'];
             $comp_share = $comp_share + $c_share;
             $pz_share = $pz_share + $p_share;
             
             
             $output .= "<td>" . $c_share . "</td>";
             $output .= "<td>" . $p_share . "</td></tr>";
            // $output .= "<td>" . $company['color'] . "</td>";
            // $output .= "<td>" . $company['registration'] . "</td></tr>";
            //\t Vehicle make \t Model \t Color \t Registration

            //$output .= (float)number_format($company_commission, 3) . "\t";
            // $output .= (float)number_format($fly_commission, 3) . "\t";
            // $output .= $cancel_remaining_amount . "\t";
            // $output .= $company['payment_medium'] . "\t";
            // $output .= $company['palenty_to'] . "\t";
            // $output .= $company['palenty_amount'] . "\t";
            //$output .= ($company['palenty_amount'] + $adjust) . "\t";


            $i++;
            if ($company['palenty_to'] == "Company") {
                $total_planty_to_company += $company['palenty_amount'];
            }
            if ($company['palenty_to'] == "JETSEEKER") {
                $total_planty_to_fly += $company['palenty_amount'];
            }

            //$total += $company['total_amount'];
            $total += $totals;
            //$total += $company['total_amount'];
            $lprice += $company['booking_amount'];
            $aprice += $company_commission;
            $commission_price += $fly_commission;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            if ($company["booking_status"] == 'Cancelled') {
                $can_count++;
            }
            if ($company["booking_status"] == 'Refund') {
                $ref_count++;
            }
             if ($company["booking_status"] == 'Abandon') {
                $abd_count++;
            }
             if ($company["booking_status"] == 'Completed') {
                $comp_count++;
            }

            $allrefund += $adjust;
            $cancelled += $cancel;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            $html .= $output;
        }

        $totalamount = ($total * 1) + ($discount * 1);
        if ($allrefund > 0 || $cancelled > 0) {
            $totalamount = $totalamount - $allrefund - $cancelled;
            $total = $total - $allrefund - $cancelled;
            //$lprice = $lprice - $allrefund ;

        }

        $return_book = $lprice - $allrefund - $cancelled;
        $vat = 20 / 100 * $aprice;
        $cprice = $aprice - $vat;
        $adjusts = isset($_GET['adjust']) ? $_GET['adjust'] : 0;
        $net_amount = $aprice + $adjusts;

        $totalcommission = ($commission_price * 1) - ($discount * 1);
        $html .= "</tbody></table><br/><br/><br/><br/><table border='1'><tbody>";
        if (in_array("Amounts", $user_permissions)){
        $html .= "  <tr><td><b> Total Booking amount  </b></td><td>" . $lprice . " </td> </tr>";
        $html .= "  <tr><td><b> Service Provider Share    </b></td><td>" . $comp_share . " </td> </tr>";
        $html .= "  <tr><td><b> Booking Agent Share   </b></td><td>" . $pz_share . " </td> </tr>";
       
        // $html .= " <tr><td> <b>Net Amount Paid by customer </b></td><td>" . $return_book . " </td></tr> ";


        // $html .= " <tr><td> <b>Total Booking Cancel Amount</b> </td><td>" . $cancelled . " </td> </tr>";
        // $html .= " <tr><td> <b>Total Booking Refund Amount </b></td><td>" . $allrefund . " </td> </tr>";
        // $html .= "<tr> <td> <b>Total Company planty Amount</b> </td><td>" . $total_planty_to_company . "</td>  </tr>";
        }
        // $html .= " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        $html .= "<tr><td> <b>Total Bookings</b> </td><td>  " . count($companies) . "</td>  </tr>";
        $html .= "<tr><td> <b>Total No of Booked</b> </td><td>  " . $comp_count . "</td> </tr> ";
        $html .= "<tr><td> <b>Total No of Refund</b> </td><td>  " . $ref_count . "</td> </tr>";
        $html .= "<tr><td> <b>Total No of Cancel</b> </td><td>  " . $can_count . "</td>  </tr>";
        $html .= "<tr><td> <b>Total No of abandoned </b> </td><td>  " . $abd_count . "</td> </tr>";
       
      

        $html .= "</tbody></table>";
        //$html .= "\t";

//echo $html; die();


//        $view = View::make('myview_name');
//        $html = $view->render();

        $pdf = new TCPDF();


        $pdf::setFooterData(array(0, 64, 0), array(0, 64, 128));

// set default monospaced font
        $pdf::SetDefaultMonospacedFont("courier");

// set margins
        $pdf::SetMargins(15, 27, 15);
        $pdf::SetHeaderMargin(5);
        $pdf::SetFooterMargin(10);

// set auto page breaks
        $pdf::SetAutoPageBreak(TRUE, 25);


        $pdf::SetFont('times', '', 19, '', true);
// ---------------------------------------------------------

// set default font subsetting mode
        $pdf::setFontSubsetting(true);
        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);


        $pdf::SetFont('times', '', 19, '', true);

        $pdf::AddPage("L");
        $pdf::SetXY(15, 6);
        if($adminName != ''){
        $pdf::Write(12, "$adminName Report", '', 0, 'L', true, 0, false, false, 0);
        }
        else
        {
         $pdf::Write(12, "Airport Booking Report", '', 0, 'L', true, 0, false, false, 0);
        }

        $pdf::SetXY(140, 8);
        $pdf::Write(5, "Total Records:" . ($i-1), '', 0, 'L', true, 0, false, false, 0);
//echo $bookings;
        $pdf::SetFont('times', '', 9, '', true);
        $pdf::writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);


//        $pdf::SetFont('helvetica', '', 9);
//
//        $pdf::SetTitle('Hello World');
//        $pdf::AddPage();
//        $pdf::writeHTML($html, true, false, false, false, '');
        //header("Content-type:application/pdf");

// It will be called downloaded.pdf
        //header("Content-Disposition:attachment;filename='pz_airport_parking_report.pdf'");
        $pdf::Output($_SERVER['DOCUMENT_ROOT'].'/bookinglist.pdf','FD');

        // dd("testing pdf");

    }
    function admin_booking_report_pdf_agent(Request $request)
    {
         $adminName = '';
        $aid = Auth::user()->id;
        $ads = DB::select("Select name from users where id = '$aid'");
        $adminName = $ads[0]->name;
     
   
        $data = $request->all();
        $total_planty_to_company = 0;
        $total_planty_to_fly = 0;
        $total = 0;
        $lprice = 0;
        $aprice = 0;
        $commission_price = 0;
        $allrefund = 0;
        $cancelled = 0;
        $discount = 0;
        $can_count = 0;
        $ref_count = 0;

        $filter_search = "";
        $planty_refund = " ";
        $b_t_table = " ";
        $_refund_via_s = "";
        $palenty_to = "";
        $search = "";
        $join = "";
        $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";
        $b_t_table = "bt.*, ";
        if ($data != null && count($data) > 0) {

            if (!empty($data['search'])) {
                $search = trim(preg_replace('/\s+/', ' ', $data['search']));
                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";
            }

            if (!empty($data['airport']) && $data['airport'] == "all" || $data['airport'] == null) {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all"||  $data['companies'] == null) {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            }

            if (!empty($data['admins']) && $data['admins'] == "all"|| $data['admins'] == null) {
                $_admins = "";
            } else {
                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";
            }

            if (!empty($data['payment']) && $data['payment'] == "all"|| $data['payment'] == null) {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }

            if (!empty($data['status']) && $data['status'] == "all"|| $data['status'] == null) {
                $_status = "and b.booking_status != 'Abandon'";
            } else {
                if ($data['status'] == 'Booked') {
                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";
                } elseif ($data['status'] == 'Refund') {

                    if ($data['refund_via'] == 'all') {
                        $_refund_via_s = "";
                    } else {
                        $_refund_via_s = " AND bt.payment_medium = '" . $data['refund_via'] . "'";
                    }
                    if ($data['palenty_to'] == 'all') {
                        $palenty_to = "";
                    } else {
                        $palenty_to = " AND bt.palenty_to = '" . $data['palenty_to'] . "'";
                    }


                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                } else {
                    if ($data["status"] != "") {
                          if ($data['status'] == 'Completed') {
                              $data['status'] = 'Booked';
                          }
                        
                        $_status = " AND b.booking_action = '" . $data['status'] . "'";
                    } else {
                        $_status = "and b.booking_status != 'Abandon'";
                    }
                }
            }
            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }
            if ($data['filter'] != "" && $data['filter'] != "all") {
                if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }

                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }  else if ($data['filter'] == null) {
                  if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }
               $data['filter']="created_at";
                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;


                  }else {
                $_filter = "";
            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
        } else {
            $_current = " and b.created_at  >= CURDATE()";
            $filter_search = $_current;
        }


        $companies = "SELECT $b_t_table b.id as booking_id,b.model,b.make,b.color,b.registration ,b.referenceNo,b.booking_amount, b.booking_status, b.payment_method,b.email, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.first_name as firstname , b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
	 b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
	(c.share_percentage/100*(b.booking_amount)) As fly_commission 		
	FROM airports_bookings as b 
	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
	join airports as a on a.id = b.airportID
	$planty_refund
	WHERE c.admin_id = ".Auth::user()->id." and b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.removed = 'No' and b.status = 'Yes' $join $filter_search GROUP BY b.referenceNo ORDER BY b.id DESC";


        $companies = DB::select(DB::raw($companies));
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();


        //$html = "<table><thead><tr><th>Sr#</th></tr></thead>";
        $html = "<table border=\"1\" cellspacing='0' ><thead><tr><th><b>Sr# </b></th><th><b> Reference No </b></th><th><b> Carpark Name </b></th><th><b> Name </b></th><th><b> Status </b></th><th><b> Booked Date </b></th><th><b> Departures Date </b></th><th><b> Arrival Date </b></th><th> <b> Total Amount </b></th><th><b> Booking Agent Share  </b></th><th> <b>Service Provider Share  </b></th></tr></thead><tbody> ";
        // echo "Sr# \t Reference No \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departure Date \t Arrival Date \t Total Days  \t Cancel Amount \t Total Amount \t Vehicle make \t Model \t Color \t Registration ";

        // $html .="\n";
        $i = 1;
         $comp_share = 0 ;
        $pz_share = 0;
        foreach ($companies as $company) {

            $cancel = 0;
            $adjust = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);

            $transaction = (array)$transactions;
            foreach ($transactions as $transaction) {
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                    $cancel += $transaction['payable'];
                }
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {
                    $adjust += $transaction['payable'];
                }
            }

            // $refund = 0;
            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){
            // $refund  = $company['booking_amount'];
            // }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){
            // $refund  = $company['booking_amount'];
            // }

            $booking_amount = $company['booking_amount'];
            $share_percentage = $company['share_percentage'];

            $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));
            $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));
            $company_commission_after_refund = $company_commission_before_refund;

            if ($adjust != $company['booking_amount'] && $adjust > 0) {
                //$booking_amount = $booking_amount - $adjust;
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }
            if ($adjust == $company['booking_amount']) {
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }

            $company_commission = $company_commission_after_refund;
            $fly_commission = $fly_commission_before_refund;

            // $explode  = explode("FP",$company['company_code']);
            // $cname = $explode[1];
            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");
            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];
            $cname = $company['company_name'];

            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));
            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));
            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));
            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));

            $booking_amount = $company['booking_amount'];
            $totals = $company['total_amount'];

            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){
            $cancel_remaining_amount = 0;
            if ($cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
                $cancel_remaining_amount = $booking_amount - $cancel;
            }

            $output = "<tr><td>" . $i . "</td>";
            $output .= "<td>" . $company['referenceNo'] . "</td>";
            $output .= "<td>" . $cname . "</td>";
            // $output .= $company['airport_name'] . "\t";
            $output .= "<td>" . $company['firstname']." ".$company['Surname'] . "</td>";
            $output .= "<td>" . $company['booking_status'] . "</td>";
            $output .= "<td>" . date("d/m/Y", strtotime($company['createdate'])) . "</td>";
            $output .= "<td>" . date("d/m/Y", strtotime($company['start_date'])) . "</td>";
            $output .= "<td>" . date("d/m/Y", strtotime($company['end_date'])) . "</td>";
            // $output .= "<td>" . $company['no_of_days'] . "</td>";
            //$output .="<td>". $cancel . "</td>";
            // $output .= $adjust . "\t";
            $output .= "<td>" . $company['booking_amount'] . "</td>";
             $c_share =  (100 - $company['share_percentage'])/100 * $company['booking_amount'];
             $p_share =  ($company['share_percentage']/100) * $company['booking_amount'];
             $comp_share = $comp_share + $c_share;
             $pz_share = $pz_share + $p_share;
             
             
             $output .= "<td>" . $c_share . "</td>";
             $output .= "<td>" . $p_share . "</td></tr>";
            // $output .= "<td>" . $company['make'] . "</td>";
            // $output .= "<td>" . $company['model'] . "</td>";
            // $output .= "<td>" . $company['color'] . "</td>";
            // $output .= "<td>" . $company['registration'] . "</td></tr>";
            

            $i++;
            if ($company['palenty_to'] == "Company") {
                $total_planty_to_company += $company['palenty_amount'];
            }
            if ($company['palenty_to'] == "JETSEEKER") {
                $total_planty_to_fly += $company['palenty_amount'];
            }

            //$total += $company['total_amount'];
            $total += $totals;
            //$total += $company['total_amount'];
            $lprice += $company['booking_amount'];
            $aprice += $company_commission;
            $commission_price += $fly_commission;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            if ($company["booking_status"] == 'Cancelled') {
                $can_count++;
            }
            if ($company["booking_status"] == 'Refund') {
                $ref_count++;
            }

            $allrefund += $adjust;
            $cancelled += $cancel;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            $html .= $output;
        }

        $totalamount = ($total * 1) + ($discount * 1);
        if ($allrefund > 0 || $cancelled > 0) {
            $totalamount = $totalamount - $allrefund - $cancelled;
            $total = $total - $allrefund - $cancelled;
            //$lprice = $lprice - $allrefund ;

        }

        $return_book = $lprice - $allrefund - $cancelled;
        $vat = 20 / 100 * $aprice;
        $cprice = $aprice - $vat;
        $adjusts = isset($_GET['adjust']) ? $_GET['adjust'] : 0;
        $net_amount = $aprice + $adjusts;

        $totalcommission = ($commission_price * 1) - ($discount * 1);
        $html .= "</tbody></table><br/><br/><br/><br/><table border='1'><tbody>";
        $html .= "  <tr><td><b>Total Booking amount </b></td><td>" . $lprice . " </td> </tr>";
        $html .= "  <tr><td><b> Booking Agent Share    </b></td><td>" . $comp_share . " </td> </tr>";
        $html .= "  <tr><td><b> Service Provider Share   </b></td><td>" . $pz_share . " </td> </tr>";
        // $html .= " <tr><td> <b>Net Amount Paid by customer </b></td><td>" . $return_book . " </td></tr> ";


        // $html .= " <tr><td> <b>Total Booking Cancel Amount</b> </td><td>" . $cancelled . " </td> </tr>";
        // $html .= " <tr><td> <b>Total Booking Refund Amount </b></td><td>" . $allrefund . " </td> </tr>";
        // $html .= "<tr> <td> <b>Total Company planty Amount</b> </td><td>" . $total_planty_to_company . "</td>  </tr>";
        // $html .= " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        $html .= "<tr><td> <b>Total Bookings</b> </td><td>  " . count($companies) . "</td>  </tr>";
        // $html .= "<tr><td> <b>Total No of Refund</b> </td><td>  " . $ref_count . "</td> </tr>";
        // $html .= "<tr><td> <b>Total No of Cancel</b> </td><td>  " . $can_count . "</td>  </tr>";
        // $html .= "<tr><td> <b>Total No of Booked</b> </td><td>  " . (count($companies) - $can_count - $ref_count) . "</td> </tr> ";

        $html .= "</tbody></table>";
        //$html .= "\t";

//echo $html; die();


//        $view = View::make('myview_name');
//        $html = $view->render();

        $pdf = new TCPDF();


        $pdf::setFooterData(array(0, 64, 0), array(0, 64, 128));

// set default monospaced font
        $pdf::SetDefaultMonospacedFont("courier");

// set margins
        $pdf::SetMargins(15, 27, 15);
        $pdf::SetHeaderMargin(5);
        $pdf::SetFooterMargin(10);

// set auto page breaks
        $pdf::SetAutoPageBreak(TRUE, 25);


        $pdf::SetFont('times', '', 19, '', true);
// ---------------------------------------------------------

// set default font subsetting mode
        $pdf::setFontSubsetting(true);
        $pdf::setPrintHeader(false);
        $pdf::setPrintFooter(false);


        $pdf::SetFont('times', '', 19, '', true);

        $pdf::AddPage("L");
        $pdf::SetXY(15, 6);
          if($adminName != ''){
        $pdf::Write(12, "$adminName Report", '', 0, 'L', true, 0, false, false, 0);
        }
        else
        {
         $pdf::Write(12, "Airport Booking Report", '', 0, 'L', true, 0, false, false, 0);
        }
       
        $pdf::SetXY(140, 8);
        $pdf::Write(5, "Total Records:" . ($i-1), '', 0, 'L', true, 0, false, false, 0);
//echo $bookings;
        $pdf::SetFont('times', '', 9, '', true);
        $pdf::writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);


//        $pdf::SetFont('helvetica', '', 9);
//
//        $pdf::SetTitle('Hello World');
//        $pdf::AddPage();
//        $pdf::writeHTML($html, true, false, false, false, '');
        //header("Content-type:application/pdf");

// It will be called downloaded.pdf
        //header("Content-Disposition:attachment;filename='pz_airport_parking_report.pdf'");
        $pdf::Output($_SERVER['DOCUMENT_ROOT'].'/bookinglist.pdf','FD');

        // dd("testing pdf");

    }
    public function admin_booking_report_excel(Request $request)
    { 
        // dd($request);
        $user_permissions = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        
        if($user_permissions){
            $user_permissions =explode(",",$user_permissions["permissions"]);   
        }
        $data = $request->all();
      // dd($data);
        $total_planty_to_company = 0;
        $total_planty_to_fly = 0;
        $total = 0;
        $lprice = 0;
        $aprice = 0;
        $commission_price = 0;
        $allrefund = 0;
        $cancelled = 0;
        $discount = 0;
        $can_count = 0;
        $ref_count = 0;
        $abd_count = 0;
        $comp_count = 0;

        $filter_search = "";
        $planty_refund = " ";
        $b_t_table = " ";
        $_refund_via_s = "";
        $palenty_to = "";
        $search = "";
        $join = "";
        $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";
        $b_t_table = "bt.*, ";
        if( $data['filter']==null)
        {
           
                

$data['filter']='all';



        }

        if ($data != null && count($data) > 0) {

            if (!empty($data['search'])) {
                $search = trim(preg_replace('/\s+/', ' ', $data['search']));
                $search = " and (b.referenceNo like '%" . $search . "%' or 
                            b.first_name like '%" . $search . "%' or 
                            b.last_name like '%" . $search . "%' or 
                            b.email like '%" . $search . "%' or 
                            b.phone_number like '%" . $search . "%' or 
                            b.postal_code like '%" . $search . "%' or 
                            b.deptFlight like '%" . $search . "%' or 
                            b.returnFlight like '%" . $search . "%'
                )";
            }

            if (!empty($data['airport']) && $data['airport'] == "all" || $data['airport'] == null) {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all" ||  $data['companies'] == null) {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            }

            if (!empty($data['admins']) && $data['admins'] == "all" || $data['admins'] == null) {
                $_admins = "";
            } else {
                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";
            }

            if (!empty($data['payment']) && $data['payment'] == "all" || $data['payment'] == null) {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }

            if (!empty($data['status']) && $data['status'] == "all" || $data['status'] == null) {
                $_status = "";
            } else {
                if ($data['status'] == 'Booked') {
                    //$_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";
                } elseif ($data['status'] == 'Refund') {

                    if ($data['refund_via'] == 'all') {
                        $_refund_via_s = "";
                    } else {
                        $_refund_via_s = " AND bt.payment_medium = '" . $data['refund_via'] . "'";
                    }
                    if ($data['palenty_to'] == 'all') {
                        $palenty_to = "";
                    } else {
                        $palenty_to = " AND bt.palenty_to = '" . $data['palenty_to'] . "'";
                    }


                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                } else {
                    if ($data["status"] != "") {
                        if($data["status"] == 'Completed')
                        {
                        $data['status'] = 'Booked';
                        }
                        $_status = " AND b.booking_action = '" . $data['status'] . "'";
                    } else {
                        $_status = "";
                    }
                }
            }
            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }
            if ($data['filter'] != "" && $data['filter'] != "all"|| $data['filter'] == null) {
                if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }

                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;
            } else if ($data['filter'] == null) {
                  if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }
               $data['filter']="created_at";
                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;


                  }
                  
            else {
                $_filter = "";
                $data['filter']="created_at";
                $from = "'" . date('Y-m-d') . "'";
                $to = "'" . date('Y-m-d') . "'";
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;
                
            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
        } else {
            $_current = " and b.created_at  >= CURDATE()";
            $filter_search = $_current;
        }
     
    
/*
        $companies = "SELECT $b_t_table b.id as booking_id,b.model,b.make,b.color,b.registration ,b.referenceNo,b.booking_amount, b.booking_status, b.payment_method,b.email, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.first_name As firstname , b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
     b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
    b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
    (c.share_percentage/100*(b.booking_amount)) As fly_commission       
    FROM airports_bookings as b 
    left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    join airports as a on a.id = b.airportID
    $planty_refund
    WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.removed = 'No' and b.status = 'Yes' $join $filter_search GROUP BY bt.referenceNo ORDER BY b.id DESC";
*/
    $companies = "SELECT  b.id as booking_id,b.model,b.make,b.color,b.registration ,b.referenceNo, b.ext_ref,b.booking_amount, b.booking_status, b.payment_method,b.email, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.first_name As firstname , b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
     b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
    b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
    (c.share_percentage/100*(b.booking_amount)) As fly_commission       
    FROM airports_bookings as b 
    left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    join airports as a on a.id = b.airportID
    WHERE  b.removed = 'No' and b.status = 'Yes' $join $filter_search  ORDER BY b.id DESC";
        
        $companies = DB::select(DB::raw($companies));
       
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();



        header('Content-Type: application/vnd.ms-excel');    //define header info for browser
        header('Content-Disposition: attachment; filename=bookinglist' . date('Ymd') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "Sr# \t Reference No \t External Ref \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departures Date \t Arrival Date  " ; if (in_array('Amounts', $user_permissions)){ echo "\t Booking Amount";} echo " \t Booking Agent Share \t Service Provider share \t ";
        // echo "Sr# \t Reference No \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departure Date \t Arrival Date \t Total Days  \t Cancel Amount \t Total Amount \t Vehicle make \t Model \t Color \t Registration ";

        print("\n");
        $i = 1;
        $totalamount = 0;
        $compshare = 0;
        $pzshare = 0;
        foreach ($companies as $company) {

            $cancel = 0;
            $adjust = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);

            $transaction = (array)$transactions;
            foreach ($transactions as $transaction) {
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                    $cancel += $transaction['payable'];
                }
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {
                    $adjust += $transaction['payable'];
                }
            }

            // $refund = 0;
            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){
            // $refund  = $company['booking_amount'];
            // }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){
            // $refund  = $company['booking_amount'];
            // }

            $booking_amount = $company['booking_amount'];
            $share_percentage = $company['share_percentage'];

            $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));
            $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));
            $company_commission_after_refund = $company_commission_before_refund;

            if ($adjust != $company['booking_amount'] && $adjust > 0) {
                //$booking_amount = $booking_amount - $adjust;
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }
            if ($adjust == $company['booking_amount']) {
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }

            $company_commission = $company_commission_after_refund;
            $fly_commission = $fly_commission_before_refund;

            // $explode  = explode("FP",$company['company_code']);
            // $cname = $explode[1];
            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");
            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];
            $cname = $company['company_name'];

            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));
            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));
            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));
            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));

            $booking_amount = $company['booking_amount'];
            $totals = $company['total_amount'];

            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){
            $cancel_remaining_amount = 0;
            if ($cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
                $cancel_remaining_amount = $booking_amount - $cancel;
            }

            $output = $i . "\t";
            $output .= $company['referenceNo'] . "\t";
            $output .= $company['ext_ref'] . "\t";
            $output .= $cname . "\t";
            // $output .= $company['airport_name'] . "\t";
            $output .= $company['firstname']." ".$company['Surname'] . "\t";
            $output .= $company['booking_status'] . "\t";
            $output .= $company['payment_method'] . "\t";
            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";
            // $output .= $company['no_of_days'] . "\t";
            //$output .= $cancel . "\t";
                  // $output .= $adjust . "\t";
            if (in_array("Amounts", $user_permissions)){
            $output .= $company['booking_amount'] . "\t";
            }
            $c_share = (100 - $company['share_percentage'])/100 * $company['booking_amount'];
            $p_share = ($company['share_percentage']/100) * $company['booking_amount'];
            $compshare = $compshare + $c_share;
            $pzshare  = $pzshare + $p_share;
            $output .= $c_share . "\t";
            $output .= $p_share . "\t";
            // $output .= $company['color'] . "\t";
            // $output .= $company['registration'] . "\t";
            //\t Vehicle make \t Model \t Color \t Registration

            //$output .= (float)number_format($company_commission, 3) . "\t";
            // $output .= (float)number_format($fly_commission, 3) . "\t";
            // $output .= $cancel_remaining_amount . "\t";
            // $output .= $company['payment_medium'] . "\t";
            // $output .= $company['palenty_to'] . "\t";
            // $output .= $company['palenty_amount'] . "\t";
            //$output .= ($company['palenty_amount'] + $adjust) . "\t";


            $i++;
            // if ($company['palenty_to'] == "Company") {
            //     $total_planty_to_company += $company['palenty_amount'];
            // }
            // if ($company['palenty_to'] == "JETSEEKER") {
            //     $total_planty_to_fly += $company['palenty_amount'];
            // }
             if ($company["booking_status"] == 'Completed') {
                $totalamount = $totalamount + $company['booking_amount'];
                $comp_count++;
            }
          
            //$total += $company['total_amount'];
            $total += $totals;
            //$total += $company['total_amount'];
            $lprice += $company['total_amount'];
            $aprice += $company_commission;
            $commission_price += $fly_commission;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            if ($company["booking_status"] == 'Cancelled') {
                $can_count++;
            }
            if ($company["booking_status"] == 'Refund') {
                $ref_count++;
            }
             if ($company["booking_status"] == 'Abandon') {
                $abd_count++;
            }

            $allrefund += $adjust;
            $cancelled += $cancel;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            print(trim($output)) . "\t\n";
        }

        // $totalamount = ($total * 1) + ($discount * 1);
        // if ($allrefund > 0 || $cancelled > 0) {
        //     $totalamount = $totalamount - $allrefund - $cancelled;
        //     $total = $total - $allrefund - $cancelled;
        //     //$lprice = $lprice - $allrefund ;

        // }

        $return_book = $lprice - $allrefund - $cancelled;
        $vat = 20 / 100 * $aprice;
        $cprice = $aprice - $vat;
        $adjusts = isset($_GET['adjust']) ? $_GET['adjust'] : 0;
        $net_amount = $aprice + $adjusts;

        $totalcommission = ($commission_price * 1) - ($discount * 1);
         echo "\n\n \t Total Bookings \t  " . count($companies) . "\t  ";
         echo "\n \t Total Booked Bookings \t  " . $comp_count . "\t  ";
         echo "\n  \t Total Abandond Bookings \t  " . $abd_count . "\t  ";
         echo "\n \t Total Cancelled Bookings \t  " . $can_count . "\t  ";
         echo "\n  \t Total Refunded Bookings \t  " . $ref_count . "\t  ";
         
        if (in_array("Amounts", $user_permissions)){
        echo " \n \n \n \t Total Booking amount \t" . $totalamount . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n  \t  Service Provider  Share \t" . $compshare . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n  \t  Booking Agent Share \t" . $pzshare . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        
        
    


      
        }
       
      

        echo "\n";
        echo "\t";

        //print(trim($output))."\t\n";
    }
    public function admin_booking_report_excel_agent(Request $request)
    { 
        $data = $request->all();
      // dd($data);
        $total_planty_to_company = 0;
        $total_planty_to_fly = 0;
        $total = 0;
        $lprice = 0;
        $aprice = 0;
        $commission_price = 0;
        $allrefund = 0;
        $cancelled = 0; 
        $discount = 0;
        $can_count = 0;
        $ref_count = 0;

        $filter_search = "";
        $planty_refund = " ";
        $b_t_table = " ";
        $_refund_via_s = "";
        $palenty_to = "";
        $search = "";
        $join = "";
        $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";
        $b_t_table = "bt.*, ";
        if( $data['filter']==null)
        {
           
                

              $data['filter']='all';



        }

        if ($data != null && count($data) > 0) {

            if (!empty($data['search'])) {
                $search = trim(preg_replace('/\s+/', ' ', $data['search']));
                $search = " and (b.referenceNo like '%" . $search . "%' or 
                            b.first_name like '%" . $search . "%' or 
                            b.last_name like '%" . $search . "%' or 
                            b.email like '%" . $search . "%' or 
                            b.phone_number like '%" . $search . "%' or 
                            b.postal_code like '%" . $search . "%' or 
                            b.deptFlight like '%" . $search . "%' or 
                            b.returnFlight like '%" . $search . "%'
                )";
            }

            if (!empty($data['airport']) && $data['airport'] == "all" || $data['airport'] == null) {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all" ||  $data['companies'] == null) {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            }

            if (!empty($data['admins']) && $data['admins'] == "all" || $data['admins'] == null) {
                $_admins = "";
            } else {
                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";
            }

            if (!empty($data['payment']) && $data['payment'] == "all" || $data['payment'] == null) {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }

            if (!empty($data['status']) && $data['status'] == "all" || $data['status'] == null) {
                $_status = " AND b.booking_action != 'Abandon'";
            } else {
                if ($data['status'] == 'Booked') {
                    //$_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";
                } elseif ($data['status'] == 'Refund') {

                    if ($data['refund_via'] == 'all') {
                        $_refund_via_s = "";
                    } else {
                        $_refund_via_s = " AND bt.payment_medium = '" . $data['refund_via'] . "'";
                    }
                    if ($data['palenty_to'] == 'all') {
                        $palenty_to = "";
                    } else {
                        $palenty_to = " AND bt.palenty_to = '" . $data['palenty_to'] . "'";
                    }


                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                } else {
                    if ($data["status"] != "") {
                         if ($data['status'] == 'Completed') 
                         {
                             $data['status'] = 'Booked';
                         }
                        $_status = " AND b.booking_action = '" . $data['status'] . "'";
                    } else {
                        $_status = " AND b.booking_action != 'Abandon'";
                    }
                }
            }
            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }
            if ($data['filter'] != "" && $data['filter'] != "all"|| $data['filter'] == null) {
                if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }

                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;
            } else if ($data['filter'] == null) {
                  if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }
               $data['filter']="created_at";
                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;


                  }
            else {
                $_filter = "";
            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
        } else {
            $_current = " and b.created_at  >= CURDATE()";
            $filter_search = $_current;
        }

   $companies = "SELECT $b_t_table b.id as booking_id,b.model,b.make,b.color,b.registration ,b.referenceNo, b.ext_ref,b.booking_amount, b.booking_status, b.payment_method,b.email, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.first_name As firstname , b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
     b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
    b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
    (c.share_percentage/100*(b.booking_amount)) As fly_commission       
    FROM airports_bookings as b 
    left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    join airports as a on a.id = b.airportID
    $planty_refund
    WHERE c.admin_id = ".Auth::user()->id."  and b.booking_action != 'Cancelled' and b.booking_action != 'Refund'  and b.removed = 'No' and b.status = 'Yes' $join $filter_search  ORDER BY b.id DESC";
//exit;
        $companies = DB::select(DB::raw($companies));
       // dd($companies);
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();



        header('Content-Type: application/vnd.ms-excel');    //define header info for browser
        header('Content-Disposition: attachment; filename=bookinglist' . date('Ymd') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "Sr# \t Reference No \t External Ref \t Company Name \t Name \t Status \t Booked Date \t Departures Date \t Arrival Date   \t Total Amount \t Booking Agent share  \t Service Provider Share   ";
        // echo "Sr# \t Reference No \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departure Date \t Arrival Date \t Total Days  \t Cancel Amount \t Total Amount \t Vehicle make \t Model \t Color \t Registration ";

        print("\n");
        $i = 1;
         $compshare = 0;
         $pzshare = 0;
        foreach ($companies as $company) {

            $cancel = 0;
            $adjust = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);

            $transaction = (array)$transactions;
            foreach ($transactions as $transaction) {
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                    $cancel += $transaction['payable'];
                }
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {
                    $adjust += $transaction['payable'];
                }
            }

            // $refund = 0;
            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){
            // $refund  = $company['booking_amount'];
            // }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){
            // $refund  = $company['booking_amount'];
            // }

            $booking_amount = $company['booking_amount'];
            $share_percentage = $company['share_percentage'];

            $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));
            $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));
            $company_commission_after_refund = $company_commission_before_refund;

            if ($adjust != $company['booking_amount'] && $adjust > 0) {
                //$booking_amount = $booking_amount - $adjust;
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }
            if ($adjust == $company['booking_amount']) {
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }

            $company_commission = $company_commission_after_refund;
            $fly_commission = $fly_commission_before_refund;

            // $explode  = explode("FP",$company['company_code']);
            // $cname = $explode[1];
            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");
            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];
            $cname = $company['company_name'];

            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));
            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));
            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));
            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));

            $booking_amount = $company['booking_amount'];
            $totals = $company['total_amount'];

            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){
            $cancel_remaining_amount = 0;
            if ($cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
                $cancel_remaining_amount = $booking_amount - $cancel;
            }

            $output = $i . "\t";
            $output .= $company['referenceNo'] . "\t";
            $output .= $company['ext_ref'] . "\t";
            $output .= $cname . "\t";
            // $output .= $company['airport_name'] . "\t";
            $output .= $company['firstname']." ".$company['Surname'] . "\t";
            $output .= $company['booking_status'] . "\t";
            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";
            // $output .= $company['no_of_days'] . "\t";
            //$output .= $cancel . "\t";
                  // $output .= $adjust . "\t";
            $output .= $company['booking_amount'] . "\t";
             $c_share = (100 - $company['share_percentage'])/100 * $company['booking_amount'];
            $p_share = ($company['share_percentage']/100) * $company['booking_amount'];
            $compshare = $compshare + $c_share;
            $pzshare  = $pzshare + $p_share;
            $output .= $c_share . "\t";
            $output .= $p_share . "\t";
            // $output .= $company['make'] . "\t";
            // $output .= $company['model'] . "\t";
            // $output .= $company['color'] . "\t";
            // $output .= $company['registration'] . "\t";
            //\t Vehicle make \t Model \t Color \t Registration

            //$output .= (float)number_format($company_commission, 3) . "\t";
            // $output .= (float)number_format($fly_commission, 3) . "\t";
            // $output .= $cancel_remaining_amount . "\t";
            // $output .= $company['payment_medium'] . "\t";
            // $output .= $company['palenty_to'] . "\t";
            // $output .= $company['palenty_amount'] . "\t";
            //$output .= ($company['palenty_amount'] + $adjust) . "\t";


            $i++;
            if ($company['palenty_to'] == "Company") {
                $total_planty_to_company += $company['palenty_amount'];
            }
            if ($company['palenty_to'] == "JETSEEKER") {
                $total_planty_to_fly += $company['palenty_amount'];
            }

            //$total += $company['total_amount'];
            $total += $totals;
            //$total += $company['total_amount'];
            $lprice += $company['booking_amount'];
            $aprice += $company_commission;
            $commission_price += $fly_commission;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            if ($company["booking_status"] == 'Cancelled') {
                $can_count++;
            }
            if ($company["booking_status"] == 'Refund') {
                $ref_count++;
            }

            $allrefund += $adjust;
            $cancelled += $cancel;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            print(trim($output)) . "\t\n";
        }

        $totalamount = ($total * 1) + ($discount * 1);
        if ($allrefund > 0 || $cancelled > 0) {
            $totalamount = $totalamount - $allrefund - $cancelled;
            $total = $total - $allrefund - $cancelled;
            //$lprice = $lprice - $allrefund ;

        }

        $return_book = $lprice - $allrefund - $cancelled;
        $vat = 20 / 100 * $aprice;
        $cprice = $aprice - $vat;
        $adjusts = isset($_GET['adjust']) ? $_GET['adjust'] : 0;
        $net_amount = $aprice + $adjusts;

        $totalcommission = ($commission_price * 1) - ($discount * 1);

        echo " \n \n \n \t Total Booking amount \t" . $lprice . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n  \t   Booking Agent Share \t" . $compshare . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n  \t  Service Provider  Share \t" . $pzshare . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        //echo " \n \t Net Amount Paid by customer \t" . $return_book . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";


        //echo " \n \t Total Booking Cancel Amount \t" . $cancelled . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        //echo " \n \t Total Booking Refund Amount \t" . $allrefund . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        //echo " \n \t Total Company planty Amount \t" . $total_planty_to_company . " \t \t \t \t \t \t \t \t \t \t \t \t \t \t  ";
        //echo " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo "\n\n \t Total Bookings \t  " . count($companies) . "\t  ";
       // echo "\n \t Total No of Refund \t  " . $ref_count . "\t  ";
       // echo "\n \t Total No of Cancel \t  " . $can_count . "\t  ";
        //echo "\n \t Total No of Booked \t  " . (count($companies) - $can_count - $ref_count) . "\t  ";

        echo "\n";
        echo "\t";

        //print(trim($output))."\t\n";
    }
    public function admin_booking_report_excel_bk(Request $request)
    {
        $data = $request->all();
       //dd($data);
        $total_planty_to_company = 0;
        $total_planty_to_fly = 0;
        $total = 0;
        $lprice = 0;
        $aprice = 0;
        $commission_price = 0;
        $allrefund = 0;
        $cancelled = 0;
        $discount = 0;
        $can_count = 0;
        $ref_count = 0;

        $filter_search = "";
        $planty_refund = " ";
        $b_t_table = " ";
        $_refund_via_s = "";
        $palenty_to = "";
        $search = "";
        $join = "";
        $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";
        $b_t_table = "bt.*, ";

           if( $data['filter']==null)
        {
           
                

$data['filter']='all';



        }

        if ($data != null && count($data) > 0) {

            if (!empty($data['search'])) {
                $search = trim(preg_replace('/\s+/', ' ', $data['search']));
                $search = " and (b.referenceNo like '%" . $search . "%' or 
                            b.first_name like '%" . $search . "%' or 
                            b.last_name like '%" . $search . "%' or 
                            b.email like '%" . $search . "%' or 
                            b.phone_number like '%" . $search . "%' or 
                            b.postal_code like '%" . $search . "%' or 
                            b.deptFlight like '%" . $search . "%' or 
                            b.returnFlight like '%" . $search . "%'
                )";
            }

            if (!empty($data['airport']) && $data['airport'] == "all" || $data['airport'] == null) {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all" ||  $data['companies'] == null) {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            }

            if (!empty($data['admins']) && $data['admins'] == "all" || $data['admins'] == null) {
                $_admins = "";
            } else {
                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";
            }

            if (!empty($data['payment']) && $data['payment'] == "all" || $data['payment'] == null) {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }

            if (!empty($data['status']) && $data['status'] == "all" || $data['status'] == null) {
                $_status = "";
            } else {
                if ($data['status'] == 'Booked' || $data['status'] == 'Completed') {
                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";
                } elseif ($data['status'] == 'Refund') {

                    if ($data['refund_via'] == 'all') {
                        $_refund_via_s = "";
                    } else {
                        $_refund_via_s = " AND bt.payment_medium = '" . $data['refund_via'] . "'";
                    }
                    if ($data['palenty_to'] == 'all') {
                        $palenty_to = "";
                    } else {
                        $palenty_to = " AND bt.palenty_to = '" . $data['palenty_to'] . "'";
                    }


                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                } else {
                    if ($data["status"] != "") {
                        //$_status = " AND b.booking_action = '" . $data['status'] . "'";
                    } else {
                        $_status = "";
                    }
                }
            }
            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }
            if ($data['filter'] != "" && $data['filter'] != "all"|| $data['filter'] == null) {
                if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }

                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;
            } else if ($data['filter'] == null) {
                  if ($data['start_date'] != "") {
                    $start_date = date("'Y-m-d'", strtotime($data['start_date']));
                } else {
                    $start_date = date('Y-m-d');
                }

                if ($data['end_date'] != "") {
                    $end_date = date("'Y-m-d'", strtotime($data['end_date']));
                } else {
                    $end_date = date('Y-m-d');
                }
               $data['filter']="created_at";
                $from = $start_date;
                $to = $end_date;
                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " .$date_format. " BETWEEN " .$from. " AND " .$to;


                  }
            else {
                $_filter = "";
            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
        } else {
            $_current = " and b.created_at  >= CURDATE()";
            $filter_search = $_current;
        }


        $companies = "SELECT $b_t_table b.id as booking_id,b.model,b.make,b.color,b.registration ,b.referenceNo,b.booking_amount, b.booking_status, b.payment_method,b.email, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.first_name As firstname , b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
	 b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
	(c.share_percentage/100*(b.booking_amount)) As fly_commission 		
	FROM airports_bookings as b 
	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
	join airports as a on a.id = b.airportID
	$planty_refund
	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.removed = 'No' and b.status = 'Yes' $join $filter_search GROUP BY bt.referenceNo ORDER BY b.id DESC";

        $companies = DB::select(DB::raw($companies));
       // dd($companies);
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();



        header('Content-Type: application/vnd.ms-excel');    //define header info for browser
        header('Content-Disposition: attachment; filename=bookinglist' . date('Ymd') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "Sr# \t Reference No \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departures Date \t Arrival Date \t Total Days  \t Total Amount \t Vehicle make \t Model \t Color \t Registration  ";
        // echo "Sr# \t Reference No \t Carpark Name \t Name \t Status \t Payment Method \t Booked Date \t Departure Date \t Arrival Date \t Total Days  \t Cancel Amount \t Total Amount \t Vehicle make \t Model \t Color \t Registration ";

        print("\n");
        $i = 1;
        foreach ($companies as $company) {

            $cancel = 0;
            $adjust = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);

            $transaction = (array)$transactions;
            foreach ($transactions as $transaction) {
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                    $cancel += $transaction['payable'];
                }
                if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {
                    $adjust += $transaction['payable'];
                }
            }

            // $refund = 0;
            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){
            // $refund  = $company['booking_amount'];
            // }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){
            // $refund  = $company['booking_amount'];
            // }

            $booking_amount = $company['booking_amount'];
            $share_percentage = $company['share_percentage'];

            $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));
            $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));
            $company_commission_after_refund = $company_commission_before_refund;

            if ($adjust != $company['booking_amount'] && $adjust > 0) {
                //$booking_amount = $booking_amount - $adjust;
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }
            if ($adjust == $company['booking_amount']) {
                $company_commission_after_refund = $company_commission_before_refund - $adjust;
            }

            $company_commission = $company_commission_after_refund;
            $fly_commission = $fly_commission_before_refund;

            // $explode  = explode("FP",$company['company_code']);
            // $cname = $explode[1];
            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");
            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];
            $cname = $company['company_name'];

            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));
            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));
            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));
            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));

            $booking_amount = $company['booking_amount'];
            $totals = $company['total_amount'];

            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){
            $cancel_remaining_amount = 0;
            if ($cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
                $cancel_remaining_amount = $booking_amount - $cancel;
            }

            $output = $i . "\t";
            $output .= $company['referenceNo'] . "\t";
            $output .= $cname . "\t";
            // $output .= $company['airport_name'] . "\t";
            $output .= $company['firstname']." ".$company['Surname'] . "\t";
            $output .= $company['booking_status'] . "\t";
            $output .= $company['payment_method'] . "\t";
            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";
            $output .= $company['no_of_days'] . "\t";
            //$output .= $cancel . "\t";
                  // $output .= $adjust . "\t";
            $output .= $company['booking_amount'] . "\t";
            $output .= $company['make'] . "\t";
            $output .= $company['model'] . "\t";
            $output .= $company['color'] . "\t";
            $output .= $company['registration'] . "\t";
            //\t Vehicle make \t Model \t Color \t Registration

            //$output .= (float)number_format($company_commission, 3) . "\t";
            // $output .= (float)number_format($fly_commission, 3) . "\t";
            // $output .= $cancel_remaining_amount . "\t";
            // $output .= $company['payment_medium'] . "\t";
            // $output .= $company['palenty_to'] . "\t";
            // $output .= $company['palenty_amount'] . "\t";
            //$output .= ($company['palenty_amount'] + $adjust) . "\t";


            $i++;
            if ($company['palenty_to'] == "Company") {
                $total_planty_to_company += $company['palenty_amount'];
            }
            if ($company['palenty_to'] == "JETSEEKER") {
                $total_planty_to_fly += $company['palenty_amount'];
            }

            //$total += $company['total_amount'];
            $total += $totals;
            //$total += $company['total_amount'];
            $lprice += $company['booking_amount'];
            $aprice += $company_commission;
            $commission_price += $fly_commission;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            if ($company["booking_status"] == 'Cancelled') {
                $can_count++;
            }
            if ($company["booking_status"] == 'Refund') {
                $ref_count++;
            }

            $allrefund += $adjust;
            $cancelled += $cancel;
            //$extra_amount += $company['extra_amount'];
            //$discount += $company['discount_amount'];

            print(trim($output)) . "\t\n";
        }

        $totalamount = ($total * 1) + ($discount * 1);
        if ($allrefund > 0 || $cancelled > 0) {
            $totalamount = $totalamount - $allrefund - $cancelled;
            $total = $total - $allrefund - $cancelled;
            //$lprice = $lprice - $allrefund ;

        }

        $return_book = $lprice - $allrefund - $cancelled;
        $vat = 20 / 100 * $aprice;
        $cprice = $aprice - $vat;
        $adjusts = isset($_GET['adjust']) ? $_GET['adjust'] : 0;
        $net_amount = $aprice + $adjusts;

        $totalcommission = ($commission_price * 1) - ($discount * 1);

        echo " \n \n \n \t Total Booking amount Paid by customer \t" . $lprice . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Net Amount Paid by customer \t" . $return_book . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";


        echo " \n \t Total Booking Cancel Amount \t" . $cancelled . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Total Booking Refund Amount \t" . $allrefund . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Total Company planty Amount \t" . $total_planty_to_company . " \t \t \t \t \t \t \t \t \t \t \t \t \t \t  ";
        echo " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo "\n\n \t Total Bookings \t  " . count($companies) . "\t  ";
        echo "\n \t Total No of Refund \t  " . $ref_count . "\t  ";
        echo "\n \t Total No of Cancel \t  " . $can_count . "\t  ";
        echo "\n \t Total No of Booked \t  " . (count($companies) - $can_count - $ref_count) . "\t  ";

        echo "\n";
        echo "\t";


        //print(trim($output))."\t\n";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $adminName = '';
        $books = new airports_bookings();
        $books =  $books->select("c.aph_id","airports_bookings.*");
        $books = $books->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
         if ($request->has("search")) {
            $name = $request->input("search");
            if ($name != "" && $name != "all") {
                $books = $books->where('referenceNo','LIKE',"%{$name}%");
                $books = $books->orWhere('first_name','LIKE',"%{$name}%");
                $books = $books->orWhere('last_name','LIKE',"%{$name}%");
                $books = $books->orWhere('airports_bookings.email','LIKE',"%{$name}%");
                $books = $books->orWhere('phone_number','LIKE',"%{$name}%");
                $books = $books->orWhere('postal_code','LIKE',"%{$name}%");
                $books = $books->orWhere('deptFlight','LIKE',"%{$name}%");
                $books = $books->orWhere('returnFlight','LIKE',"%{$name}%");

                //$bookings_count = $bookings_count->where("referenceNo", $name);
            }
        }
        if ($request->has("filter") && $request->has("start_date") && $request->has("end_date")) {

            $name = $request->input("filter");
           // dd($name);
            if ($name != "" && $name != "all") {
                // echo "start=".$request->input("start_date");
                // echo "<br>end=".$request->input("end_date");
                
                 $start_date = date("Y-m-d", strtotime($request->input("start_date")));
                 $end_date = date("Y-m-d", strtotime($request->input("end_date")));
                
                $books->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $books->whereDate('airports_bookings.'.$name, '<=', $end_date);

                //$bookings = $bookings->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);

             

                //$bookings_count = $bookings_count->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);
            }
        } else {

            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "created_at";

            $books->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $books->whereDate('airports_bookings.'.$name, '<=', $end_date);

           
            Input::merge(['start_date' => date("d-M-Y",strtotime($start_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
        }
//dd($bookings_count);
//DB::enableQueryLog();
        if ($request->has("booking_source")) {
            $name = $request->input("booking_source");

            if ($name != "" && $name != "all") {
                //  dd($name);

				// if ($role_nam == "Marketing" && $name=='paid') 
				// {

				// 	$bookings = $bookings->where("traffic_src",'=', 'PPC');
				// 	$bookings = $bookings->orWhere("traffic_src", 'BING');
				// 	$bookings = $bookings->orWhere("traffic_src", 'ORG');
				// 	$bookings = $bookings->orWhere("traffic_src", 'EM');
				// 	$bookings = $bookings->orWhere("traffic_src", 'POR');
				// 	$bookings_count = $bookings_count->where("traffic_src",'=', 'PPC');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'BING');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'ORG');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'EM');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'POR');
					
				// }
				// else
				// {
					$books = $books->where("traffic_src", $name);
                
				// }
            }
			
        }

        if ($request->has("no_of_days")) {
            $name = $request->input("no_of_days");
            if ($name != "" && $name != "all") {
                $books = $books->where("no_of_days", $name);
            }
        }
        
      
            $books = $books->where("agentID", '1');
            
         

        if ($request->has("airport") ) {
            $name = $request->input("airport");
            if ($name != "" && $name != "all") {
                $books = $books->where("airportID", $name);
            
            }
        }
        if ($request->has("companies") ) {
            $name = $request->input("companies");
            if ($name != "" && $name != "all") {
                $books = $books->where("companyId", $name);
              
            }
        }

        if ($request->has("admins")) {
           
            
            $name = $request->input("admins");
           
            if ($name != "" && $name != "all") {
                  $ads = DB::select("Select name from users where id = '$name'");
           
               $adminName = $ads[0]->name;
                $books = $books->where("c.admin_id", $name);
              
            }
        }

        if ($request->has("payment")) {
            $name = $request->input("payment");
            if ($name != "" && $name != "all") {
                $books = $books->where("payment_method", $name);
            
            }
        }
        if ($request->has("status")) {
            $name = $request->input("status");
            if ($name != "" && $name != "all") {
                $books = $books->where("booking_status", $name);
              
            }
        }
        $books = $books->where('booking_status','Completed')->get();
        
        
        
        
        $role_nam = users_roles::get_user_role(Auth::user()->id)->name;

        $user_role_details = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        if($user_role_details){
            $user_role_details =explode(",",$user_role_details["bk_source"]);
        }
        // dd($request->all());

        set_time_limit(0);
        $bookings = new airports_bookings();
        $bookings =  $bookings->select("c.aph_id","c.admin_id","p.alias","airports_bookings.*");
        $bookings = $bookings->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $bookings = $bookings->Join('partners as p','airports_bookings.AgentID','=','p.id');
         
        $bookings_count = new airports_bookings();
        $bookings_count =  $bookings_count->select("airports_bookings.*");
        $bookings_count = $bookings_count->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $data = $request->all();
        // $roles = users_roles::where('role_id','7')->get();
        // $checks = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
        //   ->select('users.name')->where('role_id','7')
        //  ->get();
        //  dd($checks);
        $admins = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();
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
                $bookings = $bookings->orWhere('airports_bookings.email','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('phone_number','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('postal_code','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('deptFlight','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('returnFlight','LIKE',"%{$name}%");
                   
                //$bookings_count = $bookings_count->where("referenceNo", $name);
                $bookings_count = $bookings_count->where('referenceNo','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('first_name','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('last_name','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('airports_bookings.email','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('phone_number','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('postal_code','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('deptFlight','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('returnFlight','LIKE',"%{$name}%");
            }
        }

        if ($request->has("filter") && $request->has("start_date") && $request->has("end_date")) {

            $name = $request->input("filter");
           // dd($name);
            if ($name != "" && $name != "all") {
                // echo "start=".$request->input("start_date");
                // echo "<br>end=".$request->input("end_date");
                
                 $start_date = date("Y-m-d", strtotime($request->input("start_date")));
                 $end_date = date("Y-m-d", strtotime($request->input("end_date")));
                
                $bookings->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $bookings->whereDate('airports_bookings.'.$name, '<=', $end_date);
                  //dd($start_date . ' ' . $end_date);
                //$bookings = $bookings->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);

                $bookings_count->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $bookings_count->whereDate('airports_bookings.'.$name, '<=', $end_date);

                //$bookings_count = $bookings_count->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);
            }
        } else {
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "created_at";

            $bookings->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $bookings->whereDate('airports_bookings.'.$name, '<=', $end_date);

            $bookings_count->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $bookings_count->whereDate('airports_bookings.'.$name, '<=', $end_date);

            Input::merge(['start_date' => date("d-M-Y",strtotime($start_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
        }
        //dd($bookings_count);
        //DB::enableQueryLog();
        if ($request->has("booking_source")) {
            $name = $request->input("booking_source");
            if ($name != "" && $name != "all") {
                //  dd($name);

				// if ($role_nam == "Marketing" && $name=='paid') 
				// {

				// 	$bookings = $bookings->where("traffic_src",'=', 'PPC');
				// 	$bookings = $bookings->orWhere("traffic_src", 'BING');
				// 	$bookings = $bookings->orWhere("traffic_src", 'ORG');
				// 	$bookings = $bookings->orWhere("traffic_src", 'EM');
				// 	$bookings = $bookings->orWhere("traffic_src", 'POR');
				// 	$bookings_count = $bookings_count->where("traffic_src",'=', 'PPC');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'BING');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'ORG');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'EM');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'POR');
					
				// }
				// else
				// {
					$bookings = $bookings->where("traffic_src", $name);
                	$bookings_count = $bookings_count->where("traffic_src", $name);
				// }
            }		
        }

        if ($request->has("no_of_days")) {
            $name = $request->input("no_of_days");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("no_of_days", $name);
                $bookings_count = $bookings_count->where("no_of_days", $name);
            }
        }
        
     
                $bookings_count = $bookings_count->where("agentID", '1');
           

        if ($request->has("airport") ) {
            $name = $request->input("airport");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("airportID", $name);
                $bookings_count = $bookings_count->where("airportID", $name);
            }
        }
        if ($request->has("companies") ) {
            $name = $request->input("companies");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("companyId", $name);
                $bookings_count = $bookings_count->where("companyId", $name);
            }
        }

        if ($request->has("admins")) {
            $name = $request->input("admins");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("c.admin_id", $name);
                $bookings_count = $bookings_count->where("c.admin_id", $name);
            }
        }

        if ($request->has("payment")) {
            $name = $request->input("payment");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("payment_method", $name);
                $bookings_count = $bookings_count->where("payment_method", $name);
            }
        }
        if ($request->has("status")) {
            $name = $request->input("status");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("booking_status", $name);
                $bookings_count = $bookings_count->where("booking_status", $name);
            }
        }


        //$bookings = $bookings->orderBy('id', 'DESC')->get();
        $bookings = $bookings->whereIn('traffic_src', $user_role_details);
        // dd($user_role_details);
        $bookings = $bookings->orderBy('id', 'DESC');
        
        $bookings_count = $bookings_count->orderBy('id', 'DESC')->get();

        //$sql = str_replace_array('?', $bookings->getBindings(), $bookings->toSql()); 
        //dd($sql);
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        echo "<pre>";print_r($lastQuery);exit;*/

        $bookings = $bookings->paginate(20);
        
        
        $apbookings = new airports_bookings();
        $apbookings =  $apbookings->select("c.aph_id","c.admin_id","airports_bookings.*");
        $apbookings = $apbookings->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $apbookings = $apbookings->join('users as u', 'c.admin_id', '=', 'u.id');
        $apbookings = $apbookings->where('u.id',Auth::user()->id);
        
        $apbookings_count = new airports_bookings();
        $apbookings_count =  $apbookings_count->select("airports_bookings.*");
        $apbookings_count = $apbookings_count->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $apbookings_count = $apbookings_count->join('users as u', 'c.admin_id', '=', 'u.id');
        $apbookings_count = $apbookings_count->where('u.id',Auth::user()->id);
        $data = $request->all();
        // $roles = users_roles::where('role_id','7')->get();
        // $checks = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
        //   ->select('users.name')->where('role_id','7')
        //  ->get();
        //  dd($checks);
        $admins = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();
        $airports = airport::all();
        $companies_dlist = Company::all();
        $agent=partners::all();

        $show = 0;

        if ($request->has("search")) {
            $name = $request->input("search");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where('referenceNo','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('first_name','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('last_name','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('airports_bookings.email','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('phone_number','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('postal_code','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('deptFlight','LIKE',"%{$name}%");
                $apbookings = $apbookings->orWhere('returnFlight','LIKE',"%{$name}%");
                   
                //$apbookings_count = $apbookings_count->where("referenceNo", $name);
                $apbookings_count = $apbookings_count->where('referenceNo','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('first_name','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('last_name','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('airports_bookings.email','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('phone_number','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('postal_code','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('deptFlight','LIKE',"%{$name}%");
                $apbookings_count = $apbookings_count->orWhere('returnFlight','LIKE',"%{$name}%");
            }
        }

        if ($request->has("filter") && $request->has("start_date") && $request->has("end_date")) {

            $name = $request->input("filter");
           // dd($name);
            if ($name != "" && $name != "all") {
                // echo "start=".$request->input("start_date");
                // echo "<br>end=".$request->input("end_date");
                
                 $start_date = date("Y-m-d", strtotime($request->input("start_date")));
                 $end_date = date("Y-m-d", strtotime($request->input("end_date")));
                
                $apbookings->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $apbookings->whereDate('airports_bookings.'.$name, '<=', $end_date);
                  //dd($start_date . ' ' . $end_date);
                //$bookings = $bookings->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);

                $apbookings_count->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $apbookings_count->whereDate('airports_bookings.'.$name, '<=', $end_date);

                //$bookings_count = $bookings_count->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);
            }
        } else {
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "created_at";

            $apbookings->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $apbookings->whereDate('airports_bookings.'.$name, '<=', $end_date);

            $apbookings_count->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $apbookings_count->whereDate('airports_bookings.'.$name, '<=', $end_date);

            Input::merge(['start_date' => date("d-M-Y",strtotime($start_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
        }
        //dd($bookings_count);
        //DB::enableQueryLog();
        if ($request->has("booking_source")) {
            $name = $request->input("booking_source");
            if ($name != "" && $name != "all") {
                //  dd($name);

				// if ($role_nam == "Marketing" && $name=='paid') 
				// {

				// 	$bookings = $bookings->where("traffic_src",'=', 'PPC');
				// 	$bookings = $bookings->orWhere("traffic_src", 'BING');
				// 	$bookings = $bookings->orWhere("traffic_src", 'ORG');
				// 	$bookings = $bookings->orWhere("traffic_src", 'EM');
				// 	$bookings = $bookings->orWhere("traffic_src", 'POR');
				// 	$bookings_count = $bookings_count->where("traffic_src",'=', 'PPC');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'BING');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'ORG');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'EM');
				// 	$bookings_count = $bookings_count->orWhere("traffic_src", 'POR');
					
				// }
				// else
				// {
					$apbookings = $apbookings->where("traffic_src", $name);
                	$apbookings_count = $apbookings_count->where("traffic_src", $name);
				// }
            }		
        }

        if ($request->has("no_of_days")) {
            $name = $request->input("no_of_days");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("no_of_days", $name);
                $apbookings_count = $apbookings_count->where("no_of_days", $name);
            }
        }
        
        if ($request->has("agentID")) {
            $name = $request->input("agentID");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("agentID", $name);
                $apbookings_count = $apbookings_count->where("agentID", $name);
            }
        }

        if ($request->has("airport") ) {
            $name = $request->input("airport");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("airportID", $name);
                $apbookings_count = $apbookings_count->where("airportID", $name);
            }
        }
        if ($request->has("companies") ) {
            $name = $request->input("companies");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("companyId", $name);
                $apbookings_count = $apbookings_count->where("companyId", $name);
            }
        }

        if ($request->has("admins")) {
            $name = $request->input("admins");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("c.admin_id", $name);
                $apbookings_count = $apbookings_count->where("c.admin_id", $name);
            }
        }

        if ($request->has("payment")) {
            $name = $request->input("payment");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("payment_method", $name);
                $apbookings_count = $apbookings_count->where("payment_method", $name);
            }
        }
        if ($request->has("status")) {
            $name = $request->input("status");
            if ($name != "" && $name != "all") {
                $apbookings = $apbookings->where("booking_status", $name);
                $apbookings_count = $apbookings_count->where("booking_status", $name);
            }
        }


        //$apbookings = $apbookings->orderBy('id', 'DESC')->get();
        $apbookings = $apbookings->whereIn('traffic_src', $user_role_details);
        // dd($user_role_details);
        $apbookings = $apbookings->orderBy('id', 'DESC');
        
        $apbookings_count = $apbookings_count->orderBy('id', 'DESC')->get();

        //$sql = str_replace_array('?', $apbookings->getBindings(), $apbookings->toSql()); 
        //dd($sql);
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        echo "<pre>";print_r($lastQuery);exit;*/

        $apbookings = $apbookings->paginate(20);
        
        
        
        
        
        
        
        
        
        
        

        $sourceList = array("all"=>"All",
        "paid"=>"Paid",
        "ORG"=>"Organic",
        "PPC"=>"PPC",
        "BING"=>"BING",
        "EM"=>"E Marketing",
        "POR"=>"POR",
        "FB"=>"FaceBook",
        "Ln"=>"LinkedIn",
        "In"=>"Instagram",
        "G+"=>"Google+",
        "Pi"=>"Pinterest",
        "Tw"=>"Twitter",
        "Yt"=>"Youtube",
        "Blg"=>"Blogging",
        "BL"=>"BL",
        "BK"=>"Backend");

       // dd($bookings);

        if ($role_nam == "Operations" || $role_nam == "Sales") {

            return view("admin.booking.booking_list", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings, "bookings_count" => $bookings_count, "role_name" => $role_nam,'sourceList'=>$sourceList,'user_role_details'=>$user_role_details, 'books' => $books ,'adminName' => $adminName ]);
        } 
        else if($role_nam == "airport_parking"){
            return view("admin.booking.booking_list_agents", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $apbookings, "bookings_count" => $bookings_count, "role_name" => $role_nam,'sourceList'=>$sourceList,'user_role_details'=>$user_role_details, 'books' => $books , 'adminName' => $adminName]);
        }else {
            return view("admin.booking.booking_list", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings, "bookings_count" => $bookings_count, "role_name" => $role_nam,'sourceList'=>$sourceList,'user_role_details'=>$user_role_details, 'books' => $books , 'adminName' => $adminName]);
        }
        //   return view("admin.booking.booking_list", ["bookings" => $bookings]);


    }
    public function agent_bookings(Request $request)
    {
        $adminName = '';
        $aid = Auth::user()->id;
        $ads = DB::select("Select name from users where id = '$aid'");
        $adminName = $ads[0]->name;
       
        $role_nam = users_roles::get_user_role(Auth::user()->id)->name;

        $user_role_details = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        if($user_role_details){
            $user_role_details =explode(",",$user_role_details["bk_source"]);
        }
        // dd($request->all());

        set_time_limit(0);
        $bookings = new airports_bookings();
        $bookings =  $bookings->select("c.aph_id","c.admin_id","c.name as company_name","airports_bookings.*");
        $bookings = $bookings->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
         
        $bookings_count = new airports_bookings();
        $bookings_count =  $bookings_count->select("airports_bookings.*");
        $bookings_count = $bookings_count->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $data = $request->all();
       
        $admins = User::all();
        $airports = airport::all();
        $companies_dlist = Company::where('admin_id', Auth::user()->id)->get();
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
                $bookings = $bookings->orWhere('deptFlight','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('returnFlight','LIKE',"%{$name}%");

                //$bookings_count = $bookings_count->where("referenceNo", $name);
                $bookings_count = $bookings_count->where('referenceNo','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('first_name','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('last_name','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('email','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('phone_number','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('postal_code','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('deptFlight','LIKE',"%{$name}%");
                $bookings_count = $bookings_count->orWhere('returnFlight','LIKE',"%{$name}%");
            }
        }

        if ($request->has("filter") && $request->has("start_date") && $request->has("end_date")) {

            $name = $request->input("filter");
           // dd($name);
            if ($name != "" && $name != "all") {
                // echo "start=".$request->input("start_date");
                // echo "<br>end=".$request->input("end_date");
                
                 $start_date = date("Y-m-d", strtotime($request->input("start_date")));
                 $end_date = date("Y-m-d", strtotime($request->input("end_date")));
                
                $bookings->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $bookings->whereDate('airports_bookings.'.$name, '<=', $end_date);

                //$bookings = $bookings->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);

                $bookings_count->whereDate('airports_bookings.'.$name, '>=', $start_date);
                $bookings_count->whereDate('airports_bookings.'.$name, '<=', $end_date);

                //$bookings_count = $bookings_count->whereBetween(DB::raw('DATE_FORMAT(airports_bookings.' . $name . ', "%Y-%m-%d")'), ['"'.$start_date.'"', '"'.$end_date.'"']);
            }
        } else {
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "created_at";

            $bookings->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $bookings->whereDate('airports_bookings.'.$name, '<=', $end_date);

            $bookings_count->whereDate('airports_bookings.'.$name, '>=', $start_date);
            $bookings_count->whereDate('airports_bookings.'.$name, '<=', $end_date);

            Input::merge(['start_date' => date("d-M-Y",strtotime($start_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($end_date))]);
        }
        //dd($bookings_count);
        //DB::enableQueryLog();
        

        if ($request->has("no_of_days")) {
            $name = $request->input("no_of_days");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("no_of_days", $name);
                $bookings_count = $bookings_count->where("no_of_days", $name);
            }
        }

        if ($request->has("airport") ) {
            $name = $request->input("airport");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("airportID", $name);
                $bookings_count = $bookings_count->where("airportID", $name);
            }
        }
        if ($request->has("companies") ) {
            $name = $request->input("companies");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("companyId", $name);
                $bookings_count = $bookings_count->where("companyId", $name);
            }
        }


        if ($request->has("status")) {
            $name = $request->input("status");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("booking_status", $name);
                $bookings_count = $bookings_count->where("booking_status", $name);
            }else{
                $bookings = $bookings->where("booking_status",'!=' ,'Abandon');
                $bookings_count = $bookings_count->where("booking_status", '!=' ,'Abandon');
            }
        }
        else{
            $bookings = $bookings->where("booking_status",'!=' ,'Abandon');
            $bookings_count = $bookings_count->where("booking_status", '!=' ,'Abandon');
        }

        $bookings = $bookings->where("c.admin_id", Auth::user()->id);
        $bookings_count = $bookings_count->where("c.admin_id", Auth::user()->id);

        //$bookings = $bookings->orderBy('id', 'DESC')->get();
        //$bookings = $bookings->whereIn('traffic_src', $user_role_details);
        $bookings = $bookings->orderBy('id', 'DESC');
        
        $bookings_count = $bookings_count->orderBy('id', 'DESC')->get();
        // dd($bookings_count);

        //$sql = str_replace_array('?', $bookings->getBindings(), $bookings->toSql()); 
        //dd($sql);
        /*$query = DB::getQueryLog();
        $lastQuery = end($query);
        echo "<pre>";print_r($lastQuery);exit;*/

        $bookings = $bookings->paginate(20);

        return view("admin.booking.booking_list_agents", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings, "bookings_count" => $bookings_count, "role_name" => $role_nam,'user_role_details'=>$user_role_details , "adminName" => $adminName]);
       

    }
    public function overnightreport()
     {
         $bookings = airports_bookings::all();
         $startdate = '2022-09-02';
         $enddate =  date("Y-m-d");
         $datetimestart = new DateTime($startdate);
         $datetimeend = new DateTime($enddate);
         $intervals = $datetimestart->diff($datetimeend);
         $totaldayss = $intervals->format('%a');
         $overnight = '0';
        $temp = '0';
          for($i=0;$i<=$totaldayss; $i++){       
                $nextday = Carbon::parse($startdate)->addDays($i)->format('Y-m-d');
                $result[$i]['$enddate'] = date("d-m-Y");
                $result[$i]['currentdate'] = $nextday;
                $result[$i]['departure'] = airports_bookings::whereDate('departDate',$nextday)->where('booking_status','Completed')->get()->count();
                $result[$i]['arrivals'] = airports_bookings::whereDate('returnDate',$nextday)->where('booking_status','Completed')->get()->count();
                if($result[$i]['departure'] == '0'  && $result[$i]['arrivals'] == '0')
            {
                $overnight = $overnight;
            }
            else
            {
              $overnight =  $result[$i]['departure'] - $result[$i]['arrivals'];
              $overnight = $temp + $overnight;
               
            }
              $temp = $overnight;
              $result[$i]['overnight'] = $overnight;
              
         
          }  
           
           return view('admin.reports.overnightreport',["result"=>$result]);  
       
        //  dd($bookings);
     }
     
    public function daywisereport(Request $request){
        // dd($request->all());
      
        if ($request->has("start_date") && $request->has("end_date")) {         
            $fdate = $request->start_date;
            $tdate = $request->end_date;
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval = $datetime1->diff($datetime2);
            $totaldays = $interval->format('%a');
        } else {
            $now = new DateTime();           
            $fdate = $now->format('01-F-Y');
            $tdate = $now->format('d-F-Y');
            $datetime1 = new DateTime($fdate);
            $datetime2 = new DateTime($tdate);
            $interval = $datetime1->diff($datetime2);
            $totaldays = $interval->format('%a');   
            
            Input::merge(['start_date' => date("d-M-Y",strtotime($fdate))]);
            Input::merge(['end_date' => date("d-M-Y",strtotime($tdate))]);
        }
         $overnight = '0';
             $temp = '0';
        for($i=0;$i<=$totaldays; $i++){            
            $nextday = Carbon::parse($fdate)->addDays($i)->format('Y-m-d');
            $tomorrow = Carbon::parse($fdate)->addDays($i+1)->format('Y-m-d');
            $yesterday=Carbon::parse($fdate)->addDays($i-1)->format('Y-m-d');
            // dd($yesterday);
            $result[$i]['nextday'] = $nextday;
            $result[$i]['departure'] = airports_bookings::whereDate('departDate',$nextday)->where('booking_status','Completed')->get()->count();
            $result[$i]['yest_departure'] = airports_bookings::whereDate('departDate',$yesterday)->where('booking_status','Completed')->get()->count();
            $result[$i]['arrivals'] = airports_bookings::whereDate('returnDate',$nextday)->where('booking_status','Completed')->get()->count();
            $result[$i]['next_arrival'] = airports_bookings::whereDate('returnDate',$tomorrow)->where('booking_status','Completed')->get()->count();
            $result[$i]['yest_arrival'] = airports_bookings::whereDate('returnDate',$yesterday)->where('booking_status','Completed')->get()->count();
            $result[$i]['pre_overnight']=$result[$i]['yest_departure']+$result[$i]['next_arrival']-$result[$i]['yest_arrival'];
            // dd($result[$i]['pre_overnight']);
            $result[$i]['bookings'] = airports_bookings::whereDate('returnDate',$nextday)->where('booking_status','Completed')->get()->count();
            $result[$i]['revenue'] = airports_bookings::whereDate('returnDate',$nextday)->where('booking_status','Completed')->get()->sum('total_amount');
            if($result[$i]['departure'] == '0'  && $result[$i]['arrivals'] == '0')
            {
                $overnight = $overnight;
            }
            else
            {
               $overnight =  $result[$i]['departure'] - $result[$i]['arrivals'];
               $overnight = $temp + $overnight;
               
            }
              $temp = $overnight;
              $result[$i]['overnight'] = $overnight;
              
         }


        // dd($bookings);
        return view('admin.reports.daywisereport',["result"=>$result]);  
    }


    public function incomplete_Booking(Request $request)
    {
        //


        set_time_limit(0);
        $bookings = new airports_bookings();
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
                $bookings = $bookings->orWhere('postal_code','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('deptFlight','LIKE',"%{$name}%");
                $bookings = $bookings->orWhere('returnFlight','LIKE',"%{$name}%");
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
				$name = "created_at";

				$bookings = $bookings->whereDate($name, '>=', $start_date);
				$bookings = $bookings->whereDate($name, '<=', $end_date);
			}
        } else {
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            $name = "created_at";

            //$bookings = $bookings->whereDate($name, '>=', $start_date);
            //$bookings = $bookings->whereDate($name, '<=', $end_date);
        }



        // $bookings = airports_bookings::where("booking_status", "Abandon");
        $bookings = $bookings->where("booking_status", "Abandon");
        $bookings = $bookings->orderBy('id', 'DESC');
        $bookings = $bookings->paginate(20);
        // return view("admin.booking.incomplete_list", ["bookings" => $bookings]);
        return view("admin.booking.incomplete_list", ["companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings]);
    }
    public function dsp()
    {
        //

        return view("admin.dsp.dsp");
    }
    public function dspview()
    {
        //

        return view("admin.dsp.dspview");
    }
    public function airport_commission_report()
    {
        //
        $bookings = airports_bookings::where("booking_status", "Abandon")->paginate(20);
        return view("admin.reports.airport_commission_report", ["bookings" => $bookings]);
    }
//       public function invoice_commission_report()
//    {
//        //
//        $bookings = airports_bookings::where("booking_status", "Abandon")->paginate(20);
//        return view("admin.invoices.invoice_commission_report", ["bookings" => $bookings]);
//    }
    public function company_report()
    {
        //
        $bookings = airports_bookings::where("booking_status", "Abandon")->paginate(20);
        return view("admin.reports.company_report", ["bookings" => $bookings]);
    }

    public function bookinghistroy(Request $request)
    {     


        $bookings = new booking_transaction();
       
       // $bookings = $bookings->leftjoin('booking_transaction as t', 't.orderID', '=', 'booking_transaction.orderID');
        if ($request->has("name")) {
            $name = $request->input("name");
            $bookings = $bookings->where("booking_transaction.referenceNo", $name);
        }

        //  $bookings = airports_bookings::where("booking_status", "Abandon")->paginate(20);
  
       /// $bookings = $bookings->where("booking_transaction.booking_status", "Abandon");
        //$bookings= $bookings->where("booking_transaction.id", "t.id");
        //$bookings= $bookings->where("booking_transaction.id","<", "t.id");
        

        $bookings= $bookings->where("booking_transaction.payment_action","!=", " ");
		$bookings = $bookings->orderBy('id', 'DESC');

        $bookings = $bookings->paginate(20);
                //   dd( $bookings);
        return view("admin.booking.bookinghistroy_list", ["bookings" => $bookings]);
    }

    public function sendEmailBooking(Request $request)
    {
        $id = $request->input("id");
        $cid = $request->input("cid");
        $type = $request->input("type");

        $row = airports_bookings::getSingleRowById($id);
        $company = DB::table('companies')->where('id',$row->companyId)->first();
       
        //$company_data = DB::table('companies')->where('id', $cid)->first();
		$directions =   "<strong>Arrival:</strong><br>".$row->company->arival."<br>".
                        "<strong>Return:</strong><br>".$row->company->return_proc."<br>";
                      
        $template_data = [];
        $template_data["username"] = $row->first_name . " " . $row->last_name;
        $template_data["email"] = $row->email;
        $template_data["telephone"] = $row->phone_number;
        $template_data["carpark"] = "Car Park";
        $template_data["guidence"] = $directions;
        //dd($template_data);
        //$template_data["c_parent"] = "";
        if ($row->company) {
            $template_data["c_parent"] = $row->company->name;
        }
        $template_data["ptype"] = $row->booked_type;

        if ($row->airport) {
            $template_data["airport"] = $row->airport->name;
        }

        if ($row->dterminal) {
            $template_data["terminal"] = $row->dterminal->name;
        }

        if ($row->rterminal) {
            $template_data["rterminal"] = $row->rterminal->name;
        }

        $template_data["company"] = $company->name;
        $template_data["days"] = $row->no_of_days;
        $template_data["start_date"] = $row->departDate;
        $template_data["end_date"] = $row->returnDate;
        $template_data["booktime"] = $row->created_at;
        $template_data["r_flight_no"] = $row->returnFlight;
        $template_data["reg"] = $row->registration;
        $template_data["model"] = $row->model;
        $template_data["make"] = $row->make;
        $template_data["color"] = $row->color;
        $template_data["payment_gatway"] = $row->payment_method;
        $template_data["payment_status"] = "success";
        $template_data["price"] = $row->total_amount;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] = $row->referenceNo;
        $email_send = new EmailController();
        if ($type == "client" || $type == "all") {
             
            $email_send->sendEmail("Update Booking", $row->email, $template_data);
        }
        if ($type == "company" || $type == "all") {
            
            if ($row->company) {
                // dd($row->company->company_email);
                $email_send = new EmailController();
                $emailOne = explode (",", $row->company->company_email); 
                // dd($emailOne);
                foreach($emailOne as $email)
                {
                    
                    $email_send->sendEmail("Add Booking Company", $email, $template_data);
                }
               
               
               
            }
        }

        return "<h2>Email send successfully</h2>";
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

    public function getQuote1(Request $request)
    {


        $messages = [
            'required.dropoffdate' => 'Dropoff Date field is required.',
            'required.pickup_date' => 'Pickup Date field is required.',
            'required.dropoftime' => 'Dropoff Time field is required.',
            'required.pickup_time' => 'Pickup time field is required.',
            'required.airport_id' => 'Airport field is required.'
        ];
        $validatedData = Validator::make(Input::all(), [
            'dropoffdate' => 'required|string|max:255',
            'pickup_date' => 'required|string',
            'dropoftime' => 'required|string',
            'airport_id' => 'required|string',
            'pickup_time' => 'required|string'

        ], $messages);

        if ($validatedData->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json(["success" => 0, 'errors' => $validatedData->errors()]);
        } else {


            $airport_id = $request->input('airport_id');

            $dropdate = $request->input('dropoffdate');
            $pickdate = $request->input('pickup_date');
            $dropoftime = $request->input('dropoftime');
            $pickuptime = $request->input('pickup_time');
            $no_of_days = $request->input('no_of_days') + 1;

            $promo = $request->input('promo');


            $html = '';
            $i = 1;
            $j = 1;
            $inactive = 0;
            $inactiv = 0;

            $selected_date = strtotime($dropdate);
            $year = date('Y', $selected_date);
            $month = date('n', $selected_date);
            $day = date('j', $selected_date);


            $dropofdate = date('Y-m-d', strtotime($dropdate));
            $pickupdate = date('Y-m-d', strtotime($pickdate));
            $dStart = new DateTime($dropofdate);
            $dEnd = new DateTime($pickupdate);
            $dDiff = $dStart->diff($dEnd);
            $dDiff->format('%R');
            $no_of_days = $dDiff->days;
            $totals_days = $no_of_days + 1;

            if ($no_of_days > 30) {
                $total_days = '30';
            } else {
                $total_days = $no_of_days;
            }

            // Calculate Days Difference From Now
            $dropdate1 = strtotime($dropdate);
            $c_time = date("Y-m-d");
            $dropdate1 = date("Y-m-d", $dropdate1);
            $datetime1 = new DateTime($c_time);
            $datetime2 = new DateTime($dropdate1);
            $interval = $datetime1->diff($datetime2);
            $diff_date = $interval->format('%R%a');
            $diff_date = substr($diff_date, 1);
            ////////********** END **********///////

            $companyID = $request->input('companyID');
            if($companyID !=''){
                $com_cond =" and fc.id=".$companyID." ";
            }else{
                $com_cond ="";
            }
            

            $query = "SELECT distinct fapp.id,fc.company_code as product_code,a.name as airport_name,a.removed as rmd, fc.opening_time,fc.closing_time,fc.id as companyID,fc.aph_id,fc.name,fc.processtime,fc.awards,fc.featured,fc.recommended,fc.special_features,fc.overview,IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,fc.terms,fc.address,fc.town,fc.post_code,fc.message,fc.extra_charges,fc.parking_type,fc.logo,fc.travel_time,fc.miles_from_airport, fc.cancelable, fc.editable, fc.bookingspace,   fapp.id, fasb.brand_name, fapb.after_30_days, fapp.id as pl_id, IF( fapb.day_" . $total_days . " >0, fapb.day_" . $total_days . "+fapp.extra, 0.00) AS price FROM companies as fc
                left join companies_set_price_plans as fapp on fc.id = fapp.cid
                left join airports as a on fc.airport_id = a.id
                left join companies_set_assign_price_plans  as fasb on fapp.id = fasb.plan_id and fasb.day_no = 'day_" . $total_days . "'
                left join companies_product_prices as fapb on fapb.cid = fc.id and fapb.brand_name = fasb.brand_name
                WHERE is_active = 'Yes' and fc.removed != 'Yes'  and airport_id = '" . $airport_id . "' and fapp.cmp_month = '" . $month . "'  and fapp.cmp_year = '" . $year . "' ".$com_cond."
                ";
//echo $query; die();

            $companies = DB::select(DB::raw($query));
               //////////////////////////////////////////////
        //////////api working start//////////////////
        /////////////////////////////////////////////


        $apiairports = airport::all()->where("id", $airport_id)->toArray();

       foreach ($apiairports as $apiairport) {
           $airport_name = $apiairport['name'];
           $airport_code = $apiairport['iata_code'];
           $airport_post_code = $apiairport['post_code'];
           $airport_address = $apiairport['address'];
           $airport_town = $apiairport['city'];
       }
       $ArrivalDate = date('dMy', strtotime($dropdate));
       $DepartDate = date('dMy', strtotime($pickdate));
       $ArrivalTime = date("Hi", strtotime($dropoftime));
       $DepartTime = date("Hi", strtotime($pickuptime));


    $xml = '<API_Request
     System="APH"
     Version="1.0"
     Product="CarPark"
     Customer="X"
     Session="000000003"
     RequestCode="11">
     <Agent>
     <ABTANumber>'.config('app.ABTANumber').'</ABTANumber>
     <Password>'.config('app.Password').'</Password>
     <Initials>'.config('app.Initials').'</Initials>
     </Agent>
     <Itinerary>
     <ArrivalDate>' . $ArrivalDate . '</ArrivalDate>
     <DepartDate>' . $DepartDate . '</DepartDate>
     <ArrivalTime>' . $ArrivalTime . '</ArrivalTime>
     <DepartTime>' . $DepartTime . '</DepartTime>
     <Location>' . $airport_code . '</Location>
     <Terminals>ALL</Terminals>
     </Itinerary>
     </API_Request>';

       $aph_functions = new aph_functions();
       $api = new \api();
       $APHcompanies = @$aph_functions->AphBooking($xml);


       $aph_record = @$api->aph_record($APHcompanies, $airport_id, $search_filter); 
    
       //print_r($aph_record);
		$aph_record = array(); //disable aph booking from admin
       if (count($aph_record) > 0) {    
            if($com_cond ==''){
                $aph_record = json_decode(json_encode($aph_record)); // convert to object
                $companies = array_merge((array) $companies, (array) $aph_record);
            }else{
                $companies =$companies;
            }
       } 

            foreach ($companies as $company) {


                $total_amount = $company->price + $this->_setting["booking_fee"];

                  if(isset($company->aph_id)){ ?>
                    <div class="companies-listing">
                    <span class="companies-list"> <?php echo $company->name; ?><?php echo  $airport_name ; ?> </span>
                    <span class="companies-list-dd">(<?php echo $company->parking_type ?> )</span>
                    <table class="table clisting-table">
                        <tbody>
                        <tr>
                            <th>Booking Price</th>
                            <td>£<?php echo $company->price ?></td>
                            <th>Booking Fee</th>
                            <td>£<?php echo $this->_setting["booking_fee"]; ?></td>

                        </tr>
                        <tr>
                            <th>Add Extra</th>
                            <td>£0.00</td>
                            <th><b>Total Amount</b></th>
                            <td class="price-text">£<?php echo $total_amount; ?></td>
                            <th></th>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <form id="cform<?php echo $company->companyID ?>" method="post">
                             
                             <?php $company->aph_id ?>
                         
                        
                        <input type="hidden" name="company_id" value="<?php echo $company->companyID ?>">
                        <input type="hidden" name="company_name" value="<?php echo $company->name ?>">
                        <input type="hidden" name="parking_type" value="<?php echo $company->parking_type ?>">
                        <input type="hidden" name="booking_price" value="<?php echo $company->price ?>">
                        <input type="hidden" name="discount_price" value="0.00">
                        <input type="hidden" name="cancel_price"
                               value="<?php echo $this->_setting["cancellation_fee"] ?>">
                        <input type="hidden" name="booking_fee" value="<?php echo $this->_setting["booking_fee"]; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_amount; ?>">
                        <input type="hidden" name="discounted_amount" value="0.00">

                        <input type="hidden" name="airport_name" value="<?php echo $airport_name; ?>">
                        <input type="hidden" name="dropoffdate" value="<?php echo $request->input("dropoffdate"); ?>">
                        <input type="hidden" name="pickup_date" value="<?php echo $request->input("pickup_date"); ?>">
                        <input type="hidden" name="pickup_time" value="<?php echo $request->input("pickup_time"); ?>">
                        <input type="hidden" name="dropoftime" value="<?php echo $request->input("dropoftime"); ?>">

                        <input type="hidden" name="airport_id" value="<?php echo $request->input("airport_id"); ?>">
                        <input type="hidden" name="total_days" value="<?php echo $total_days; ?>">


                        <input type="hidden" name="extra_amount" value="0.00">
                         <input type="hidden" name="company_code" value="<?php echo $company->aph_id ?>">

                        <input type="hidden" name="product_code" value="<?php echo $company->product_code ?>">

                        <input type="button" onclick="selectCompany(<?php echo $company->companyID ?>)"
                               class="btn btn-primary"
                               value="Select">
                    </form>
                </div>
                 <?php  }else
                  {
                ?>

                <div class="companies-listing">
                    <span class="companies-list"><?php echo $company->name ?><?php echo $company->airport_name; ?> </span>
                    <span class="companies-list-dd">(<?php echo $company->parking_type ?> )</span>
                    <table class="table clisting-table">
                        <tbody>
                        <tr>
                            <th>Booking Price</th>
                            <td>£<?php echo $company->price ?></td>
                            <th>Booking Fee</th>
                            <td>£<?php echo $this->_setting["booking_fee"]; ?></td>

                        </tr>
                        <tr>
                            <th>Add Extra</th>
                            <td>£0.00</td>
                            <th><b>Total Amount</b></th>
                            <td class="price-text">£<?php echo $total_amount; ?></td>
                            <th></th>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <form id="cform<?php echo $company->companyID ?>" method="post">
                        
                       
                        <input type="hidden" name="company_id" value="<?php echo $company->companyID ?>">
                        <input type="hidden" name="company_name" value="<?php echo $company->name ?>">
                        <input type="hidden" name="parking_type" value="<?php echo $company->parking_type ?>">
                        <input type="hidden" name="booking_price" value="<?php echo $company->price ?>">
                        <input type="hidden" name="discount_price" value="0.00">
                        <input type="hidden" name="cancel_price"
                               value="<?php echo $this->_setting["cancellation_fee"] ?>">
                        <input type="hidden" name="booking_fee" value="<?php echo $this->_setting["booking_fee"]; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_amount; ?>">
                        <input type="hidden" name="discounted_amount" value="0.00">

                        <input type="hidden" name="airport_name" value="<?php echo $company->airport_name; ?>">
                        <input type="hidden" name="dropoffdate" value="<?php echo $request->input("dropoffdate"); ?>">
                        <input type="hidden" name="pickup_date" value="<?php echo $request->input("pickup_date"); ?>">
                        <input type="hidden" name="pickup_time" value="<?php echo $request->input("pickup_time"); ?>">
                        <input type="hidden" name="dropoftime" value="<?php echo $request->input("dropoftime"); ?>">

                        <input type="hidden" name="airport_id" value="<?php echo $request->input("airport_id"); ?>">
                        <input type="hidden" name="total_days" value="<?php echo $total_days; ?>">
                        
                        <input type="hidden" name="product_code" value="<?php echo $company->product_code; ?>">

                        <input type="hidden" name="extra_amount" value="0.00">

                        <input type="button" onclick="selectCompany(<?php echo $company->companyID ?>)"
                               class="btn btn-primary"
                               value="Select">
                    </form>
                </div>

                <?php
            }
             }

            //return response()->json(["success" => 1, 'data' => $companies]);
            //$this->respondCreated('Lesson created successfully');
        }


    }

     public function getQuote(Request $request)
    {


        $messages = [
            'required.dropoffdate' => 'Dropoff Date field is required.',
            'required.pickup_date' => 'Pickup Date field is required.',
            'required.dropoftime' => 'Dropoff Time field is required.',
            'required.pickup_time' => 'Pickup time field is required.',
            'required.airport_id' => 'Airport field is required.'
        ];
        $validatedData = Validator::make(Input::all(), [
            'dropoffdate' => 'required|string|max:255',
            'pickup_date' => 'required|string',
            'dropoftime' => 'required|string',
            'airport_id' => 'required|string',
            'pickup_time' => 'required|string'

        ], $messages);

        if ($validatedData->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json(["success" => 0, 'errors' => $validatedData->errors()]);
        } else {


            $airport_id = $request->input('airport_id');

            $dropdate = $request->input('dropoffdate');
            $pickdate = $request->input('pickup_date');
            $dropoftime = $request->input('dropoftime');
            $pickuptime = $request->input('pickup_time');
            $no_of_days = $request->input('no_of_days') + 1;

            $promo = $request->input('promo');


            $html = '';
            $i = 1;
            $j = 1;
            $inactive = 0;
            $inactiv = 0;

            $selected_date = strtotime($dropdate);
            $year = date('Y', $selected_date);
            $month = date('n', $selected_date);
            $day = date('j', $selected_date);


            $dropofdate = date('Y-m-d', strtotime($dropdate));
            $pickupdate = date('Y-m-d', strtotime($pickdate));
            $dStart = new DateTime($dropofdate);
            $dEnd = new DateTime($pickupdate);
            $dDiff = $dStart->diff($dEnd);
            $dDiff->format('%R');
            $no_of_days = $dDiff->days;
            $totals_days = $no_of_days + 1;

            if ($no_of_days > 30) {
                $total_days = '30';
            } else {
                $total_days = $no_of_days;
            }

            // Calculate Days Difference From Now
            $dropdate1 = strtotime($dropdate);
            $c_time = date("Y-m-d");
            $dropdate1 = date("Y-m-d", $dropdate1);
            $datetime1 = new DateTime($c_time);
            $datetime2 = new DateTime($dropdate1);
            $interval = $datetime1->diff($datetime2);
            $diff_date = $interval->format('%R%a');
            $diff_date = substr($diff_date, 1);
            ////////********** END **********///////


            $query = "SELECT a.name as airport_name,a.removed as rmd, fc.opening_time,fc.closing_time,fc.id as companyID,fc.aph_id,fc.name,fc.processtime,fc.awards,fc.featured,fc.recommended,fc.special_features,fc.overview,IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,fc.terms,fc.address,fc.town,fc.post_code,fc.message,fc.extra_charges,fc.parking_type,fc.logo,fc.travel_time,fc.miles_from_airport, fc.cancelable, fc.editable, fc.bookingspace,   fapp.id, fasb.brand_name, fapb.after_30_days, fapp.id as pl_id, IF( fapb.day_" . $total_days . " >0, fapb.day_" . $total_days . "+fapp.extra, 0.00) AS price FROM companies as fc
                left join companies_set_price_plans as fapp on fc.id = fapp.cid
                left join airports as a on fc.airport_id = a.id
                left join companies_set_assign_price_plans  as fasb on fapp.id = fasb.plan_id and fasb.day_no = 'day_" . $total_days . "'
                left join companies_product_prices as fapb on fapb.cid = fc.id and fapb.brand_name = fasb.brand_name
                WHERE is_active = 'Yes' and fc.removed != 'Yes'  and airport_id = '" . $airport_id . "' and fapp.cmp_month = '" . $month . "'  and fapp.cmp_year = '" . $year . "' 
                ";
//echo $query; die();

            $companies = DB::select(DB::raw($query));
               //////////////////////////////////////////////
        //////////api working start//////////////////
        /////////////////////////////////////////////


        $apiairports = airport::all()->where("id", $airport_id)->toArray();

       foreach ($apiairports as $apiairport) {
           $airport_name = $apiairport['name'];
           $airport_code = $apiairport['iata_code'];
           $airport_post_code = $apiairport['post_code'];
           $airport_address = $apiairport['address'];
           $airport_town = $apiairport['city'];
       }
       $ArrivalDate = date('dMy', strtotime($dropdate));
       $DepartDate = date('dMy', strtotime($pickdate));
       $ArrivalTime = date("Hi", strtotime($dropoftime));
       $DepartTime = date("Hi", strtotime($pickuptime));


    $xml = '<API_Request
     System="APH"
     Version="1.0"
     Product="CarPark"
     Customer="X"
     Session="000000003"
     RequestCode="11">
     <Agent>
     <ABTANumber>'.config('app.ABTANumber').'</ABTANumber>
     <Password>'.config('app.Password').'</Password>
     <Initials>'.config('app.Initials').'</Initials>
     </Agent>
     <Itinerary>
     <ArrivalDate>' . $ArrivalDate . '</ArrivalDate>
     <DepartDate>' . $DepartDate . '</DepartDate>
     <ArrivalTime>' . $ArrivalTime . '</ArrivalTime>
     <DepartTime>' . $DepartTime . '</DepartTime>
     <Location>' . $airport_code . '</Location>
     <Terminals>ALL</Terminals>
     </Itinerary>
     </API_Request>';

       $aph_functions = new aph_functions();
       $api = new \api();
       $APHcompanies = @$aph_functions->AphBooking($xml);


       $aph_record = @$api->aph_record($APHcompanies, $airport_id, $search_filter); 
    
       //print_r($aph_record);

       if (count($aph_record) > 0) {    

            $aph_record = json_decode(json_encode($aph_record)); // convert to object

            $companies = array_merge((array) $companies, (array) $aph_record);
            //print_r($companies);

       } 

            foreach ($companies as $company) {

$discount_amount=0;
$facilities = \App\Company::find($company->companyID)->facilities;
if ($no_of_days > 30000) {
$after30Days = $company->after_30_days;
$booking_price = number_format($company->price, 2, '.', '');
$booking_price = $booking_price + $after30Days * ($no_of_days - 30);
$booking_price = number_format($booking_price, 2, '.', '');
} else {
	$booking_price = number_format($company->price, 2, '.', '');
}
  $booking_fee = $this->_setting["booking_fee"];
  $booking_price = $booking_fee +  $booking_price;

 $parking_total = $booking_price;
/*
if ($promo != '') {

	$promo_verify = \App\discounts::varifyPromoCode($promo);
	if($promo_verify=="Verify"){

		$discount_amount = \App\discounts::getPromoDiscount($promo, $booking_price, $bookingfor,$company->companyID);
		
	   if($booking_price>$discount_amount){
			$booking_price = $booking_price - $discount_amount;
			$booking_price = round($booking_price,2);
		}
	}

}
*/
                $total_amount = $booking_price;

                  if(isset($company->aph_id)){ ?>
                    <div class="companies-listing">
                    <span class="companies-list"> <?php echo $company->name; ?><?php echo  $airport_name ; ?> </span>
                    <span class="companies-list-dd">(<?php echo $company->parking_type ?> )</span>
                    <table class="table clisting-table">
                        <tbody>
                        <tr>
                            <th>Booking Price</th>
                            <td>£<?php echo $company->price ?></td>
                            <th>Booking Fee</th>
                            <td>£<?php echo $this->_setting["booking_fee"]; ?></td>

                        </tr>
                        <tr>
                            <th>Add Extra</th>
                            <td>£0.00</td>
                            <th><b>Total Amount</b></th>
                            <td class="price-text">£<?php echo $total_amount; ?></td>
                            <th></th>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <form id="cform<?php echo $company->companyID ?>" method="post">
                         <style type="text/css">
                           .btn1{
                          
                                background-color: #428BCA!important;
                          border-color: #428BCA;
                           }
                              .btn1:focus{
                          
                                background-color: green!important;
                          border-color: green;
                           }
                       </style>
                        <input type="hidden" name="company_id" value="<?php echo $company->companyID ?>">
                        <input type="hidden" name="company_name" value="<?php echo $company->name ?>">
                        <input type="hidden" name="parking_type" value="<?php echo $company->parking_type ?>">
                        <input type="hidden" name="booking_price" value="<?php echo $company->price ?>">
                        <input type="hidden" name="discount_price" value="0.00">
                        <input type="hidden" name="cancel_price"
                               value="<?php echo $this->_setting["cancellation_fee"] ?>">
                        <input type="hidden" name="booking_fee" value="<?php echo $this->_setting["booking_fee"]; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_amount; ?>">
                        <input type="hidden" name="discounted_amount" value="0.00">

                        <input type="hidden" name="airport_name" value="<?php echo $airport_name; ?>">
                        <input type="hidden" name="dropoffdate" value="<?php echo $request->input("dropoffdate"); ?>">
                        <input type="hidden" name="pickup_date" value="<?php echo $request->input("pickup_date"); ?>">
                        <input type="hidden" name="pickup_time" value="<?php echo $request->input("pickup_time"); ?>">
                        <input type="hidden" name="dropoftime" value="<?php echo $request->input("dropoftime"); ?>">

                        <input type="hidden" name="airport_id" value="<?php echo $request->input("airport_id"); ?>">
                        <input type="hidden" name="total_days" value="<?php echo $total_days; ?>">


                        <input type="hidden" name="extra_amount" value="0.00">
                        <input type="hidden" name="company_code" value="<?php echo $company->aph_id ?>">

                        <input type="hidden" name="product_code" value="<?php echo $company->product_code ?>">

                        <input type="button" onclick="selectCompany(<?php echo $company->companyID ?>)"
                               class="btn btn1"
                               value="Select">
                    </form>
                </div>
                 <?php  }else
                  {
                ?>

                <div class="companies-listing">
                    <span class="companies-list"><?php echo $company->name ?><?php echo $company->airport_name; ?> </span>
                    <span class="companies-list-dd">(<?php echo $company->parking_type ?> )</span>
                    <table class="table clisting-table">
                        <tbody>
                        <tr>
                            <th>Booking Price</th>
                            <td>£<?php echo $company->price ?></td>
                            <th>Booking Fee</th>
                            <td>£<?php echo $this->_setting["booking_fee"]; ?></td>

                        </tr>
                        <tr>
                            <th>Add Extra</th>
                            <td>£0.00</td>
                            <th><b>Total Amount</b></th>
                            <td class="price-text">£<?php echo $total_amount; ?></td>
                            <th></th>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <form id="cform<?php echo $company->companyID ?>" method="post">
                        <input type="hidden" name="company_id" value="<?php echo $company->companyID ?>">
                        <input type="hidden" name="company_name" value="<?php echo $company->name ?>">
                        <input type="hidden" name="parking_type" value="<?php echo $company->parking_type ?>">
                        <input type="hidden" name="booking_price" value="<?php echo $company->price ?>">
                        <input type="hidden" name="discount_price" value="0.00">
                        <input type="hidden" name="cancel_price"
                               value="<?php echo $this->_setting["cancellation_fee"] ?>">
                        <input type="hidden" name="booking_fee" value="<?php echo $this->_setting["booking_fee"]; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_amount; ?>">
                        <input type="hidden" name="discounted_amount" value="0.00">

                        <input type="hidden" name="airport_name" value="<?php echo $company->airport_name; ?>">
                        <input type="hidden" name="dropoffdate" value="<?php echo $request->input("dropoffdate"); ?>">
                        <input type="hidden" name="pickup_date" value="<?php echo $request->input("pickup_date"); ?>">
                        <input type="hidden" name="pickup_time" value="<?php echo $request->input("pickup_time"); ?>">
                        <input type="hidden" name="dropoftime" value="<?php echo $request->input("dropoftime"); ?>">

                        <input type="hidden" name="airport_id" value="<?php echo $request->input("airport_id"); ?>">
                        <input type="hidden" name="total_days" value="<?php echo $total_days; ?>">


                        <input type="hidden" name="extra_amount" value="0.00">

                        <input type="button" onclick="selectCompany(<?php echo $company->companyID ?>)"
                               class="btn btn-primary"
                               value="Select">
                    </form>
                </div>

                <?php
            }
             }

            //return response()->json(["success" => 1, 'data' => $companies]);
            //$this->respondCreated('Lesson created successfully');
        }


    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $booking_detail="";
        $edit = 0;
        $airports = airport::getAll();
        return view("admin.booking.add_booking", ["airports" => $airports, "type" => "add", "settings" => $this->_setting, "edit" => $edit,"booking_detail"=>$booking_detail]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    

        $airport_id = $request->input("airport_id");

        $dropdate = $request->input("dep_date");
        $droptime = $request->input("departure_time");
        $pickdate = $request->input("return_date");
        $picktime = $request->input("return_time");


        $dep_date = date('Y-m-d H:i:s', strtotime($dropdate . " " . $droptime));
        $return_date = date('Y-m-d H:i:s', strtotime($pickdate . " " . $picktime));
        $ArrivalDate = date('dMy', strtotime( $dropdate ));
        $DepartDate = date('dMy', strtotime($pickdate ));
        $ArrivalTime = date("Hi",strtotime($dropdate ));
        $DepartTime = date("Hi",strtotime($picktime ));

      $company_code= $request->input("company_code");
      $product_code = $request->input("product_code");
       

        $promo = $request->input("promo");
        $first_name = $request->input("first_name");
        $title = $request->input("title");

       // dd($title );
        $last_name = $request->input("last_name");
        $email = $request->input("email");
         $phone_number= $contact = $request->input("contact");

        $address = $request->input("address");
        $address2 = $request->input("address2");
        $post_code = $request->input("post_code");
        $town = $request->input("town");
        $terminal=   $departure_terminal = $request->input("departure_terminal");
        $rterminal=   $return_terminal = $request->input("return_terminal");

        $make=$veh_make = $request->input("veh_make");
        $model=  $veh_model = $request->input("veh_model");
        $color= $veh_colour = $request->input("veh_colour");
        $registration  =  $veh_registration = $request->input("veh_registration");
        $transaction_id = $request->input("transaction_id");

        $payment_method = $request->input("payment_method");

        $company_id = $request->input("company_id");
        $parking_type = $request->input("parking_type");
        $discount_amount = $request->input("discount_amount");
        $p_booking_amount = $request->input("p_booking_amount");
        $payment_method = $request->input("payment_method");

        $postalFEE = $request->input("postalFEE");
        $bookingFEE = $request->input("bookingFEE");
        $add_extra = $request->input("add_extra");
        $totalAMOUNT = $request->input("totalAMOUNT");
        $h_totalAMOUNT = $request->input("h_totalAMOUNT");

        $no_of_days = $request->input("no_of_days");
        $smsFEE = $request->input("smsFEE");
        $cancelFEE = $request->input("cancelFEE");
        $passenger = $request->input("passenger");
        $return_flight_no = $request->input("return_flight_no");
        $dept_flight_no = $request->input("dept_flight_no");

        //creating customer
        $pass = $this->randomPassword();
        $data = array();
        $data["title"] = $title;
        $data["first_name"] = $first_name;
        $data["last_name"] = $last_name;
        $data["phone_number"] = $contact;
        $data["password"] = md5($pass);
        $data["address"] = $address;
        $data["address2"] = $address2;
        $data["town"] = $town;
        $data["added_on"] = date("Y-m-d H:i:s");
        $data["update_on"] = date("Y-m-d H:i:s");
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
        $data["companyId"] = $company_id;
        $data["product_code"] = $product_code;
        $data["title"] = $title;
        $data["first_name"] = $first_name;
        $data["last_name"] = $last_name;
        $data["email"] = $email;
        $data["phone_number"] = $contact;
        $data["fulladdress"] = $address;
        $data["address"] = $address;
        $data["address2"] = $address2;
        $data["town"] = $town;
        $data["postal_code"] = $post_code;
        $data["passenger"] = $passenger;
        $data["departDate"] = $dep_date;
        $data["deprTerminal"] = $departure_terminal;
        $data["deptFlight"] = $dept_flight_no;
        $data["returnDate"] = $return_date;
        $data["returnFlight"] = $return_flight_no;
        $data["returnTerminal"] = $return_terminal;
        $data["no_of_days"] = $no_of_days;
        $data["discount_code"] = $promo;
        $data["discount_amount"] = $discount_amount;
        $data["booking_amount"] = $p_booking_amount;
        $data["booking_extra"] = $add_extra;
        $data["smsfee"] = $smsFEE;

        $data["booking_fee"] = $bookingFEE;
        $data["cancelfee"] = $cancelFEE;
        $data["total_amount"] = $totalAMOUNT;
        //$data["leavy_fee"] = $l_fee;
        $data["booked_type"] = $parking_type;

        $data["make"] = $veh_make;
        $data["model"] = $veh_model;
        $data["color"] = $veh_colour;
        $data["registration"] = $veh_registration;
        $data["payment_method"] = $payment_method;
        $data["user_ip"] = $ip;
        $data["booking_status"] = "completed";
        $data["payment_status"] = "success";
       
        // dd($data);

        //if ($referenceNo == '') {
        //$booking = airports_bookings::create($data)->toSql();
        $booking_id = DB::table("airports_bookings")->insertGetId($data);

//                $booking_id = $booking->id;
//        } else {
//            $booking = airports_bookings::where('referenceNo', $referenceNo)->first();
//            if ($booking) {
//                airports_bookings::where("referenceNo", $referenceNo)->update($data);
//                $booking_id = $booking->id;
//            } else {
//                $booking_id = 0;
//            }
//        }
        $bookingref = 'PZ01';
        $bookingref .= date("y") . date("m") . date("d");
        //$bookingref = $bookingref.substr($_bookID, -3);

        $bookingref = $bookingref . $booking_id;
        $data = [];
        $data["referenceNo"] = $bookingref;
        airports_bookings::where("id", $booking_id)->update($data);
        {
            // dd($resp["data"]->getLastResponse()->json);
          {             




                   
        $aph_functions = new aph_functions();

        $xml1 = '<API_Request 
                        System="APH"
                        Version="1.0"
                        Product="CarPark"
                        Customer="X"
                        Session="000000006"
                        RequestCode="5">
                        <Agent>
                            <ABTANumber>'.config('app.ABTANumber').'</ABTANumber>
                            <Password>'.config('app.Password').'</Password>
                            <Initials>'.config('app.Initials').'</Initials>
                        </Agent>
                        <Itinerary>
                            <ArrivalDate>'.$ArrivalDate.'</ArrivalDate>
                            <DepartDate>'.$DepartDate.'</DepartDate>
                            <ArrivalTime>'.$ArrivalTime.'</ArrivalTime>
                            <DepartTime>'.$DepartTime.'</DepartTime>
                            <Duration>'.$no_of_days.'</Duration>
                            <CarParkCode>'.$company_code.'</CarParkCode>
                            <ProductCode>'.$product_code.'</ProductCode>
                            <NumberOfPax>'.$passenger.'</NumberOfPax>
                            <ReturnFlight>'.$dept_flight_no.'</ReturnFlight>
                            <DepTerminal>'.$terminal.'</DepTerminal>
                            <OutFlight></OutFlight>
                            <RetTerminal>'.$rterminal.'</RetTerminal>
                        </Itinerary>
                        <CarDetails>
                            <CarReg>'.$registration.'</CarReg>
                            <CarMake>'.$make.'</CarMake>
                            <CarModel>'.$model.'</CarModel>
                            <CarColour>'.$color.'</CarColour>
                        </CarDetails>
                        <ClientDetails>
                            <Title>'.$title.'</Title>
                            <Initial>'.$first_name.'</Initial>
                            <Surname>'.$last_name.'</Surname>
                            <Telephone1>'.$phone_number.'</Telephone1>
                        </ClientDetails>
                    </API_Request>  ';

                $aphorder = $aph_functions->AphBookingOrder($xml1);
//dd($aphorder);
                    $ext_ref = isset($aphorder['BookingRef']) ? $aphorder['BookingRef'] : '';
                    $aphData['ext_ref'] = $ext_ref;
                    airports_bookings::where("referenceNo", $bookingref)->update($aphData);
                }  
               $row = DB::table('airports_bookings')->where('referenceNo', $bookingref)->first();
        $airport_detail = airport::where("id", $row->airportID)->first();
        $company_data = DB::table('companies')->where('id', $row->companyId)->first();
        $directions = "<strong>Arrival:</strong><br>".$company_data->arival."<br>".
                        "<strong>Return:</strong><br>".$company_data->return_proc."<br>";
                        

       
        $template_data = [];
        $template_data["guidence"] = $directions;
        $template_data["username"] = $row->first_name. " " . $row->last_name;
        $template_data["email"] =  $row->email;
        $template_data["telephone"] = $row->phone_number;
        $template_data["carpark"] = "Car Park";
        $template_data["c_parent"] = "";
        $template_data["ptype"] =$row->booked_type;
        $template_data["airport"] = $airport_detail->name;

        if($row->deprTerminal!="TBA" && $row->deprTerminal!="") {
          //  echo 'test'. ($row->deprTerminal);
            $terminal = airports_terminals::where("id",$row->deprTerminal)->first();
            $template_data["terminal"] = $terminal->name;
        } else {
            $template_data["terminal"] ="TBA";
        }

        if($row->returnTerminal!="TBA" && $row->returnTerminal!="") {
            $terminal = airports_terminals::where("id",$row->returnTerminal)->first();
            $template_data["rterminal"] = $terminal->name;
        } else {
            $template_data["rterminal"] ="TBA";
        }


        $template_data["days"] = $row->no_of_days;
        $template_data["start_date"] = $row->departDate;
        $template_data["end_date"] = $row->returnDate;
        $template_data["booktime"] = date("Y-m-d H:i:s");
        $template_data["r_flight_no"] = $row->returnFlight;
        $template_data["reg"] = $row->registration;
        $template_data["model"] = $row->model;
        $template_data["make"] = $row->make;
        $template_data["color"] = $row->color;
        $template_data["payment_gatway"] =  $payment_method;
        $template_data["payment_status"] = "success";
        $template_data["price"] = $row->total_amount;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] =  $bookingref;
        $template_data["api_ref"] =  $row->ext_ref;
        $template_data["company"] = $company_data->name;

        $email_send = new EmailController();
        //$email_send->sendEmail("Update Booking", $request->input("email"), $template_data);
        $toemails = [$request->input("email"),'bookings@parkingzone.co.uk'];
        $email_send->sendEmail("Add Booking Admin", $toemails, $template_data);

        $email_send->sendEmail("Add Booking Admin",'bookings@parkingzone.co.uk', $template_data);
        
        if(is_null($company_data->aph_id)){ 
            $filePath = $this->create_csv($row->id, 'Next');
            $email_send->sendEmailWithFile("Add Booking Company",$company_data->company_email, $template_data, $filePath);
        }else{
            $email_send->sendEmail("Add Booking Company", $company_data->company_email, $template_data);  
        }
        $functions = new functions();
        $functions->send_sms($contact, $bookingref);
        //$email_send->sendEmail("Add Booking Company", $company_data->company_email, $template_data);  
        return response(["success" => "1"]);

    }
}
  
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showdetail(Request $request)
    {
        $user_permissions = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        
        if($user_permissions){
            $user_permissions =explode(",",$user_permissions["permissions"]);   
        }
        $discount_amount = 0;
        // dd($request->input("id"));
        $id = $request->input("id");
        $row = airports_bookings::getSingleRowById($id);
        $company = Company::where("id",$row->companyId)->first();
        $shareValue = $company->share_percentage;
        $agnetShare = $row->booking_amount * $shareValue /100;
        
        // dd($row->booking_amount. " AND ".$agnetShare);
        $discount= discounts::where("promo",$row->discount_code)->first();
        if(isset($discount->discount_type) && $discount->discount_type=="percent"){
            $sign = "%";
        }else{
            $sign = "GBP";
        }
        if ($row->booking_amount > $row->discount_amount) {
            $discount_amount = ((float)$row->total_amount + (float)$row->discount_amount);
        }
        $company_name = "";
        if ($row->company) {
            $company_name = $row->company->name;
        }
        $airport_name = "";
        if ($row->airport) {
            $airport_name = $row->airport->name;
        }
        $rterminal = "";
        if ($row->rterminal) {
            $rterminal = $row->rterminal->name;
        }
        $dterminal = "";
        if ($row->dterminal) {
            $dterminal = $row->dterminal->name;
        }

        $address = $row->address;
        if ($row->address == "") {
            $address = $row->fulladdress;
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
        <td>Departure Date:</td>
        <td>" . date("Y-m-d", strtotime($row->departDate)) . "</td>
        </tr>
        
        
        <tr>
        <td>Departure Time:</td>
        <td>" . date("H:i:s", strtotime($row->departDate)) . "</td>
        </tr>
        
        
        <tr>
        <td>Return Date:</td>
        <td>" . date("Y-m-d", strtotime($row->returnDate)) . "</td>
        </tr>
        
        <tr>
        <td>Return Time:</td>
        <td>" . date("H:i:s", strtotime($row->returnDate)) . "</td>
        </tr>
        
       
        
        <tr>
        <td>Return Flight:</td>
        <td>" . $row->returnFlight . "</td>
        </tr>
        
        
        <tr>
        <td>Departure Terminal:</td>
        <td>" . $dterminal . "</td>
        </tr>
        
        
        <tr>
        <td>Arrival Terminal:</td>
        <td>" . $rterminal . "</td>
        </tr>
        
        
        <tr>
        <td>Airport:</td>
        <td>" . $airport_name . "</td>
        </tr>
        
        
        <tr>
        <td>Company Name:</td>
        <td>" . $company_name . "</td>
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
        <td>Address:</td>
        <td>" . $address . "</td>
        </tr>
        
        <!--tr>
        <td>Postal:</td>
        <td>" . $row->postal_code . "</td>
        </tr-->
        
        <tr>
        <td>Make:</td>
        <td>" . $row->make . "</td>
        </tr>
        
        <tr>
        <td>Model:</td>
        <td>" . $row->model . "</td>
        </tr>
        
        
        <tr>
        <td>Colour:</td>
        <td>" . $row->color . "</td>
        </tr>
        
        
        <tr>
        <td>Registration No:</td>
        <td>" . $row->registration . "</td>
        </tr>
        
         <tr>
        <td>API  Order No:</td>
        <td>" . $row->ext_ref . "</td>
        </tr>
        <tr>
        <td>Park API:</td>
        <td style='text-transform: uppercase;'>" . $row->park_api . "</td>
        </tr>
        
        
        
        
        
        </table>
        </div>
    </div>";
    if (in_array("Amounts", $user_permissions)){
    $html .="
            <table class=\"table table-bordered responsive\">
			<thead>
			<tr>
			
			
				<th>Booking Amount</th>
				<th>Add Extra BE</th>
				<th>Percentage Extra BD</th>
				<th>Default Booking Share</th>
				<th>Sms Fee</th>
				<th>Booking Fee</th>
				<th>Cancel Fee</th>
				<th>Levy Fee</th>
				<th>Total Amount without Discount</th>
				<th>Total Booking agent share</th>
				<th>Discount Applied</th>
				<th>Discount Amount</th>
				<th>Net Agent Share</th>
				<th>Net Amount Customer Paid</th>
			</tr>
	
			</thead>
			<tbody><tr>
				<td><stron class=\"badge badge-success badge-roundless\">" . $this->priceFormat($row->booking_amount) . "</stron></td>
				<td>" . $this->priceFormat($row->extra_amount) . "</td>
				<td>" . $this->priceFormat($row->booking_extra) . "</td>
				
				<td>" . $this->priceFormat($agnetShare) . "</td> 
				
				<td>" . $this->priceFormat($row->smsfee) . "</td>
				<td>" . $this->priceFormat($row->booking_fee) . "</td>
				<td>" . $this->priceFormat($row->cancelfee) . "</td>
				
				<td>" . $this->priceFormat($row->leavy_fee) . "</td>
				<td>" . $this->priceFormat($row->booking_amount+$row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee+$row->booking_extra) . "</td>
				
				<td>" . $this->priceFormat($row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee+$row->booking_extra+$agnetShare) . "</td>
				
				<td>
				$discount->discount_value $sign
				</td>
				
				<td>" . $this->priceFormat($row->discount_amount) . "</td>
				
				
				<td>" . $this->priceFormat(($row->booking_fee+$row->smsfee+$row->cancelfee+$row->extra_amount+$row->leavy_fee+$row->booking_extra+$agnetShare)-$row->discount_amount) . "</td>
				<td><stron class=\"badge badge-success badge-roundless\">" . $this->priceFormat($row->total_amount) . "</stron></td>
			</tr>
			</tbody><tbody>
			</tbody></table>";
			}
			$html .="
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
    public function cancelForm(Request $request)
    {

        //
        $id = $request->input("id");
        $row = airports_bookings::getSingleRowById($id);

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
                   url:'" . route('cancelFormAction') . "',
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
    public function refundForm(Request $request)
    {
        //
        $id = $request->input("id");
        $row = airports_bookings::getSingleRowById($id);
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
                    url:'" . route('cancelFormAction') . "',
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
    public function cancelFormAction(Request $request)
    {

        $id = $request->input("id");

        $already_booking_refunds = 0;
        $refund_trans = booking_transaction::all()->where("orderID", $id);


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




    public function cancelNotShow($id)
    {

        //$id = $request->get("id");
        //dd($id);
        $amt = 'full';
        $reason = 00;
        $email_to = array('company'=>'company', 'admin'=>'n_admin');
        $this->refunds($id,'','','','cancel',$amt,$reason,"",$email_to);
        return redirect("admin/booking/")->with("success", "Booking Cancelled Successfully");

        //return back();

    }

    //Refund
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
        $booking = airports_bookings::where("id", $id)->first();


        $companyID = $booking->companyId;
        //$company = "companyId = '" . $companyID . "'";

        $status = $mode == 'cancel' ? 'Cancel Booking' : 'Refund';
        $cstatus = $mode == 'cancel' ? 'Cancel Booking Company' : 'Refund';
        $getstatus = 1;


        // }

//        if ($type == 'hotels') {
//            $booking = $db->get_row("SELECT * FROM " . $db->prefix . "hotel_bookings WHERE id='" . $id . "' ");
//            $btbl = $db->prefix . "hotel_bookings";
//            $tbl = $db->prefix . "hotel_transaction";
//            $company = "hotelID = '" . $booking['hotel_id'] . "'";
//            $companyID = $booking['hotel_id'];
//            $status = 'Cancel Hotel Booking';
//            $cstatus = 'Cancel Hotel Booking Company';
//            $getstatus = 1;
//            $booking_action = "";
//        }
//
//        if ($type == 'hotelsparking') {
//            $booking = $db->get_row("SELECT * FROM " . $db->prefix . "hotel_parking_booking WHERE id='" . $id . "' ");
//            $btbl = $db->prefix . "hotel_parking_booking";
//            $tbl = $db->prefix . "hotel_parking_transaction";
//            $company = "hotelID = '" . $booking['hotel_id'] . "'";
//            $companyID = $booking['hotel_id'];
//            $status = 'Cancel Hotel Parking Booking';
//            $cstatus = 'Cancel Hotel Parking Booking Company';
//            $getstatus = 1;
//            $booking_action = "";
//        }
//
//        if ($type == 'lounges') {
//            $booking = $db->get_row("SELECT * FROM " . $db->prefix . "lounges_bookings WHERE id='" . $id . "' ");
//            $btbl = $db->prefix . "lounges_bookings";
//            $tbl = $db->prefix . "lounges_transaction";
//            $company = "loungeID = '" . $booking['lounge_id'] . "'";
//            $companyID = $booking['lounge_id'];
//            $status = 'Cancel Lounges Booking';
//            $cstatus = 'Cancel Lounges Booking Company';
//            $booking_action = $mode == 'cancel' ? ", booking_action = 'Cancelled'" : ", booking_action = 'Refund'";
//            if ($mode == "cancel") {
//                if ($booking['referenceNo_ext'] != "") {
//                    $getstatus = get_cancel_status($booking['referenceNo_ext']);
//                } else {
//                    $getstatus = "Cancel Not availble..!!";
//                }
//            } else {
//                $getstatus = 1;
//            }
//
//        }

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
                    $data["companyId"] = $booking->companyId;
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

                    $transaction = DB::table('booking_transaction')->insertGetId($data);


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


                            $inserted = DB::table('penalty_details')->insert($d);


//                        $inserted = $db->insert("INSERT INTO " . $db->prefix . "penalty_details set
//
//				trans_id = '" . $trans_id . "',
//				payment_medium = '" . $payment_medium[$i] . "',
//				penalty_amount = '" . $penalty . "',
//				penalty_to = '" . $palenty_to[$i] . "'
//				");
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
                        $dd["payment_status"] = "Refund";
                        if ($mode == 'cancel') {
                            $dd["booking_action"] = "Cancelled";
                        }


                        if ($reason == '00') {
                            $dd["booking_action"] = "Noshow";
                        }


                        $query = DB::table('airports_bookings')
                            ->where("id", $id)
                            ->update($dd);



                        $query = 1;
                        if ($query) {
                            $company_email = is_numeric($companyID) ? 'company' : 'pakingzone@gmail.com';


                            $bookData = airports_bookings::getSingleRowById($id);

                            $template_data = [];
                            $template_data["username"] = $bookData->first_name . " " . $bookData->last_name;

                            $template_data["airport"] = $bookData->airport->name;
                            if ($bookData->dterminal) {
                                $template_data["terminal"] = $bookData->dterminal->name;
                            }
                            if ($bookData->rterminal) {
                                $template_data["rterminal"] = $bookData->rterminal->name;
                            }
							if ($bookData->company) {
								$template_data["company"] = $bookData->company->name;
							}
                            $template_data["days"] = $bookData->no_of_days;
							$template_data["email"] = $bookData->email;
                            $template_data["start_date"] = $bookData->departDate;
                            $template_data["end_date"] = $bookData->returnDate;
                            $template_data["r_flight_no"] = $bookData->returnFlight;
                            $template_data["reg"] = $bookData->registration;
							$template_data["vehicle"] = $bookData->registration;
                            $template_data["ref"] = $bookData->referenceNo;
                            $template_data["refund"] = $refund;
                            file_put_contents("cancel_email_data.txt",print_r($template_data,true));

                            if ($mode == 'cancel' && !empty($companyemail)) {
                                //notifications($id, $cstatus, $company_email);

                                $email_send = new EmailController();
                                $company_data = DB::table('companies')->where('id', $bookData->companyId)->first();
                                if ($company_data->company_email) {
                                    $company_email = $company_data->company_email;
                                }
                                if(is_null($company_data->aph_id)){ 
									$filePath = $this->create_csv($id, 'Cancel');
									$email_send->sendEmailWithFile($cstatus,$company_email, $template_data, $filePath);
								}
								else{ 
									
									//$email_send->sendEmail("Add Booking Company", $company_data->company_email, $template_data);  
									$email_send->sendEmail($cstatus, $company_email, $template_data);
								}
                                

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
                            if($bookData->park_api == 'global' && $bookData->ext_ref != null){
                                
                                $data['key'] = '8ghzs9qe4252t2eg';
                                $data['sku'] = $bookData->product_code;
                                
                                
                                $data['reference'] = $bookData->referenceNo;
                                $data['status'] = 2;
                                //dd($data);
                                $url = "https://live-api.opitech.co.uk/api-cancel.php";
                                $ch = curl_init($url);
                        		$timeout = 5;
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                                    'agentCode' =>  'PARZ',
                                    'key' =>  'GzJew9QjhzzcOsSdq4u0jpInT5xNQSA0',
                                    'reference' =>  $bookData->ext_ref,
                                ]);
                        		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        		$curlresp = curl_exec($ch);
                        		$curlresp = json_decode($curlresp, true);
                        		curl_close($ch);
                            }
                            
                            if($bookData->park_api == 'Opitech' && $bookData->ext_ref != null){
                                
                                $data['reference'] = $bookData->referenceNo;
                                $url = "https://live-api.opitechdevelopment.com/api-cancel.php";
                                $ch = curl_init($url);
                        		$timeout = 5;
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                                    'agentCode' =>  'PARZ',
                                    'key' =>  'hg563hrfs4J3FyLn6n3UypQo4ZST5zNQJ',
                                    'reference' =>  $bookData->ext_ref,
                                ]);
                        		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        		$curlresp = curl_exec($ch);
                        		$curlresp = json_decode($curlresp, true);
                        		curl_close($ch);
                        		//echo "<pre>"; print_r($resp); echo "</pre>"; exit;
                            }
                            
                            
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


    function refundPayment($alltotal, $OrderID, $transcationID)
    {
        // global $PayzoneHelper;
        require_once(app_path('library/payzone/includes/payzone_gateway_new.php'));

        $IntegrationType = $PayzoneGateway->getIntegrationType();
        $SecretKey = $PayzoneGateway->getSecretKey();
        $HashMethod = $PayzoneGateway->getHashMethod();


        $CurrencyCode = $PayzoneGateway->getCurrencyCode();
        $respobj = array();
        $queryObj = array();
        $queryObj["Amount"] = $alltotal;
        $queryObj["CurrencyCode"] = $CurrencyCode;
        $queryObj["OrderID"] = $OrderID;
        $queryObj["CrossReference"] = $transcationID;
        $queryObj["OrderDescription"] = "Refund order";
        $queryObj["HashMethod"] = $HashMethod;
        $StringToHash = $PayzoneHelper->generateStringToHashDirect($queryObj["Amount"], $CurrencyCode, $queryObj["OrderID"], $queryObj["OrderDescription"], $SecretKey);
        $ShoppingCartHashDigest = $PayzoneHelper->calculateHashDigest($StringToHash, $SecretKey, $HashMethod);
        $ShoppingCartValidation = $PayzoneHelper->checkIntegrityOfIncomingVariablesDirect("SHOPPING_CART_CHECKOUT", $queryObj, $ShoppingCartHashDigest, $SecretKey);
        $respobj["ShoppingCartHashDigest"] = $ShoppingCartHashDigest;
        if ($ShoppingCartValidation) {
            $queryObj = $PayzoneGateway->buildXHRefund($queryObj["Amount"], $queryObj["OrderID"], $queryObj["CrossReference"]);
            //Process the transaction

            //require_once(__DIR__ . "/includes/gateway/refund_process.php");
            require_once(app_path('library/payzone/includes//gateway/refund_process.php'));

            return $paymentResponse;
            // return json_encode($paymentResponse);//pass the response object back to the JS handler to process
        } else {
            $paymentResponse["Message"] = 'Hash mismatch validation failure';
            $paymentResponse["ErrorMessages"] = true;
            $paymentResponse["StatusCode"] = 100;
            //return json_encode($paymentResponse);
            return $paymentResponse;
        }

    }


    function transferBookingForm($id)
    {

        $html = "<table class=\"table table-bordered responsive\">
	<tbody><tr>
	<td>
	<select name=\"company_id\" id='company_id_pop' class=\"form-control company-bookings\">";

        $companies = Company::all();

        // dd($companies);
        foreach ($companies as $company) {

            $airport = "";
            if ($company->airport) {
                $airport = $company->airport->name;
            }

            $html .= "<option value=\"$company->id\"> $company->name &amp;  --- " . $airport . " </option>";
        }

        $html .= "</select></td></tr><tr><td>
	<input class=\"btn btn-info\"onclick='transferSubmit($id)' name=\"booking_transfer\" value=\"Transfer\"></td></tr></tbody></table>";
        return $html;
    }


    function transferBooking(Request $request)
    {

        $id = $request->input("id");
        $new_cid = $request->input("new_cid");

        $row = airports_bookings::getSingleRowById($id);

        $company_email = "developmentfive1@gmail.com";
        if ($row->company) {
            $company_email = $row->company->company_email;
        }


        $template_data = [];
        $company_data = DB::table('companies')->where('id', $new_cid)->first();
		$overview = $company_data->overview."<br>";
		$companyemail = $company_data->company_email;
		$directions = "<strong>Arrival:</strong><br>".$company_data->arival."<br>".
						"<strong>Return:</strong><br>".$company_data->return_proc."<br>";
				
        $template_data["username"] = $row->first_name . " " . $row->last_name;
        $template_data["ref"] = $row->referenceNo;
        if ($row->company) {
            $template_data["company"] = $row->company->name;
        }


        if ($row->airport) {
            $template_data["airport"] = $row->airport->name;
        }


        $template_data["days"] = $row->no_of_days;
        $template_data["start_date"] = $row->departDate;
        $template_data["end_date"] = $row->returnDate;

        $template_data["r_flight_no"] = $row->returnFlight;
        if ($row->rterminal) {
            $template_data["terminal"] = $row->rterminal->name;
        }
        $template_data["vehicle"] = $row->registration;


        $email_send = new EmailController();
        $email_send->sendEmail("Cancel Booking Company", $company_email, $template_data);


        $d = [];
        $d["companyId"] = $new_cid;
        DB::table('airports_bookings')->where("id", $id)->update($d);


        $template_data = [];
        $template_data["username"] = $row->first_name . " " . $row->last_name;
        $template_data["ref"] = $row->referenceNo;
        if ($row->company) {
            $template_data["company"] = $row->company->name;
        }


        if ($row->airport) {
            $template_data["airport"] = $row->airport->name;
        }


        $template_data["days"] = $row->no_of_days;
        $template_data["carpark"] = "Car Park";
        $template_data["payment_status"] = $row->payment_status;
        $template_data["ptype"] = $row->booked_type;
        $template_data["start_date"] = $row->departDate;
        $template_data["end_date"] = $row->returnDate;
        $template_data["email"] = $row->email;
        $template_data["telephone"] = $row->phone_number;
        $template_data["booktime"] = $row->created_at;
        $template_data["payment_gatway"] = $row->payment_method;
        $template_data["price"] = $row->total_amount;


        $template_data["r_flight_no"] = $row->returnFlight;
        if ($row->rterminal) {
            $template_data["rterminal"] = $row->rterminal->name;
        }
        if ($row->dterminal) {
            $template_data["terminal"] = $row->dterminal->name;
        }
        $template_data["reg"] = $row->registration;
        $template_data["make"] = $row->make;
        $template_data["model"] = $row->model;
        $template_data["color"] = $row->color;
        $template_data["bprice"] = $row->booking_amount;
        $template_data["guidence"] = $overview.' '.$directions;

        $new_company_email = Company::where("id", $new_cid)->first();

//dd($new_company_email);
        $email_send = new EmailController();
        $email_send->sendEmail("Add Booking Company", $new_company_email->company_email, $template_data);


        $template_data["c_parent"] = $new_company_email->name;
        $email_send = new EmailController();
        $email_send->sendEmail("client change company", $row->email, $template_data);


        $html = "<h1>Company Successfully Transfer</h1>";


        return $html;
        // notifications($booking_id, 'Cancel Booking Company',$company_email);

//
//        $query = $db->update("UPDATE " . $db->prefix . "booking SET companyId = '".$new_company_id."'  WHERE id = '" . $booking_id . "' ");
//
//
//        $new_company_email = is_numeric($new_company_id) ? 'company' : 'customerservices@aph.com';
//        notifications($booking_id, 'Add Booking Company', $new_company_email);
//
//
//        $return = notifications($booking_id, 'client change company', $company['email']);


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        

        
       
        $booking_detail = airports_bookings::findOrFail($id);
       // dd($booking_detail);
        $edit = 1;
        $airports = airport::All();

        return view("admin.booking.update_booking", ["airports" => $airports, "type" => "edit", "id" => $id, "settings" => $this->_setting, "edit" => $edit,"booking_detail"=>$booking_detail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //


        $airport_id = $request->input("airportid");

        $dropdate = $request->input("dep_date");
        $droptime = $request->input("departure_time");
        $pickdate = $request->input("return_date");
        $picktime = $request->input("return_time");


        $dep_date = date('Y-m-d H:i:s', strtotime($dropdate . " " . $droptime));
        $return_date = date('Y-m-d H:i:s', strtotime($pickdate . " " . $picktime));
		
		
        $fulladdress= $request->input("fulladdress");

        $promo = $request->input("promo");
        $first_name = $request->input("first_name");
        $title = $request->input("title");
        $last_name = $request->input("last_name");
        $email = $request->input("email");
        $contact = $request->input("contact");

        $address = $request->input("address");
        $address2 = $request->input("address2");
        $post_code = $request->input("post_code");
        $town = $request->input("town");
        $departure_terminal = $request->input("departure_terminal");
        $return_terminal = $request->input("return_terminal");

        $veh_make = $request->input("veh_make");
        $veh_model = $request->input("veh_model");
        $veh_colour = $request->input("veh_colour");
        $veh_registration = $request->input("veh_registration");
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

        $no_of_days = $request->input("no_of_days");
		
		//arif, add total number of days dynamically
		$new_dropofdate = date('Y-m-d', strtotime($dropdate));
        $new_pickupdate = date('Y-m-d', strtotime($pickdate));
        $dStart = new DateTime($new_dropofdate);
        $dEnd = new DateTime($new_pickupdate);
        $dDiff = $dStart->diff($dEnd);
        $dDiff->format('%R');
        $no_of_days = $dDiff->days;
        $no_of_days = $no_of_days + 1;
		//end arif code
		
        $smsFEE = $request->input("smsFEE");
        $cancelFEE = $request->input("cancelFEE");
        $passenger = $request->input("passenger");
        $return_flight_no = $request->input("return_flight_no");
        $dept_flight_no = $request->input("dept_flight_no");

//creating customer
        $pass = $this->randomPassword();
        $data = array();
        $data["title"] = $title;
        $data["first_name"] = $first_name;
        $data["last_name"] = $last_name;
        $data["phone_number"] = $contact;
        $data["password"] = md5($pass);
        $data["address"] = $address;
        $data["address2"] = $address2;
        $data["town"] = $town;
        
        $data["added_on"] = date("Y-m-d H:i:s");
        $data["update_on"] = date("Y-m-d H:i:s");
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
        $data["fulladdress"] = $fulladdress;
        $data["address"] = $address;
        $data["address2"] = $address2;
        $data["town"] = $town;
        $data["postal_code"] = $post_code;
        $data["passenger"] = $passenger;
        $data["departDate"] = $dep_date;

        $data["deprTerminal"] = $departure_terminal;
        $data["deptFlight"] = $dept_flight_no;
        $data["returnDate"] = $return_date;
        $data["returnFlight"] = $return_flight_no;
        $data["returnTerminal"] = $return_terminal;
        $data["no_of_days"] = $no_of_days;
        $data["discount_code"] = $promo;
        $data["discount_amount"] = $discount_amount;
        $data["booking_amount"] = $p_booking_amount;
        $data["booking_extra"] = $add_extra;
        $data["smsfee"] = $smsFEE;

        $data["booking_fee"] = $bookingFEE;
        $data["cancelfee"] = $cancelFEE;
        $data["total_amount"] = $totalAMOUNT;
        //$data["leavy_fee"] = $l_fee;
        $data["booked_type"] = $parking_type;

        $data["make"] = $veh_make;
        $data["model"] = $veh_model;
        $data["color"] = $veh_colour;
        $data["registration"] = $veh_registration;
        //$data["traffic_src"]=$_traffic_src;
        $data["user_ip"] = $ip;
        $data["booking_status"] = "completed";

        //dd($data);
  



        airports_bookings::where("id", $id)->update($data);
        $row = DB::table('airports_bookings')->where("id", $id)->first();

        $airport_detail = airport::where("id", $row->airportID)->first();
        $company_data = DB::table('companies')->where('id', $row->companyId)->first();
        $directions ='';
        if(!empty($company_data)){
            $directions = "<strong>Arrival:</strong><br>".$company_data->arival."<br><strong>Return:</strong><br>".$company_data->return_proc."<br>";
        }
       
        $template_data = [];
        $template_data["guidence"] = $directions;
        $template_data["username"] = $row->first_name. " " . $row->last_name;
        $template_data["email"] =  $row->email;
        $template_data["telephone"] = $row->phone_number;
        $template_data["carpark"] = "Car Park";
        $template_data["c_parent"] = $company_data->name;
		$template_data["company"] = $company_data->name;
        $template_data["ptype"] = $row->booked_type;
        $template_data["airport"] = $airport_detail->name;

        if($row->deprTerminal!="TBA" && $row->deprTerminal!="") {
			$terminal = airports_terminals::where("id",$row->deprTerminal)->first();
             $template_data["terminal"] = $terminal->name;
        } else {
            $template_data["terminal"] ="TBA";
        }

        if($row->returnTerminal!="TBA" && $row->returnTerminal!="") {
            $terminal = airports_terminals::where("id",$row->returnTerminal)->first();
            $template_data["rterminal"] = $terminal->name;
        } else {
            $template_data["rterminal"] ="TBA";
        }


        $template_data["days"] = $row->no_of_days;
        $template_data["start_date"] = $row->departDate;
        $template_data["end_date"] = $row->returnDate;
        $template_data["booktime"] = date("Y-m-d H:i:s");
        $template_data["r_flight_no"] = $row->returnFlight;
        $template_data["reg"] = $row->registration;
        $template_data["model"] = $row->model;
        $template_data["make"] = $row->make;
        $template_data["color"] = $row->color;
        $template_data["payment_gatway"] =  $payment_method;
        $template_data["payment_status"] = "success";
        $template_data["price"] = $row->total_amount;
        $template_data["addtionalprice"] = 0;
        $template_data["ref"] =  $row->referenceNo;
        $template_data["api_ref"] =  $row->ext_ref;
		file_put_contents("amend_email_data.txt",print_r($template_data,true));
        $email_send = new EmailController();
        //$email_send->sendEmail("Update Booking", $request->input("email"), $template_data);
        $toemails = [$request->input("email"),'bookings@parkingzone.co.uk'];
        //$email_send->sendEmail("Update Booking", $toemails, $template_data);
		
		if(is_null($company_data->aph_id)){ 
            $filePath = $this->create_csv($id, 'Amend');
            //$email_send->sendEmailWithFile("Update Booking Company",$company_data->company_email, $template_data, $filePath);
        }
        else{ 
            
            //$email_send->sendEmail("Update Booking Company", $company_data->company_email, $template_data);  
        }
        if($row->park_api == 'global'){
            $globalorder = $this->updateOnGlobal($row);
        }

        return redirect("admin/booking/edit/".$id)->with("success", "Booking updated Successfully");
       // return back();


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of agents.
     *
     * @return \Illuminate\Http\Response
     */
    public function agentbooking(Request $request)
    {
        //
        $role_nam = users_roles::get_user_role(Auth::user()->id)->name;

        set_time_limit(0);
        $bookings = new airports_bookings();
        $bookings =  $bookings->select("airports_bookings.*")->addSelect("p.alias")->where("booking_status", "Completed");
        $bookings = $bookings->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $bookings = $bookings->leftJoin('partners as p', 'airports_bookings.agentID', '=', 'p.id');

        $bookings_count = new airports_bookings();
        $bookings_count =  $bookings_count->select("airports_bookings.*")->where("booking_status", "Completed");
        $bookings_count = $bookings_count->leftJoin('companies as c', 'airports_bookings.companyId', '=', 'c.id');
        $data = $request->all();
        $admins = User::all();
        $airports = airport::all();
        $companies_dlist = Company::all();
        $agent=partners::all();

        $show = 0;

        
        if ($request->has("agentID")) {
            $name = $request->input("agentID");
            if ($name != "" && $name != "all") {
                $bookings = $bookings->where("agentID", $name);
                $bookings_count = $bookings_count->where("agentID", $name);
            }
        }

        


        $bookings = $bookings->orderBy('id', 'DESC');
        $bookings_count = $bookings_count->orderBy('id', 'DESC')->get();


        $bookings = $bookings->paginate(20);


        if ($role_nam == "Operations" || $role_nam == "Sales") {

            //return view("admin.booking.booking_list_operations", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings, "bookings_count" => $bookings_count, "role_name" => $role_nam]);
        } else {
            return view("admin.booking.agent_booking_list", ["companies_dlist" => $companies_dlist,"agent" => $agent, "airports" => $airports, "admins" => $admins, "show" => $show, "bookings" => $bookings, "bookings_count" => $bookings_count, "role_name" => $role_nam]);
        }
        //   return view("admin.booking.booking_list", ["bookings" => $bookings]);


    }
	
	    public function create_csv($token, $status)
		{   
		 $query = "select 
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
                    WHERE b.id =".$token;
 
            $results = DB::select(DB::raw($query));
		   
			if($results>0){
				$datenow = date("dmYhms");
				$name = "FPP_$datenow.csv";
				$csvpath = public_path('csv/');
				$filepath = $csvpath.$name;
				$outstream = fopen($filepath, "w");   
				if($status != ""){
				 	$results[0]->BookingStatus = 'BookingStatus';	
				}				
				  fputcsv($outstream, array_keys((array) $results[0]));

				foreach($results as $result)
				{
					if($status != ""){
				       	$result->BookingStatus = $status;
					}
					fputcsv($outstream, (array) $result);
				}

			   // fclose($outstream);
				rewind($outstream);
				fclose($outstream);
			}
			return $filepath;
	}
    
    public function updateOnGlobal($booking_arr) {
        //dd($booking_arr);
        $reference_no = $booking_arr->referenceNo;
        
        
		$product_code = $booking_arr->product_code;
        $passenger = $booking_arr->passenger;

        $dropdate = $booking_arr->departDate; // drop/arrival date on car park

        $pickdate = $booking_arr->returnDate; // pick/depart date from car park
        
        $dropdate = date('Y-m-d', strtotime($dropdate));
        $droptime = date('H:i', strtotime($booking_arr->departDate));
        
        $pickdate = date('Y-m-d', strtotime($pickdate));
        $picktime = date('H:i', strtotime($booking_arr->returnDate));
        
        $no_of_days = $booking_arr->no_of_days;
        
        $returnFlight = $booking_arr->returnFlight;

        $terminal = $booking_arr->deprTerminal;
        $rterminal = $booking_arr->returnTerminal;

        if($terminal != "TBA" && $terminal!="") {
            $terminaldb = airports_terminals::where("id",$terminal)->first();
            $terminal = $terminaldb->name;
        }

        if($rterminal != "TBA" && $rterminal !="") {
            $rterminaldb = airports_terminals::where("id",$rterminal)->first();
            $rterminal = $rterminaldb->name;
        }
        
        $data['key'] = 'fsemmmp8kkt78eof';
        $data['sku'] = $product_code;
        /* ======= Vehicle Details ======= */
        $data['payment_type'] = 'stripe';
        $data['amount'] = $booking_arr->booking_amount;
        $data['discount'] = $booking_arr->discount_amount;
        $data['extra_charges'] = $booking_arr->extra_amount;
        $data['levy_charges'] = $booking_arr->leavy_fee;
        $data['transaction_id'] = $booking_arr->id;
        
        /* ======= Vehicle Details ======= */
        $data['make'] = $booking_arr->make;
        $data['model'] = $booking_arr->model;
        $data['color'] = $booking_arr->color;
        $data['registration'] = $booking_arr->registration;
        
        /* ======= Customer Details ======= */
        $title = $booking_arr->title;
        $first_name = $booking_arr->first_name;
        $last_name = $booking_arr->last_name;
        
        $data['name'] = $title.' '.$first_name.' '.$last_name;
        $data['email'] = $booking_arr->email;
        $data['contact_no'] = $booking_arr->phone_number;
        
        /* ======= Booking Details ======= */
        
        
        $data['reference'] = $reference_no;
        $data['status'] = 1;
        
        $data['departure_date'] = $dropdate;
        $data['departure_time'] = $droptime;
        $data['departure_terminal'] = $terminal;
        $data['departure_flight_no'] = '';
        
        $data['arrival_date'] = $pickdate;
        $data['arrival_time'] = $picktime;
        $data['arrival_terminal'] = $rterminal;
        $data['arrival_flight_no'] = $returnFlight;
        $data['num_people'] = $passenger;
    	//echo "<pre>"; print_r($data); echo "</pre>"; exit;
        $url = "https://globalparkingmanagement.co.uk/api/bookings/supplierUpdate";
        $ch = curl_init($url);
		$timeout = 5;
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$resp = curl_exec($ch);
		curl_close($ch);
		//echo "<pre>"; print_r($resp); echo "</pre>"; exit;
		return $resp;
		
    }


}
