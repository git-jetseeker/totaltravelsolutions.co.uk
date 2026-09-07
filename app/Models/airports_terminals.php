<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class airports_terminals extends Model
{
    //
    protected $fillable = ["name","aid"];

    public function bookings()
    {
        return $this->hasMany("App\Models\airports_bookings","airportID");
    }
}
