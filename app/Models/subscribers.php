<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subscribers extends Model
{
    //
    public $timestamps=false;

    public  function airport(){
        return $this->belongsTo("App\Models\airport","airport_id");
    }
}
