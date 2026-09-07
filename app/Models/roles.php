<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class roles extends Model

{        


    protected $fillable = ["id","name","permissions","status"];



    //

    /**

     * The role that belong to the user.

     */

    public function user()

    {

        return $this->hasMany('App\Models\User');

    }

    public function role()

    {

        return $this->hasMany('App\Models\user_roles');

    }

}

