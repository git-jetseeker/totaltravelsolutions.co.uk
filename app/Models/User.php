<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable

{

    use HasApiTokens, HasFactory, Notifiable;



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];





    public function role()

    {

        return $this->belongsTo('App\Models\users_roles');

    }



    /**

     * The User that belong to the role.

     */

    public function roles()

    {

        return $this->hasOne('App\Models\users_roles');

    }





}

