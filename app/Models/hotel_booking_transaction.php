<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hotel_booking_transaction extends Model
{
    protected $table = 'hotel_booking_transaction';

    public $timestamps = false;

    public function booking()
    {
        return $this->belongsTo(hotels_bookings::class, 'orderID');
    }
}
