<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class email_logs extends Model
{
    protected $table = 'email_logs';

    protected $fillable = [
        'booking_id',
        'booking_ref',
        'to_email',
        'cc',
        'template_id',
        'subject',
        'body_excerpt',
        'provider',
        'provider_message_id',
        'status',
        'error_message',
        'triggered_by',
        'user_id',
        'tracking_token',
        'opened_at',
        'open_count',
        'last_opened_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'open_count' => 'integer',
    ];
}
