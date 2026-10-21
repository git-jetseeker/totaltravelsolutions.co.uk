<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class carhire_bookings extends Model
{
    protected $table = 'carhire_bookings';

    protected $fillable = [
        'airportID', 'customerId', 'product_code', 'option_id', 'bookfhr_search_id',
        'bookfhr_cart_id', 'bookfhr_item_id', 'title', 'first_name', 'last_name', 'email',
        'phone_number', 'referenceNo', 'ext_ref', 'supplier_reference', 'pickup_datetime',
        'dropoff_datetime', 'duration_days', 'driver_age', 'residence_country_code',
        'vehicle_name', 'vehicle_type', 'acriss_code', 'transmission', 'fuel_type', 'seats',
        'doors', 'supplier_name', 'pickup_location', 'dropoff_location', 'pickup_address',
        'dropoff_address', 'discount_code', 'discount_amount', 'booking_amount', 'extra_amount',
        'booking_fee', 'cancelfee', 'smsfee', 'deposit_amount', 'pay_later', 'total_amount',
        'carhire_api', 'booked_type', 'payment_status', 'booking_status', 'booking_action',
        'payment_method', 'PayerID', 'intent_id', 'token', 'enjoytravel_access_token',
        'api_res', 'bookfhr_api_res', 'enjoytravel_api_res',
        'extras_json', 'browser_data', 'user_ip', 'traffic_src', 'agentID', 'removed',
        'email_check', 'status',
    ];

    public function airport()
    {
        return $this->belongsTo(airport::class, 'airportID');
    }

    public function agent()
    {
        return $this->belongsTo(partners::class, 'agentID');
    }

    public static function getSingleRowById($id)
    {
        return static::with(['airport', 'agent'])->where('id', $id)->first();
    }
}
