<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class companies_assign_awards extends Model
{
    //




    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class,"cid","id");

    }

    public function award()
    {
        return $this->hasOne(\App\Models\Awards::class,"id","award_id");

    }
}