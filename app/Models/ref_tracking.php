<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_tracking extends Model
{
    //
    protected $fillable = ["id","ref_url", "ref_src" , "agentID","current_url","user_ip","email","traffic_src","created_at","updated_at","is_internal_url"];

}
