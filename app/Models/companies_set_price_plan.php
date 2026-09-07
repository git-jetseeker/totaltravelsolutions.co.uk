<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class companies_set_price_plan extends Model
{
    //
    protected $fillable = ["id","cid","cmp_month","cmp_year","extra"];

    public function Days(){
        return $this->hasMany(companies_set_assign_price_plan::class, 'plan_id', 'id');
    }
}
