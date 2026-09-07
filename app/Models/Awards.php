<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class awards extends Model
{
    //
    protected $fillable = ["id", "awardname", "image"];

    public $timestamps = false;

    public static function getAll()
    {
        $awards = awards::all()->toArray();
        $awardsList = [];
        foreach ($awards as $u) {
            $awardsList[$u["id"]] = $u["name"];
        }
        return $awardsList;
    }

}




