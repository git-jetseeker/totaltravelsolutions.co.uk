<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OffDays extends Model
{
    
    protected $table = 'off_days_manage';
    protected $fillable = [
        "company_id",
        "admin_id",
        "off_days",
        "off_type",
        ];
    function admin(){
        return $this->belongsTo(User::class, 'admin_id','id');
    }
    function company(){
        return $this->belongsTo(Company::class, 'company_id','id');
    }
    
}
