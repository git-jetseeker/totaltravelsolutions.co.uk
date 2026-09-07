<?php

namespace App\Http\Controllers;

use App\Models\modules_settings;
use App\Models\settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public $_setting = [];
    public $_module_setting = [];

    function __construct()
    {
        $this->middleware("auth");
        $modules_settings = settings::all();
        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->field_name] = $setting->field_value;
        }
        //module settings
        $modules_settings = modules_settings::all();
        foreach ($modules_settings as $setting) {
            $this->_module_setting[$setting->name] = $setting->value;
        }

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

    function seo_setting()
    {
        return view("admin.settings.seo_setting", ["settings" => $this->_setting]);
    }
    function bannerlist()
    { 
        
        return view("admin.banner.banner_list",  ["settings" => $this->_setting, "sliders" => unserialize($this->_setting['sliders']) ]);
    }
    
    function UploadBannerList(){
        $sliders_images = serialize($_POST['images']);
        echo $sliders_images;
        $setting = settings::updateOrCreate(["field_name" => 'sliders'], ["field_name" => 'sliders', "field_value" => $sliders_images]);
        $setting->save();
    }

    function company_setting()
    {
        $social_sites = [];

        $social_sites[] = ["fieldName" => "booking_fee", "title" => "Booking fee"];
        $social_sites[] = ["fieldName" => "booking_fee_note", "title" => "Booking Fee Note"];
        $social_sites[] = ["fieldName" => "booking_fee_status", "title" => "Status", "type" => "dropdown", "data" => ["Active" => "Active", "Inactive" => "Inactive"],"after"=>"<hr/>"];

        $social_sites[] = ["fieldName" => "cancellation_fee", "title" => "Cancellation fee"];
        $social_sites[] = ["fieldName" => "cancellation_fee_note", "title" => "Cancellation Fee Note"];
        $social_sites[] = ["fieldName" => "cancellation_fee_status", "title" => "Status", "type" => "dropdown", "data" => ["Active" => "Active", "Inactive" => "Inactive"],"after"=>"<hr/>"];


        $social_sites[] = ["fieldName" => "sms_notification_fee", "title" => "Sms Notification fee"];
        $social_sites[] = ["fieldName" => "sms_notification_fee_note", "title" => "Sms Notification Fee Note"];
        $social_sites[] = ["fieldName" => "sms_fee_status", "title" => "Status", "type" => "dropdown", "data" => ["Active" => "Active", "Inactive" => "Inactive"],"after"=>"<hr/>"];


        $social_sites[] = ["fieldName" => "postal_notification_fee", "title" => "Postal Notification fee"];
        $social_sites[] = ["fieldName" => "postal_notification_fee_note", "title" => "Postal Notification Fee Note"];
        $social_sites[] = ["fieldName" => "postal_status", "title" => "Status", "type" => "dropdown", "data" => ["Active" => "Active", "Inactive" => "Inactive"]];


        return view("admin.settings.company_setting", ["settings" => $this->_setting, "social_sites" => $social_sites]);
    }

    function social_setting()
    {
        $social_sites = [];
        $social_sites[] = ["fieldName" => "facebook", "title" => "Facebook"];
        $social_sites[] = ["fieldName" => "twitter", "title" => "Twitter"];
        $social_sites[] = ["fieldName" => "instagram", "title" => "Instagram Link"];
        $social_sites[] = ["fieldName" => "google_plus", "title" => "Google Plus Link"];
        $social_sites[] = ["fieldName" => "youtube", "title" => "Youtube Link"];
        $social_sites[] = ["fieldName" => "pinterest", "title" => "Pinterest Link"];
        $social_sites[] = ["fieldName" => "linkedin", "title" => "LinkedIn Link"];
        return view("admin.settings.social_setting", ["settings" => $this->_setting, "social_sites" => $social_sites]);
    }

    function email_setting()
    {
        $social_sites = [];
        $social_sites[] = ["fieldName" => "email_host", "title" => "Host Name"];
        $social_sites[] = ["fieldName" => "email_port", "title" => "Port Name"];
        $social_sites[] = ["fieldName" => "email_encryption_type", "title" => "Envryption type"];
        $social_sites[] = ["fieldName" => "email_username", "title" => "Username"];
        $social_sites[] = ["fieldName" => "email_password", "title" => "Password"];

        return view("admin.settings.email_setting", ["settings" => $this->_setting, "social_sites" => $social_sites]);
    }

    function general_setting()
    {

        $social_sites = [];
        $social_sites[] = ["fieldName" => "payment_type", "title" => "Active Payment Type", "type" => "dropdown", "data" => ["stripe" => "Stripe", "payzone" => "Payzone"],"after"=>"<hr/>"];
        $social_sites[] = ["fieldName" => "stripe_public_key", "title" => "Stripe Public Key"];
        $social_sites[] = ["fieldName" => "stripe_private_key", "title" => "Stripe Private Key","after"=>"<hr/>"];

        $social_sites[] = ["fieldName" => "payzone_merchant_id ", "title" => "Payzone Merchant id "];
        $social_sites[] = ["fieldName" => "payzone_mechant_password ", "title" => "Payzone Mechant Password "];
        $social_sites[] = ["fieldName" => "payzone_shared_key", "title" => "Payzone Shared Key"];
        $social_sites[] = ["fieldName" => "payzone_secret_key", "title" => "Payzone Secret Key","after"=>"<hr/>"];
        $social_sites[] = ["fieldName" => "address_key", "title" => "Address Api Key","after"=>"<hr/>"];


        return view("admin.settings.general_setting", ["settings" => $this->_module_setting, "social_sites" => $social_sites]);
    }

    function analytics_setting()
    {
        $social_sites = [];
        $social_sites[] = ["fieldName" => "site_header_analytics", "title" => "Site Header Analytics"];
        $social_sites[] = ["fieldName" => "site_footer_analytics", "title" => "Site Footer Analytics"];
        $social_sites[] = ["fieldName" => "site_confirm_page_analytics", "title" => "Site Confirmation Page Analytics"];
        return view("admin.settings.analytics", ["settings" => $this->_setting, "social_sites" => $social_sites]);
    }


    function footer_setting()
    {
        $social_sites = [];
        $social_sites[] = ["fieldName" => "footer_address", "title" => "Footer Address"];
        $social_sites[] = ["fieldName" => "footer_email", "title" => "Footer Email"];
        $social_sites[] = ["fieldName" => "footer_phone_no", "title" => "Footer Phone No"];
        $social_sites[] = ["fieldName" => "footer_copyright", "title" => "Footer Copyright"];
        $social_sites[] = ["fieldName" => "footer_company_reg_no", "title" => "Company number"]; 
        $social_sites[] = ["fieldName" => "footer_catch_line", "title" => "Footer Catch Line"];
        return view("admin.settings.footer_setting", ["settings" => $this->_setting, "social_sites" => $social_sites]);
    }


    function homepage_setting()
    {
        $social_sites = [];
        $social_sites[] = ["fieldName" => "homepage_howitwork_grid1_heading", "title" => " Grid1 heading"];
        $social_sites[] = ["fieldName" => "homepage_howitwork_grid1_descp", "title" => " Grid1 Description"];


        $social_sites[] = ["fieldName" => "homepage_howitwork_grid2_heading", "title" => " Grid2 heading"];
        $social_sites[] = ["fieldName" => "homepage_howitwork_grid2_descp", "title" => "Grid2 Description"];


        $social_sites[] = ["fieldName" => "homepage_howitwork_grid3_heading", "title" => "Grid3 heading"];
        $social_sites[] = ["fieldName" => "homepage_howitwork_grid3_descp", "title" => "Grid3 Description"];


        $social_sites[] = ["fieldName" => "homepage_howitwork_grid4_heading", "title" => "Grid4 heading"];
        $social_sites[] = ["fieldName" => "homepage_howitwork_grid4_descp", "title" => "Grid4 Description"];



        $why_chooseus_sites = [];
        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid1_heading", "title" => " Grid1 heading"];
        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid1_descp", "title" => " Grid1 Description"];


        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid2_heading", "title" => " Grid2 heading"];
        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid2_descp", "title" => "Grid2 Description"];


        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid3_heading", "title" => "Grid3 heading"];
        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid3_descp", "title" => "Grid3 Description"];


        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid4_heading", "title" => "Grid4 heading"];
        $why_chooseus_sites[] = ["fieldName" => "homepage_whychooseus_grid4_descp", "title" => "Grid4 Description"];


        $general = [] ;
        $general[] = ["fieldName" => "homepage_tagline_heading", "title" => "Header Tagline Heading"];
        $general[] = ["fieldName" => "homepage_tagline", "title" => "Header Tagline"];


        $parking_services= [];
        $parking_services[] = ["fieldName" => "homepage_service_heading", "title" => "Services Heading"];
        $parking_services[] = ["fieldName" => "homepage_service_descprition", "title" => "Services Description"];
    
        $parking_services[] = ["fieldName" => "homepage_service1", "title" => "Service 1"];
        $parking_services[] = ["fieldName" => "homepage_service2", "title" => "Service 2"];
        $parking_services[] = ["fieldName" => "homepage_service3", "title" => "Service 3"];


        $join_us_settings= [];
        $join_us_settings[] = ["fieldName" => "homepage_joinus_heading", "title" => "Join us Heading"];
        $join_us_settings[] = ["fieldName" => "homepage_joinus_subheading", "title" => "Join us Sub heading"];
        $join_us_settings[] = ["fieldName" => "homepage_joinus_text", "title" => "Join us text"];

        $main_headings= [];
        $main_headings[] = ["fieldName" => "homepage_airport_heading", "title" => "Airports Heading"];
        $main_headings[] = ["fieldName" => "homepage_work_heading", "title" => "Work Heading"];
        $main_headings[] = ["fieldName" => "homepage_review_heading", "title" => "Review Heading"];



        return view("admin.settings.homepage_setting", ["settings" => $this->_setting, "social_sites" => $social_sites,"why_chooseus_sites"=>$why_chooseus_sites,"general"=>$general,"parking_services"=>$parking_services,"join_us_settings"=>$join_us_settings, "main_headings"=>$main_headings]);
    }


    function services_page_setting()
    {


        //parking
        $parking_settings = [];
        $parking_settings[] = ["fieldName" => "services_page_parking_heading", "title" => "Heading"];
        $parking_settings[] = ["fieldName" => "services_page_parking_descp", "title" => "Description"];

        $parking_settings[] = ["fieldName" => "services_page_parking_sec1_heading", "title" => " Section 1 Heading "];
        $parking_settings[] = ["fieldName" => "services_page_parking_sec1_descp", "title" => "Section 1 Description"];

        $parking_settings[] = ["fieldName" => "services_page_parking_sec1_meetandgreet", "title" => "Meet and Greet"];
        $parking_settings[] = ["fieldName" => "services_page_parking_sec1_parkandride", "title" => "Park and Ride"];
        $parking_settings[] = ["fieldName" => "services_page_parking_sec1_onairport", "title" => "On Airport"];


        $parking_settings[] = ["fieldName" => "services_page_parking_sec2_heading", "title" => " Section 2 Heading "];
        $parking_settings[] = ["fieldName" => "services_page_parking_sec2_descp", "title" => "Section 2 Description"];

        $parking_settings[] = ["fieldName" => "services_page_parking_sec2_step1", "title" => "Section 2 Step 1"];
        $parking_settings[] = ["fieldName" => "services_page_parking_sec2_step2", "title" => "Section 2 Step 2"];
        $parking_settings[] = ["fieldName" => "services_page_parking_sec2_step3", "title" => "Section 2 Step 3"];


        //lounges
        $lounges_settings = [];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_heading", "title" => "Heading"];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_descp", "title" => "Description"];

        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec1_heading", "title" => " Section 1 Heading "];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec1_descp", "title" => "Section 1 Description"];

        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec1_grid1", "title" => "Section 1 Grid 1"];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec1_grid2", "title" => "Section 1 Grid 2"];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec1_grid3", "title" => "Section 1 Grid 3"];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec1_grid4", "title" => "Section 1 Grid 4"];


        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec2_heading", "title" => " Section 2 Heading "];
        $lounges_settings[] = ["fieldName" => "services_page_lounges_sec2_descp", "title" => "Section 2 Description"];


        //transfer
        $transfer_settings = [];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_heading", "title" => "Heading"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_descp", "title" => "Description"];

        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec1_bestprice", "title" => "BEST PRICES"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec1_bestsevices", "title" => "BEST SERVICE"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec1_safetransfer", "title" => "SAFE TRANSFERS"];


        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid1_heading", "title" => " Section 2 Grid 1 Heading"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid1_descp", "title" => " Section 2 Grid 1 Description"];

        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid2_heading", "title" => " Section 2 Grid 2 Heading"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid2_descp", "title" => " Section 2 Grid 2 Description"];

        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid3_heading", "title" => " Section 2 Grid 3 Heading"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid3_descp", "title" => " Section 2 Grid 3 Description"];

        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid4_heading", "title" => " Section 2 Grid 4 Heading"];
        $transfer_settings[] = ["fieldName" => "services_page_transfer_sec2_grid4_descp", "title" => " Section 2 Grid 4 Description"];











        return view("admin.settings.service_page_setting", ["settings" => $this->_setting, "transfer_settings" => $transfer_settings,"lounges_settings"=>$lounges_settings,"parking_settings"=>$parking_settings]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\settings $settings
     * @return \Illuminate\Http\Response
     */
    public function show(settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\settings $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        foreach ($request->input("setting") as $field_name => $field_value) {
            $setting = settings::updateOrCreate(["field_name" => $field_name], ["field_name" => $field_name, "field_value" => $field_value]);
            $setting->save();
        }
        return redirect()->back();
    }


    public function updateModuleSettings(Request $request)
    {
        //
        foreach ($request->input("setting") as $field_name => $field_value) {
            $setting = modules_settings::updateOrCreate(["name" => $field_name], ["name" => $field_name, "value" => $field_value]);
            $setting->save();
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\settings $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(settings $settings)
    {
        //
    }
}
