<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ticket_chat extends Model
{
    //
    public $timestamps = false;

    protected $fillable = ["ticket_id","id","message","attachment","clientunread","adminunread","Companyread","replyingtime","replyingadmin","reply_by","reply_to","hold","users_beep"];



    public function ticket()
    {
        return $this->belongsTo('tickets','ticket_id','id');
    }

}
