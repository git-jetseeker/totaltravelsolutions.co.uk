<?php

namespace App\Http\Controllers;

use App\reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\airport;
use App\Partners;
use App\users_roles;
use App\airports_bookings;
use App\booking_transaction;
use App\Company;
use App\User;
use App\customers;
use App\modules_settings;
use App\penalty_details;
use DateTime;

class ReviewsController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
          $airports = airport::all();
        $companies_dlist = Company::all();
      
      $reviews = new reviews();
      $reviews = $reviews->select(["reviews.*"]);
      $reviews = $reviews->leftJoin("companies as c", 'reviews.type_id', '=', 'c.id');

      

      if ($request->has("search") && $request->input("search")!="" ) {
            $name = $request->input("search");
            $reviews = $reviews
            ->where('username', 'like', '%' . $name . '%');        
        }

        if ($request->has("airport") && $request->input("airport")!="all") {

            $name = $request->input("airport");
            $reviews = $reviews->where('c.airport_id', '=',  $name );        
        }

        if ($request->has("companies") && $request->input("companies")!="all") {
            $name = $request->input("companies");
            $reviews = $reviews->where('type_id', '=',  $name );        
        }

        if ($request->input("start_date") !="" && $request->input("end_date") !="") {

                  $start_date = date("Y-m-d",strtotime($request->input("start_date")));
                  $end_date = date("Y-m-d",strtotime($request->input("end_date")));

                  $reviews = $reviews->where( DB::raw('DATE_FORMAT(reviews.created_at, "%Y-%m-%d")'),">=", $start_date);

                  $reviews = $reviews->where( DB::raw('DATE_FORMAT(reviews.created_at, "%Y-%m-%d")'),"<=", $end_date);
        }

        $reviews = $reviews->orderBy('id')->paginate(20);
        //dd($reviews->user->email);
        return view("admin.reviews.list", ["reviews" => $reviews,"companies_dlist" => $companies_dlist, "airports" => $airports]);
    }
    public function create()
    {
        //
        //dd($reviews->user->email);
        return view("admin.reviews.create");
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
            'review' => 'required|string'
        ], $messages);
    
    
    
    
        $review = new reviews();  
        $review->title = $request->input("title");
        $review->username = $request->input("username");
        $review->email = $request->input("email");
        $review->rating = $request->input("rating");
        $review->type = $request->input("type"); 
        $review->status = $request->input("status");
        $review->review = $request->input("review");
        
        if ($request->hasFile('logo')) { 
            if ($request->file('logo')) {
            $imagePath = $request->file('logo');
            $imageName = $imagePath->getClientOriginalName();
            $path = $request->file('logo')->storeAs('theme/images', $imageName);
        }
        $review->logo = $path;
        
        }
        if ($request->hasFile('background_image')) { 
            if ($request->file('background_image')) {
                $imagePath = $request->file('background_image');
                $imageName = $imagePath->getClientOriginalName();
                $path = $request->file('background_image')->storeAs('theme/images', $imageName);
            }
        $review->background_image = $path;
        
        } 
        
        if ($review->save()) {
            return redirect(route("reviews.index"))->with('success', 'Data Inserted Successfully');
        }
    
    
    
    }
    public function edit($id)
    {
        //
        $review = reviews::findOrFail($id);
    
        return view("admin.reviews.edit", ["review" => $review]);
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
         
        
        $review = Reviews::findOrFail($id);

        $messages = [
            'required' => 'This field is required.',
        ];
        $validatedData = $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'review' => 'required|string'
        ], $messages);


        //$page = [];
        $review["title"] = $request->input("title");
        $review["username"] = $request->input("username");
        $review["email"] = $request->input("email");
        $review["rating"] = $request->input("rating");
        $review["type"] = $request->input("type"); 
        $review["status"] = $request->input("status");
        $review["review"] = $request->input("review");

        if ($request->hasFile('logo')) { 
                if ($request->file('logo')) {
                    $imagePath = $request->file('logo');
                    $imageName = $imagePath->getClientOriginalName();
                    $path = $request->file('logo')->storeAs('theme/images', $imageName);
                }
                 $review["logo"] = $path;
 
        }
        if ($request->hasFile('background_image')) { 
                if ($request->file('background_image')) {
                    $imagePath = $request->file('background_image');
                    $imageName = $imagePath->getClientOriginalName();
                    $path = $request->file('background_image')->storeAs('theme/images', $imageName);
                }
                 $review["background_image"] = $path;
        
        }


        if ($review->update()) {
            return redirect(route("reviews.index"))->with('success', 'Data updated  successfully');
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
        $d = DB::table('reviews')->where('id', $id)->delete();

        return redirect(route("reviews.index"))->with('success', 'Review Deleted successfully');
    }

}
