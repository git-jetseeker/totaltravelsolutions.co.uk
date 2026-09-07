<?php

namespace App\Http\Controllers;

use App\menus;
use App\role_permissions;
use App\roles;
use App\users_roles;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
    {
        $pers = role_permissions::all();
        return view("admin.roles.create", ["pers" => $pers]);
    }

    public function createRole(Request $request)
    {

        $name = $request->input("name");
        $permission = $request->input("permission");

        if (count($permission) > 0) {

            $permission = implode(",", $permission);
        }
        $status = $request->input("active");

        $role = new roles();
        $role->name = $name;
        $role->permissions = $permission;
        $role->status = $status;
        $role->save();
        return json_encode(["success" => 1]);
    }

    public function getMenusByIdjson($id)
    {
        $menus = new menus();
        $menu = $menus->getMenusByRoleID($id);
        if (!empty($menu)) {
            $html = $this->make($menu);
        } else {
            $html = 0;
        }
        echo json_encode($html);
    }

    function getRolesDD()
    {
        $roles = roles::all();
        $html = "";
        if (!empty($roles)) {
            foreach ($roles as $menu) {
                $html .= '<option value="' . $menu->id . '">' . $menu->name . '</option>';
            }
        } else {
            $html = 'No Menu Found';
        }
        return json_encode($html);
    }


    function make(array $array, $no = 0)
    {
        $child = $this->hasChildren($array, $no);
        if (empty($child))
            return "";
        $content = "<ol class='dd-list' id='nest'>";
        foreach ($child as $value) {
            $content .= "<li class='dd-item dd3-item ' data-name='" . $value['name'] . "' data-id='" . $value['id'] . "'>
                        <div class='dd-handle dd2-handle'>
														<i class='normal-icon ace-icon fa fa-bars blue bigger-130'></i>

														<i class='drag-icon ace-icon fa fa-arrows bigger-125'></i>
													</div><div class='dd2-content'>" . $value['name'] . "
                       
                        <div class='pull-right action-buttons'>
																	<a id='" . $value['id'] . "' class='blue edit-button'>
																		<i class='ace-icon fa fa-pencil bigger-130'></i>
																	</a>

																	<a id='" . $value['id'] . "' class='red delete-button'>
																		<i class='ace-icon fa fa-trash-o bigger-130'></i>
																	</a>
																</div>
                        </div>";
            $content .= $this->make($array, $value['id']);
        }
        $content .= "</li></ol>";
        return $content;
    }

    function hasChildren($array, $id)
    {
        return array_filter($array, function ($var) use ($id) {
            return $var['parent_id'] == $id;
        });
    }

    function updateMenuSortOrder(Request $request)
    {
        $data = json_decode($request->input('data'), true);


        $result = $this->arrayparsing($data);

        $i = 0;
        foreach ($result as $row) {
            $i++;
            $data2 = [];
            $data2["parent_id"] = $row['parent_id'];
            $data2["sort_order"] = $i;


            DB::table('menus')
                ->where('id', $row['id'])
                ->update($data2);

        }

    }

    function arrayparsing($array, $parent_id = 0)
    {
        $result = array();
        foreach ($array as $item) {
            $subarray = array();
            if (!empty($item['children'])) {
                $subarray = $this->arrayparsing($item['children'], $item['id']);
            }

            $result[] = array('id' => $item['id'], 'parent_id' => $parent_id);
            $result = array_merge($result, $subarray);
        }
        return $result;
    }

    function creat_clone(Request $request)
    {

        $menus = menus::where("role_id", $request->input("c_from"))->get();

        if ($menus) {
            $count = 1;
            foreach ($menus as $row) {

                $menu = new menus();
                $menu->role_id = $request->input("c_to");
                $menu->name = $row->name;
                $menu->parent_id = '0';
                $menu->route_name = $row->route_name;
                $menu->icon = $row->icon;
                $menu->sort_order = $count;
                $menu->permissions = $row->permissions;
                $menu->save();
                $count++;
            }
            return 1;
        }
        exit;
    }

    function insertMenu(Request $request)
    {

        $maxID = menus::where("role_id", $request->input("role_id"))->max("id");
        $sortID = $maxID + 1;
        $permissions = "";
        if (!empty($request->input("permission"))) {
            $permissions = implode(",", $request->input("permission"));
        }

        if ($request->has("id") && $request->input('id') != "" && $request->input('id') > 0) {

            $data2 = [];
            $data2["role_id"] = $request->input("menu_id");
            $data2["name"] = $request->input("name");
            $data2["route_name"] = $request->input("slug");
            $data2["icon"] = $request->input("icons");

            $data2["sort_order"] = $sortID;
            $data2["permissions"] = $permissions;
            DB::table('menus')
                ->where('id', $request->input('id'))
                ->update($data2);
            return 1;
        } else {
            $menu = new menus();
            $menu->role_id = $request->input("menu_id");
            $menu->name = $request->input("name");
            $menu->icon = $request->input("icons");
            $menu->route_name = $request->input("slug");
            $menu->sort_order = $sortID;
            $menu->permissions = $permissions;
            $menu->save();
            return 1;
        }


    }

    function getEdata(Request $request)
    {
        $id = $request->input("id");
        $menu = menus::where("id", $id)->get();
        return json_encode($menu);
    }


    function delMenu(Request $request)
    {
        $id = $request->input("id");
        menus::where("id", $id)->delete();
        menus::where("parent_id", $id)->delete();
        return 1;
    }


    function getSidebarMenu()
    {
        $userId = Auth::id();


        $users_roles = users_roles::where("user_id", $userId)->first();
        $role_id = $users_roles->role_id;


        $html = '<ul class="nav nav-list">
            <li class="">
                <a href="' . url("/admin") . '">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> Dashboard </span>
                </a>
                <b class="arrow"></b>
            </li>';


        $menus = menus::where("role_id", $role_id)->where("parent_id", "0")->where("status", "Yes")->get();
        //dd($menus);
        foreach ($menus as $key => $menu) {

            //if (Gate::check("menu_auth", [$menu->name])) {
                $countSubmenu = menus::where("parent_id", $menu->id)->where("status", "Yes")->count();
                # code...
                $class = "";
                if (Route::currentRouteName() == $menu->route_name) {
                    $class = "active";

                }
                $class_submenu = "";
                if ($countSubmenu > 0) {
                    $submenutOpen = $this->openSubMenu($menu->id, Route::currentRouteName());

                    if ($submenutOpen) {
                        $class_submenu = "open";
                    }


                }

                if($menu->route_name=="#" || $menu->route_name=="" ){
                    $url ="#";
                }else{
                    $url = route($menu->route_name);
                }
                $aclass = "";
                if ($countSubmenu > 0) {
                    $class = "";
                    $url = "#";
                    $aclass = "dropdown-toggle";
                }

                $html .= '<li class="' . $class . " " . $class_submenu . '">
                <a href="' . $url . '" class="' . $aclass . '">
                    <i class="menu-icon '.$menu->icon. '"></i>
                    <span class="menu-text">' . $menu->name . '</span>';
                if ($countSubmenu > 0) {
                    $html .= '<b class="arrow fa fa-angle-down"></b>';
                }

                $html .= '</a>';

                if ($countSubmenu > 0) {
                    $html .= $this->getSidebarSubMenu($menu->id);
                }


                $html .= '</li>';
                //$html .= "</ul>";
            //}

            
        }
        $html .= "</ul>";

            return $html;
    }

    function getSidebarSubMenu($parent_id)
    {

        $html = '<ul class="submenu">';

        $menus = menus::where("parent_id", $parent_id)->where("status", "Yes")->get();
        foreach ($menus as $key => $menu) {
            $countSubmenu = menus::where("parent_id", $menu->id)->where("status", "Yes")->count();
            # code...
            $class = "";
            if (Route::currentRouteName() == $menu->route_name) {
                $class = "open";
            }

            if($menu->route_name=="#" || $menu->route_name=="" ){
                    $url ="#";
                }else{
                    $url = route($menu->route_name);
                }

            $aclass = "";
            if ($countSubmenu > 0) {
                if ($this->openSubMenu($menu->id, Route::currentRouteName())) {
                    $class = "open";
                } else {
                    $class = "";
                }

                //$url = "#";
                $aclass = "dropdown-toggle";
            }

            if (Route::currentRouteName() == $menu->route_name && $countSubmenu == 0) {
                $class = "active";
            }


            $html .= '<li class="' . $class . '">
                <a href="' . $url . '" class="' . $aclass . '">
                
                    <span class="menu-text">' . $menu->name . '</span>';
            if ($countSubmenu > 0) {
                $html .= '<b class="arrow fa fa-angle-down"></b>';
            }


            $html .= '</a>';


            if ($countSubmenu > 0) {
                $html .= $this->getSidebarSubMenu($menu->id);
            }


            $html .= '</li>';
        }

        $html .= "</ul>";
        // dd($html);
        return $html;
    }

    function openSubMenu($parent_id, $activeMenuRouteName, &$flag = 0)
    {
        $menus = menus::where("parent_id", $parent_id)->where("status", "Yes")->get();
        $totalSubMenus = menus::where("parent_id", $parent_id)->where("status", "Yes")->count();


        foreach ($menus as $key => $menu) {
            $count_sub_menus = menus::where("parent_id", $menu->id)->where("status", "Yes")->count();

            if ($menu->route_name === $activeMenuRouteName) {

                $flag = 1;

            }

            if ($totalSubMenus > 0) {

                $this->openSubMenu($menu->id, $activeMenuRouteName, $flag);
            }


            # code...

        }
        //echo $flag."flag"; 
        return $flag;
    }


}
