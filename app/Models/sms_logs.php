<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sms_logs extends Model
{
    protected $table = 'sms_logs';

    protected $fillable = [
        'booking_id',
        'booking_ref',
        'to_number',
        'template_id',
        'body',
        'provider',
        'provider_message_id',
        'status',
        'error_message',
        'triggered_by',
        'user_id',
    ];
}
