<?php

namespace App\Http\Controllers;

use App\Models\airports_bookings;
use App\Library\functions;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Mail;


class CronController extends Controller
{
    public $_setting = [];

    /**
     * Site agent id — always required so Magr shared DB rows are never claimed cross-brand.
     * JetSeeker-parity flow, with forced agent scope for white-labels.
     */
    private function siteAgentId(): int
    {
        if (function_exists('current_agent_id')) {
            $id = (int) current_agent_id();
            if ($id > 0) {
                return $id;
            }
        }
        if (function_exists('current_site_agent_id')) {
            $id = (int) current_site_agent_id();
            if ($id > 0) {
                return $id;
            }
        }

        return 9;
    }

    function index() {

        $agentId = $this->siteAgentId();
        $bookings = airports_bookings::query()
            ->where('incomplete_email', '0')
            ->whereIn('booking_status', ['Abandon', 'incompleted'])
            ->where('booking_action', 'Abandon')
            ->where('agentID', $agentId)
            ->get();

        foreach($bookings as $booking){
                $id=$booking->id;
                $link = url('/booking/incomplete/' . $id);
                //send email to customer
                
                 $template_data["link"] ="<a href=".$link." >Click Here</a>";
                 $template_data["username"] = $booking->first_name.' '.$booking->last_name;
                echo "<br>".$booking->email."---send email to customer";
                $email_send = new EmailController();
                $email_send->sendGmail("Incomplete Booking", $booking->email, $template_data);
                $update = airports_bookings::where('id',  $booking->id)
                    ->where('agentID', $agentId)
                    ->update(['incomplete_email'=>1]);
            
        }
    } // end of function
    
    function sendsms_incomplete() {

        $agentId = $this->siteAgentId();
        $bookings = airports_bookings::query()
            ->where('incomplete_sms', '0')
            ->whereIn('booking_status', ['Abandon', 'incompleted'])
            ->where('booking_action', 'Abandon')
            ->where('agentID', $agentId)
            ->get();

        foreach($bookings as $booking){
            
                $id=$booking->id;
                $number = $booking->phone_number;
                $link = url('/booking/incomplete/' . $id);
                 $template_data["link"] ="<a href=".$link." >Click Here</a>";
                 $template_data["username"] = $booking->first_name.' '.$booking->last_name;
                echo "<br>".$number."---send sms to customer";
                $sms_send = new functions();
                $sms_send->incomplete_sms($number,$link);
                $update = airports_bookings::where('id',  $booking->id)
                    ->where('agentID', $agentId)
                    ->update(['incomplete_sms'=>1]);
         
        }
    }

}
