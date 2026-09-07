<?php



namespace App\Http\Controllers;

use App\airport;

use App\airports_terminals;

use App\Awards;

use App\companies_assign_awards;

use App\companies_products;

use App\companies_special_features;

use App\Company;

use App\facilities;

use App\User;

use App\discounts;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



class DiscountsController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

       

        $discounts = new discounts();



          if ($request->has("parking_type")) {

            $name = $request->input("parking_type");



            if ($name != "" && $name != "all") {

                $discounts = $discounts->where('parking_type', '=',$name );

              

            }

        } 





        if ($request->has("discount_for")) {

            $name = $request->input("discount_for");



            if ($name != "" && $name != "all") {

                $discounts = $discounts->where('discount_for', '=',$name );

              

            }

        } 





        if ($request->has("status")) {

            $name = $request->input("status");



            if ($name != "" && $name != "all") {

                $discounts = $discounts->where('status', '=',$name );

              

            }

        } 





        $discounts = $discounts->orderByDesc('id')->get();

        //dd($reviews->user->email);

        return view("admin.discounts.list", ["discounts" => $discounts]);

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

       return  view("admin.discounts.create");

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

            'parking_type' => 'required|string',

            'discount_for' => 'required|string',

            'pre_promo' => 'required|string',

            'promo' => 'required|string',

            'status' => 'required|string',

            'discount_type' => 'required|string',

            'discount_value' => 'required|string',

            'start_date' => 'required|string',

            'end_date' => 'required|string'

        ], $messages);





        $discounts = new discounts();

        $discounts->promo = $request->input("pre_promo")."-".$request->input("promo");

        $discounts->status = "Yes";

        $discounts->discount_campaign = "General";

        $discounts->start_date = date("Y-m-d",strtotime($request->input("start_date")));

        $discounts->end_date =date("Y-m-d",strtotime($request->input("end_date")));



        $discounts->discount_value = $request->input("discount_value");

        $discounts->discount_type = $request->input("discount_type");

        $discounts->parking_type = $request->input("parking_type");

        $discounts->discount_for = $request->input("discount_for");



        if ($discounts->save()) {

            return redirect(route("discounts.index"))->with('success', 'Data Inserted  successfully');

        }

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\discounts  $discounts

     * @return \Illuminate\Http\Response

     */

    public function show(discounts $discounts)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\discounts  $discounts

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        //



        $model = discounts::findOrFail($id);





        return view("admin.discounts.edit",["discount"=>$model]);

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\discounts  $discounts

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {
        //

        $discounts = discounts::findOrFail($id);

        $messages = [

            'required' => 'This field is required.',

        ];

        $validatedData = $request->validate([



            'parking_type' => 'required|string',

            'discount_for' => 'required|string',



            'promo' => 'required|string',

            'status' => 'required|string',

            'discount_type' => 'required|string',

            'discount_value' => 'required|string',

            'start_date' => 'required|string',

            'end_date' => 'required|string'

        ],$messages);







        $discounts->promo = $request->input("pre_promo")."-".$request->input("promo");

        $discounts->status = $request->input("status");

        $discounts->start_date = date("Y-m-d",strtotime($request->input("start_date")));

        $discounts->end_date =date("Y-m-d",strtotime($request->input("end_date")));



        $discounts->discount_value = $request->input("discount_value");

        $discounts->discount_type = $request->input("discount_type");

        $discounts->parking_type = $request->input("parking_type");

        $discounts->discount_for = $request->input("discount_for");

        if ($discounts->save()) {

            return redirect(route("discounts.index"))->with('success', 'Data Updated  successfully');

        }

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\discounts  $discounts

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        //

        $aid = $id;

        DB::table('discounts')->where('id', $aid)->delete();

        return redirect()->back()->with('success', 'Information Deleted successfully');

    }

}

