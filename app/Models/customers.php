<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class customers extends Authenticatable
{
    use Notifiable, CanResetPassword;

    protected $fillable = [
        "title",
        "first_name",
        "last_name",
        "email",
        "phone_number",
        "password",
        "postal_code",
        "address",
        "address2",
        "town",
        "status"
    ];
}