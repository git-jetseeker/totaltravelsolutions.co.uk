<?php

namespace App\Http\Controllers;

use App\Models\email_templates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $emails= email_templates::orderBy('id')->get();
        //dd($reviews->user->email);
        return view("admin.emails.list", ["emails" => $emails]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("admin.emails.create");
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
            'title' => 'required|string',
            'subject' => 'required|string',
            'description' => 'required|string'
        ], $messages);


        $emails = new email_templates();
        $emails->title = $request->input("title");
        $emails->subject = $request->input("subject");
        $emails->description = $request->input("description");

        if ($emails->save()) {
            return redirect(route("emails.index"))->with('success', 'Data Inserted  successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\email_templates  $email_templates
     * @return \Illuminate\Http\Response
     */
    public function show(email_templates $email_templates)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\email_templates  $email_templates
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $email = email_templates::findOrFail($id);

        return view("admin.emails.edit", ["email" => $email]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\email_templates  $email_templates
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $emails= email_templates::findOrFail($id);

        $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'subject' => 'required|string'
        ], $messages);


        //$page = [];
        $emails["title"] = $request->input("title");
        $emails["description"] = $request->input("description");
        $emails["subject"] = $request->input("subject");


        if ($emails->update()) {
            return redirect(route("emails.index"))->with('success', 'Data updated  successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\email_templates  $email_templates
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $aid = $id;
        $d = DB::table('email_templates')->where('id', $aid)->delete();

        return redirect(route("emails.index"))->with('success', 'Information Deleted successfully');
    }
}
