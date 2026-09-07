<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hotels_bookings extends Model
{
    protected $fillable = [
        'airportID', 'hotel_id', 'customerId', 'product_code', 'option_id', 'bookfhr_search_id',
        'bookfhr_cart_id', 'bookfhr_item_id', 'title', 'first_name', 'last_name', 'email',
        'phone_number', 'referenceNo', 'ext_ref', 'referenceLink_ext', 'check_in', 'check_out',
        'check_in_time', 'check_out_time', 'adults', 'children', 'infants', 'rooms', 'no_of_nights',
        'room_type', 'room_title', 'hotel_name', 'discount_code', 'discount_amount', 'booking_amount',
        'booking_fee', 'cancelfee', 'smsfee', 'total_amount', 'hotel_api', 'booked_type',
        'payment_status', 'booking_status', 'booking_action', 'payment_method', 'PayerID', 'intent_id',
        'api_res', 'bookfhr_api_res', 'browser_data', 'user_ip', 'traffic_src', 'status', 'removed',
        'email_check', 'agentID',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function airport()
    {
        return $this->belongsTo(airport::class, 'airportID');
    }

    public static function getSingleRowById($id)
    {
        return static::where('id', $id)->first();
    }
}
