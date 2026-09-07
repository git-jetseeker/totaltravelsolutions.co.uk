<?php

namespace App\Http\Controllers;

use App\Models\faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $faqs= faqs::orderBy('id')->get();
        //dd($reviews->user->email);
        return view("admin.faqs.list", ["faqs" => $faqs]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //dd($reviews->user->email);
        return view("admin.faqs.create");
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
            'type' => 'required|string',
            'content' => 'required|string'
        ], $messages);


        $faq = new faqs();
        $faq->title = $request->input("title");
        $faq->type = $request->input("type");
        $faq->content = $request->input("content");
        $faq->status = $request->input("status");
        if ($faq->save()) {
            return redirect(route("faqs.index"))->with('success', 'Data Inserted  successfully');
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function show(faqs $faqs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $faq = faqs::findOrFail($id);

        return view("admin.faqs.edit", ["faq" => $faq]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $faqs = faqs::findOrFail($id);

        $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'content' => 'required|string'
        ], $messages);


        //$page = [];
        $faqs["title"] = $request->input("title");
        $faqs["type"] = $request->input("type");
        $faqs["content"] = $request->input("content");
        $faqs["status"] = $request->input("status");


        if ($faqs->update()) {
            return redirect(route("faqs.index"))->with('success', 'Data updated  successfully');
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\faqs  $faqs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $aid = $id;
        $d = DB::table('faqs')->where('id', $aid)->delete();

        return redirect(route("faqs.index"))->with('success', 'Information Deleted successfully');
    }
}
