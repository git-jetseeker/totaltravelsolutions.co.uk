<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transfer_transaction extends Model
{
    //
    protected $table = "transfer_transaction";
    public $timestamps = false;

    public  function getBooking(){
        // return booking_transaction::where("id",$id)->first();
        return $this->belongsTo("App\Models\transfer_transaction","orderID");

    }
}
