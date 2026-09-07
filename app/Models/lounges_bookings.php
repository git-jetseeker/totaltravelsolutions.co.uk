<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class lounges_bookings extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'airportID', 'country_code', 'lounge_id', 'customerId', 'title', 'first_name',
        'last_name', 'email', 'phone_number', 'fulladdress', 'address', 'terminal',
        'town', 'postal_code', 'passenger', 'additional_pass_details', 'referenceNo',
        'ext_ref', 'referenceNo_ext', 'referenceLink_ext', 'check_in', 'check_in_time',
        'adults', 'infants', 'children', 'deprTerminal', 'deptFlight', 'returnDate',
        'returnTerminal', 'returnFlight', 'no_of_days', 'discount_code', 'discount_amount',
        'booking_amount', 'extra_amount', 'smsfee', 'postal_fee', 'booking_fee', 'cancelfee',
        'agent_commission', 'total_amount', 'currency_allowed', 'booking_status',
        'booking_action', 'payment_status', 'PayerID', 'api_res', 'token', 'status',
        'booked_type', 'browser_data', 'email_status', 'payment_method', 'email_respond',
        'removed', 'traffic_src', 'lounge_available', 'intent_id', 'agentID', 'lounge_api',
        'lounge_name', 'lounge_code', 'updated_at', 'created_at', 'createdate', 'modifydate',
    ];

    public function lounge()
    {
        return $this->belongsTo(Lounges::class, 'lounge_id');
    }

    public function airport()
    {
        return $this->belongsTo(airport::class, 'airportID');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function getSingleRowById($id)
    {
        return lounges_bookings::where('id', $id)->first();
    }

    public function getTranscation()
    {
        return $this->belongsTo(booking_transaction::class, 'id');
    }

    public function getThisMonthlySale()
    {
        $month = date('m');

        $query = $this->select(
            DB::raw('DATE_FORMAT(created_at, "%d") as dayDate'),
            DB::raw('count(*) as total_booking')
        );

        $query = $query->where(DB::raw('MONTH(created_at)'), "'".$month."'");
        $query = $query->where('lounges_bookings.booking_status', "'Completed'");
        $query = $query->where('lounges_bookings.payment_status', "'success'");
        $query = $query->where('lounges_bookings.removed', "'No'");
        $query = $query->where('lounges_bookings.status', "'Yes'");
        $query = $query->groupBy(DB::raw('DATE_FORMAT(created_at,  "%Y-%m-%d")'));

        return $query->get();
    }
}
