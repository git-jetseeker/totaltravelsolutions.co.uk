<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;





class Company extends Model

{

    //

    protected $fillable = ["admin_id", "name", "company_code","aph_id", "company_email", "airport_id", "terminal", "address", "address2", "town", "parking_type", "closing_time", "opening_time", "share_percentage", "max_discount", "overview", "post_code", "return_proc", "arival", "returnfront", "is_active", "processtime", "recommended", "featured", "cancelable", "editable", "special_features", "logo","ShortDescription","ArrivalTelephoneNumber","ReturnTelephoneNumber","TransferDetails","ParkingOptionTitle","ParkingOptionDesc","ParkingRestrictions","ImportantInformation","MinimumStayCharges","DisabledFacilities","Latitude","Longitude"];





    public function facilities()

    {

        return $this->hasMany('App\Models\facilities',"company_id");

    }



    public function awards()

    {

        //return $this->hasManyThrough('awards', 'companies_assign_awards', 'award_id', 'id');

       return  $this->hasMany(\App\Models\companies_assign_awards::class);



    }

    public function bookings()

    {

        return $this->hasMany("App\Models\airports_bookings","airportID");

    }



    public function airport()

    {

        return $this->belongsTo("App\Models\airport","airport_id");

    }















}