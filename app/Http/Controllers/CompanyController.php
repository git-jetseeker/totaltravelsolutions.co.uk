<?php

namespace App\Http\Controllers;

use App\airport;
use App\airports_terminals;
use App\Awards;
use App\companies_assign_awards;
use App\companies_products;
use App\companies_special_features;
use App\Company;
use App\lounge;
use App\facilities;
use App\User;
use App\roles;
use App\users_roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
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
    public function index(Request $request)
    {
        // $admins = User::all();
        $checks = User::rightJoin('users_roles','users_roles.user_id','=','users.id')
          ->select('users.name','users.id')->where('role_id','7')
         ->get();
        $airports = airport::all();
        $companies_dlist = Company::all();
        $companies = new Company();
        //
        
        $companies = $companies->select("companies.*", "airports.name as aname", "users.name as uname");
        $companies =$companies->leftJoin("airports", "airports.id", "=", "companies.airport_id");
        $companies =$companies->leftJoin("users", "users.id", "=", "companies.admin_id");
        
// dd($companies);


        if ($request->has("name") && $request->input("name") != "") {
            $name = $request->input("name");
            $companies = $companies->where("companies.name",'like', '%' . $name . '%');
               
        }

        if ($request->has("airport") && $request->input("airport") != "all") {
            $name = $request->input("airport");
            $companies = $companies->where("companies.airport_id",'=', $name);
               
        }

        if ($request->has("admins") && $request->input("admins") != "all") {
            $name = $request->input("admins");
            $companies = $companies->where("companies.admin_id",'=', $name);
               
        }

        if ($request->has("parking_type") && $request->input("parking_type") != "all") {
            $name = $request->input("parking_type");
            $companies = $companies->where("companies.parking_type",'=', $name);
               
        }

        if ($request->has("status") && $request->input("status") != "all") {
            $name = $request->input("status");
            $companies = $companies->where("companies.is_active",'=', $name);
               
        }

        if ($request->has("companies") && $request->input("companies") != "all") {
            $name = $request->input("companies");
            $companies = $companies->where("companies.id",'=', $name);
               
        }


        


        $companies = $companies->paginate(20);
        return view("admin.company.list", ["companies" => $companies,"companies_dlist" => $companies_dlist, "airports" => $airports, "admins" => $checks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
        $users = User::all()->toArray();
        $usersList = [];
        foreach ($users as $u) {
            $usersList[$u["id"]] = $u["name"];
        }

        $airports = airport::all()->where("status", "Yes")->toArray();
        $airportsList = [];
        foreach ($airports as $u) {
            $airportsList[$u["id"]] = $u["name"];
        }


        $awards = Awards::all()->where("type", "=", "company");


        $opening_closing_time = [];
        for ($i = 0; $i <= 23; $i++) {
            for ($j = 0; $j <= 45; $j += 15) {
                //$sel = str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT) == $opening_time ? 'selected' : '';
                //echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'"'.$sel.'>'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'</option>';
                $opening_closing_time[str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT);
            }
        }

        $features = companies_special_features::all()->toArray();
        $features1 = [];
        $features2 = [];
        $hald_features = (int)count($features) / 2;
        $i = 1;
        foreach ($features as $u) {
            if ($i > $hald_features) {
                $features2[] = $u["name"];

            } else {
                $features1[] = $u["name"];
            }

            $i++;
        }


        return view("admin.company.create", ["users" => $usersList, "airportsList" => $airportsList, "opening_closing_time" => $opening_closing_time, "features1" => $features1, "features2" => $features2, "awardsList" => $awards]);
    }
    
    public function create_lounge()
    {

        //
        $users = User::all()->toArray();
        $usersList = [];
        foreach ($users as $u) {
            $usersList[$u["id"]] = $u["name"];
        }


        return view("admin.company.create_lounge", ["users" => $usersList]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //echo "<pre>"; print_r($request->input("company_email")); die();
        $messages = [
            'required' => 'This field is required.',
        ];
        $request->validate([
            'name' => 'required|string|max:255',
            'admin_id' => 'required',
            'company_code' => 'required',

            'airport_id' => 'required',
            'terminal' => 'required'
        ], $messages);

        $company = [];
        $company["name"] = $request->input("name");
        $company["admin_id"] = $request->input("admin_id");
        $company["company_code"] = $request->input("company_code");
        $company["aph_id"] = $request->input("aph_id");

        if ($request->input("company_email")) {
            $company["company_email"] = implode(",", $request->input("company_email"));
        }
        $company["airport_id"] = $request->input("airport_id");
        $company["terminal"] = $request->input("terminal");

        $company["address"] = $request->input("address");
        $company["address2"] = $request->input("address2");
        $company["town"] = $request->input("town");
        $company["parking_type"] = $request->input("parking_type");
        $company["closing_time"] = $request->input("closing_time");
        $company["opening_time"] = $request->input("opening_time");
        $company["share_percentage"] = $request->input("share_percentage");
        $company["max_discount"] = $request->input("max_discount");
        $company["overview"] = $request->input("overview");
        $company["post_code"] = $request->input("post_code");

        $company["return_proc"] = $request->input("return_proc");
        $company["arival"] = $request->input("arival");
        $company["returnfront"] = $request->input("returnfront");
        $company["is_active"] = $request->input("status");
        $company["message"] = $request->input("message");
        $company["processtime"] = $request->input("process_time");

        $company["recommended"] = $request->input("recommended");
        $company["featured"] = $request->input("featured");

        // $company->levy_checked =$request->input("levy_checked");
        $company["cancelable"] = $request->input("cancelable");
        $company["editable"] = $request->input("editable");
        if ($request->input("features")) {
            $company["special_features"] = implode(",", $request->input("features"));
        }
        //FACILITY ADDED


        // echo "<pre>"; print_r($company); die();

//file storage
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('companies');
            $company["logo"] = $path;
        }

//        print_r($request->file('logo')); die();
//exit;

        if ($saveData = Company::create($company)) {

            $cid = $saveData->id;
            $facilities = $request->input("facility");
            $products = $request->input("product");
            $awards = $request->input("awards");

            //add facilites
            if (count($facilities) > 0 && !empty($facilities) && is_array($facilities)) {
                $data = [];
                foreach ($facilities as $facility) {
                    if ($facility != "") {
                        $data[] = array("company_id" => $cid, "description" => $facility, 'type' => "company");
                    }
                }
                DB::table('facilities')->insert($data);
            }

            //add products
            if (count($products) > 0 && !empty($products) && is_array($products)) {
                $data = [];
                foreach ($products as $product) {
                    if ($product != "") {
                        $data[] = array("company_id" => $cid, "product_name" => $product);
                    }
                }
                DB::table('companies_products')->insert($data);
            }

            //add awards
            if (count($awards) > 0 && !empty($awards) && is_array($awards)) {
                $data = [];
                foreach ($awards as $award) {
                    if ($award != "") {
                        $data[] = array("cid" => $cid, "award_id" => $award);
                    }
                }
                DB::table('companies_assign_awards')->insert($data);
            }


            return redirect("admin/company")->with("success", "Company added Successfully");

        }

    }
    
    public function store_lounge(Request $request)
    {
        
        //echo "<pre>"; print_r($request->input("company_email")); die();
        $messages = [
            'required' => 'This field is required.',
        ];
        $request->validate([
            'name' => 'required|string|max:255',
            'admin_id' => 'required',
        ], $messages);

        $company = [];
        $company["title"] = $request->input("name");
        $company["admin_id"] = $request->input("admin_id");
        $company["company_email"] = $request->input("company_email");

        $company["share_percentage"] = $request->input("share_percentage");
        $company["max_discount"] = $request->input("max_discount");
        $company["overview"] = $request->input("overview");

        $company["terms"] = $request->input("arival");
        $company["status"] = $request->input("status");
//dd($company);

//        print_r($request->file('logo')); die();
//exit;

        if ($saveData = lounge::create($company)) {
            $cid = $saveData->id;
            return redirect("admin/company")->with("success", "Company added Successfully");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        //facilities
        //companies_product
        //companies_assign_awards

        $saved_facilities = facilities::all()->where("company_id", "=", $id)->toArray();

        $saved_facilities_sorted = [];
        foreach ($saved_facilities as $save) {
            $saved_facilities_sorted[]["description"] = $save["description"];


        }


        $saved_products = companies_products::all()->where("company_id", "=", $id)->toArray();
        $saved_products_sorted = [];
        foreach ($saved_products as $save) {
            $saved_products_sorted[] = $save["product_name"];
        }

        $saved_awards = companies_assign_awards::all()->where("cid", "=", $id)->toArray();


        $saved_awards_sorted = [];
        foreach ($saved_awards as $save) {
            $saved_awards_sorted[] = $save["award_id"];
        }

        //
        $company = Company::findOrFail($id);
        //
        $users = User::all()->toArray();
        $usersList = [];
        foreach ($users as $u) {
            $usersList[$u["id"]] = $u["name"];
        }

        $airports = airport::all()->where("status", "Yes")->toArray();
        $airportsList = [];
        foreach ($airports as $u) {
            $airportsList[$u["id"]] = $u["name"];
        }


        $awards = Awards::all()->where("type", "=", "company");


        $opening_closing_time = [];
        for ($i = 0; $i <= 23; $i++) {
            for ($j = 0; $j <= 45; $j += 15) {
                //$sel = str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT) == $opening_time ? 'selected' : '';
                //echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'"'.$sel.'>'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'</option>';
                $opening_closing_time[str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT);
            }
        }

        $features = companies_special_features::all()->toArray();
        $features1 = [];
        $features2 = [];
        $hald_features = (int)count($features) / 2;
        $i = 1;
        foreach ($features as $u) {
            if ($i > $hald_features) {
                $features2[] = $u["name"];

            } else {
                $features1[] = $u["name"];
            }

            $i++;
        }


        return view('admin.company.edit', ["saved_facilities" => $saved_facilities_sorted, "saved_awards_sorted" => $saved_awards_sorted, "saved_products" => $saved_products_sorted, "company" => $company, "id" => $id, "users" => $usersList, "airportsList" => $airportsList, "opening_closing_time" => $opening_closing_time, "features1" => $features1, "features2" => $features2, "awardsList" => $awards]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);


        //echo "<pre>"; print_r($request->input("company_email")); die();
        $messages = [
            'required' => 'This field is required.',
        ];
        $request->validate([
            'name' => 'required|string|max:255',
            'admin_id' => 'required',
            'company_code' => 'required',

            'airport_id' => 'required',
            'terminal' => 'required'
        ], $messages);


        $company["name"] = $request->input("name");
        $company["admin_id"] = $request->input("admin_id");
        $company["company_code"] = $request->input("company_code");
        $company["aph_id"] = $request->input("aph_id");

        if ($request->input("company_email")) {
            $company["company_email"] = implode(",", $request->input("company_email"));
        }
        $company["airport_id"] = $request->input("airport_id");
        $company["terminal"] = $request->input("terminal");

        $company["address"] = $request->input("address");
        $company["address2"] = $request->input("address2");
        $company["town"] = $request->input("town");
        $company["parking_type"] = $request->input("parking_type");
        $company["closing_time"] = $request->input("closing_time");
        $company["opening_time"] = $request->input("opening_time");
        $company["share_percentage"] = $request->input("share_percentage");
        $company["max_discount"] = $request->input("max_discount");
        $company["overview"] = $request->input("overview");
        $company["post_code"] = $request->input("post_code");

        $company["return_proc"] = $request->input("return_proc");
        $company["arival"] = $request->input("arival");
        $company["returnfront"] = $request->input("returnfront");
        $company["is_active"] = $request->input("status");
        $company["message"] = $request->input("message");
        $company["processtime"] = $request->input("process_time");

        $company["recommended"] = $request->input("recommended");
        $company["featured"] = $request->input("featured");

        // $company->levy_checked =$request->input("levy_checked");
        $company["cancelable"] = $request->input("cancelable");
        $company["editable"] = $request->input("editable");
        if ($request->input("features")) {
            $company["special_features"] = implode(",", $request->input("features"));
        }
        //FACILITY ADDED


         //echo "<pre>"; dd($request->hasFile('logo')); die();

//file storage
        if ($request->hasFile('logo')) {
            
            $path = $request->file('logo')->store('companies');
            //echo $path."||| pather here"; die();
            $company["logo"] = $path;
            //dd($company["logo"]);
        }



        if ($company->save()) {



            $cid = $id;
            $facilities = $request->input("facility");
            $products = $request->input("product");
            $awards = $request->input("awards");

            DB::table('facilities')->where('company_id', "=",$cid)->delete();
            //add facilites
            if (count($facilities) > 0 && !empty($facilities) && is_array($facilities)) {

                $data = [];
                foreach ($facilities as $facility) {
                    if ($facility != "") {
                        $data[] = array("company_id" => $cid, "description" => $facility, 'type' => "company");
                    }
                }
                DB::table('facilities')->insert($data);
            }

            //add products
            DB::table('companies_products')->where('company_id', "=",$cid)->delete();
            if (count($products) > 0 && !empty($products) && is_array($products)) {
                $data = [];
                foreach ($products as $product) {
                    if ($product != "") {
                        $data[] = array("company_id" => $cid, "product_name" => $product);
                    }
                }
                DB::table('companies_products')->insert($data);
            }

            //add awards
            DB::table('companies_assign_awards')->where('cid', "=",$cid)->delete();
            if (count($awards) > 0 && !empty($awards) && is_array($awards)) {
                $data = [];
                foreach ($awards as $award) {
                    if ($award != "") {
                        $data[] = array("cid" => $cid, "award_id" => $award);
                    }
                }
                DB::table('companies_assign_awards')->insert($data);
            }


            return redirect("/admin/company")->with("success", "Company updated Successfully");
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $aid = $id;
        DB::table('companies')->where('id', $aid)->delete();
        return redirect("/admin/company")->with('success', 'Information Deleted successfully');
    }


    public function getTerminalsByAirportId($aid)
    {
        $terminals = airports_terminals::all()->where("aid", "=", $aid);
        //print_r($terminals); die();
        $html = '';
        foreach ($terminals as $terminal) {
            $html .= "<option value='" . $terminal->id . "'>" . $terminal->name . "</option>";
        }
        //$html .= "</select>";
        return $html;
    }


    public function get($aid)
    {
        $terminals = airports_terminals::all()->where("aid", "=", $aid);
        //print_r($terminals); die();
        $html = '';
        foreach ($terminals as $terminal) {
            $html .= "<option value='" . $terminal->id . "'>" . $terminal->name . "</option>";
        }
        //$html .= "</select>";
        return $html;
    }


    public function generateCompanyCode($id)
    {
        $code = "FPP";
        $company_code = $code . $id;
        $company_code = preg_replace('/[^a-zA-Z0-9\s]/', '', strip_tags($company_code));
        $company_code = strtoupper($company_code);
        return $company_code;
    }

}
