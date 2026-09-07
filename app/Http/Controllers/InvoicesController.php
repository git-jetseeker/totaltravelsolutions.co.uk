<?php

namespace App\Http\Controllers;

use App\airport;
use App\booking_transaction;
use App\users_roles;
use Illuminate\Support\Facades\Auth;
use App\Company; 
use App\modules_settings;
use App\penalty_details;
use App\airports_terminals;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


class InvoicesController extends Controller
{
    //
    public $_setting = [];

    public function __construct()
    {
        $this->middleware('auth');
        $modules_settings = modules_settings::all();
        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }
    }

    public function searchForm(Request $request)
    {
        // dd($request);
        $show = 0;
        if ($request->input("submit") == "Search") {
            $show = 1;
        }
        

        return view("admin.invoices.search_form", ["show" => $show, "start_date" => $request->input("start_date"), "end_date" => $request->input("end_date") , "filter" => $request->input("filter")]);

    }

    

    public function invoicesDetailInvoice(Request $request)

    {

    // dd($request);

        $_filter = "";

        $b_t_table = " ";

        $filter_search = "";

        $planty_refund = " ";

        $outputs = 0;



        if (!empty($request->get('search')) || !empty($request->get('companies')) || !empty($request->get('filter')) || !empty($request->get('start_date')) || !empty($request->get('end_date'))) {





            $_refund_via_s = "";

            $palenty_to = "";

            if (!empty($request->get('search'))) {

                $search = trim(preg_replace('/\s+/', ' ', $request->get('search')));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if ($request->get('airport') == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $request->get('airport') . "'";

            }



            if ($request->get('admins') == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $request->get('admins') . "'";

            }



            if ($request->get('payment') == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $request->get('payment') . "'";

            }



            if ($request->get('status') == "all") {



                // if($_GET['refund_via']=='all'){

                // $_refund_via_s ="";

                // }

                // else{

                // $_refund_via_s =" AND bt.payment_medium = '".$_GET['refund_via']."'";

                // }

                // if($_GET['palenty_to']=='all'){

                // $palenty_to ="";

                // }

                // else{

                // $palenty_to =" AND bt.palenty_to = '".$_GET['palenty_to']."'";

                // }





                // $planty_refund = "left join " . $db->prefix . "booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                // $b_t_table = "bt.*, ";





                $_status = "";

            } else {

                if ($request->get('status') == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($request->get('status') == 'Refund') {



                    if ($request->get('refund_via') == 'all') {

                        $_refund_via_s = "";

                    } else {

                        $_refund_via_s = " AND bt.payment_medium = '" . $request->get('refund_via') . "'";

                    }

                    if ($request->get('palenty_to') == 'all') {

                        $palenty_to = "";

                    } else {

                        $palenty_to = " AND bt.palenty_to = '" . $request->get('palenty_to') . "'";

                    }



                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";

                    $planty_refund = " left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                    $b_t_table = "bt.*, ";

                } else {

                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";

                }

            }



            if ($request->get('companies') == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $request->get('companies') . "'";

            }



            if ($request->get('filter') == "all") {

                $_filter = "";

            } elseif($request->get('filter') == "created_at") {

                $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.created_at,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }
            elseif($request->get('filter') == 'returnDate')
            {
                 $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.returnDate,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }
            else
            {
                $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.departDate,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }



            //$filter_search=$search.$_airport.$_admins.$_payment.$_companies.$_status.$_refund_via_s.$palenty_to.$_filter;



            $filter_search = $_filter;

        }





        





        $query = "select * from users as u left join users_roles as r on(r.user_id=u.id)  where  u.active='1' and r.role_id ='7' ";





        $total_admins = DB::select(DB::raw($query));

        $total_admins = collect($total_admins)->map(function ($x) {

            return (array)$x;

        })->toArray();
        // dd($total_admins);

        header('Content-Type: application/vnd.ms-excel');    //define header info for browser

        header('Content-Disposition: attachment; filename=PZ_invoice_full' . date('Y-m-d H:i') . '.xls');

        header('Pragma: no-cache');

        header('Expires: 0');

        foreach ($total_admins as $total_admin) 
        {
           

            //$total_admin = array($total_admin);
              
            $filter_search_2 = '';
            $_admins = '';
            $_admins = " AND c.admin_id = '" . $total_admin['user_id'] . "'";

            $filter_search_2 = $_admins . $filter_search;

            $totalamount = 0;

            $total = 0;

            $discount = 0;

            $lprice = 0;

            $cancelled = 0;

            $allrefund = 0;

            $total_planty_to_company = 0;

            $total_planty_to_fly = 0;

            $aprice = 0;

            $l_fee = 0;

            $booking_fee = 0;

            $sms_fee = 0;

            $cancel_fee = 0;

            $postal_fee = 0;

            $extra_amount = 0;

            $No_Show_cancel2 = 0;

            $all_cancel_remaining_amount = 0;

            $commission_price = 0;

            $total_planty_to_companyss = 0;

            $total_planty_to_flyss = 0;
			
			$total_pz_ds_comission = 0;
			
			$total_percent_20 = 0;
			
			$total_percent_80 = 0;
			
            $total_bank_amount = 0;





            unset($company);





            echo " \t \n ";

            //$com = ($ap=='GB') ? '10' : '12';



      

       
           $companies = "SELECT $b_t_table b.id,adm.name as admin_login,b.leavy_fee,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.created_at, b.departDate AS start_date, 

		b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,

		b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
		(b.total_amount - b.booking_fee) as bank_amount,
		(c.share_percentage/100*(b.booking_amount)) As fly_commission,
		((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

		FROM airports_bookings as b 

		left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

		join users as adm on adm.id = c.admin_id

		join airports as a on a.id = b.airportID

		$planty_refund

		WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.booking_status != 'Cancelled' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $filter_search_2 GROUP BY b.referenceNo";
 
          
           
       
      

            $companies = DB::select(DB::raw($companies));

            $companies = collect($companies)->map(function ($x) {

                return (array)$x;

            })->toArray();

  // echo $companies; die();



            echo "\t\t\t\t\t\t\t\t\t\t" . $total_admin['name'] . "\t \n ";



            echo "S.NO \t Booking Ref \t Admin \t Company Name \t Surname\t Booking Status \t Booking Date\t Departures Date  \t Return Date \t Booking Amount \t Discount Amount \t Online Charges  \t Extra \t SMS Fee \t Levy Fee \t Cancellation Fee \t Cancellation Amount \t Ns ";

            //if($_GET['status'] == 'Refund'){

            //echo"\t Refund Via \t Palenty To \t Palenty Amount \t B Refund Amount";

            echo "\t  Palenty Amount \t B Refund Amount";

            //}

            echo "\t Total Refund \t Customer Paid Amount \t Bank Amount \t Penalty To Company \t Penalty to yayparking\t Company Commission \t PZ Commission";
           // echo "\t 20% Commission \t 80% Commission";

            print("\n");

            $i = 1;

            foreach ($companies as $company) {



                $cancel = 0;

                $adjust = 0;

                $No_Show_cancel = 0;

                $plenty_amount = 0;

                $total_planty_to_fly = 0;

                $total_planty_to_company = 0;

                $total_planty_to_flys = 0;

                $total_planty_to_companys = 0;



                //$transactions = "SELECT * from booking_transaction where referenceNo = '" . $company["referenceNo"] . "'";

                $transactions = booking_transaction::where("referenceNo", $company["referenceNo"])->get();



                $transaction = (array)$transactions;





                foreach ($transactions as $transaction) {

                    if ($transaction['booking_status'] != 'Noshow') {

                        if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                            $cancel += $transaction['payable'];

                        }

                        if ($transaction['amount_type'] == 'credit' && ($transaction['payment_case'] == 'Refund' || $transaction['payment_case'] == 'edit')) {

                            $adjust += $transaction['payable'];



                        }



                        //$penalty_trans = "SELECT * FROM penalty_details where trans_id = '" . $transaction['id'] . "'";

                        $penalty_trans = penalty_details::where("trans_id", $transaction["id"])->get();





                        foreach ($penalty_trans as $penalty) {

                            $penalty_trans = (array)$penalty_trans;

                            if ($penalty['penalty_to'] == "Company") {

                                $total_planty_to_companys += $penalty['penalty_amount'];

                            }

                            if ($penalty['penalty_to'] == "yayparking") {

                                $total_planty_to_flys += $penalty['penalty_amount'];

                            }

                        }

                        $total_planty_to_company += $total_planty_to_companys;

                        $total_planty_to_fly += $total_planty_to_flys;



                        // if($transaction['palenty_amount']==''){

                        // $transaction['palenty_amount']=0;

                        // }



                        $plenty_amount += ($total_planty_to_fly + $total_planty_to_company);



                    } else {



                        if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                            $No_Show_cancel += $transaction['payable'];

                            $No_Show_cancel2 += $transaction['payable'];

                        }

                    }

                }



                // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){

                // $refund  += $company['booking_amount'];

                // }



                // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){

                // $refund  += $company['booking_amount'];

                // }



                $booking_amount = $company['booking_amount'];

                $share_percentage = $company['share_percentage'];



                // if($cancel !=  $company['booking_amount'] && $cancel > 0){

                // $booking_amount = $booking_amount - $cancel;

                // }



                $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));

                $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));

                $company_commission_after_refund = $company_commission_before_refund;



                if ($adjust > 0) {

                    //$booking_amount = $booking_amount - $adjust;



                    //if(($adjust <  $company['booking_amount']) && ($company['discount_amount'] > 0)){

                    if ($adjust >= 0) {

                        $company_commission_after_refund = $company_commission_before_refund - $company['booking_amount'];

                    } else {

                        $company_commission_after_refund = $company_commission_before_refund - $adjust;

                    }



                }



                $company_commission = $company_commission_after_refund;

                $fly_commission = $fly_commission_before_refund;
				
				$discount_commission = $company['discount_commission'];





                // $explode = explode("FP", $company['company_code']);

                $cname = $company["id"];



                //$cname = "SELECT name from companies where id = '" . $cname . "'";





                $cname = Company::where("id", $cname)->first();



                $cname = (array)$cname;



                $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];

                // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));

                // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));

                // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));

                // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));



                $totals = $company['total_amount'];



                //if($refund > 0){

                // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){

                $cancel_remaining_amount = 0;

                if ($cancel > 0) {

                    $company_commission = 0;

                    $fly_commission = 0;
					$discount_commission = 0;

                    $cancel_remaining_amount = $booking_amount - $cancel;

                } elseif ($No_Show_cancel > 0) {

                    $company_commission = 0;

                    $fly_commission = 0;
					$discount_commission = 0;

                }





                $output = "\t" . $i . "\t";

                $output .= $company['referenceNo'] . "\t";

                $output .= $company['admin_login'] . "\t";

                $output .= $company['company_name'] . "\t";

                $output .= $company['Surname'] . "\t";



                $output .= $company['booking_action'] . "\t";



                $output .= date("d/m/Y", strtotime($company['created_at'])) . "\t";

                $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
                $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";

 




                $output .= $company['booking_amount'] . "\t";

                $output .= $company['discount_amount'] . "\t";

                $output .= $company['booking_fee'] . "\t";

                $output .= $company['booking_extra'] . "\t";

                $output .= $company['smsfee'] . "\t";

                $output .= $company['leavy_fee'] . "\t";

                $output .= $company['cancelfee'] . "\t";

                //$output .=$adjust."\t";

                $output .= $cancel . "\t";

                $output .= (float)number_format($No_Show_cancel, 3) . "\t";





                // if($_GET['status'] == 'Refund'){

                // $output .=$company['payment_medium']."\t";

                // $output .=$company['palenty_to']."\t";

                $output .= $plenty_amount . "\t";

                $output .= $adjust . "\t";



                // }



                $output .= ($plenty_amount + $adjust) . "\t";

                $nettt = ($company['booking_amount'] - $adjust);

                $nettt = ($nettt - $plenty_amount);

                $nettt = ($nettt - $cancel);

                $nettt = ($nettt + $company['booking_fee']);

                $nettt = ($nettt + $company['booking_extra']);

                $nettt = ($nettt + $company['smsfee']);

                $nettt = ($nettt + $company['cancelfee']);


				$percent_20 = (20/100)*$discount_commission;
				
				$percent_80 = (80/100)*$discount_commission;



                //$output .=$nettt."\t";
                
                $output .= (float)number_format($company['total_amount'], 3) . "\t";

				$output .= (float)number_format($company['bank_amount'], 3) . "\t";
				
                $output .= (float)number_format($total_planty_to_company, 3) . "\t";

                $output .= (float)number_format($total_planty_to_fly, 3) . "\t";
                 if($total_admin['user_id'] == 39)
                 {
                      $company_commission = $company['total_amount'] * 0.70;
                      $fly_commission =  $company['total_amount'] * 0.30;
                 }
                 else
                 {
                     $output .= (float)number_format($company_commission, 3) . "\t";
                     $output .= (float)number_format($fly_commission, 3) . "\t";

                 }

               
             
			
				//$output .= (float)number_format($discount_commission, 2) . "\t";
				
				//$output .= (float)number_format($percent_20, 2) . "\t";
				
				//$output .= (float)number_format($percent_80, 2) . "\t";





                $i++;

                //Plenty Space

                //$total += $company['total_amount'];

                $total += $totals;

                $lprice += $company['booking_amount'];

                $aprice += $company_commission;

				$total_bank_amount += $company['bank_amount'];
				
                $l_fee += $company['leavy_fee'];

                $commission_price += $fly_commission;
			
				$total_pz_ds_comission += $discount_commission;
				
				$total_percent_20 += $percent_20;
				
				$total_percent_80 += $percent_80;

                $extra_amount += $company['booking_extra'] + $company['extra_amount'];

                $discount += $company['discount_amount'];

                $allrefund += $adjust;

                $cancelled += $cancel;

                $No_Show_cancel += $No_Show_cancel;

                $all_cancel_remaining_amount += $cancel_remaining_amount;

                $booking_fee += $company['booking_fee'];

                $sms_fee += $company['smsfee'];

                $cancel_fee += $company['cancelfee'];

                $postal_fee += $company['postal_fee'];

                $plenty_amount += $plenty_amount;

                $total_planty_to_companyss += $total_planty_to_company;

                $total_planty_to_flyss += $total_planty_to_fly;



                print(trim($output)) . "\t\n";



                //}

            }



            $totalamount = ($total * 1) + ($discount * 1);

            if ($allrefund > 0 || $cancelled > 0) {

                //$totalamount = $totalamount - $allrefund - $cancelled ;

                //$total = $total - $allrefund - $cancelled;

                //$lprice = $lprice - $allrefund - $cancelled;

            }

            $totalcommission = ($commission_price * 1) - ($discount * 1);

            $diff = ($totalamount * 1) - ($lprice * 1);

            $netdiff = ($totalcommission * 1) + ($diff * 1);

            $vat_deduction = ($commission_price / 100) * (20);

            $diff = ($diff + $all_cancel_remaining_amount + $No_Show_cancel2);



            $vat_add_extra = ($diff / 100) * (20);


            
            
            echo " \n \n \n \t Booking Amount given by companies \t" . $lprice . " \t \n ";
            
            echo "  \t Total Booking Fee \t" . $booking_fee . " \t \n ";
            
            echo " \t Total Amount Paid by customer \t" . $total . " \t \n ";
            
            echo " \t Company Commission \t" . $aprice . " \t \n ";
            
			echo " \t PZ Commission \t" . round($commission_price, 2) . " \t \n ";
			
		
			
// 			echo "\t PZ Commission with Discount \t" . round($total_pz_ds_comission, 2) . " \t \n ";
			
// 			echo " \t Total 20% Commission \t" . round($total_percent_20, 2) . " \t \n ";
			
// 			echo " \t Total 80% Commission \t" . round($total_percent_80, 2) . " \t \n ";


// 			echo " \n \t Total Amount without discount \t" . $totalamount . " \t \n ";

//             echo " \t Discount given by Yayparking \t" . $discount . " \t \n ";

           
			
// 			echo " \t Total Amount Paid in Bank \t" . $total_bank_amount . " \t \n ";

         

//             echo " \n\t Net Booking Amount after Refund,cancel and Palenty \t" . ($lprice - $cancelled - ($allrefund + $total_planty_to_companyss)) . " \t \n\n ";

//             echo " \t Booking Amount Cancelled \t" . $cancelled . " \t \n ";



//             echo " \t Booking Amount Refunded \t" . $allrefund . " \t \n ";





//             echo " \t Total Palenty Amount \t" . ($total_planty_to_companyss + $total_planty_to_flyss) . " \t \n ";





//             echo " \t Total Company Penalty Amount \t" . $total_planty_to_companyss . " \t \n ";

//             echo " \t Total PZ Penalty Amount \t" . $total_planty_to_flyss . " \t \n\n ";

          

//             echo " \t Company Commission After Deducting Penalty\t" . ($aprice - $total_planty_to_companyss) . " \t \n ";

//             echo " \t Company Levy Amount\t" . round($l_fee, 3) . " \t \n ";

//             echo " \t Net Invoice Payable to Company \t" . round(($l_fee + ($aprice - $total_planty_to_companyss)), 3) . " \t \n ";

//             echo " \n ";

//             echo " \t PZ Commission \t" . $commission_price . " \t \n ";



//             echo " \n \n \n \t Total Booking Fee \t" . $booking_fee . " \t \n ";

//             echo " \t Total SMS Fee \t" . $sms_fee . " \t \n ";

//             echo " \t Total Cancel Fee \t" . $cancel_fee . " \t \n ";

//             echo " \t Total Postal Fee \t" . $postal_fee . " \t \n ";

//             echo " \t Total No Show Amount \t" . $No_Show_cancel2 . " \t \n ";

//             echo " \t Add Extra Amount \t" . $extra_amount . " \t \n ";

//             echo " \t Remaining Cancel Amount \t" . $all_cancel_remaining_amount . " \t \n ";

//             echo " \t Total Extra Amount \t" . ($all_cancel_remaining_amount + $booking_fee + $sms_fee + $cancel_fee + $postal_fee + $extra_amount + $No_Show_cancel2) . " \t \n ";

            echo "\n";

            echo "\t";





            $outputs += $outputs;



        }



        //print(trim($outputs)) . "\t\n";





    }



    public function invoiceSummery(Request $request)

    {



        $_filter = "";

        $b_t_table = " ";

        $filter_search = "";

        $planty_refund = " ";

        $outputs = 0;



        if (!empty($request->get('search')) || !empty($request->get('companies')) || !empty($request->get('filter')) || !empty($request->get('start_date')) || !empty($request->get('end_date'))) {





            $_refund_via_s = "";

            $palenty_to = "";

            if (!empty($request->get('search'))) {

                $search = trim(preg_replace('/\s+/', ' ', $request->get('search')));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if ($request->get('airport') == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $request->get('airport') . "'";

            }



            if ($request->get('admins') == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $request->get('admins') . "'";

            }



            if ($request->get('payment') == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $request->get('payment') . "'";

            }



            if ($request->get('status') == "all") {



                // if($_GET['refund_via']=='all'){

                // $_refund_via_s ="";

                // }

                // else{

                // $_refund_via_s =" AND bt.payment_medium = '".$_GET['refund_via']."'";

                // }

                // if($_GET['palenty_to']=='all'){

                // $palenty_to ="";

                // }

                // else{

                // $palenty_to =" AND bt.palenty_to = '".$_GET['palenty_to']."'";

                // }





                // $planty_refund = "left join " . $db->prefix . "booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                // $b_t_table = "bt.*, ";





                $_status = "";

            } else {

                if ($request->get('status') == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($request->get('status') == 'Refund') {



                    if ($request->get('refund_via') == 'all') {

                        $_refund_via_s = "";

                    } else {

                        $_refund_via_s = " AND bt.payment_medium = '" . $request->get('refund_via') . "'";

                    }

                    if ($request->get('palenty_to') == 'all') {

                        $palenty_to = "";

                    } else {

                        $palenty_to = " AND bt.palenty_to = '" . $request->get('palenty_to') . "'";

                    }



                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";

                    $planty_refund = " left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                    $b_t_table = "bt.*, ";

                } else {

                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";

                }

            }



            if ($request->get('companies') == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $request->get('companies') . "'";

            }



              if ($request->get('filter') == "all") {

                $_filter = "";

            } elseif($request->get('filter') == "created_at") {

                $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.created_at,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }
            elseif($request->get('filter') == 'returnDate')
            {
                 $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.returnDate,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }
            else
            {
                $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.departDate,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }





            //$filter_search=$search.$_airport.$_admins.$_payment.$_companies.$_status.$_refund_via_s.$palenty_to.$_filter;



            $filter_search = $_filter;

        }





        header('Content-Type: application/vnd.ms-excel');    //define header info for browser

        header('Content-Disposition: attachment; filename=YP_invoice_summery' . date('Y-m-d H:i') . '.xls');

        header('Pragma: no-cache');

        header('Expires: 0');





        $total_company_commissions = 0;



        $query = "select *,u.name as admin_login from users as u left join users_roles as r on(r.user_id=u.id)  where  u.active='1' and r.role_id ='7' ";





        $total_admins = DB::select(DB::raw($query));

        $total_admins = collect($total_admins)->map(function ($x) {

            return (array)$x;

        })->toArray();



        foreach ($total_admins as $total_admin)
         {

            $_admins = '';
            $filter_search_2 = '';
            $_admins = " AND c.admin_id = '" . $total_admin['user_id'] . "'";
            $admin_name = $total_admin['name'];
            $filter_search_2 = $_admins . $filter_search;
            
            $totalamount = 0;

            $total = 0;

            $discount = 0;

            $lprice = 0;

            $cancelled = 0;

            $allrefund = 0;

            $total_planty_to_company = 0;

            $total_planty_to_fly = 0;

            $aprice = 0;

            $l_fee = 0;

            $booking_fee = 0;

            $sms_fee = 0;

            $cancel_fee = 0;

            $postal_fee = 0;

            $extra_amount = 0;

            $No_Show_cancel2 = 0;

            $all_cancel_remaining_amount = 0;

            $commission_price = 0;

            $total_planty_to_companyss = 0;

            $total_planty_to_flyss = 0;





            unset($company);





            echo " \t \n ";

            $companies = "SELECT $b_t_table b.id,adm.name as admin_login,b.leavy_fee,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.created_at, b.departDate AS start_date, 

	b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,

	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 

	(c.share_percentage/100*(b.booking_amount)) As fly_commission 		

	FROM airports_bookings as b 

	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	join users as adm on adm.id = c.admin_id

	join airports as a on a.id = b.airportID

	$planty_refund

	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $filter_search_2 GROUP BY b.referenceNo";



//echo $companies; die();

            $companies = DB::select(DB::raw($companies));

            $companies = collect($companies)->map(function ($x) {

                return (array)$x;

            })->toArray();



            $i = 1;

            foreach ($companies as $company) {



                $cancel = 0;

                $adjust = 0;

                $No_Show_cancel = 0;

                $plenty_amount = 0;

                $total_planty_to_fly = 0;

                $total_planty_to_company = 0;

                $total_planty_to_flys = 0;

                $total_planty_to_companys = 0;



                $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);





                foreach ($transactions as $transaction) {

                    $transactions = (array)$transactions;

                    if ($transaction['booking_status'] != 'Noshow') {

                        if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                            $cancel += $transaction['payable'];

                        }

                        if ($transaction['amount_type'] == 'credit' && ($transaction['payment_case'] == 'Refund' || $transaction['payment_case'] == 'edit')) {

                            $adjust += $transaction['payable'];



                        }



                        // $penalty_trans = $db->select("SELECT * FROM " . $db->prefix . "penalty_details where trans_id = '".$transaction['id']."'");

                        $penalty_trans = penalty_details::where("trans_id", $transaction['id']);



                        foreach ($penalty_trans as $penalty) {

                            $penalty = (array)$penalty;

                            if ($penalty['penalty_to'] == "Company") {

                                $total_planty_to_companys += $penalty['penalty_amount'];

                            }



                            if ($penalty['penalty_to'] == "JETSEEKER") {



                                $total_planty_to_flys += $penalty['penalty_amount'];

                            }

                        }

                        $total_planty_to_company += $total_planty_to_companys;

                        $total_planty_to_fly += $total_planty_to_flys;



                        // if($transaction['palenty_amount']==''){

                        // $transaction['palenty_amount']=0;

                        // }



                        $plenty_amount += ($total_planty_to_fly + $total_planty_to_company);



                    } else {



                        if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                            $No_Show_cancel += $transaction['payable'];

                            $No_Show_cancel2 += $transaction['payable'];

                        }

                    }

                }



                // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){

                // $refund  += $company['booking_amount'];

                // }



                // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){

                // $refund  += $company['booking_amount'];

                // }



                $booking_amount = $company['booking_amount'];

                $share_percentage = $company['share_percentage'];



                // if($cancel !=  $company['booking_amount'] && $cancel > 0){

                // $booking_amount = $booking_amount - $cancel;

                // }



                $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));

                $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));

                $company_commission_after_refund = $company_commission_before_refund;



                if ($adjust > 0) {

                    //$booking_amount = $booking_amount - $adjust;



                    //if(($adjust <  $company['booking_amount']) && ($company['discount_amount'] > 0)){

                    if ($adjust >= 0) {

                        $company_commission_after_refund = $company_commission_before_refund - $company['booking_amount'];

                    } else {

                        $company_commission_after_refund = $company_commission_before_refund - $adjust;

                    }



                }



                $company_commission = $company_commission_after_refund;

                $fly_commission = $fly_commission_before_refund;



                // if($adjust == $company['booking_amount']){

                // $company_commission_after_refund = $company_commission_before_refund - $adjust;

                // }



                $company_commission = $company_commission_after_refund;

                $fly_commission = $fly_commission_before_refund;





                $cname = $company["id"];



                //$cname = "SELECT name from companies where id = '" . $cname . "'";





                $cname = Company::where("id", $cname)->first();



                $cname = (array)$cname;





                $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];

                $totals = $company['total_amount'];



                //if($refund > 0){

                // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){

                $cancel_remaining_amount = 0;

                if ($cancel > 0) {

                    $company_commission = 0;

                    $fly_commission = 0;

                    $cancel_remaining_amount = $booking_amount - $cancel;

                } elseif ($No_Show_cancel > 0) {

                    $company_commission = 0;

                    $fly_commission = 0;

                }





                $output = "\t" . $i . "\t";

                $output .= $company['referenceNo'] . "\t";

                $output .= $company['admin_login'] . "\t";

                $output .= $company['company_name'] . "\t";

                $output .= $company['Surname'] . "\t";

                $output .= $company['booking_action'] . "\t";



                $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";





                $output .= $company['booking_amount'] . "\t";

                $output .= $company['discount_amount'] . "\t";

                $output .= $company['booking_fee'] . "\t";

                $output .= $company['booking_extra'] . "\t";

                $output .= $company['smsfee'] . "\t";

                $output .= $company['leavy_fee'] . "\t";

                $output .= $company['cancelfee'] . "\t";

                //$output .=$adjust."\t";

                $output .= $cancel . "\t";

                $output .= (float)number_format($No_Show_cancel, 3) . "\t";





                // if($_GET['status'] == 'Refund'){

                // $output .=$company['payment_medium']."\t";

                // $output .=$company['palenty_to']."\t";

                $output .= $plenty_amount . "\t";

                $output .= $adjust . "\t";



                // }



                $output .= ($plenty_amount + $adjust) . "\t";

                $nettt = ($company['booking_amount'] - $adjust);

                $nettt = ($nettt - $plenty_amount);

                $nettt = ($nettt - $cancel);

                $nettt = ($nettt + $company['booking_fee']);

                $nettt = ($nettt + $company['booking_extra']);

                $nettt = ($nettt + $company['smsfee']);

                $nettt = ($nettt + $company['cancelfee']);





                //$output .=$nettt."\t";

                $output .= (float)number_format($nettt, 3) . "\t";

                $output .= (float)number_format($total_planty_to_company, 3) . "\t";

                $output .= (float)number_format($total_planty_to_fly, 3) . "\t";

                $output .= (float)number_format($company_commission, 3) . "\t";

                $output .= (float)number_format($fly_commission, 3) . "\t";



                $i++;

                //Plenty Space

                //$total += $company['total_amount'];

                $total += $totals;

                $lprice += $company['booking_amount'];
                if($total_admin['user_id'] == 39)
                {
                 $company_commission = $company['total_amount'] * 0.70;
                }
                $aprice += $company_commission;

                $l_fee += $company['leavy_fee'];

                $commission_price += $fly_commission;

                $extra_amount += $company['booking_extra'] + $company['extra_amount'];

                $discount += $company['discount_amount'];

                $allrefund += $adjust;

                $cancelled += $cancel;

                $No_Show_cancel += $No_Show_cancel;

                $all_cancel_remaining_amount += $cancel_remaining_amount;

                $booking_fee += $company['booking_fee'];

                $sms_fee += $company['smsfee'];

                $cancel_fee += $company['cancelfee'];

                $postal_fee += $company['postal_fee'];

                $plenty_amount += $plenty_amount;

                $total_planty_to_companyss += $total_planty_to_company;

                $total_planty_to_flyss += $total_planty_to_fly;



                //print(trim($output))."\t\n";



                //}

            }



            $totalamount = ($total * 1) + ($discount * 1);

            if ($allrefund > 0 || $cancelled > 0) {

                //$totalamount = $totalamount - $allrefund - $cancelled ;

                //$total = $total - $allrefund - $cancelled;

                //$lprice = $lprice - $allrefund - $cancelled;

            }

            $totalcommission = ($commission_price * 1) - ($discount * 1);

            $diff = ($totalamount * 1) - ($lprice * 1);

            $netdiff = ($totalcommission * 1) + ($diff * 1);

            $vat_deduction = ($commission_price / 100) * (20);

            $diff = ($diff + $all_cancel_remaining_amount + $No_Show_cancel2);



            $vat_add_extra = ($diff / 100) * (20);





            if (($aprice - $total_planty_to_companyss) != 0) {

              //  echo "  \n\t" . $total_admin['admin_login'] . "\tNet Company Commission\t" . ($aprice - $total_planty_to_companyss) . " \t ";
                echo "  \n\t" . $admin_name . "\tNet Company Commission\t" . ($aprice - $total_planty_to_companyss) . " \t ";


            }

            $total_company_commission = ($aprice - $total_planty_to_companyss);

            $total_company_commissions += $total_company_commission;





        }





        echo "\n\n\t\tTotal Commission\t" . trim($total_company_commissions) . "\t\n";

    }


   public function CompanyCommissionReport(Request $request)
    {

        $data = $request->all();

        $admins = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();

        $airports = airport::all();

        $companies_dlist = Company::all();

        $show = 0;

        if ($request->input("submit") == "Search") {

            $show = 1;

        }



        $filter_search = "";

        $planty_refund = " ";

        $b_t_table = " ";

        $_refund_via_s = "";

        $palenty_to = "";

        $search = "";

        $join = "";

        $show = 0;

        if ($request->input("submit") == "Search") {

            $show = 1;

        }

        if ($data != null && count($data) > 0) {



            if (!empty($data['search'])) {

                $search = trim(preg_replace('/\s+/', ' ', $data['search']));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if (!empty($data['airport']) && $data['airport'] == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $data['airport'] . "'";

            }



            if (!empty($data['companies']) && $data['companies'] == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $data['companies'] . "'";

            }



            if (!empty($data['admins']) && $data['admins'] == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";

            }



            if (!empty($data['payment']) && $data['payment'] == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";

            }



            if (!empty($data['status']) && $data['status'] == "all") {

                $_status = "";

            } else {

                if ($data['status'] == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($data['status'] == 'Refund') {


                   /*
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

         */


                       $_refund_via_s = "";
                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                    $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                    $b_t_table = "bt.*, ";

                } else {

                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                }

            }

            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }

            if (isset($data['filter']) && $data['filter'] == "all") {


                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b.created_at, '%Y-%m-%d')";
                $date_format2 = " DATE_FORMAT(b.departDate, '%Y-%m-%d')";
                $date_format3 = " DATE_FORMAT(b.returnDate, '%Y-%m-%d')";

                $_filter = " AND (" . $date_format . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format2 . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format3 . " BETWEEN " . $from . " AND " . $to .') ';

            } else {

                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;

        } else {

            $_current = " and b.created_at  >= CURDATE()";

            $filter_search = $_current;

        }



        $limit = 20;

        $skip = 0;

        $page = 1;

        if ($request->input("page") > 1) {

            $page = $request->input("page");

            $skip = $limit * ($page - 1);

        }





        $companies = "SELECT $b_t_table b.id, b.referenceNo, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, 

	b.booking_amount AS ListPrice,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' 
	$join $filter_search ORDER BY b.id DESC LIMIT $skip,$limit";



//echo $companies; die();

    $companies2 = "SELECT $b_t_table b.id, b.referenceNo, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, 

	b.booking_amount AS ListPrice,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' 
	$join $filter_search ORDER BY b.id DESC ";





        $companies = DB::select(DB::raw($companies));
    
        $companies2 = DB::select(DB::raw($companies2));
    
        $total_list_price = 0;
    	$total_amount_paid = 0;
    	$total_pz_commission = 0;
    	$total_booking = 0;
        foreach($companies2 as $company){
            $total_list_price = $total_list_price + $company->ListPrice;
            $total_amount_paid = $total_amount_paid + $company->AmountPaid;
            $total_pz_commission = $total_pz_commission + $company->commission;
            $total_booking++;
        }



        $companies = collect($companies)->map(function ($x) {

            return (array)$x;

        })->toArray();





        $paginator = new Paginator($companies2, count($companies2), $limit, $page, [

            'path' => $request->url(),

            'query' => $request->query(),

        ]);

        //company send to view

        //print_r($companies); die();

        return view("admin.reports.company_report", ["companies" => $companies,"total_booking" => $total_booking, "total_list_price" => $total_list_price, "total_amount_paid" => $total_amount_paid, "total_pz_commission" => $total_pz_commission, "companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $admins, "show" => $show, "paginator" => $paginator]);



    }


    public function ParkingzoneDeailCommissionReport(Request $request)

    {

        $data = $request->all();



        $admins = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();

        $airports = airport::all();

        $companies_dlist = Company::all();





        $filter_search = "";

        $planty_refund = " ";

        $b_t_table = " ";

        $_refund_via_s = "";

        $palenty_to = "";

        $search = "";

        $join = "";

        $show = 0;

        if ($request->input("submit") == "Search") {

            $show = 1;

        }

        if ($data != null && count($data) > 0) {



            if (!empty($data['search'])) {

                $search = trim(preg_replace('/\s+/', ' ', $data['search']));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if (!empty($data['airport']) && $data['airport'] == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $data['airport'] . "'";

            }



            if (!empty($data['companies']) && $data['companies'] == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $data['companies'] . "'";

            }



            if (!empty($data['admins']) && $data['admins'] == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";

            }



            if (!empty($data['payment']) && $data['payment'] == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";

            }



            if (!empty($data['status']) && $data['status'] == "all") {

                $_status = "";

            } else {

                if ($data['status'] == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($data['status'] == 'Refund') {


/*
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

 */

 $_refund_via_s = "";

                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                    $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                    $b_t_table = "bt.*, ";

                } else {

                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                }

            }

            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }

            if (isset($data['filter']) && $data['filter'] == "all") {

                $_filter = "";
                
                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b.created_at, '%Y-%m-%d')";
                $date_format2 = " DATE_FORMAT(b.departDate, '%Y-%m-%d')";
                $date_format3 = " DATE_FORMAT(b.returnDate, '%Y-%m-%d')";

                $_filter = " AND (" . $date_format . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format2 . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format3 . " BETWEEN " . $from . " AND " . $to .') ';

            } else {

                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;

        } 
        else {

            $_current = " and b.created_at  >= CURDATE()";

            $filter_search = $_current;

        }



        $limit = 20;

        $skip = 0;

        $page = 1;

        if ($request->input("page") > 1) {

            $page = $request->input("page");

            $skip = $limit * ($page - 1);

        }





        $companies = "SELECT $b_t_table b.id, b.referenceNo, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, 

	b.booking_amount AS ListPrice, b.booking_fee, b.discount_amount,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' 
	$join $filter_search ORDER BY b.id DESC LIMIT $skip,$limit";



//echo $companies; die();





        $companies2 = "SELECT $b_t_table b.id, b.referenceNo, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, 

	b.booking_amount AS ListPrice, b.booking_fee, b.discount_amount,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' 
	$join $filter_search  ORDER BY b.id DESC ";





        $companies = DB::select(DB::raw($companies));

        $companies2 = DB::select(DB::raw($companies2));

        $total_list_price = 0;
    	$total_amount_paid = 0;
    	$total_pz_commission = 0;
    	$total_booking = 0;
        foreach($companies2 as $company){
            $total_list_price = $total_list_price + $company->ListPrice;
            $total_amount_paid = $total_amount_paid + $company->AmountPaid;
            $total_pz_commission = $total_pz_commission + $company->commission;
            $total_booking++;
        }



        $companies = collect($companies)->map(function ($x) {

            return (array)$x;

        })->toArray();





        $paginator = new Paginator($companies2, count($companies2), $limit, $page, [

            'path' => $request->url(),

            'query' => $request->query(),

        ]);

        //company send to view

        //print_r($companies); die();

        return view("admin.reports.airport_commission_report", ["companies" => $companies, "total_booking" => $total_booking, "total_list_price" => $total_list_price, "total_amount_paid" => $total_amount_paid, "total_pz_commission" => $total_pz_commission, "companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $admins, "show" => $show, "paginator" => $paginator]);



    }



    public function CompanyReportExcelPZ(Request $request)

    {



        $_filter = "";

        $b_t_table = " ";

        $filter_search = "";

        $planty_refund = " ";

        $outputs = 0;



        $total = 0;

        $lprice = 0;

        $aprice = 0;

        $commission_price = 0;

        $extra_amount = 0;

        $discount = 0;

        $allrefund = 0;

        $cancelled = 0;

        $all_cancel_remaining_amount = 0;

        $booking_fee = 0;

        $sms_fee = 0;

        $cancel_fee = 0;

        $postal_fee = 0;



        $planty_refund = " left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";

        $b_t_table = "bt.*, ";



        if (!empty($request->get('search')) || !empty($request->get('companies')) || !empty($request->get('filter')) || !empty($request->get('start_date')) || !empty($request->get('end_date'))) {





            $_refund_via_s = "";

            $palenty_to = "";

            if (!empty($request->get('search'))) {

                $search = trim(preg_replace('/\s+/', ' ', $request->get('search')));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if ($request->get('airport') == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $request->get('airport') . "'";

            }



            if ($request->get('admins') == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $request->get('admins') . "'";

            }



            if ($request->get('payment') == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $request->get('payment') . "'";

            }



            if ($request->get('status') == "all") {



                // if($_GET['refund_via']=='all'){

                // $_refund_via_s ="";

                // }

                // else{

                // $_refund_via_s =" AND bt.payment_medium = '".$_GET['refund_via']."'";

                // }

                // if($_GET['palenty_to']=='all'){

                // $palenty_to ="";

                // }

                // else{

                // $palenty_to =" AND bt.palenty_to = '".$_GET['palenty_to']."'";

                // }





                // $planty_refund = "left join " . $db->prefix . "booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                // $b_t_table = "bt.*, ";





                $_status = "";

            } else {

                if ($request->get('status') == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($request->get('status') == 'Refund') {



                    if ($request->get('refund_via') == 'all') {

                        $_refund_via_s = "";

                    } else {

                        $_refund_via_s = " AND bt.payment_medium = '" . $request->get('refund_via') . "'";

                    }

                    if ($request->get('palenty_to') == 'all') {

                        $palenty_to = "";

                    } else {

                        $palenty_to = " AND bt.palenty_to = '" . $request->get('palenty_to') . "'";

                    }



                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";



                } else {

                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";

                }

            }



            if ($request->get('companies') == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $request->get('companies') . "'";

            }



            if ($request->get('filter') == "all") {

                $_filter = "";
                
                $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.created_at, '%Y-%m-%d')";
                $date_format2 = " DATE_FORMAT(b.departDate, '%Y-%m-%d')";
                $date_format3 = " DATE_FORMAT(b.returnDate, '%Y-%m-%d')";

                $_filter = " AND (" . $date_format . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format2 . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format3 . " BETWEEN " . $from . " AND " . $to .') ';

            } else {

                $from = date("'Y-m-d'", strtotime($request->get('start_date')));

                $to = date("'Y-m-d'", strtotime($request->get('end_date')));

                $date_format = " DATE_FORMAT(b.departDate,'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }



            //$filter_search=$search.$_airport.$_admins.$_payment.$_companies.$_status.$_refund_via_s.$palenty_to.$_filter;



            $filter_search = $_filter;

        }

        //$com = ($ap=='GB') ? '10' : '12';



        $companies = "SELECT $b_t_table b.id,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 

	b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,

	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 

	(c.share_percentage/100*(b.booking_amount)) As fly_commission 		

	FROM airports_bookings as b 

	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	join airports as a on a.id = b.airportID

	

	$planty_refund

	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $filter_search";



//echo $companies; die();

        $companies = DB::select(DB::raw($companies));

        $companies = collect($companies)->map(function ($x) {

            return (array)$x;

        })->toArray();



        header('Content-Type: application/vnd.ms-excel');    //define header info for browser

        header('Content-Disposition: attachment; filename=JETSEEKER-commission-report' . date('Ymd') . '.xls');

        header('Pragma: no-cache');

        header('Expires: 0');



        echo "S.NO \t Booking Ref \t Carpark Name \t Airport \t Surname \t Booking Status \t Payment Method \t Booking Date \t Departures Date \t Return Date \t Days \t Vehicle Reg \t Vehicle Make \t Vehicle Model \t Vehicle Color \t Telephone \t Discount Amount \t Adjustment Amount \t Cancel Amount \t  Cancel Remaining \t B.Refund Amount \t Total Amount \t Booking Amount \t Company Commission \t JETSEEKER Commission \t Ns \t Refund Via \t Penalty To \t Penalty Amount \t T Refund Amount";

        print("\n");

        $i = 1;

        foreach ($companies as $company) {

            $cancel = 0;

            $adjust = 0;

            $No_Show_cancel = 0;

            $No_Show_cancel2 = 0;

            $plenty_amount = 0;

            $total_planty_to_fly = 0;

            $total_planty_to_company = 0;

            $total_planty_to_flys = 0;

            $total_planty_to_companys = 0;

            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"]);



            $transaction = (array)$transactions;

            foreach ($transactions as $transaction) {

                if ($transaction['booking_status'] != 'Noshow') {

                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                        $cancel += $transaction['payable'];

                    }

                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {

                        $adjust += $transaction['payable'];



                    }

                } else {



                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                        $No_Show_cancel += $transaction['payable'];

                        $No_Show_cancel2 += $transaction['payable'];

                    }

                }

            }



            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){

            // $refund  += $company['booking_amount'];

            // }



            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){

            // $refund  += $company['booking_amount'];

            // }



            $booking_amount = $company['booking_amount'];

            $share_percentage = $company['share_percentage'];



            // if($cancel !=  $company['booking_amount'] && $cancel > 0){

            // $booking_amount = $booking_amount - $cancel;

            // }



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





            $explode = explode("PZ", $company['company_code']);

            // $cname = $explode[1];

            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");

            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];

            $cname = $company['company_name'];

            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));

            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));

            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));

            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));



            $totals = $company['total_amount'];



            //if($refund > 0){

            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){

            $cancel_remaining_amount = 0;

            if ($cancel > 0) {

                $company_commission = 0;

                $fly_commission = 0;

                $cancel_remaining_amount = $booking_amount - $cancel;

            } elseif ($No_Show_cancel > 0) {

                $company_commission = 0;

                $fly_commission = 0;

            }



            $output = $i . "\t";

            $output .= $company['referenceNo'] . "\t";

            $output .= $cname . "\t";

            $output .= $company['airport_name'] . "\t";

            $output .= $company['Surname'] . "\t";

            $output .= $company['booking_action'] . "\t";

            $output .= $company['payment_method'] . "\t";

            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";

            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";

            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";

            $output .= $company['no_of_days'] . "\t";

            $output .= $company['registration'] . "\t";

            $output .= $company['make'] . "\t";

            $output .= $company['model'] . "\t";

            $output .= $company['color'] . "\t";

            $output .= $company['phone_number'] . "\t";

            $output .= $company['discount_amount'] . "\t";



            $output .= $adjust . "\t";

            $output .= $cancel . "\t";

            $output .= $cancel_remaining_amount . "\t";

            //$output .=$company['booking_extra']."\t";

            $output .= $adjust . "\t";

            $output .= $totals . "\t";

            $output .= $company['booking_amount'] . "\t";

            $output .= (float)number_format($company_commission, 3) . "\t";

            $output .= (float)number_format($fly_commission, 3) . "\t";

            $output .= (float)number_format($No_Show_cancel, 3) . "\t";

            $output .= $company['payment_medium'] . "\t";

            $output .= $company['palenty_to'] . "\t";

            $output .= $company['palenty_amount'] . "\t";

            $output .= ($company['palenty_amount'] + $adjust) . "\t";

            $i++;

            if ($company['palenty_to'] == "Company") {

                $total_planty_to_company += $company['palenty_amount'];

            }

            if ($company['palenty_to'] == "JETSEEKER") {

                $total_planty_to_fly += $company['palenty_amount'];

            }

            //$total += $company['total_amount'];

            $total += $totals;

            $lprice += $company['booking_amount'];

            $aprice += $company_commission;

            $commission_price += $fly_commission;

            $extra_amount += $company['booking_extra'] + $company['extra_amount'];

            $discount += $company['discount_amount'];

            $allrefund += $adjust;

            $cancelled += $cancel;

            $No_Show_cancel += $No_Show_cancel;

            $all_cancel_remaining_amount += $cancel_remaining_amount;

            $booking_fee += $company['booking_fee'];

            $sms_fee += $company['smsfee'];

            $cancel_fee += $company['cancelfee'];

            $postal_fee += $company['postal_fee'];



            print(trim($output)) . "\t\n";



        }

        $totalamount = ($total * 1) + ($discount * 1);

        if ($allrefund > 0 || $cancelled > 0) {

            //$totalamount = $totalamount - $allrefund - $cancelled ;

            //$total = $total - $allrefund - $cancelled;

            //$lprice = $lprice - $allrefund - $cancelled;

        }

        $totalcommission = ($commission_price * 1) - ($discount * 1);

        $diff = ($totalamount * 1) - ($lprice * 1);

        $netdiff = ($totalcommission * 1) + ($diff * 1);

        $vat_deduction = ($commission_price / 100) * (20);

        $diff = ($diff + $all_cancel_remaining_amount + $No_Show_cancel2);



        $vat_add_extra = ($diff / 100) * (20);





        echo " \n \n \n \t Total Amount without discount \t" . $totalamount . " \t \n ";

        echo " \t Discount given by JETSEEKER \t" . $discount . " \t \n ";

        echo " \t Total Amount Paid by customer \t" . $total . " \t \n ";

        echo " \t Booking Amount given by companies \t" . $lprice . " \t \n ";

        echo " \t Amount Cancelled \t" . $cancelled . " \t \n ";



        echo " \t Total Refunded Amount \t" . ($allrefund + $total_planty_to_company + $total_planty_to_fly) . " \t \n ";



        echo " \t Booking Amount Refunded \t" . $allrefund . " \t \n ";





        echo " \t Total Penalty Amount \t" . ($total_planty_to_company + $total_planty_to_fly) . " \t \n ";





        echo " \t Total Company Penalty Amount \t" . $total_planty_to_company . " \t \n ";

        echo " \t Total JETSEEKER planty Amount \t" . $total_planty_to_fly . " \t \n ";

        echo " \t Company Commission \t" . $aprice . " \t \n ";

        echo " \t Company Commission After Deducting Planty\t" . ($aprice - $total_planty_to_company) . " \t \n ";

        echo " \n ";

        echo " \t Difference (Including Remaining Cancel and NO Show) \t" . ($diff) . " \t \n ";

        echo " \n ";

        echo " \t JETSEEKER Commission \t" . $commission_price . " \t \n ";



        echo " \t 20% VAT of " . $commission_price . " \t -" . $vat_deduction . " \t \n ";

        echo " \t Discount deducted \t" . $discount . " \t \n ";

        echo " \t JETSEEKER Commission after Deducting discount and VAT \t" . ($commission_price_total = $commission_price - ($vat_deduction + $discount)) . " \t \n ";

        echo " \t 20% VAT of Add Extra(" . $diff . ") \t" . $vat_add_extra . " \t \n ";

        echo " \t Remaining Add Extra after Deducting VAT(" . $vat_add_extra . ") \t" . ($remaining_vat = ($diff - $vat_add_extra)) . " \t \n ";

        echo " \t Net JETSEEKER Commission (Remaining Commission + Remaining Add Extra) \t" . ($commission_price_total + $remaining_vat) . " \t \n ";

        echo " \t JETSEEKER Commission after deducting Penalty\t" . (($commission_price_total + $remaining_vat) - ($total_planty_to_fly)) . " \t \n ";

        echo "\n";

        echo "\t";



        echo " \n \n \n \t Total Booking Fee \t" . $booking_fee . " \t \n ";

        echo " \t Total SMS Fee \t" . $sms_fee . " \t \n ";

        echo " \t Total Cancel Fee \t" . $cancel_fee . " \t \n ";

        echo " \t Total Postal Fee \t" . $postal_fee . " \t \n ";

        echo " \t Total No Show Amount \t" . $No_Show_cancel2 . " \t \n ";

        echo " \t Add Extra Amount \t" . $extra_amount . " \t \n ";

        echo " \t Remaining Cancel Amount \t" . $all_cancel_remaining_amount . " \t \n ";

        echo " \t Total Extra Amount \t" . ($all_cancel_remaining_amount + $booking_fee + $sms_fee + $cancel_fee + $postal_fee + $extra_amount + $No_Show_cancel2) . " \t \n ";

        echo "\n";

        echo "\t";


        //print(trim($outputs)) . "\t\n";

    }


    public function CompanyReportExcel(Request $request)
    {

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



            if (!empty($data['airport']) && $data['airport'] == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $data['airport'] . "'";

            }



            if (!empty($data['companies']) && $data['companies'] == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $data['companies'] . "'";

            }



            if (!empty($data['admins']) && $data['admins'] == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";

            }



            if (!empty($data['payment']) && $data['payment'] == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";

            }



            if (!empty($data['status']) && $data['status'] == "all") {

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

                        $_status = " AND b.booking_action = '" . $data['status'] . "'";

                    } else {

                        $_status = "";

                    }

                }

            }

            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }

            if (isset($data['filter']) && $data['filter'] == "all") {

                $_filter = "";
                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b.created_at, '%Y-%m-%d')";
                $date_format2 = " DATE_FORMAT(b.departDate, '%Y-%m-%d')";
                $date_format3 = " DATE_FORMAT(b.returnDate, '%Y-%m-%d')";

                $_filter = " AND (" . $date_format . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format2 . " BETWEEN " . $from . " AND " . $to .' ';
                $_filter .= " OR " . $date_format3 . " BETWEEN " . $from . " AND " . $to .') ';

            } else {

                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }

            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;

        } else {

            $_current = " and b.created_at  >= CURDATE()";

            $filter_search = $_current;

        }





        $companies = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 

	 b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,

	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 

	(c.share_percentage/100*(b.booking_amount)) As fly_commission 		

	FROM airports_bookings as b 

	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	join airports as a on a.id = b.airportID

	$planty_refund

	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $join $filter_search";



        $companies = DB::select(DB::raw($companies));

        $companies = collect($companies)->map(function ($x) {

            return (array)$x;

        })->toArray();





        header('Content-Type: application/vnd.ms-excel');    //define header info for browser

        header('Content-Disposition: attachment; filename=pz-commission-report' . date('Ymd') . '.xls');

        header('Pragma: no-cache');

        header('Expires: 0');



        echo "S.NO \t Booking Ref \t Ext Ref \t Carpark Name \t Airport \t Surname \t Booking Status \t Payment Method \t Booking Date \t Departures Date \t Return Date \t Days  \t Cancel Amount \t Refund Amount \t Booking Amount \t Company Commission \t JETSEEKER Commission \t  Cancel Remaining \t Refund Via \t Penalty to \t Penalty Amount \t Total refund Amount  ";

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
            
            $output .= $company['ext_ref'] . "\t";

            $output .= $cname . "\t";

            $output .= $company['airport_name'] . "\t";

            $output .= $company['Surname'] . "\t";

            $output .= $company['booking_action'] . "\t";

            $output .= $company['payment_method'] . "\t";

            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";

            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";

            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";

            $output .= $company['no_of_days'] . "\t";

            $output .= $cancel . "\t";

            $output .= $adjust . "\t";

            $output .= $company['booking_amount'] . "\t";

            $output .= (float)number_format($company_commission, 3) . "\t";

            $output .= (float)number_format($fly_commission, 3) . "\t";

            $output .= $cancel_remaining_amount . "\t";

            $output .= $company['payment_medium'] . "\t";

            $output .= $company['palenty_to'] . "\t";

            $output .= $company['palenty_amount'] . "\t";

            $output .= ($company['palenty_amount'] + $adjust) . "\t";

            $i++;

            if ($company['palenty_to'] == "Company") {

                $total_planty_to_company += $company['palenty_amount'];

            }

            if ($company['palenty_to'] == " JETSEEKER") {

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



        echo " \n \n \n \t Booking Amount \t" . $lprice . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Cancel Amount \t" . $cancelled . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Refund Amount \t" . $allrefund . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Net Booking Amount \t" . $return_book . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Total Company Penalty Amount \t" . $total_planty_to_company . " \t \t \t \t \t \t \t \t \t \t \t \t \t \t  ";

        echo " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t  JETSEEKER Commission \t" . $commission_price . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Company Commission  \t" . ($aprice) . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Company Commission After Penalty  \t" . ($aprice - $total_planty_to_company) . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        // echo " \n \t VAT included in company invoice\t".$vat." \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        // echo " \n \t Company Commission included VAT  \t".$cprice." \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Previous adjustments \t" . $adjusts . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";

        echo " \n \t Net company Commission invoice to pay \t" . ($net_amount - $total_planty_to_company) . " \t \t \t \t \t \t \t \t \t \t \t \t \t";

        echo "\n";

        echo "\t";





        // print(trim($outputs))."\t\n";

    }


    public function invoiceOperationExcel(Request $request)
    {
        
        $user_permissions = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        
        if($user_permissions){
            $user_permissions =explode(",",$user_permissions["permissions"]);   
        }
        $data = $request->all();

        $filter_search = "";

        $planty_refund = " ";

        $b_t_table = " ";

        $_refund_via_s = "";

        $palenty_to = "";

        $penalty_select = "";

        $penalty_join = "";

        $No_Show_cancel2=0;



        $search="";



        $total=0;

        $lprice=0;

        $aprice=0;

        $l_fee=0;

        $commission_price=0;

        $extra_amount=0;

        $discount=0;

        $allrefund=0;

        $cancelled=0;

        $all_cancel_remaining_amount=0;

        $booking_fee=0;

        $sms_fee=0;

        $cancel_fee=0;

        $postal_fee=0;

        $total_planty_to_companyss=0;

        $total_planty_to_flyss=0;

		$total_pz_ds_comission = 0;
		
		$total_percent_20 = 0;
		
		$total_percent_80 = 0;

$total_bank_amount = 0;


        if (!empty($data['search']) || !empty($data['companies']) || !empty($data['filter']) || !empty($data['start_date']) || !empty($data['end_date'])) {



            if (!empty($data['search'])) {

                $search = trim(preg_replace('/\s+/', ' ', $data['search']));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if (isset($data['airport']) && $data['airport'] == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $data['airport'] . "'";

            }



            if (isset($data['admins']) && $data['admins'] == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";

            }

/*

            if (isset($data['payment']) && $data['payment'] == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";

            }

*/

            if (isset($data['status']) && $data['status'] == "all") {



                $_status = "";

            } else {

                if ($data['status'] == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($data['status'] == 'invoice') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend' OR b.booking_action = 'refund' OR b.booking_action = 'cancelled')";

                } elseif ($data['status'] == 'Refund') {



                    if ($data['refund_via'] == 'all') {

                        $_refund_via_s = "";

                    }

                    // else{

                    // $_refund_via_s =" AND bt.payment_medium = '".$_GET['refund_via']."'";

                    // }

                    if (isset($data['palenty_to']) && $data['palenty_to'] == "all") {

                        $penalty_select = "";

                        $palenty_to = "";

                    } else {

                        $palenty_to = " AND pd.penalty_to = '" . $data['palenty_to'] . "' AND pd.penalty_amount>0";

                        $penalty_select = ",bt.*,sum(pd.penalty_amount) as p_amount";

                        $penalty_join = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo inner join penalty_details as pd on pd.trans_id = bt.id";

                    }

                   

                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                    // $planty_refund = "left join " . $db->prefix . "booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                    // $b_t_table = "bt.*, ";

                } else {

                    if($data["status"]!="") {

                        $_status = " AND b.booking_action = '" . $data['status'] . "'";

                    }else {

                        $_status="";

                    }

                }

            }





            if (isset($data['companies']) && $data['companies'] == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $data['companies'] . "'";

            }


			if (isset($data['filter']) && $data['filter'] == "all") {

				$from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b.created_at,'%Y-%m-%d')";

                $_filter = " AND (" . $date_format . " BETWEEN " . $from . " AND " . $to;
				
				
                $date_format1 = " DATE_FORMAT(b.departDate,'%Y-%m-%d')";

                $_filter .= " OR " . $date_format . " BETWEEN " . $from . " AND " . $to;
				
                $date_format2 = " DATE_FORMAT(b.returnDate,'%Y-%m-%d')";

                $_filter .= " OR " . $date_format . " BETWEEN " . $from . " AND " . $to . " ) ";

            } else {

                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }


            $filter_search = $search . $_airport . $_admins . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;

        }

//$com = ($ap=='GB') ? '10' : '12';
    if(isset($data['admins']))
    {
     if($data['admins'] == "39"){

    $companies = "SELECT  b.id,adm.*, adm.name as admin_login,b.leavy_fee,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.ext_ref, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.first_name as firstName, b.created_at as createdate, b.departDate AS start_date, 
    b.returnTerminal, b.returnFlight, b.product_code,
	b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,

	b.discount_amount, b.total_amount, b.booking_amount, (b.total_amount - (c.share_percentage/100*(b.total_amount)) ) As company_commission, 
	(b.total_amount - b.booking_fee) as bank_amount,
	(c.share_percentage/100*(b.total_amount)) As fly_commission,
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	
	
	$penalty_select		

	FROM airports_bookings as b 

	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	join users as adm on adm.id = c.admin_id

	join airports as a on a.id = b.airportID

	$penalty_join

	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and  b.booking_status != 'Cancelled' and b.booking_status != 'Refund' and b.payment_status = 'success' and b.booking_action = 'Booked' and b.removed = 'No' and b.status = 'Yes' $filter_search GROUP BY b.referenceNo";

}
else
{
   
    $companies = "SELECT  b.id,adm.*, adm.name as admin_login,b.leavy_fee,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.ext_ref, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.first_name as firstName, b.created_at as createdate, b.departDate AS start_date, 
    b.returnTerminal, b.returnFlight, b.product_code,
	b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,

	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
	(b.booking_amount - b.booking_fee) as bank_amount,
	(c.share_percentage/100*(b.booking_amount)) As fly_commission,
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	
	
	$penalty_select		

	FROM airports_bookings as b 

	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	join users as adm on adm.id = c.admin_id

	join airports as a on a.id = b.airportID

	$penalty_join

	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and  b.booking_status != 'Cancelled' and b.booking_status != 'Refund' and b.payment_status = 'success' and b.booking_action = 'Booked' and b.removed = 'No' and b.status = 'Yes' $filter_search GROUP BY b.referenceNo";    
}
}
else
{
     $companies = "SELECT  b.id,adm.*, adm.name as admin_login,b.leavy_fee,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.ext_ref, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.first_name as firstName, b.created_at as createdate, b.departDate AS start_date, 
    b.returnTerminal, b.returnFlight, b.product_code,
	b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,c.share_percentage,

	b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
	(b.booking_amount - b.booking_fee) as bank_amount,
	(c.share_percentage/100*(b.booking_amount)) As fly_commission,
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	
	
	$penalty_select		

	FROM airports_bookings as b 

	left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	join users as adm on adm.id = c.admin_id

	join airports as a on a.id = b.airportID

	$penalty_join

	WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and  b.booking_status != 'Cancelled' and b.booking_status != 'Refund' and b.payment_status = 'success' and b.booking_action = 'Booked' and b.removed = 'No' and b.status = 'Yes' $filter_search GROUP BY b.referenceNo";    
}
        $companies = DB::select(DB::raw($companies));
          
        $companies = collect($companies)->map(function ($x) {

            return (array)$x;

        })->toArray();
     
        //  dd($companies);
        header('Content-Type: application/vnd.ms-excel');    //define header info for browser

        header('Content-Disposition: attachment; filename="pz_invoice_operation' . date('Y-m-d H:i') . '.xls"');

        header('Pragma: no-cache');

        header('Expires: 0');
        


        echo "S.NO \t Booking Ref  \t  Ext Ref \t  Name  \t Booking Date\t Departures Date  \t  Return Date \t Days"; if (in_array('Amounts', $user_permissions)){ echo "\t Booking Amount \t Company Commission\t PZ Commission\t";}

//if($_GET['status'] == 'Refund'){

//echo"\t Refund Via \t Palenty To \t Palenty Amount \t B Refund Amount";


//}

        // echo "\t Company Commission\t PZ Commission\t";

        print("\n");

        $i = 1;
        
         $totalamount = 0;
         $compcommission = 0;
         $pzcommission = 0;
        foreach ($companies as $company) {

            $cancel = 0;

            $adjust = 0;

            $No_Show_cancel = 0;

            $plenty_amount = 0;

            $total_planty_to_fly = 0;

            $total_planty_to_company = 0;

            $total_planty_to_flys = 0;

            $total_planty_to_companys = 0;
            
			
			
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"])->get();


            $transaction = (array)$transactions;
      

           foreach ($transactions as $transaction) {

                if ($transaction['booking_status'] != 'Noshow') {

                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                        $cancel += $transaction['payable'];

                    }

                    if ($transaction['amount_type'] == 'credit' && ($transaction['payment_case'] == 'Refund' || $transaction['payment_case'] == 'edit')) {

                        $adjust += $transaction['payable'];



                    }



                   // $penalty_trans = $db->select("SELECT * FROM " . $db->prefix . "penalty_details where trans_id = '" . $transaction['id'] . "'");

                    $penalty_trans = penalty_details::where("trans_id", $transaction["id"])->get();

                    foreach ($penalty_trans as $penalty) {

                        if ($penalty['penalty_to'] == "Company") {

                            $total_planty_to_companys += $penalty['penalty_amount'];

                        }

                        if ($penalty['penalty_to'] == "JETSEEKER") {

                            $total_planty_to_flys += $penalty['penalty_amount'];

                        }

                    }

                    $total_planty_to_company += $total_planty_to_companys;

                    $total_planty_to_fly += $total_planty_to_flys;



                    // if($transaction['palenty_amount']==''){

                    // $transaction['palenty_amount']=0;

                    // }



                    $plenty_amount += ($total_planty_to_fly + $total_planty_to_company);



                } else {



                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {

                        $No_Show_cancel += $transaction['payable'];

                        $No_Show_cancel2 += $transaction['payable'];

                    }

                }

            }



            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){

            // $refund  += $company['booking_amount'];

            // }



            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){

            // $refund  += $company['booking_amount'];

            // }



            $booking_amount = $company['booking_amount'];

            $share_percentage = $company['share_percentage'];



            // if($cancel !=  $company['booking_amount'] && $cancel > 0){

            // $booking_amount = $booking_amount - $cancel;

            // }



            $fly_commission_before_refund = !empty($company['fly_commission']) ? ($share_percentage / 100 * ($booking_amount)) : (17 / 100 * ($booking_amount));

            $company_commission_before_refund = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage / 100 * ($booking_amount))) : ($booking_amount - (17 / 100 * ($booking_amount)));

            $company_commission_after_refund = $company_commission_before_refund;



            if ($adjust > 0) {

                //$booking_amount = $booking_amount - $adjust;



                //if(($adjust <  $company['booking_amount']) && ($company['discount_amount'] > 0)){

                if ($adjust >= 0) {

                    $company_commission_after_refund = $company_commission_before_refund - $company['booking_amount'];

                } else {

                    $company_commission_after_refund = $company_commission_before_refund - $adjust;

                }



            }

            // if($adjust == $company['booking_amount']){

            // $company_commission_after_refund = $company_commission_before_refund - $adjust;

            // }



            $company_commission = $company_commission_after_refund;

            $fly_commission = $fly_commission_before_refund;
            $discount_commission = $company['discount_commission'];



            $cname = $company["id"];

//            $explode = explode("FP", $company['company_code']);

//            $cname = $explode[1];

            //$cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '" . $cname . "'");

            $cname = Company::where("id", $cname)->first();



            $cname = (array)$cname;

            $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];



            $totals = $company['total_amount'];



            $cancel_remaining_amount = 0;

            if ($cancel > 0) {

                $company_commission = 0;

                $fly_commission = 0;
                $discount_commission = 0;

                $cancel_remaining_amount = $booking_amount - $cancel;

            } elseif ($No_Show_cancel > 0) {

                $company_commission = 0;

                $fly_commission = 0;
                $discount_commission = 0;

            }

            if($company['returnTerminal']!="TBA" && $company['returnTerminal']!="") {
                $terminal = airports_terminals::where("id",$company['returnTerminal'])->first();
                $return_terminal = isset($terminal->name) ? $terminal->name:'';
            } else {
                $return_terminal ="";
            }

            $output = $i . "\t";

            $output .= $company['referenceNo'] . "\t";
            
            $output .= $company['ext_ref'] . "\t";

            $output .= $company['firstName'] . ' ' . $company['Surname'] . "\t"; 
            

            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";

            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
            
            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";

            $output .= $company['no_of_days'] . "\t";
            
            if (in_array("Amounts", $user_permissions)){
            
            $output .= $company['booking_amount'] . "\t";
            }

            // $output .=$plenty_amount."\t";

        
            //$output .=$adjust."\t";

         



            // if($_GET['status'] == 'Refund'){

            // $output .=$company['payment_medium']."\t";

            // $output .=$company['palenty_to']."\t";

            // $compcommission = (100 - $share_percentage)/100 * $booking_amount;
          

            //$output .=$nettt."\t";

            //$output .=(float)number_format($nettt ,3)."\t";
            if (in_array("Amounts", $user_permissions)){

            $output .= $company['company_commission'] . "\t";

            $output .= $company['fly_commission'] . "\t";
            }
			
            //$output .= (float)number_format($discount_commission, 2) . "\t";
			
            //$output .= (float)number_format($percent_20, 2) . "\t";
			
            //$output .= (float)number_format($percent_80, 2) . "\t";



            $i++;

            //Plenty Space
             if(isset($data['admins']))
       {
     if($data['admins'] == "39"){
              $totalamount = $totalamount + $company['total_amount'];
      }
      else
      {
           $totalamount = $totalamount + $company['booking_amount'];
      }
       }
      else
      {
           $totalamount = $totalamount + $company['booking_amount'];
      }
              $compcommission +=  $company['company_commission'];
              $pzcommission += $company['fly_commission'];
            //$total += $company['total_amount'];

            // $total += $totals;

            // $lprice += $company['booking_amount'];
            // $total_bank_amount += $company['bank_amount'];

            // $aprice += $company_commission;

            // $l_fee += $company['leavy_fee'];

            // $commission_price += $fly_commission;
			
            // $total_pz_ds_comission += $discount_commission;
			
            // $total_percent_20 += $percent_20;
			
            // $total_percent_80 += $percent_80;

            // $extra_amount += $company['booking_extra'] + $company['extra_amount'];

            // $discount += $company['discount_amount'];

            // $allrefund += $adjust;

            // $cancelled += $cancel;

            // $No_Show_cancel += $No_Show_cancel;

            // $all_cancel_remaining_amount += $cancel_remaining_amount;

            // $booking_fee += $company['booking_fee'];

            // $sms_fee += $company['smsfee'];

            // $cancel_fee += $company['cancelfee'];

            // $postal_fee += $company['postal_fee'];

            // $plenty_amount += $plenty_amount;

            // $total_planty_to_companyss += $total_planty_to_company;

            // $total_planty_to_flyss += $total_planty_to_fly;



            print(trim($output)) . "\t\n";



        }

//         $totalamount = ($total * 1) + ($discount * 1);

//         if ($allrefund > 0 || $cancelled > 0) {

//             //$totalamount = $totalamount - $allrefund - $cancelled ;

//             //$total = $total - $allrefund - $cancelled;

//             //$lprice = $lprice - $allrefund - $cancelled;

//         }

//         $totalcommission = ($commission_price * 1) - ($discount * 1);

//         $diff = ($totalamount * 1) - ($lprice * 1);

//         $netdiff = ($totalcommission * 1) + ($diff * 1);

//         $vat_deduction = ($commission_price / 100) * (20);

//         $diff = ($diff + $all_cancel_remaining_amount + $No_Show_cancel2);



//         $vat_add_extra = ($diff / 100) * (20);



      echo "\n \n \n";

        // echo "\n \n \n \t JETSEEKER Commission \t" . round($commission_price, 2) . " \t \n ";
//         // echo "\t JETSEEKER Commission with Discount \t" . round($total_pz_ds_comission, 2) . " \t \n ";
		
//         // echo " \t Total 20% Commission \t" . round($total_percent_20, 2) . " \t \n ";
		
//         // echo " \t Total 80% Commission \t" . round($total_percent_80, 2) . " \t \n ";


//         echo " \n \t Total Amount without discount \t" . $totalamount . " \t \n ";

//         echo " \t Discount given by JETSEEKER \t" . $discount . " \t \n ";

//         echo " \t Total Amount Paid by customer \t" . $total . " \t \n ";
//         //echo " \t Total Amount Paid in Bank \t" . $total_bank_amount . " \t \n ";

//         echo " \t Booking Amount given by companies \t" . $lprice . " \t \n ";

//         echo " \n\t Net Booking Amount after Refund,cancel and Penalty \t" . ($lprice - $cancelled - ($allrefund + $total_planty_to_companyss)) . " \t \n\n ";

//         echo " \t Booking Amount Cancelled \t" . $cancelled . " \t \n ";



// //echo " \t Total Refunded Amount (Including Cancel's Penalty) \t".($allrefund + $total_planty_to_company + $total_planty_to_fly)." \t \n ";



//         echo " \t Booking Amount Refunded \t" . $allrefund . " \t \n ";





//         echo " \t Total Penalty Amount \t" . ($total_planty_to_companyss + $total_planty_to_flyss) . " \t \n ";





//         echo " \t Total Company Penalty Amount \t" . $total_planty_to_companyss . " \t \n ";

//         echo " \t Total JETSEEKER Penalty Amount \t" . $total_planty_to_flyss . " \t \n\n ";

//         echo " \t Company Commission \t" . $aprice . " \t \n\n ";

//         echo " \t Company Commission After Deducting Penalty\t" . ($aprice - $total_planty_to_companyss) . " \t \n";

//         echo " \t Company Levy Amount\t" . round($l_fee, 3) . " \t \n ";

//         echo " \t Net Invoice Payable to Company \t" . round(($l_fee + ($aprice - $total_planty_to_companyss)), 3) . " \t \n  \n ";


//         echo "\n \t JETSEEKER Commission \t" . round($commission_price, 2) . " \t \n  \n ";
           $i = $i - 1 ;
      
          echo " \t Total Bookings  \t" . $i . " \t \n "; 
          
            if (in_array("Amounts", $user_permissions)){
          echo " \n \t Total Amount  \t" . $totalamount . " \t \n ";


      
          echo " \t Total Company Commission  \t" . $compcommission  . " \t \n ";

          echo " \t  Total PZ Commission \t" . $pzcommission . " \t \n ";
            }

//         echo " \t Total No Show Amount \t" . $No_Show_cancel2 . " \t \n ";

//         echo " \t Add Extra Amount \t" . $extra_amount . " \t \n ";

//         echo " \t Remaining Cancel Amount \t" . $all_cancel_remaining_amount . " \t \n ";

//         echo " \t Total Extra Amount \t" . ($all_cancel_remaining_amount + $booking_fee + $sms_fee + $cancel_fee + $postal_fee + $extra_amount + $No_Show_cancel2) . " \t \n ";

//         echo "\n";

//         echo "\t";

   

    }

    public function invoiceOperation(Request $request){

         
        

        $data = $request->all();
        // dd($data);

        $admins = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();

        $airports = airport::all();

        $companies_dlist = Company::all();





        $filter_search = "";

        $planty_refund = " ";

        $b_t_table = " ";

        $_refund_via_s = "";

        $palenty_to = "";

        $search = "";

        $join = "";

        $show = 0;

        if ($request->input("submit") == "Search") {

            $show = 1;

        }

        if ($data != null && count($data) > 0) {



            if (!empty($data['search'])) {

                $search = trim(preg_replace('/\s+/', ' ', $data['search']));

                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";

            }



            if (!empty($data['airport']) && $data['airport'] == "all") {

                $_airport = "";

            } else {

                $_airport = " AND b.airportID = '" . $data['airport'] . "'";

            }



            if (!empty($data['companies']) && $data['companies'] == "all") {

                $_companies = "";

            } else {

                $_companies = " AND b.companyId = '" . $data['companies'] . "'";

            }



            if (!empty($data['admins']) && $data['admins'] == "all") {

                $_admins = "";

            } else {

                $_admins = " AND c.admin_id = '" . $data['admins'] . "'";

            }

/*

            if (!empty($data['payment']) && $data['payment'] == "all") {

                $_payment = "";

            } else {

                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";

            }
*/


            if (!empty($data['status']) && $data['status'] == "all") {

                $_status = "";

            } else {

                if ($data['status'] == 'Booked') {

                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";

                } elseif ($data['status'] == 'Refund') {



                   /* if ($data['refund_via'] == 'all') {

                        $_refund_via_s = "";

                    } else {

                        $_refund_via_s = " AND bt.payment_medium = '" . $data['refund_via'] . "'";

                    }

                    if ($data['palenty_to'] == 'all') {

                        $palenty_to = "";

                    } else {

                        $palenty_to = " AND bt.palenty_to = '" . $data['palenty_to'] . "'";

                    }
                       */




                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                    $planty_refund = "left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";

                    $b_t_table = "bt.*, ";

                } else {

                    $_status = " AND b.booking_action = '" . $data['status'] . "'";

                }

            }

            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }

            if (isset($data['filter']) && $data['filter'] == "all") {

                //$_filter = "";
				$from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b.created_at,'%Y-%m-%d')";

                $_filter = " AND (" . $date_format . " BETWEEN " . $from . " AND " . $to;
				
				
                $date_format1 = " DATE_FORMAT(b.departDate,'%Y-%m-%d')";

                $_filter .= " OR " . $date_format . " BETWEEN " . $from . " AND " . $to;
				
                $date_format2 = " DATE_FORMAT(b.returnDate,'%Y-%m-%d')";

                $_filter .= " OR " . $date_format . " BETWEEN " . $from . " AND " . $to . " ) ";

            } else {

                $from = date("'Y-m-d'", strtotime($data['start_date']));

                $to = date("'Y-m-d'", strtotime($data['end_date']));

                $date_format = " DATE_FORMAT(b." . $data['filter'] . ",'%Y-%m-%d')";

                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;

            }

		$filter_search = $search . $_airport . $_admins . $_companies . $_status  . $_filter;

        } else {

            $_current = " and b.created_at  >= CURDATE()";

            $filter_search = $_current;

        }



        $limit = 20;

        $skip = 0;

        $page = 1;

        if ($request->input("page") > 1) {

            $page = $request->input("page");

            $skip = $limit * ($page - 1);

        }



    if(isset($data['admins']))
    {
          if($data['admins'] == "39"){
             
         
        $companies = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, b.booking_fee, 
    b.total_amount as finalamount,
   	b.booking_amount AS ListPrice, b.total_amount AS client_paid,
	(b.total_amount - b.booking_fee) as bank_amount,

	(b.total_amount - (c.share_percentage/100*(b.total_amount))) As AmountPaid,
        
    (c.share_percentage/100*(b.total_amount)) As commission,	
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE  b.payment_status = 'success' and  b.booking_status != 'Cancelled' and b.booking_status != 'Noshow' and b.booking_status != 'Refund' and b.booking_status != 'Abandon' and b.removed = 'No' and b.status = 'Yes' $join $filter_search 
	ORDER BY b.id DESC LIMIT $skip,$limit
	";
	



        $companies2 = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, b.booking_fee, 
     b.total_amount as finalamount,
	b.booking_amount AS ListPrice, b.total_amount AS client_paid,
	(b.total_amount - b.booking_fee) as bank_amount,

	(b.total_amount - (c.share_percentage/100*(b.total_amount))) As AmountPaid,

    (c.share_percentage/100*(b.total_amount)) As commission,	
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE  b.payment_status = 'success' and b.removed = 'No' and  b.booking_status != 'Cancelled' and b.booking_status != 'Noshow' and b.booking_status != 'Refund' and b.booking_status != 'Abandon' and b.status = 'Yes' $join $filter_search 
	ORDER BY b.id DESC
	";
 }
 else
    {
        
                $companies = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, b.booking_fee, 
     b.booking_amount as finalamount,
	b.booking_amount AS ListPrice, b.total_amount AS client_paid,
	(b.booking_amount - b.booking_fee) as bank_amount,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission,	
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE  b.payment_status = 'success' and  b.booking_status != 'Cancelled' and b.booking_status != 'Noshow' and b.booking_status != 'Refund' and b.booking_status != 'Abandon' and b.removed = 'No' and b.status = 'Yes' $join $filter_search 
	ORDER BY b.id DESC LIMIT $skip,$limit
	";
	



        $companies2 = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, b.booking_fee, 
    b.booking_amount as finalamount,
	b.booking_amount AS ListPrice, b.total_amount AS client_paid,
	(b.total_amount - b.booking_fee) as bank_amount,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission,	
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE  b.payment_status = 'success' and b.removed = 'No' and  b.booking_status != 'Cancelled' and b.booking_status != 'Noshow' and b.booking_status != 'Refund' and b.booking_status != 'Abandon' and b.status = 'Yes' $join $filter_search 
	ORDER BY b.id DESC
	";
    }
}
else
{
      $companies = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, b.booking_fee, 
     b.booking_amount as finalamount,
	b.booking_amount AS ListPrice, b.total_amount AS client_paid,
	(b.booking_amount - b.booking_fee) as bank_amount,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission,	
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE  b.payment_status = 'success' and  b.booking_status != 'Cancelled' and b.booking_status != 'Noshow' and b.booking_status != 'Refund' and b.booking_status != 'Abandon' and b.removed = 'No' and b.status = 'Yes' $join $filter_search 
	ORDER BY b.id DESC LIMIT $skip,$limit
	";
	



        $companies2 = "SELECT $b_t_table b.id, b.referenceNo, b.ext_ref, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 

	b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, b.booking_fee, 
    b.booking_amount as finalamount,
	b.booking_amount AS ListPrice, b.total_amount AS client_paid,
	(b.total_amount - b.booking_fee) as bank_amount,

	(b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,

    (c.share_percentage/100*(b.booking_amount)) As commission,	
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	

	FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 

	$planty_refund

	WHERE  b.payment_status = 'success' and b.removed = 'No' and  b.booking_status != 'Cancelled' and b.booking_status != 'Noshow' and b.booking_status != 'Refund' and b.booking_status != 'Abandon' and b.status = 'Yes' $join $filter_search 
	ORDER BY b.id DESC
	";
}


        $companies = DB::select(DB::raw($companies));
    
        $companies2 = DB::select(DB::raw($companies2));
        // dd($companies2);
        $total_list_price = 0;
    	$total_amount_paid = 0;
    	$total_pz_commission = 0;
    	$booking_amount_paid = 0 ;
    	$total_booking = 0;
        foreach($companies2 as $company){
            $booking_amount_paid = $booking_amount_paid + $company->bank_amount;
            $total_list_price = $total_list_price + $company->ListPrice;
            $total_amount_paid = $total_amount_paid + $company->AmountPaid;
            $total_pz_commission = $total_pz_commission + $company->commission;
            $total_booking++;
            
        }
        // dd($total_list_price . ' ' . $total_amount_paid .' ' . $total_pz_commission .' ' .  $booking_amount_paid);
 
         
        $companies = collect($companies)->map(function ($x) {

            return (array)$x;

        })->toArray();

        

    

        $paginator = new Paginator($companies2, count($companies2), $limit, $page, [

            'path' => $request->url(),

            'query' => $request->query(),

        ]);

        return view("admin.reports.invoice_operations",["booking_amount_paid" => $booking_amount_paid  , "companies" => $companies, "total_booking" => $total_booking, "total_list_price" => $total_list_price, "total_amount_paid" => $total_amount_paid, "total_pz_commission" => $total_pz_commission, "companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $admins, "show" => $show, "paginator" => $paginator]);

    }


 
    public function CompanyDepartureCommissionReport(Request $request)
    {
        
        // dd($request->all());
        $data = $request->all();
        $admins = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();
        $airports = airport::all();
        $companies_dlist = Company::all();
        $show = 0;
        if ($request->input("submit") == "Search") {
            $show = 1;
        } 
        $filter_search = "";
        $planty_refund = " ";
        $b_t_table = " ";
        $_refund_via_s = "";
        $palenty_to = "";
        $search = "";
        $join = "";
        $show = 0;
        $start_date = "";
        $end_date = ""; 
        $_admins = "";
        
        if ($data != null && count($data) > 0) {

            if (!empty($data['search'])) {
                $search = trim(preg_replace('/\s+/', ' ', $data['search']));
                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";
            }

            if (!empty($data['airport']) && $data['airport'] == "all") {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all") {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            } 

            if (!empty($data['payment']) && $data['payment'] == "all") {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }
            $_status = " AND b.booking_action = 'Booked' AND b.booking_status = 'Completed'";  
            $from = date("'Y-m-d'", strtotime($data['start_date']));
            $to = date("'Y-m-d'", strtotime($data['end_date'])); 
            $_filter = " AND  b.departDate BETWEEN " . $from . " AND " . $to; 
            $_filter .= " AND  b.departDate <= CURDATE() ";
            $filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
            
        } else { 
            $_current = " and b.departDate BETWEEN DATE_SUB(CURDATE(), INTERVAL -1 WEEK) AND CURDATE() and b.booking_status = 'Completed' and b.booking_action = 'Booked' ";
            $filter_search = $_current;
        }

        $limit = 20;
        $skip = 0;
        $page = 1;
        if ($request->input("page") > 1) {
            $page = $request->input("page");
            $skip = $limit * ($page - 1);
        }


    $companies = "SELECT $b_t_table b.id, b.referenceNo, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 
    b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, 
    b.booking_amount AS ListPrice,
    (b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,
    (c.share_percentage/100*(b.booking_amount)) As commission,   
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	
    FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    $planty_refund
    WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $join $filter_search ORDER BY departDate DESC";

    $companies2 = "SELECT $b_t_table b.id, b.referenceNo, b.companyId, b.title, b.first_name, b.last_name,b.parent_id, b.payment_method, b.created_at AS createdate, 
    b.departDate AS start_date,  b.returnDate AS end_date, b.no_of_days, 
    b.booking_amount AS ListPrice,
    (b.booking_amount - (c.share_percentage/100*(b.booking_amount))) As AmountPaid,
    (c.share_percentage/100*(b.booking_amount)) As commission,
    ((c.share_percentage/100*(b.booking_amount))- b.discount_amount + b.booking_fee) As discount_commission	   
    FROM airports_bookings as b left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    $planty_refund
    WHERE b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $join $filter_search ORDER BY departDate DESC";

 
        $companies = DB::select(DB::raw($companies));
        $companies2 = DB::select(DB::raw($companies2));


        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();
        
        if(count($companies)>0){
            $show = 1;
        }

        $paginator = new Paginator($companies2, count($companies2), $limit, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
        //company send to view 
        return view("admin.reports.company_departure_report", ["companies" => $companies, "companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $admins, "show" => $show, "paginator" => $paginator]);

    }

 public function CompanyDepartureReportExcelPZ(Request $request)
    {
        $user_permissions = users_roles::where('user_id',Auth::user()->id)->first()->toArray();
        
        if($user_permissions){
            $user_permissions =explode(",",$user_permissions["permissions"]);   
        }

        $_filter = "";
        $b_t_table = " ";
        $filter_search = "";
        $planty_refund = " ";
        $outputs = 0;
        $search = "";
        $total = 0;
        $lprice = 0;
        $aprice = 0;
        $commission_price = 0;
        $extra_amount = 0;
        $discount = 0;
        $allrefund = 0;
        $cancelled = 0;
        $all_cancel_remaining_amount = 0;
        $booking_fee = 0;
        $sms_fee = 0;
        $cancel_fee = 0;
        $postal_fee = 0;
        $join = "";
        $planty_refund = " left join booking_transaction as bt on bt.referenceNo = b.referenceNo ";
        $b_t_table = "bt.*, ";
        $No_Show_cancel2 = 0;
        $total_planty_to_company = 0;
        $total_planty_to_fly = 0;
        if (!empty($request->get('search')) || !empty($request->get('companies')) || !empty($request->get('filter')) || !empty($request->get('start_date')) || !empty($request->get('end_date'))) {


            $_refund_via_s = "";
            $palenty_to = "";
            if (!empty($request->get('search'))) {
                $search = trim(preg_replace('/\s+/', ' ', $request->get('search')));
                $search = " and (b.referenceNo like '%" . $search . "%' or b.first_name like '%" . $search . "%' or b.last_name like '%" . $search . "%' or  b.deptFlight like '%" . $search . "%' or  b.returnFlight like '%" . $search . "%')";
            }

            if ($request->get('airport') == "all") {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $request->get('airport') . "'";
            }

            // if ($request->get('admins') == "all") {
            //     $_admins = "";
            // } else {
            //     $_admins = " AND c.admin_id = '" . $request->get('admins') . "'";
            // }

            if ($request->get('payment') == "all") {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $request->get('payment') . "'";
            }

            if ($request->get('status') == "all") { 
                $_status = "";
            } else {
                if ($request->get('status') == 'Booked') {
                    $_status = " AND (b.booking_action = 'Booked' OR b.booking_action = 'Amend')";
                } elseif ($request->get('status') == 'Refund') {

                    if ($request->get('refund_via') == 'all') {
                        $_refund_via_s = "";
                    } else {
                        $_refund_via_s = " AND bt.payment_medium = '" . $request->get('refund_via') . "'";
                    }
                    if ($request->get('palenty_to') == 'all') {
                        $palenty_to = "";
                    } else {
                        $palenty_to = " AND bt.palenty_to = '" . $request->get('palenty_to') . "'";
                    }

                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";

                } else {
                    $_status = " AND b.booking_action = '" . $request->get('status') . "'";
                }
            }

            if ($request->get('companies') == "all") {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $request->get('companies') . "'";
            }

            if ($request->get('filter') == "all") {
                $_filter = "";
            } else {
                $from = date("'Y-m-d'", strtotime($request->get('start_date')));
                $to = date("'Y-m-d'", strtotime($request->get('end_date')));
                $date_format = " DATE_FORMAT(b.departDate,'%Y-%m-%d')";
                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }

            $filter_search=$search.$_airport.$_payment.$_companies.$_filter;

            // $filter_search = $_filter;
        }
        //$com = ($ap=='GB') ? '10' : '12';

  

    $companies = "SELECT $b_t_table b.id,b.extra_amount,b.booking_fee,b.postal_fee,b.smsfee, b.referenceNo, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.booking_extra, a.name as airport_name, b.last_name As Surname, b.createdate, b.departDate AS start_date, 
    b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
    b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
    (c.share_percentage/100*(b.booking_amount)) As fly_commission       
    FROM airports_bookings as b 
    left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    join airports as a on a.id = b.airportID 
    $planty_refund
    WHERE  
    b.booking_status = 'Completed'
    and b.booking_action = 'Booked' 
    and b.payment_status = 'success'
    and b.removed = 'No' 
    and b.status = 'Yes'
    $join $filter_search
    ORDER BY b.departDate ASC ";
 

    //$planty_refund
   // WHERE  b.booking_status != 'Abandon' and b.booking_status != 'Pending' and b.payment_status = 'success' and b.removed = 'No' and b.status = 'Yes' $filter_search";
 
    //echo $companies; die();
        $companies = DB::select(DB::raw($companies));
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();
         
        header('Content-Type: application/vnd.ms-excel');    //define header info for browser
        header('Content-Disposition: attachment; filename=JETSEEKER-commission-report' . date('Ymd') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "S.NO \t Booking Ref \t Carpark Name \t Airport \t Surname \t Booking Status \t Payment Method \t Booking Date \t Departures Date \t Return Date \t Days \t Vehicle Reg \t Vehicle Make \t Vehicle Model \t Vehicle Color \t Telephone"; if (in_array("Amounts", $user_permissions)){echo " \t Discount Amount \t Adjustment Amount \t Cancel Amount \t  Cancel Remaining \t B.Refund Amount \t Total Amount \t Booking Amount \t Company Commission \t JETSEEKER Commission \t Ns \t Refund Via \t Palenty To \t Palenty Amount \t T Refund Amount";}
        print("\n");
        $i = 1;
        foreach ($companies as $company) {
            $cancel = 0;
            $adjust = 0;
            $No_Show_cancel = 0;
            $No_Show_cancel2 = 0;
            $plenty_amount = 0;
            $total_planty_to_fly = 0;
            $total_planty_to_company = 0;
            $total_planty_to_flys = 0;
            $total_planty_to_companys = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"])->get();

            $transaction = (array)$transactions;
            foreach ($transactions as $transaction) {
                if ($transaction['booking_status'] != 'Noshow') {
                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                        $cancel += $transaction['payable'];
                    }
                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'Refund') {
                        $adjust += $transaction['payable'];

                    }
                } else {

                    if ($transaction['amount_type'] == 'credit' && $transaction['payment_case'] == 'cancel') {
                        $No_Show_cancel += $transaction['payable'];
                        $No_Show_cancel2 += $transaction['payable'];
                    }
                }
            }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] > 0){
            // $refund  += $company['booking_amount'];
            // }

            // if($company['booking_action'] =='Cancelled' && $company['cancelfee'] !=""){
            // $refund  += $company['booking_amount'];
            // }

            $booking_amount = $company['booking_amount'];
            $share_percentage = $company['share_percentage'];

            // if($cancel !=  $company['booking_amount'] && $cancel > 0){
            // $booking_amount = $booking_amount - $cancel;
            // }

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


            $explode = explode("FP", $company['company_code']);
            // $cname = $explode[1];
            // $cname = $db->get_row("SELECT name from " . $db->prefix . "companies where id = '".$cname."'");
            // $cname = isset($cname['name']) ? $cname['name'] : $company['booked_type'];
            $cname = $company['company_name'];
            // $company_commission = !empty($company['company_commission']) ? $company['company_commission'] : ($company['booking_amount'] - (17/100*($company['booking_amount'])));
            // $fly_commission = !empty($company['fly_commission']) ? $company['fly_commission'] : (17/100*($company['booking_amount']));
            // $company_commission = !empty($company['company_commission']) ? ($booking_amount - ($share_percentage/100*($booking_amount))) : ($booking_amount - (17/100*($booking_amount)));
            // $fly_commission = !empty($company['fly_commission']) ? ($share_percentage/100*($booking_amount)) : (17/100*($booking_amount));

            $totals = $company['total_amount'];

            //if($refund > 0){
            // if($adjust == $company['booking_amount'] || $cancel == $company['booking_amount']){
            $cancel_remaining_amount = 0;
            if ($cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
                $cancel_remaining_amount = $booking_amount - $cancel;
            } elseif ($No_Show_cancel > 0) {
                $company_commission = 0;
                $fly_commission = 0;
            }

            $output = $i . "\t";
            $output .= $company['referenceNo'] . "\t";
            $output .= $cname . "\t";
            $output .= $company['airport_name'] . "\t";
            $output .= $company['Surname'] . "\t";
            $output .= $company['booking_action'] . "\t";
            $output .= $company['payment_method'] . "\t";
            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";
            $output .= $company['no_of_days'] . "\t";
            $output .= $company['registration'] . "\t";
            $output .= $company['make'] . "\t";
            $output .= $company['model'] . "\t";
            $output .= $company['color'] . "\t";
            $output .= $company['phone_number'] . "\t";
            if (in_array("Amounts", $user_permissions)){
            $output .= $company['discount_amount'] . "\t";

            $output .= $adjust . "\t";
            $output .= $cancel . "\t";
            $output .= $cancel_remaining_amount . "\t";
            //$output .=$company['booking_extra']."\t";
            $output .= $adjust . "\t";
            $output .= $totals . "\t";
            $output .= $company['booking_amount'] . "\t";
            $output .= (float)number_format($company_commission, 3) . "\t";
            $output .= (float)number_format($fly_commission, 3) . "\t";
            $output .= (float)number_format($No_Show_cancel, 3) . "\t";
            $output .= $company['payment_medium'] . "\t";
            $output .= $company['palenty_to'] . "\t";
            $output .= $company['palenty_amount'] . "\t";
            $output .= ($company['palenty_amount'] + $adjust) . "\t";
            }
            $i++;
            if ($company['palenty_to'] == "Company") {
                $total_planty_to_company += $company['palenty_amount'];
            }
            if ($company['palenty_to'] == "JETSEEKER") {
                $total_planty_to_fly += $company['palenty_amount'];
            }
            //$total += $company['total_amount'];
            $total += $totals;
            $lprice += $company['booking_amount'];
            $aprice += $company_commission;
            $commission_price += $fly_commission;
            $extra_amount += $company['booking_extra'] + $company['extra_amount'];
            $discount += $company['discount_amount'];
            $allrefund += $adjust;
            $cancelled += $cancel;
            $No_Show_cancel += $No_Show_cancel;
            $all_cancel_remaining_amount += $cancel_remaining_amount;
            $booking_fee += $company['booking_fee'];
            $sms_fee += $company['smsfee'];
            $cancel_fee += $company['cancelfee'];
            $postal_fee += $company['postal_fee'];
            
            print(trim($output)) . "\t\n";

        }
        
        
        $totalamount = ($total * 1) + ($discount * 1);
        if ($allrefund > 0 || $cancelled > 0) {
            //$totalamount = $totalamount - $allrefund - $cancelled ;
            //$total = $total - $allrefund - $cancelled;
            //$lprice = $lprice - $allrefund - $cancelled;
        }
        $totalcommission = ($commission_price * 1) - ($discount * 1);
        $diff = ($totalamount * 1) - ($lprice * 1);
        $netdiff = ($totalcommission * 1) + ($diff * 1);
        $vat_deduction = ($commission_price / 100) * (20);
        $diff = ($diff + $all_cancel_remaining_amount + $No_Show_cancel2);

        $vat_add_extra = ($diff / 100) * (20);

        if (in_array("Amounts", $user_permissions)){
        echo " \n \n \n \t Total Amount without discount \t" . $totalamount . " \t \n ";
        echo " \t Discount given by JETSEEKER \t" . $discount . " \t \n ";
        echo " \t Total Amount Paid by customer \t" . $total . " \t \n ";
        echo " \t Booking Amount given by companies \t" . $lprice . " \t \n ";
        echo " \t Amount Cancelled \t" . $cancelled . " \t \n ";

        echo " \t Total Refunded Amount \t" . ($allrefund + $total_planty_to_company + $total_planty_to_fly) . " \t \n ";

        echo " \t Booking Amount Refunded \t" . $allrefund . " \t \n ";

        
        echo " \t Total Palenty Amount \t" . ($total_planty_to_company + $total_planty_to_fly) . " \t \n ";


        echo " \t Total Company planty Amount \t" . $total_planty_to_company . " \t \n ";
        echo " \t Total JETSEEKER planty Amount \t" . $total_planty_to_fly . " \t \n ";
        echo " \t Company Commission \t" . $aprice . " \t \n ";
        echo " \t Company Commission After Deducting Planty\t" . ($aprice - $total_planty_to_company) . " \t \n ";
        echo " \n ";
        echo " \t Difference (Including Remaining Cancel and NO Show) \t" . ($diff) . " \t \n ";
        echo " \n ";
        echo " \t JETSEEKER Commission \t" . $commission_price . " \t \n ";

        echo " \t 20% VAT of " . $commission_price . " \t -" . $vat_deduction . " \t \n ";
        echo " \t Discount deducted \t" . $discount . " \t \n ";
        echo " \t JETSEEKER Commission after Deducting discount and VAT \t" . ($commission_price_total = $commission_price - ($vat_deduction + $discount)) . " \t \n ";
        echo " \t 20% VAT of Add Extra(" . $diff . ") \t" . $vat_add_extra . " \t \n ";
        echo " \t Remaining Add Extra after Deducting VAT(" . $vat_add_extra . ") \t" . ($remaining_vat = ($diff - $vat_add_extra)) . " \t \n ";
        echo " \t Net JETSEEKER Commission (Remaining Commission + Remaining Add Extra) \t" . ($commission_price_total + $remaining_vat) . " \t \n ";
        echo " \t JETSEEKER Commission after deducting Palenty\t" . (($commission_price_total + $remaining_vat) - ($total_planty_to_fly)) . " \t \n ";
        echo "\n";
        echo "\t";

        echo " \n \n \n \t Total Booking Fee \t" . $booking_fee . " \t \n ";
        echo " \t Total SMS Fee \t" . $sms_fee . " \t \n ";
        echo " \t Total Cancel Fee \t" . $cancel_fee . " \t \n ";
        echo " \t Total Postal Fee \t" . $postal_fee . " \t \n ";
        echo " \t Total No Show Amount \t" . $No_Show_cancel2 . " \t \n ";
        echo " \t Add Extra Amount \t" . $extra_amount . " \t \n ";
        echo " \t Remaining Cancel Amount \t" . $all_cancel_remaining_amount . " \t \n ";
        echo " \t Total Extra Amount \t" . ($all_cancel_remaining_amount + $booking_fee + $sms_fee + $cancel_fee + $postal_fee + $extra_amount + $No_Show_cancel2) . " \t \n ";
        }
        echo "\n";
        echo "\t"; 
        //print(trim($outputs)) . "\t\n";
    }
    
    public function CompanyDepartureReportExcel(Request $request)
    {
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

            if (!empty($data['airport']) && $data['airport'] == "all") {
                $_airport = "";
            } else {
                $_airport = " AND b.airportID = '" . $data['airport'] . "'";
            }

            if (!empty($data['companies']) && $data['companies'] == "all") {
                $_companies = "";
            } else {
                $_companies = " AND b.companyId = '" . $data['companies'] . "'";
            }

            // if (!empty($data['admins']) && $data['admins'] == "all") {
            //     $_admins = "";
            // } else {
            //     $_admins = " AND c.admin_id = '" . $data['admins'] . "'";
            // }

            if (!empty($data['payment']) && $data['payment'] == "all") {
                $_payment = "";
            } else {
                $_payment = " AND b.payment_method = '" . $data['payment'] . "'";
            }

            if (!empty($data['status']) && $data['status'] == "all") {
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
                        $_status = " AND b.booking_action = '" . $data['status'] . "'";
                    } else {
                        $_status = "";
                    }
                }
            }
            // if($request->input('filter')==null) {   $data['filter'] = 'createdate'; }
            if (isset($data['filter']) && $data['filter'] == "all") {
                $_filter = "";
            } else {
                $from = date("'Y-m-d'", strtotime($data['start_date']));
                $to = date("'Y-m-d'", strtotime($data['end_date']));
                $date_format = " DATE_FORMAT(b.departDate" . $data['filter'] . ",'%Y-%m-%d')";
                $_filter = " AND " . $date_format . " BETWEEN " . $from . " AND " . $to;
            }
            //$filter_search = $search . $_airport . $_admins . $_payment . $_companies . $_status . $_refund_via_s . $palenty_to . $_filter;
            $filter_search = $search . $_airport  . $_payment . $_companies . $_filter;
        
            
            
        } else {
            $_current = " and b.departDate  >= CURDATE()";
            $filter_search = $_current;
        }


    $companies = "SELECT $b_t_table b.id, b.referenceNo, b.booking_action, b.cancelfee, b.booked_type, b.payment_method, c.share_percentage, c.name As company_name,c.company_code, b.extra_amount, a.name as airport_name, b.last_name As Surname, b.created_at AS createdate, b.departDate AS start_date, 
    b.returnDate AS end_date, b.no_of_days, b.registration, b.make, b.model, b.color, b.phone_number,
    b.discount_amount, b.total_amount, b.booking_amount, (b.booking_amount - (c.share_percentage/100*(b.booking_amount)) ) As company_commission, 
    (c.share_percentage/100*(b.booking_amount)) As fly_commission       
    FROM airports_bookings as b 
    left join companies as c on (b.companyId = c.id OR b.companyId = c.aph_id) 
    join airports as a on a.id = b.airportID
    $planty_refund
    WHERE  
    b.booking_status = 'Completed'
    and b.booking_action = 'Booked' 
    and b.payment_status = 'success'
    and b.removed = 'No' 
    and b.status = 'Yes'
    $join $filter_search
    ORDER BY b.departDate ASC ";
         
        $companies = DB::select(DB::raw($companies));
        $companies = collect($companies)->map(function ($x) {
            return (array)$x;
        })->toArray();


        header('Content-Type: application/vnd.ms-excel');    //define header info for browser
        header('Content-Disposition: attachment; filename=commission-report' . date('Ymd') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "S.NO \t Booking Ref \t Carpark Name \t Airport \t Surname \t Booking Status \t Payment Method \t Booking Date \t Departures Date \t Return Date \t Days  \t Cancel Amount \t Refund Amount \t Booking Amount \t Company Commission \t JETSEEKER Commission \t  Cancel Remaining \t Refund Via \t Palenty to \t Palenty Amount \t Total refund Amount  ";
        print("\n");
        $i = 1;
        foreach ($companies as $company) {

            $cancel = 0;
            $adjust = 0;
            $transactions = booking_transaction::where("referenceNo", $company["referenceNo"])->get();

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
            $output .= $company['airport_name'] . "\t";
            $output .= $company['Surname'] . "\t";
            $output .= $company['booking_action'] . "\t";
            $output .= $company['payment_method'] . "\t";
            $output .= date("d/m/Y", strtotime($company['createdate'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['start_date'])) . "\t";
            $output .= date("d/m/Y", strtotime($company['end_date'])) . "\t";
            $output .= $company['no_of_days'] . "\t";
            $output .= $cancel . "\t";
            $output .= $adjust . "\t";
            $output .= $company['booking_amount'] . "\t";
            $output .= (float)number_format($company_commission, 3) . "\t";
            $output .= (float)number_format($fly_commission, 3) . "\t";
            $output .= $cancel_remaining_amount . "\t";
            $output .= $company['payment_medium'] . "\t";
            $output .= $company['palenty_to'] . "\t";
            $output .= $company['palenty_amount'] . "\t";
            $output .= ($company['palenty_amount'] + $adjust) . "\t";
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

        echo " \n \n \n \t Booking Amount \t" . $lprice . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Cancel Amount \t" . $cancelled . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Refund Amount \t" . $allrefund . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Net Booking Amount \t" . $return_book . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Total Company planty Amount \t" . $total_planty_to_company . " \t \t \t \t \t \t \t \t \t \t \t \t \t \t  ";
        echo " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t JETSEEKER Commission \t" . $commission_price . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Company Commission  \t" . ($aprice) . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Company Commission After Palenty  \t" . ($aprice - $total_planty_to_company) . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        // echo " \n \t VAT included in company invoice\t".$vat." \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        // echo " \n \t Company Commission included VAT  \t".$cprice." \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Previous adjustments \t" . $adjusts . " \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t \t \t \t \t \t \t \t \t \t \t \t \t \t \t ";
        echo " \n \t Net company Commission invoice to pay \t" . ($net_amount - $total_planty_to_company) . " \t \t \t \t \t \t \t \t \t \t \t \t \t";
        echo "\n";
        echo "\t";


        // print(trim($outputs))."\t\n";
    }


}

