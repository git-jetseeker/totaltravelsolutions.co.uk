<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class users_roles extends Model

{

    //

    public $timestamps =false;

    protected $fillable = ["id","user_id","role_id","permissions"];



    static function check_is_exist_role($user_id,$permission = null)

    {



        $user = users_roles::where("user_id",$user_id)->first();





        if($user!=null){

            $self = new self();

            

            if($self->check_user_role($user->role_id,$user->role->name)){



                if($permission!=null) {



                    if ($self->check_permiison_exists($user->permissions, $permission)) {



                        return true;

                    }

                }else {



                    return true;

                }

            }



            //return true;

        }

        return false;

    }





    public function role()

    {

        return $this->belongsTo('App\Models\roles','role_id');

    }

    public function user()

    {

        return $this->belongsTo('App\Models\User','user_id');

    }







    static function check_is_exist_menu($user_id,$menu_name)

    {



        $user = users_menus::where("user_id",$user_id)->where("menu_name",$menu_name)->first();

        //dd($user);

        if($user!=null){



          return false;

        }

        return true;

    }





    /**

     * @param $permissions

     * @param $per_name

     */

    function check_permiison_exists($permissions, $per_name){

        $permissions =  explode(",",$permissions);

        if(in_array($per_name, $permissions)) {

            return true;

        }

        return false;

    }



    function check_user_role($role_id,$role_name){

        $role = roles::where("id",$role_id)->where("name",$role_name)->get();

        if($role!=null){

            return true;

        }

        return false;

    }



   static function  get_user_role($user_id){



        $c = users_roles::leftJoin('roles', function($join) {

              $join->on('roles.id', '=', 'users_roles.role_id');

            })

            ->where('users_roles.user_id',$user_id)

            ->first();



        return $c;

    }



}

