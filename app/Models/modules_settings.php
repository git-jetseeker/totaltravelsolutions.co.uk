<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class modules_settings extends Model
{
    //

    protected $fillable = ["name","value"];
    public $timestamps = false;
    public static function getModuleSetting($name){
        $data = DB::table('companies_set_price_plan')->where('name', $name)->first();
        return $data->value;
    }
}
