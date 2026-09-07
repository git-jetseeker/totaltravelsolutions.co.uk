<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lounges_transaction extends Model
{
    //
    protected $table = "lounges_transaction";
    public $timestamps = false;

    public  function getBooking(){
        // return booking_transaction::where("id",$id)->first();
        return $this->belongsTo("App\Models\lounges_bookings","orderID");

    }
}
