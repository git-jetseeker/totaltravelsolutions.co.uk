<?php

namespace App\Http\Controllers;

use App\Models\Awards;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\View\View;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

ini_set('max_execution_time', 180);

class AwardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index(Request $request){


        $awards = new Awards();

        if ($request->has("name")) {
            $name = $request->input("name");

            $awards = $awards->where("awardname",$name);
        }
        $awards = $awards->get();
        return view("admin.awards.list", ["awards" => $awards]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("admin.awards.create");

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//print_r($request->input("terminal")); die();
        //
        $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'aname' => 'required|string|max:255',
            
        ], $messages);
        // return $validatedData;

        $awards = new awards();
        $awards->awardname = $request->input("aname");

       


        //file storage
        if ($request->hasFile('profile_image')) {

            $path = $request->file('profile_image')->store('awards');
            $awards->image = $path;
        }
        $awards->type="Company";

        if ($awards->save()) {
           
                return  redirect(route("awards.index"))->with("message", "Awards added Successfully");
               
        }
       

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\airport $awards
     * @return \Illuminate\Http\Response
     */
    public function show(Awards $awards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\airport $airport
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $award = Awards::findOrFail($id);
        //dd($award);
      //  $terminals = Awards::all()->where("aid", "=", $id);

        return view('admin.awards.edit', ["award" => $award, "id" => $id]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\airport $airport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
$awards=Awards::findOrFail($id);

      // dd($request);
    //dd($award); 


        $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'aname' => 'required|string|max:255',
          
        ], $messages);


    
         $awards["awardname"] = $request->input("aname");
         $awards["type"]="company";
       


        //file storage
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('awards');
             $awards["image"]  = $path;
        }
        if ($awards->save()) {
           return redirect(route("awards.index"))->with("message", "Information Updated Successfully");
        }
    
                    //}

        
        //return redirect("/admin/airport")->with('success', 'Information Updated successfully');
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\airport $airport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        //
        $aid = $id;

        DB::table('awards')->where('id', $aid)->delete();
        return redirect()->back()->with('success', 'Information Deleted successfully');

    }
}
