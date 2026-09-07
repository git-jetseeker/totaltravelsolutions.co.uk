<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class support_departments extends Model
{
    //
    protected $fillable = ["id","name","email"];

    public function tickets()
    {
        return $this->hasMany('App\Models\tickets','id','department');
    }
}
