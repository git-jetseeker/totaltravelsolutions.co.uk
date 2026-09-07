<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class email_templates extends Model
{
    //
    public $timestamps=false;
    protected $fillable = ["id","title","subject","description","status"];
}
