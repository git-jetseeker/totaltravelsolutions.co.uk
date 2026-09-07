<?php



namespace App\Http\Controllers;



use App\airport;

use App\companies_product_price;

use App\companies_products;

use App\companies_set_assign_price_plan;

use App\companies_set_price_plan;

use App\Company;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;



class CompaniesProductPriceController extends Controller

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

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        //

        $companies = Company::all()->toArray();

       // $airports = airport::all()->toArray();

        return view("admin.company.plan_setting",["companies"=>$companies]);



    }

    //viewEditPlan

    public function viewEditPlan()

    {
        //

        $companies = Company::all()->toArray();

       // $airports = airport::all()->toArray();

        return view("admin.company.plan_editview",["companies"=>$companies]);

    }



    public function getPlanPrices($id){
		
		//DB::enableQueryLog(); 

            $prices = companies_set_price_plan::

            select(DB::raw("companies_set_price_plans.*,c.name as company_name"))

            ->leftJoin('companies as c', 'companies_set_price_plans.cid', '=', 'c.id')

            ->where("companies_set_price_plans.cid",$id)->get();
			
			//dd(DB::getQueryLog());

            $html ='<table class="table responsive table-hover"><thead><tr><th>Company</th><th>Year</th><th>Month</th><th>Action</th></tr></thead><tbody>';

            foreach ($prices as $key => $value) {

                $monthName = date('F', mktime(0, 0, 0, $value->cmp_month, 10));

                $html .='

                <tr><td>'.$value->company_name.' </td><td>'.$value->cmp_year.'</td><td>'.$monthName.'</td><td>

                  <a id="edit" class="btn btn-info btn-xs" href="setPlan?month='.$value->cmp_month.'&year='.$value->cmp_year.'&cid='.$value->cid.'&mode=edit" }}" title="Edit"><i class="fa fa-pencil bigger-120"></i></a>

                  <a id="delete" class="btn btn-danger btn-xs" href="delPlan/'.$value->id.'" title="Delete"><i class="ace-icon  ace-icon fa fa-trash-o bigger-120"></i></a>

                  </td></tr>';

            }

            $html .='</tbody></table>';

            echo $html;

    }

    public function setPlan()
    {

        //return dd("testing");

        $companies = Company::all()->toArray();

        return view("admin.company.company_set_plan",["companies"=>$companies]);

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

    }



    /**

     * Display the specified resource.

     *

     * @param  \App\companies_product_price  $companies_product_price

     * @return \Illuminate\Http\Response

     */

    public function show(companies_product_price $companies_product_price)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  \App\companies_product_price  $companies_product_price

     * @return \Illuminate\Http\Response

     */

    public function edit(companies_product_price $companies_product_price)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \App\companies_product_price  $companies_product_price

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, companies_product_price $companies_product_price)

    {

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  \App\companies_product_price  $companies_product_price

     * @return \Illuminate\Http\Response

     */

    public function destroy(companies_product_price $companies_product_price)

    {

        //

    }





    public function getProductPricePlanView($id)

    {

        $products = companies_products::all()->where("company_id","=",$id)->toArray();

        $products_prices_arrange_array = [];







        return view("admin.company.product_price_plain_view",["products"=>$products,"id"=>$id]);

    }



    public function delPlan($id)

    {

dd($id);

        DB::table('companies_set_price_plans')->where('id', $id)->delete();

        DB::table('companies_set_assign_price_plans')->where('plan_id', $id)->delete();

        return redirect()->back()->with("success","Successfully Delete");



    }



    public function getCompanySetPlanView($id,$year,$month)

    {

        $products = companies_products::all()->where("company_id","=",$id)->toArray();



        $is_exist = companies_set_price_plan::all()

            ->where("cid","=",$id)

            ->where("cmp_year","=",$year)

            ->where("cmp_month","=",$month)->first();

        $p = [];

        $p[""]="Select Brand";

        $p["fully_booked"]="Full Booked";

        $p["fully_closed"]="Fully Closed";



        foreach ($products as $product)

        {

            $p[$product["product_name"]] = $product["product_name"];

        }





        return view("admin.company.company_set_plan_view",["products"=>$p,"id"=>$id,"month"=>$month,"year"=>$year,"is_exist"=>$is_exist]);

    }





    public function updateProductPrices(Request $request){

        if($request->input("action")=="add"){

            $data = [];

            $data["brand_name"] =$request->input("product_name");

            $data["brand_id"] =$request->input("product_id");

            $data["cid"] =$request->input("company_id");

            $data["after_30_days"] =$request->input("after_30_days");



            for($i=1;$i<=31;$i++){

                $data["day_".$i] = $request->input("p_day_".$i);

            }





            if(companies_product_price::create($data)){

                return response()->json(['success' => true, 'message' => "<div class='alert alert-success'><p>Successfully Update Price</p></div>"]);

            }else {

                return response()->json(['success' => false, 'message' => "<div class='alert alert-danger'><p>Error updating price.</p></div>  "]);

            }





        }

                if($request->input("action")=="update"){

            $data = [];
            $data["brand_name"] =$request->input("product_name");
            $data["brand_id"] =$request->input("product_id");
            $data["cid"] =$request->input("company_id");
            $data["after_30_days"] =$request->input("after_30_days");

            for($i=1;$i<=31;$i++){
                $data["day_".$i] = $request->input("p_day_".$i);
            }
            $product_check = companies_product_price::select('id','brand_id')->where('cid', '=', $request->input("company_id"))->where('brand_id', '=', $request->input("product_id"))->first();
            //echo "<pre>"; print_r($product_check); echo "</pre>"; exit;
            if(isset($product_check['id'])){
                //echo "found";
                $updated = companies_product_price::where('cid', '=', $request->input("company_id"))->where('brand_id', '=', $request->input("product_id"))->update($data);
                if($updated){
                    return response()->json(['success' => true, 'message' => "<div class='alert alert-success'><p>Successfully Update Price</p></div>"]);
                }else {
                    return response()->json(['success' => false, 'message' => "<div class='alert alert-danger'><p>Error updating price.</p></div>  "]);
                }
            }else{
                //echo "not found";
                DB::table('companies_product_prices')->where('cid', '=', $request->input("company_id"))->where('brand_name', "=",$request->input("product_name"))->delete();
                $added = companies_product_price::create($data);
                if($added){
                    return response()->json(['success' => true, 'message' => "<div class='alert alert-success'><p>Successfully Add Price</p></div>"]);
                }else {
                    return response()->json(['success' => false, 'message' => "<div class='alert alert-danger'><p>Error Adding price.</p></div>  "]);
                }
            }
        }


    }





    public function setCompanyPlanPrices(Request $request){

        if($request->input("action")=="add") {

            $data = [];

            $data["cid"] = $request->input("id");

            $data["cmp_month"] = $request->input("month");

            $data["cmp_year"] = $request->input("year");

            $extra= $request->input("extra");

            if($extra==""){

                $extra= 0;

            }

            $data["extra"] = $extra;





            if ($plain_id = companies_set_price_plan::create($data)) {

              $plain_id = $plain_id->id;

                $data = [];

                $data["plan_id"] = $plain_id;



                for ($i = 1; $i <= 31; $i++) {

                    $data["day_no"] = "day_" . $i;

                    $data["brand_name"] = $request->input("p_day_" . $i);

                    companies_set_assign_price_plan::create($data);

                }

               // print_r($data); die();







                return response()->json(['success' => true, 'message' => "<div class='alert alert-success'><p>Successfully Update Price</p></div>"]);

            } else {

                return response()->json(['success' => false, 'message' => "<div class='alert alert-danger'><p>Error updating price.</p></div>  "]);

            }



        }



        if($request->input("action")=="update"){

            $data = [];

            $data["cid"] = $request->input("id");

            $data["cmp_month"] = $request->input("month");

            $data["cmp_year"] = $request->input("year");

            $extra= $request->input("extra");

            if($extra==""){

                $extra= 0;

            }

            $data["extra"] = $extra;







            $updated = companies_set_price_plan::where('id', "=",$request->input("plain_id"))->update($data);



            if($updated){

                DB::table('companies_set_assign_price_plans')->where('plan_id', "=",$request->input("plain_id"))->delete();



                $data = [];

                $data["plan_id"] =$request->input("plain_id");



                for ($i = 1; $i <= 31; $i++) {

                    $data["day_no"] = "day_" . $i;

                    $data["brand_name"] = $request->input("p_day_" . $i);

                    companies_set_assign_price_plan::create($data);

                }





                return response()->json(['success' => true, 'message' => "<div class='alert alert-success'><p>Successfully Update Price</p></div>"]);

            }else {

                return response()->json(['success' => false, 'message' => "<div class='alert alert-danger'><p>Error updating price.</p></div>  "]);

            }



        }

    }

}

