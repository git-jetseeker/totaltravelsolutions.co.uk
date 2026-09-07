<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menus extends Model
{
    //
    public $timestamps = false;
    protected $table = "menus";

    public function getMenusByRoleID($rid){

        $menus = menus::all()->where("role_id",$rid)->sortBy("sort_order");

        return $menus->toArray();
    }
}
