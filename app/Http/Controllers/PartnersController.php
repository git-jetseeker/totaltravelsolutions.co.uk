<?php

namespace App\Http\Controllers;
use App\Awards;
use App\airport;
use App\airports_terminals;
use App\partners;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       set_time_limit(0);
        $partners = Partners::get();

      
        return view("admin.agent.list", ["partners" => $partners]);




    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("admin.agent.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'aname' => 'required|string|max:255',
            'pass' => 'required|string',
            'alies' => 'required|string',
            'email' => 'required|string',
            'url' => 'required|string'
        ], $messages);
       // return $validatedData;

        $agent = new partners();

        $agent->username = $request->input("aname");
        $agent->password = $request->input("pass");
        $agent->alias = $request->input("alies");
        $agent->company = $request->input("company");
        $agent->email = $request->input("email");
          $agent->share = $request->input("share");
            $agent->url = $request->input("url");
        $agent->status = $request->input("status");



        //file storage
     

        

        if ($agent->save()) {
           
                return  redirect(route("agent.index"))->with("success", "Agent added Successfully");
               
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function show(partners $partners)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        //
         $agent = Partners::findOrFail($id);

        //dd($award);
      //  $terminals = Awards::all()->where("aid", "=", $id);

        return view('admin.agent.edit', ["agent" => $agent, "id" => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
          $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'aname' => 'required|string|max:255',
          
        ], $messages);

 $agent = Partners::findOrFail($id);
    
        $agent->username = $request->input("aname");
        $agent->password = $request->input("pass");
        $agent->alias = $request->input("alies");
        $agent->company = $request->input("company");
        $agent->email = $request->input("email");
          $agent->share = $request->input("share");
            $agent->url = $request->input("url");
        $agent->status = $request->input("status");

        if ($agent->save()) {
           return redirect(route("agent.index"))->with("success", "Information Updated Successfully");
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function destroy(partners $partners)
    {
        //
    }
}
