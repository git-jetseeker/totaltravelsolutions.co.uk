<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Legacy lounge model alias — prefer App\Models\Lounges for BookFHR.
 */
class lounge extends Lounges
{
    protected $table = 'lounges';

    public $timestamps = false;
}
