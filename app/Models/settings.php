<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    //
    protected $fillable = ["id","field_name","field_value"];
    public $timestamps = false;
}
