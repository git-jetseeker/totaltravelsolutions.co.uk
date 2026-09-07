<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ticket_chat;

class tickets extends Model
{
    //
    public $timestamps = false;

    protected $fillable = ["id","ticket_id","title","booking_ref","user_id","company_admin_id","name","email","contact","department","urgency","date","assign_to","assign_date","status","agent_id"];



    //relation
    public function chat()
    {
        return $this->hasMany('App\Models\ticket_chat','ticket_id','id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\support_departments','id','department');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\User','assign_to','id');
    }

    public function siteagent(){
        return $this->belongsTo('App\Models\partners','agent_id');
    }

    //events
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            // ... code here
        });

        self::created(function($model){
//            $model->ticket_id = "PZT".date("dmy").$model->id;
//            $model->save();
        });

        self::updating(function($model){
            // ... code here
        });

        self::updated(function($model){
            // ... code here
        });

        self::deleting(function($model){
            // ... code here
        });

        self::deleted(function($model){
            // ... code here
        });
    }
}
