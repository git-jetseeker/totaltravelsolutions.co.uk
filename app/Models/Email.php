<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $fillable = ["email", "aid", "airport_name", "traffic_src","cid", "dropoff_date", "pickup_date", "discount_code", "reference_number"];

    public function airport()
    {
        return $this->belongsTo(Airport::class, 'aid');
    }
}
