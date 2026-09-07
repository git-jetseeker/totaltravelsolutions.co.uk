<?php

namespace App\Library;

//require_once("../session.php");
use App\Models\Company;
use App\Models\modules_settings;
use App\Models\settings;
use Illuminate\Support\Facades\DB;

class api
{
    public $_mysetting = [];

    public $_setting = [];
    
    public $a2z_equal_or_above_percentage = 19;

    public function __construct()
    {

        $modules_settings = modules_settings::all();

        foreach ($modules_settings as $setting) {
            $this->_setting[$setting->name] = $setting->value;
        }
        $my_settings = settings::all();
        foreach ($my_settings as $my_setting) {
            $this->_mysetting[$my_setting->field_name] = $my_setting->field_value;

        }

    }

    public function a2z_record($a2zCompanies, $airport_id, $search_filter)
    {
        // global $db;
        $array1 = [];
        $array = [];

        //echo "count=".count($APHcompanies);
        // dd($a2zCompanies, $airport_id, $search_filter);
        foreach ($a2zCompanies as $a2zCompany) {
            $company_details = $this->get_records_A2z($airport_id, $a2zCompany['companyID'], $search_filter);
            // dd($company_details);
            $airport = DB::table('airports')->whereRaw("id = $airport_id")->first();
            $airport_name = $airport->name;
            if (empty($company_details)) {
                continue;
            }

            $array['opening_time'] = $company_details['opening_time'];
            $array['closing_time'] = $company_details['closing_time'];
            $array['id'] = $company_details['companyID'];
            $array['companyID'] = $company_details['companyID'];
            $array['aph_id'] = $company_details['aph_id'];
            $array['product_code'] = $company_details['company_code'];
            $array['name'] = $company_details['name'];
            $array['processtime'] = $company_details['processtime'];
            $array['cancelable'] = $company_details['cancelable'];
            $array['awards'] = $company_details['awards'];
            $array['featured'] = $company_details['featured'];
            $array['recommended'] = $company_details['recommended'];
            $array['share_percentage'] = $company_details['share_percentage'];
            $array['special_features'] = $company_details['special_features'];
            $array['overview'] = $company_details['overview'];
            $array['return_proc'] = $company_details['return_proc'];
            $array['arival'] = $company_details['arival'];
            $array['terms'] = $company_details['terms'];
            $array['address'] = $company_details['address'];
            $array['town'] = $company_details['town'];
            $array['post_code'] = $company_details['post_code'];
            $array['message'] = $company_details['message'];
            $array['parking_type'] = $company_details['parking_type'];
            $array['parking_name'] = $company_details['name'].' '.$airport_name;
            $array['logo'] = $a2zCompany['logo'];
            $array['travel_time'] = $company_details['travel_time'];
            $array['miles_from_airport'] = $company_details['miles_from_airport'];
            $array['editable'] = $company_details['editable'];
            $array['is_flex'] = $company_details['is_flex'];
            $array['bookingspace'] = $company_details['bookingspace'];
            $array['price'] = $a2zCompany['price'];
            // $array['EA'] = $a2zCompany['EA'];
            $array['park_api'] = 'a2z';
            $array1[] = $this->array_flatten($array);
        }
        $array1 = json_decode(json_encode((array) $array1), true);

        return $array1;
    }

    public function aph_record($APHcompanies, $airport_id, $search_filter)
    {
        // global $db;

        $array1 = [];
        $array = [];
        //echo "count=".count($APHcompanies);

        foreach ($APHcompanies as $APHcompany) {
            $company_details = $this->get_records($airport_id, $APHcompany['companyID'], $search_filter);

            //$airport = DB::select( DB::raw("SELECT * from airports WHERE id = $airport_id"));
            $airport = DB::table('airports')->whereRaw("id = $airport_id")->first();
            //$airport = $airport[0];
            $airport_name = $airport->name;
            if (empty($company_details)) {
                continue;
            }

            $array['opening_time'] = $company_details['opening_time'];
            $array['closing_time'] = $company_details['closing_time'];
            $array['id'] = $company_details['companyID'];
            $array['companyID'] = $company_details['companyID'];
            $array['aph_id'] = $APHcompany['companyID'];
            $array['product_code'] = $APHcompany['ProductCode'];
            $array['name'] = $company_details['name'];
            $array['processtime'] = $company_details['processtime'];
            $array['cancelable'] = $company_details['cancelable'];
            $array['awards'] = $company_details['awards'];
            $array['featured'] = $company_details['featured'];
            $array['recommended'] = $company_details['recommended'];
            $array['share_percentage'] = $company_details['share_percentage'];
            $array['special_features'] = $company_details['special_features'];
            $array['overview'] = $company_details['overview'];
            $array['return_proc'] = $company_details['return_proc'];
            $array['arival'] = $company_details['arival'];
            $array['terms'] = $company_details['terms'];
            $array['address'] = $company_details['address'];
            $array['town'] = $company_details['town'];
            $array['post_code'] = $company_details['post_code'];
            $array['message'] = $company_details['message'];
            $array['parking_type'] = $APHcompany['parking_type'];
            $array['parking_name'] = $APHcompany['name'].' '.$airport_name;
            $array['logo'] = $APHcompany['Logo'];
            $array['travel_time'] = $company_details['travel_time'];
            $array['miles_from_airport'] = $company_details['miles_from_airport'];
            $array['editable'] = $company_details['editable'];
            $array['is_flex'] = $company_details['is_flex'];
            $array['bookingspace'] = $company_details['bookingspace'];
            $array['price'] = $APHcompany['price'];
            $array['EA'] = $APHcompany['EA'];
            $array['park_api'] = 'aph';
            $array['aphactive'] = 1;
            //$array1[] = $array;
            $array1[] = $this->array_flatten($array);
        }
        //print_r($array1);
        //die('aph');
        $array1 = json_decode(json_encode((array) $array1), true);

        //   dd($array1);
        return $array1;
    }

    public function ace_record($ACEcompanies, $airport_id, $search_filter)
    {
        global $db;
        $array1 = [];
        $array = [];
        foreach ($ACEcompanies as $ACEcompany) {
            $companyID = explode('FP', $ACEcompany['sku']);
            $company_details = get_records($airport_id, $companyID[1], $search_filter);
            if (empty($company_details)) {
                continue;
            }

            $array['opening_time'] = $company_details['opening_time'];
            $array['closing_time'] = $company_details['closing_time'];
            $array['id'] = $company_details['companyID'];
            $array['companyID'] = $company_details['companyID'];
            $array['sku'] = $ACEcompany['sku'];
            $array['name'] = $company_details['name'];
            $array['processtime'] = $company_details['processtime'];
            $array['cancelable'] = $company_details['cancelable'];
            $array['awards'] = $company_details['awards'];
            $array['featured'] = $company_details['featured'];
            $array['recommended'] = $company_details['recommended'];
            $array['special_features'] = $company_details['special_features'];
            $array['overview'] = $company_details['overview'];
            $array['return_proc'] = $company_details['return_proc'];
            $array['arival'] = $company_details['arival'];
            $array['terms'] = $company_details['terms'];
            $array['address'] = $company_details['address'];
            $array['town'] = $company_details['town'];
            $array['post_code'] = $company_details['post_code'];
            $array['message'] = $company_details['message'];
            $array['parking_type'] = $company_details['parking_type'];
            $array['parking_name'] = $company_details['name'];
            $array['logo'] = $company_details['logo'];
            $array['travel_time'] = $company_details['travel_time'];
            $array['miles_from_airport'] = $company_details['miles_from_airport'];
            $array['editable'] = $company_details['editable'];
            $array['bookingspace'] = $company_details['bookingspace'];
            $array['price'] = $ACEcompany['price'];

            //$array1[] = $array;
            $array1[] = array_flatten($array);
        }
        $array1 = json_decode(json_encode((array) $array1), true);

        return $array1;
    }

    public function get_records_A2z($a_id, $cid, $search_filter)
    {
        $details = '';

        if (! empty($cid) && ! empty($a_id)) {
            $cid = is_numeric($cid) ? "and fc.id = '".$cid."'" : "and fc.company_code = '".$cid."'";

            $details = \DB::table('companies as fc')
                ->selectRaw('fc.opening_time,
                fc.closing_time,
                fc.id as companyID,
                fc.aph_id,
                fc.company_code,
                fc.name,
                fc.processtime,
                fc.awards,
                fc.featured,
                fc.recommended,
                fc.special_features,
                fc.overview,
                IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,
                IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,
                fc.terms,
                fc.address,
                fc.town,
                fc.post_code,
                fc.message,
                fc.parking_type,
                fc.logo,
                fc.travel_time,
                fc.miles_from_airport,
                fc.cancelable,
                fc.editable,
                fc.is_flex,
                fc.share_percentage,
                fc.bookingspace')
                // ->whereRaw("share_percentage = '".$a2z_equal_or_above_percentage."'")
                ->whereRaw("is_active = 'Yes' and airport_id = '".$a_id."' $cid $search_filter")
                ->first();

        } //end if

        $details = json_decode(json_encode((array) $details), true);

        return $details;
    }

    public function get_records($a_id, $cid, $search_filter)
    {
        $details = '';

        if (! empty($cid) && ! empty($a_id)) {
            $cid = is_numeric($cid) ? "and fc.id = '".$cid."'" : "and fc.aph_id = '".$cid."'";

            $details = \DB::table('companies as fc')
                ->selectRaw('fc.opening_time,
                fc.closing_time,
                fc.id as companyID,
                fc.aph_id,
                fc.company_code,
                fc.name,
                fc.processtime,
                fc.awards,
                fc.featured,
                fc.recommended,
                fc.special_features,
                fc.overview,
                IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,
                IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,
                fc.terms,
                fc.address,
                fc.town,
                fc.post_code,
                fc.message,
                fc.parking_type,
                fc.logo,
                fc.travel_time,
                fc.miles_from_airport,
                fc.cancelable,
                fc.editable,
                fc.is_flex,
                fc.share_percentage,
                fc.bookingspace')
                ->whereRaw("is_active = 'Yes' and airport_id = '".$a_id."' $cid $search_filter")->first();

        } //end if

        $details = json_decode(json_encode((array) $details), true);

        return $details;
    }

    /**
     * Lookup company by company_code (case-insensitive). No listing filters applied.
     */
    public function get_records_by_code($a_id, $cid, $search_filter = '')
    {
        $details = '';
        $cid = trim((string) $cid);

        if ($cid !== '' && !empty($a_id)) {
            $details = \DB::table('companies as fc')
                ->selectRaw('fc.opening_time,
                fc.closing_time,
                fc.id as companyID,
                fc.aph_id,
                fc.company_code,
                fc.name,
                fc.processtime,
                fc.awards,
                fc.featured,
                fc.recommended,
                fc.special_features,
                fc.share_percentage,
                fc.overview,
                IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,
                IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,
                fc.terms,
                fc.address,
                fc.town,
                fc.post_code,
                fc.message,
                fc.parking_type,
                fc.logo,
                fc.travel_time,
                fc.miles_from_airport,
                fc.cancelable,
                fc.editable,
                fc.is_flex,
                fc.bookingspace')
                ->whereRaw("is_active = 'Yes' and (removed IS NULL OR removed != 'Yes') and airport_id = ? and LOWER(fc.company_code) = ?", [$a_id, strtolower($cid)])
                ->first();
        }

        return json_decode(json_encode((array) $details), true);
    }

    public function array_flatten($array)
    {
        if (! is_array($array)) {
            return false;
        }
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $arrayList = array_flatten($value);
                foreach ($arrayList as $listItem) {
                    $result[$key] = $listItem;
                }
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function holiday_record($holidaycompanies, $airport_id, $search_filter)
    {
        global $db;
        $array1 = [];
        $array = [];

        foreach ($holidaycompanies as $holidaycompany) {
            $company_details = $this->get_records_holiday_db($airport_id, $holidaycompany['sku'], $search_filter);
            if (empty($company_details)) {
                    continue;
                }
 
            if ($this->_mysetting['holiday_list_update'] == 'Active' && empty($company_details)) {

                $company_details_api = $this->get_records_holiday($airport_id, $holidaycompany['sku'], $search_filter);
                $checkCompany = DB::table('companies')->where('company_code', $holidaycompany['sku'])->get();
                $checkCompany = json_decode($checkCompany, true);

                if (empty($checkCompany)) {
                    $company_insert = $this->insert_holiday_record($company_details_api, $airport_id, $holidaycompany['sku']);
                }
                $company_details = $this->get_records_holiday_db($airport_id, $holidaycompany['sku'], $search_filter);
                if (empty($company_details)) {
                    continue;
                }
            }

            $array['opening_time'] = $company_details['opening_time'];
            $array['closing_time'] = $company_details['closing_time'];
            $array['id'] = $company_details['companyID'];
            $array['aph_id'] = $company_details['aph_id'];
            $array['companyID'] = $company_details['companyID'];
            $array['sku'] = $holidaycompany['sku'];
            $array['product_code'] = $holidaycompany['sku'];
            $array['name'] = $company_details['name'];
            $array['processtime'] = $company_details['processtime'];
            $array['cancelable'] = $company_details['cancelable'];
            $array['awards'] = $company_details['awards'];
            $array['featured'] = $company_details['featured'];
            $array['recommended'] = $company_details['recommended'];
            $array['share_percentage'] = $company_details['share_percentage'];
            $array['special_features'] = $company_details['special_features'];
            $array['overview'] = $company_details['overview'];
            $array['return_proc'] = $company_details['return_proc'];
            $array['arival'] = $company_details['arival'];
            $array['terms'] = $company_details['terms'];
            $array['address'] = $company_details['address'];
            $array['town'] = $company_details['town'];
            $array['post_code'] = $company_details['post_code'];
            $array['message'] = $company_details['message'];
            $array['parking_type'] = $company_details['parking_type'];
            $array['parking_name'] = $company_details['name'];
            $array['logo'] = $company_details['logo'];
            $array['travel_time'] = $company_details['travel_time'];
            $array['miles_from_airport'] = $company_details['miles_from_airport'];
            $array['editable'] = $company_details['editable'];
            $array['is_flex'] = $company_details['is_flex'];
            $array['bookingspace'] = $company_details['bookingspace'];
            $array['price'] = $holidaycompany['price'];
            $array['EA'] = $holidaycompany['EA'];
            $array['park_api'] = 'holiday';

            //$array1[] = $array;
            $array1[] = $this->array_flatten($array);
        }
        $array1 = json_decode(json_encode((array) $array1), true);

        //echo "<pre>"; print_r($holidaycompanies); echo "</pre>"; exit;
        return $array1;
    }

    public function get_records_holiday_db($a_id, $cid, $search_filter)
    {
        //global $db;
        $details = '';

        if (! empty($cid) && ! empty($a_id)) {
            $cid = "and fc.company_code = '".$cid."'";

            //echo "<br>".$cid."<br>";

            $details = \DB::table('companies as fc')
                ->selectRaw('fc.opening_time,
                fc.closing_time,
                fc.id as companyID,
                fc.aph_id,
                fc.name,
                fc.processtime,
                fc.awards,
                fc.featured,
                fc.recommended,
                fc.share_percentage,
                fc.special_features,
                fc.overview,
                IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,
                IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,
                fc.terms,
                fc.address,
                fc.town,
                fc.post_code,
                fc.message,
                fc.parking_type,
                fc.logo,
                fc.travel_time,
                fc.miles_from_airport,
                fc.cancelable,
                fc.editable,
                fc.is_flex,
                fc.bookingspace')
                ->whereRaw("is_active = 'Yes' and airport_id = '".$a_id."' $cid $search_filter")
            //;
            //->get();
                ->first();

        } //end if

        $details = json_decode(json_encode((array) $details), true);
        //$details = $this->array_flatten($details);
        //$details = $details[0];
        if (! empty($details)) {
            return $details;
        }
    }

    public function get_records_holiday($a_id, $cid, $search_filter)
    {

        $details = '';
        $url = 'https://api.holidayextras.co.uk/v1/product/'.$cid.'.js';
        $getString = '?ABTANumber=AJ166&Password=PAXML&Initials=pa&key=parkingzone&token=829152491';
        $final = $url.$getString;
        //echo $final;exit;
        $result = $this->curl_call($final);

        $result = json_decode($result, true);

        //echo "<pre>"; print_r($result['API_Reply']['Product'][0]); echo "</pre>";

        $details = $result['API_Reply']['Product'][0];

        return $details;
    }

    public function insert_holiday_record($company_details, $airport_id, $holidaycompay)
    {
        global $db;

        $company = [];
        $company['name'] = $company_details['name'];
        $company['admin_id'] = 38;
        $company['company_code'] = $holidaycompay;
        $company['aph_id'] = '';
        $company['company_email'] = '';
        $company['airport_id'] = $airport_id;
        //$company["terminal"] = '';

        $company['address'] = $company_details['address'];
        $company['address2'] = '';
        $company['town'] = '';
        $company['post_code'] = $company_details['postcode'];
        if ($company_details['meet_and_greet'] == 1) {
            $parking_type = 'Meet and Greet';
        } else {
            $parking_type = 'Park and Ride';
        }
        $company['parking_type'] = $parking_type;
        $company['closing_time'] = '00:00';
        $company['opening_time'] = '00:00';
        $company['share_percentage'] = 12;
        $company['max_discount'] = 2;
        $company['overview'] = $company_details['tripappintroduction'];

        $company['arival'] = $company_details['arrival_procedures'].'<br>'.$company_details['directions'].'<br> Phone:'.$company_details['telephone'];
        $company['return_proc'] = $company_details['departure_procedures'].'<br>'.$company_details['car_park_terms'];
        $company['returnfront'] = $company_details['departure_procedures'];
        $company['is_active'] = 'Yes';
        $company['message'] = '';
        $company['processtime'] = '';
        $recommended = '';
        if ($company_details['recommended'] == 1) {
            $recommended = 'Yes';
        } else {
            $recommended = 'No';
        }
        $company['recommended'] = $recommended;
        // $company["featured"] = 'No';
        $company['logo'] = 'https://holidayextras.imgix.net/'.str_replace('imageLibrary', 'libraryimages', $company_details['logo']);
        //$company['logo'] = 'companies/bstVSos42N24S48c2W0e6MdaLr9bvUK1MIyW0x5k.png';
        // $company->levy_checked =$request->input("levy_checked");
        // $company["cancelable"] = '';
        // $company["editable"] = '';

        if ($company_details['security_barrier'] == 1) {
            $facility[] = 'SECURE BARRIER';
        }
        if ($company_details['security_cctv'] == 1) {
            $facility[] = 'CCTV';
        }
        if ($company_details['security_lighting'] == 1) {
            $facility[] = 'SECURITY LIGHTING';
        }
        if ($company_details['security_patrols'] == 1) {
            $facility[] = 'PATROLLED';
        }
        if ($company_details['security_fencing'] == 1) {
            $facility[] = 'FENCING';
        }
        if ($company_details['keep_keys'] == 1) {
            $facility[] = 'KEEP YOUR KEYS';
        }
        $facility[] = 'FAMILY FRENDLY';
        $company['special_features'] = implode(',', $facility);
        //$saveData = Company::create($company);

        if ($saveData = Company::create($company)) {

            $cid = $saveData->id;

            if ($company_details['why_bookone'] != '') {
                $data1 = ['company_id' => $cid, 'description' => $company_details['why_bookone'], 'type' => 'company'];
                DB::table('facilities')->insert($data1);
            }
            if ($company_details['why_booktwo'] != '') {
                $data2 = ['company_id' => $cid, 'description' => $company_details['why_booktwo'], 'type' => 'company'];
                DB::table('facilities')->insert($data2);
            }
            if ($company_details['why_bookthree'] != '') {
                $data3 = ['company_id' => $cid, 'description' => $company_details['why_bookthree'], 'type' => 'company'];
                DB::table('facilities')->insert($data3);
            }
            if ($company_details['why_bookfour'] != '') {
                $data4 = ['company_id' => $cid, 'description' => $company_details['why_bookfour'], 'type' => 'company'];
                DB::table('facilities')->insert($data4);
            }
        }

    }

    public function holiday_lounge_record($holidaycompanies, $airport_id, $search_filter)
    {
        global $db;
        $array1 = [];
        $array = [];
        foreach ($holidaycompanies as $holidaycompany) {
            //$companyID = explode('FPP', $holidaycompany['sku']);

            $company_details = $this->get_records_holiday($airport_id, $holidaycompany['sku'], $search_filter);
            //echo "<pre>"; print_r($company_details); echo "</pre>"; exit;
            if (empty($company_details)) {

                //$company_insert = $this->insert_holiday_record($company_details_api, $airport_id, $holidaycompany['sku']);

                //$company_details = $this->get_records_holiday_db($airport_id, $holidaycompany['sku'], $search_filter);
                continue;
            }

            $array['opening_time'] = $company_details['openingtime'];
            $array['closing_time'] = $company_details['closingtime'];
            $array['companyID'] = $holidaycompany['sku'];
            $array['sku'] = $holidaycompany['sku'];
            $array['product_code'] = $holidaycompany['sku'];
            $array['name'] = $company_details['name'];

            $array['facilities'] = $company_details['facilities'];
            $array['entertainment_facilities'] = $company_details['entertainment_facilities'];
            $array['introduction'] = $company_details['introduction'];
            $array['why_bookone'] = $company_details['why_bookone'];
            $array['why_booktwo'] = $company_details['why_booktwo'];
            $array['why_bookthree'] = $company_details['why_bookthree'];
            $array['why_bookfour'] = $company_details['why_bookfour'];
            $array['menu_drinks'] = $company_details['menu_drinks'];
            $array['menu_extras'] = $company_details['menu_extras'];
            $array['menu_food'] = $company_details['menu_food'];
            $array['whats_included_drinks'] = $company_details['whats_included_drinks'];
            $array['whats_included_extras'] = $company_details['whats_included_extras'];
            $array['address'] = $company_details['address'];
            $array['businessfacilities'] = $company_details['businessfacilities'];
            $array['flightannouncements'] = $company_details['flightannouncements'];
            $array['tripappintroduction'] = $company_details['tripappintroduction'];
            $array['features'] = $company_details['_a_facilities'];
            $array['logo'] = 'https://holidayextras.imgix.net/'.str_replace('imageLibrary', 'libraryimages', $company_details['logo']);
            $array['images'] = str_replace('imageLibrary', 'libraryimages', $company_details['tripappimages']);
            $array['checkintime'] = $company_details['checkintime'];
            $array['directions'] = $company_details['directions'];
            $array['price'] = $holidaycompany['price'];
            $array['terminal'] = $holidaycompany['terminal'];
            $array['booking_url'] = $holidaycompany['booking_url'];
            $array['park_api'] = 'holiday';

            //$array1[] = $array;
            $array1[] = $this->array_flatten($array);
        }
        $array1 = json_decode(json_encode((array) $array1), true);

        //echo "<pre>"; print_r($array1); echo "</pre>"; exit;
        return $array1;
    }

    public function curl_call($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function bookfhr_record($bookfhrData, $airport_id, $search_filter)
    {
        $array1 = [];

        if (!isset($bookfhrData['results']) || empty($bookfhrData['results'])) {
            return $array1;
        }

        // Do not apply parking-type / share filters when mapping API → company row.
        $lookupFilter = '';

        foreach ($bookfhrData['results'] as $result) {
            if (!isset($result['product']) || !isset($result['options'])) {
                continue;
            }

            $product = $result['product'];
            $productId = (string) ($product['id'] ?? '');

            foreach ($result['options'] as $option) {
                $codesToTry = array_filter([
                    'BOOKFHR_' . $productId,
                    'bookfhr_' . $productId,
                    $productId !== '' ? $productId : null,
                ]);

                $company_details = [];
                foreach ($codesToTry as $code) {
                    $company_details = $this->get_records_by_code($airport_id, $code, $lookupFilter);
                    if (!empty($company_details)) {
                        break;
                    }
                }

                if (empty($company_details)) {
                    continue;
                }

                $api_price = (float) ($option['price']['amount'] ?? 0);
                $share_percentage = (float) ($company_details['share_percentage'] ?? 0);
                $temp = $api_price * $share_percentage / 100;
                $bookfhr_share = $api_price - $temp;
                $new_price = $bookfhr_share / 65 * 100;

                $array = [
                    'opening_time' => $company_details['opening_time'],
                    'closing_time' => $company_details['closing_time'],
                    'id' => $company_details['companyID'],
                    'companyID' => $company_details['companyID'],
                    'aph_id' => $company_details['aph_id'],
                    'product_code' => 'BOOKFHR_' . $productId,
                    'name' => $company_details['name'],
                    'processtime' => $company_details['processtime'],
                    'cancelable' => $company_details['cancelable'],
                    'awards' => $company_details['awards'],
                    'featured' => $company_details['featured'],
                    'recommended' => $company_details['recommended'],
                    'special_features' => $company_details['special_features'],
                    'share_percentage' => $share_percentage,
                    'overview' => $company_details['overview'],
                    'return_proc' => $company_details['return_proc'],
                    'arival' => $company_details['arival'],
                    'terms' => $company_details['terms'],
                    'address' => $company_details['address'],
                    'town' => $company_details['town'],
                    'post_code' => $company_details['post_code'],
                    'message' => $company_details['message'],
                    'parking_type' => $company_details['parking_type'],
                    'parking_name' => $company_details['name'],
                    'logo' => $company_details['logo'],
                    'travel_time' => $company_details['travel_time'],
                    'miles_from_airport' => $company_details['miles_from_airport'],
                    'editable' => $company_details['editable'],
                    'bookingspace' => $company_details['bookingspace'],
                    'price' => number_format($api_price, 2, '.', ''),
                    'new_price' => number_format($new_price, 2, '.', ''),
                    'park_api' => 'bookfhr',
                    'price_source' => 'bookfhr',
                    'searchId' => $bookfhrData['searchId'] ?? '',
                    'optionId' => $option['id'],
                    'productId' => $productId,
                ];

                $array1[] = $this->array_flatten($array);
            }
        }

        $array1 = json_decode(json_encode((array) $array1), true);

        return $array1;
    }
}
