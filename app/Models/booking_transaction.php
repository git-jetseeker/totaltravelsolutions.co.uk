<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class booking_transaction extends Model
{
    //
    protected $table = "booking_transaction";
    public $timestamps = false;

    public  function getBooking(){
        // return booking_transaction::where("id",$id)->first();
        return $this->belongsTo("App\Models\airports_bookings","orderID");

    }
}
