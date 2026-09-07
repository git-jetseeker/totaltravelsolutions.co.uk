<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users_menus extends Model
{
    protected $table = "users_menus";
    //
    public $timestamps =false;
    protected $fillable = ["id","user_id","menu_id","menu_name"];


    function get_hide_pages($user_id){
        return users_menus::where("user_id",$user_id)->get();
    }
}
