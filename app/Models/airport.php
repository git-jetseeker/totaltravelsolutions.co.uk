<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class airport extends Model
{
    //

    protected $fillable = ["name","description","address","post_code","city","status","profile_image"];

    public static function getAll(){
        $airports = airport::all()->where("status","Yes")->toArray();
        $airportsList=[];
        foreach ($airports as $u){
            $airportsList[$u["id"]]=$u["name"];
        }
        return $airportsList;
    }

//    public function airport()
//    {
//        return $this->hasMany("App\Models\airport","c");
//    }
}
