<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class transfer_bookings extends Model

{
    //

    //protected $fillable = ["airportID","companyId","customerId","edit_by","title","first_name","last_name","email","phone_number","fulladdress","address","address2","town","postal_code","passenger","referenceNo","departDate","deprTerminal","deptFlight","returnDate","returnTerminal","returnFlight","no_of_days","discount_code","created_at","booking_status","payment_status","removed","status","","","","","","","",""];
    public $timestamps = false;



    public function admin(){

        return $this->belongsTo("App\Models\User","admin_id");

    }

    public static function getSingleRowById($id){

        return transfer_bookings::where("id",$id)->first();

    }






}

