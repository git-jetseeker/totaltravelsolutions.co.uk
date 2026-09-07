<?php



namespace App\Http\Controllers\front;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;

use App\Http\Controllers\EmailController;

use App\Library\aph_functions;

use App\Library\api;

use App\Services\BookFhrService;

use App\Services\LoungeService;

use App\Services\HotelService;

use App\Models\Lounges;

use App\Models\Hotel;

use App\Support\HotelRoomFacilities;

use App\Models\OffDays;

use App\Models\airport;

use App\Models\airports_bookings;

use App\Models\airports_terminals;

use App\Models\companies_special_features;

use App\Models\discounts;

use App\Models\faqs;

use App\Models\modules_settings;

use App\Models\pages;

use App\Models\reviews;

use App\Models\settings;

use App\Models\Email;

use App\Models\ref_tracking;

use App\Models\subscribers;

use Carbon\Carbon;

use DateTime;

use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Validator;

use Symfony\Component\Console\Terminal;

use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Benchmark;





class HomeController extends Controller
{

    public $_setting = [];



    public $_settings = [];



    public $_module_setting = [];



    public function __construct()
    {

        $modules_settings = modules_settings::all();

        foreach ($modules_settings as $setting) {

            $this->_setting[$setting->name] = $setting->value;

        }



        //module settings

        $modules_settings = modules_settings::all();

        foreach ($modules_settings as $setting) {

            $this->_module_setting[$setting->name] = $setting->value;

        }

        // Added By Php Dev 07-08-2020

        $settings = settings::all();

        foreach ($settings as $setting) {

            $this->_settings[$setting->field_name] = $setting->field_value;

        }

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {





        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->where('agent_id', '9')->take(4)->sortByDesc('id');



        //dd($reviews);

        return view('home', ['airports' => $airports, 'reviews' => $reviews]);



        // return view('welcome');

    }

    public function search(Request $request)
    {
        return $this->getSearchResult($request);
    }



    public function landing()
    {

        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->where('agent_id', '1')->take(4)->sortByDesc('id');

        return view('frontend.landing', ['airports' => $airports, 'reviews' => $reviews]);

    }



    public function ppc_airport_parking()
    {

        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->where('agent_id', '1')->take(4)->sortByDesc('id');



        return view('frontend.ppc_airport_parking', ['airports' => $airports, 'reviews' => $reviews]);

    }



    public function subscribe_user(Request $request)
    {



        $messages = [

            'required' => 'This field is required.',

        ];

        //        $validatedData = $request->validate([

        //            'name' => 'required|string',

        //            'email' => 'required|string|unique:subscribers,email'

        //        ], $messages);



        $validatedData = Validator::make(Input::all(), [

            'name' => 'required|string|regex:/^[\pL\s\-]+$/u|min:4',

            'email' => 'required|string|unique:subscribers,email',



        ], $messages);



        if ($validatedData->fails()) {



            //pass validator errors as errors object for ajax response

            $d = '';

            foreach ($validatedData->messages()->getMessages() as $field_name => $messages) {

                $d .= $messages[0]; // messages are retrieved (publicly)

            }



            return response()->json(['success' => 0, 'errors' => $d]);

        } else {



            $name = $request->input('name') == '' ? 'Total Travel Solutions Subscriber ' : $request->input('name');

            $email = $request->input('email');

            $timeout = 53;

            //fiveg mailchimp

            try {

                $name_detail = explode(' ', $name);

                //print_r($name_detail);exit;

                $email_address = trim($email);

                $api_endpoint = 'https://us17.api.mailchimp.com/3.0/lists/73d9053859/members/';

                $mailchimp_user_info = [

                    'FNAME' => $name_detail[0],

                    'LNAME' => $name_detail[1],

                ];

                $data = [

                    'status' => 'subscribed',

                    'email_address' => $email_address,

                    'merge_fields' => $mailchimp_user_info

                ];



                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $api_endpoint);

                curl_setopt($ch, CURLOPT_HTTPHEADER, [

                    'Accept: application/vnd.api+json',

                    'Content-Type: application/vnd.api+json',

                    'Authorization: apikey ' . env('MAILCHIMP_API_KEY'),

                ]);

                curl_setopt($ch, CURLOPT_USERAGENT, 'X-Cart4');

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);

                //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

                curl_setopt($ch, CURLOPT_ENCODING, '');

                curl_setopt($ch, CURLINFO_HEADER_OUT, true);

                $response['body'] = curl_exec($ch);

                $response['headers'] = curl_getinfo($ch);

                $response_array = json_decode($response['body'], true);

                if (isset($response['headers']['request_header'])) {

                    $headers = $response['headers']['request_header'];

                }

                //print_r($response_array);

                if ($response['body'] === false) {

                    $last_error = curl_error($ch);

                    //print_r($last_error);

                }



                curl_close($ch);

                //print_r($mailchimp_user_info);

            } catch (Exception $e) {

            }

            //fivegmailchimp



            $sub = new subscribers();

            $sub->name = $request->input('name');

            $email1 = $sub->email = $request->input('email');

            $sub->save();

            $template_data = [];

            $template_data['username'] = $request->input('name');

            $email = new EmailController();

            $email->sendEmail('Subscription', $email1, $template_data);



            return response()->json(['success' => 1, 'data' => 'Successfully Subscribed.']);

        }

    }



    public function subscribe_user_and_discount(Request $request)
    {



        $messages = [

            'required' => 'This field is required.',

        ];

        $validatedData = Validator::make(Input::all(), [

            // 'name' => 'required|string|regex:/^[\pL\s\-]+$/u|min:4',

            'email' => 'required|string',



        ], $messages);



        if ($validatedData->fails()) {



            //pass validator errors as errors object for ajax response

            $d = '';

            foreach ($validatedData->messages()->getMessages() as $field_name => $messages) {

                $d .= $messages[0]; // messages are retrieved (publicly)

            }



            return response()->json(['success' => 0, 'errors' => $d]);

        } else {



            $name = 'Total Travel Solutions Subscriber';

            $email = $request->input('email');

            $timeout = 53;

            //fiveg mailchimp

            try {

                $name_detail = explode(' ', $name);

                //print_r($name_detail);exit;

                $email_address = trim($email);

                $api_endpoint = 'https://us17.api.mailchimp.com/3.0/lists/73d9053859/members/';

                $mailchimp_user_info = [

                    'FNAME' => $name_detail[0],

                    'LNAME' => $name_detail[1],

                ];

                $data = [

                    'status' => 'subscribed',

                    'email_address' => $email_address,

                    'merge_fields' => $mailchimp_user_info

                ];



                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $api_endpoint);

                curl_setopt($ch, CURLOPT_HTTPHEADER, [

                    'Accept: application/vnd.api+json',

                    'Content-Type: application/vnd.api+json',

                    'Authorization: apikey ' . env('MAILCHIMP_API_KEY'),

                ]);

                curl_setopt($ch, CURLOPT_USERAGENT, 'X-Cart4');

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);

                //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

                curl_setopt($ch, CURLOPT_ENCODING, '');

                curl_setopt($ch, CURLINFO_HEADER_OUT, true);

                $response['body'] = curl_exec($ch);

                $response['headers'] = curl_getinfo($ch);

                $response_array = json_decode($response['body'], true);

                if (isset($response['headers']['request_header'])) {

                    $headers = $response['headers']['request_header'];

                }

                //print_r($response_array);

                if ($response['body'] === false) {

                    $last_error = curl_error($ch);

                    //print_r($last_error);

                }



                curl_close($ch);

                //print_r($mailchimp_user_info);

            } catch (Exception $e) {

            }

            //fivegmailchimp



            $sub = new subscribers();

            $sub->name = $name;

            $email1 = $sub->email = $request->input('email');

            $sub->save();

            $template_data = [];

            // $template_data["username"] =$request->input("name");

            // $email = new  EmailController();

            // $email->sendEmail("Subscription",$email1,$template_data);



            $reff = url()->previous();



            if ($reff == 'https://www.totaltravelsolutions.co.uk/') {

                return redirect(url()->previous() . '?promo=PZ-Og-COUP05');

            } else {

                return redirect(url()->previous() . '&promo=PZ-Og-COUP05');

            }



            // return redirect(url('https://www.totaltravelsolutions.co.uk/?promo=PZ-Og-COUP05'));

        }

    }



    public function getPagebySlug()
    {
        $slug = trim(request()->path(), '/');

        if ($slug === '') {
            $slug = 'main';
        }

        $page = pages::where('slug', $slug)
            ->where('status', 'Yes')
            ->where(function ($q) {
                $q->where('agent_id', '9')
                    ->orWhere('agent_id', '1')
                    ->orWhereNull('agent_id')
                    ->orWhere('agent_id', '')
                    ->orWhere('agent_id', '0');
            })
            ->orderByRaw("CASE WHEN agent_id = '9' THEN 0 WHEN agent_id = '1' THEN 1 ELSE 2 END")
            ->first();

        if ($page) {
            return $page;
        }

        $page = new \stdClass();
        $page->meta_title = '';
        $page->meta_keyword = '';
        $page->meta_description = '';
        $page->airport_parking = '';

        return $page;
    }



    public function sitemap()
    {



        $page = $this->getPagebySlug();



        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.sitemap', ['airports' => $airports, 'page' => $page]);

    }



    public function about_us()
    {

        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->take(4)->sortByDesc('id');



        return view('frontend/about-us', ['airports' => $airports, 'reviews' => $reviews]);

    }





    public function all_reviews()
    {

        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->take(4)->sortByDesc('id');

        return view('frontend/all_reviews', ['airports' => $airports, 'reviews' => $reviews]);

    }



    public function lounges()
    {



        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.lounges', ['airports' => $airports, 'page' => $page]);

    }



    public function airportHotels()
    {

        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.airport_hotels', ['airports' => $airports, 'page' => $page]);

    }



    public function airportsparking()
    {



        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.airportsparking', ['airports' => $airports, 'page' => $page]);

    }



    public function airporttransfer()
    {



        $page = $this->getPagebySlug();



        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.airporttransfer', ['airports' => $airports, 'page' => $page]);

    }



    public function feedback()
    {

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.feedback', ['airports' => $airports]);

    }



    public function blogs()
    {

        // $now = date("y-m-d");

        $daysago = $this->addDayswithdate(date('y-m-d'), '-7');

        $daysago = date('Y-m-d h:i:s', strtotime($daysago));

        // dd($daysago);

        $airports = airport::all()->where('status', 'Yes');

        $posts = pages::all()->where('status', 'Yes')->where('type', 'post')->where('agent_id', '9');

        $recent_posts = pages::all()->where('status', 'Yes')->where('type', 'post')->where('agent_id', '9')->sortByDesc('added_on')->take(6);



        // dd($recent_posts);

        return view('frontend.blogs', ['posts' => $posts, 'airports' => $airports, 'recent_posts' => $recent_posts]);

    }



    public function blog_detail($slug)
    {

        $airports = airport::all()->where('status', 'Yes');

        /*$posts = pages::all()->where("status", "Yes")->where("type", "post")->where("slug",'!=', $slug)->take(3)->sortByDesc("id");*/

        $post = pages::where('slug', $slug)->where('status', 'Yes')->where('agent_id', '9')->first();

        // dd($post);

        if ($post) {



            $total_airports = airports_bookings::all()->count();



            return view('frontend.blog_detail', ['post' => $post, 'airports' => $airports]);

        } else {

            return view('frontend.404', ['airports' => $airports]);

        }

    }



    public function store(Request $request)
    {

        $bookings = new airports_bookings();



        $review = new reviews();



        $review->type = '3';

        $review->type = '3';

        $review->admin_id = '1';

        $review->type_id = '77';

        $review->ref = '3';



        $review->username = $request->input('name');

        $review->email = $bookings->email;

        $review->rating = $request->input('rating');

        $review->review = $request->input('message');

        $review->status = date('Y-m-d h:i:s');

        $review->count = 'open';

        $review->google_count = 'message';

        $review->save();



        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.feedback', ['airports' => $airports]);

    }



    public function contact()
    {

        $settings = [];

        $settingsAll = settings::all();

        foreach ($settingsAll as $setting) {

            $settings[$setting->field_name] = $setting->field_value;

        }

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.contact-us', ['airports' => $airports, 'settings' => $settings]);

    }



    public function privacy_policy()
    {

        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->take(4)->sortByDesc('id');



        $page = $this->getPagebySlug();



        return view('frontend/privacy-policy', ['airports' => $airports, 'reviews' => $reviews, 'page' => $page]);

    }



    public function contactUsSubmit(Request $request)
    {

        // dd($request->all());

        $data = $request->all();

        $template_data = [];

        $template_data['Name'] = $data['firstname'] . ' ' . $data['lastname'];

        $template_data['Email'] = $data['email'];

        $template_data['Phone'] = $data['phone'];

        $template_data['Subject'] = $data['subject'];

        $template_data['Message'] = $data['message'];



        $email = ['support@parkingzone.co.uk', 'bookings@parkingzone.co.uk'];

        $sendMail = new EmailController;

        foreach ($email as $contact) {

            $sendMail->sendGmail('Contact Us', $contact, $template_data);

        }



        return redirect()->back()->with('success_message', 'Email send successfully');

    }



    public function contactus_post(Request $request)
    {

        $subject = $request->input('subject');

        $title = $request->input('title') ?? 'Mr.';

        $firstname = $request->input('firstname');

        $lastname = $request->input('lastname');

        $email = $request->input('email');

        $phone = $request->input('phone');

        $message = $request->input('message');



        $email_c = new EmailController();

    }



    public function airport_guide()
    {

        //



        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');



        //dd($airports);

        return view('frontend.airport_guide', ['airports' => $airports, 'page' => $page]);

    }



    public function airport_types()
    {

        //



        $page = $this->getPagebySlug();



        // $sliders = unserialize($this->_settings['sliders']);

        $sliders = '';

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.airports_types', ['airports' => $airports, 'page' => $page, 'sliders' => $sliders]);

    }



    public function terms_and_conditions()
    {

        $page = $this->getPagebySlug();

        // $sliders = unserialize($this->_settings['sliders']);

        $sliders = '';

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.terms_and_conditions', ['airports' => $airports, 'page' => $page, 'sliders' => $sliders]);

    }

    public function affiliates()
    {

        return redirect('/');

    }



    public function site_security()
    {

        $page = $this->getPagebySlug();

        // $sliders = unserialize($this->_settings['sliders']);

        $sliders = '';

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.site_security', ['airports' => $airports, 'page' => $page, 'sliders' => $sliders]);

    }

    public function cookies()
    {

        $page = $this->getPagebySlug();

        // $sliders = unserialize($this->_settings['sliders']);

        $sliders = '';

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.cookies', ['airports' => $airports, 'page' => $page, 'sliders' => $sliders]);

    }



    public function static_page($page)
    {



        $airports = airport::all()->where('status', 'Yes');

        $page = pages::where('slug', $page)->where('status', 'Yes')->where('agent_id', '1')->first();

        //dd($page);

        if ($page) {



            $total_airports = airports_bookings::all()->count();



            return view('frontend.static_page', ['airports' => $airports, 'page' => $page]);

        } else {

            return view('frontend.404', ['airports' => $airports]);

        }

    }



    public function faqs()
    {

        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');

        $total_airports = airports_bookings::all()->count();

        //WHERE removed='No' group by type order by id asc

        // $faqs = faqs::all()->where('removed', 'No')->where('agent_id', '1')->groupBy('type');

        $faqs = faqs::all()->where('removed', 'No')->where('agent_id', '9')->where('type', 'Parking')->groupBy('type');



        if ($page->meta_title == '') {

            return view('frontend.404', ['airports' => $airports]);

        } else {

            return view('frontend.faqs', ['airports' => $airports, 'faqs' => $faqs, 'page' => $page]);

        }

    }



    public function faqs_pages()
    {

        return view('frontend.faqs_pages');

    }



    public function page($slug)
    {

        $airports = airport::all()->where('status', 'Yes');

        $reviews = reviews::all()->where('status', 'Yes')->take(4)->sortByDesc('id');

        $page = pages::where('slug', $slug)
            ->where('status', 'Yes')
            ->where(function ($q) {
                $q->where('agent_id', '9')
                    ->orWhere('agent_id', '1')
                    ->orWhereNull('agent_id')
                    ->orWhere('agent_id', '')
                    ->orWhere('agent_id', '0');
            })
            ->orderByRaw("CASE WHEN agent_id = '9' THEN 0 WHEN agent_id = '1' THEN 1 ELSE 2 END")
            ->first();



        $total_airports = airports_bookings::all()->count();



        $dropdate = date('m/d/Y');

        $dropdate1 = $this->addDayswithdate($dropdate, '7');

        //echo $dropdate1; die();

        $no_of_days = 8;

        $i = 1;

        $j = 1;

        $selected_date = strtotime($dropdate1);

        $year = date('Y', $selected_date);

        $month = date('n', $selected_date);

        //$month = 9;

        $day = date('j', $selected_date);

        if ($no_of_days > 30) {

            $total_days = '30';

        } else {

            $total_days = $no_of_days;

        }

        $dropoftime = date('h:i');

        $pickuptime = '09:00';



        $pickdate = $this->addDayswithdate($dropdate1, '8');



        if ($page) {



            $airports_Detail = airport::where('id', $page->typeid)->first();



            // $query = "SELECT fc.id as companyID,fc.name,fc.processtime,fc.awards,fc.featured,fc.recommended,fc.special_features,fc.overview,IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,fc.terms,fc.address,fc.town,fc.post_code,fc.message,fc.parking_type,fc.logo,fc.travel_time,fc.miles_from_airport, fc.cancelable, fc.editable, fc.bookingspace,

            // fapp.id, fasb.brand_name, fapb.after_30_days, IF( fapb.day_$total_days >0, fapb.day_$total_days, 0.00) AS price FROM companies as fc

            // left join companies_set_price_plans as fapp on fc.id = fapp.cid

            // left join companies_set_assign_price_plans as fasb on fapp.id = fasb.plan_id and fasb.day_no = 'day_" . $day . "'

            // left join companies_product_prices as fapb on fapb.cid = fc.id and fapb.brand_name = fasb.brand_name

            // WHERE is_active = 'Yes' and fc.name not LIKE '%Paige %' and airport_id = '" . $page->typeid . "' and fapp.cmp_month = '" . $month . "' and fapp.cmp_year = '" . $year . "' order by price asc";



            $companies = DB::table('companies as fc')

                ->select(

                    'fc.id as companyID',

                    'fc.name',

                    'fc.processtime',

                    'fc.awards',

                    'fc.featured',

                    'fc.recommended',

                    'fc.special_features',

                    'fc.overview',

                    DB::raw('IF(LENGTH(fc.returnfront) > 0, fc.returnfront, fc.return_proc) AS return_proc'),

                    DB::raw('IF(LENGTH(fc.arivalfront) > 0, fc.arivalfront, fc.arival) AS arival'),

                    'fc.terms',

                    'fc.address',

                    'fc.town',

                    'fc.post_code',

                    'fc.message',

                    'fc.parking_type',

                    'fc.logo',

                    'fc.travel_time',

                    'fc.miles_from_airport',

                    'fc.cancelable',

                    'fc.editable',

                    'fc.bookingspace',

                    'fapp.id',

                    'fasb.brand_name',

                    'fapb.after_30_days',

                    DB::raw("IF(fapb.day_$total_days > 0, fapb.day_$total_days, 0.00) AS price")

                )

                ->leftJoin('companies_set_price_plans as fapp', 'fc.id', '=', 'fapp.cid')

                ->leftJoin('companies_set_assign_price_plans as fasb', function ($join) use ($day) {

                    $join->on('fapp.id', '=', 'fasb.plan_id')->where('fasb.day_no', '=', "day_$day");

                })

                ->leftJoin('companies_product_prices as fapb', function ($join) {

                    $join->on('fapb.cid', '=', 'fc.id')->on('fapb.brand_name', '=', 'fasb.brand_name');

                })

                ->where([

                    ['is_active', '=', 'Yes'],

                    ['fc.name', 'not like', '%Paige %'],

                    ['airport_id', '=', $page->typeid],

                    ['fapp.cmp_month', '=', $month],

                    ['fapp.cmp_year', '=', $year],

                ])

                ->orderBy('price', 'asc')

                ->get();



            // $companies = DB::select($query);

            $companies = collect($companies)->map(function ($x) {

                return (array) $x;

            })->toArray();



            $all_records = [];

            if (!empty($companies)) {

                $all_records = (array) $companies;

            }



            $all_records_md = $this->Search_IN_ARRAY($all_records, 'parking_type', 'Meet and Greet');



            $all_records_pd = $this->Search_IN_ARRAY($all_records, 'parking_type', 'Park and Ride');



            $reviews = reviews::all()->where('status', 'Yes')->take(4);

            $faqs = ['title' => $page->faq_title, 'desc' => $page->faq_desc];

            // dd($faqs);

            // $faq_desc = $page->faq_desc;



            return view('frontend.page', ['id' => $page->typeid, 'faqs' => $faqs, 'airports' => $airports, 'reviews' => $reviews, 'page' => $page, 'total_airports' => $total_airports, 'airports_Detail' => $airports_Detail, 'companies' => $companies, 'all_records_md' => $all_records_md, 'all_records_pd' => $all_records_pd, 'reviews' => $reviews]);

        } else {

            return view('frontend.404', ['airports' => $airports]);

        }

    }



    public function airports()
    {

        $page = $this->getPagebySlug();

        $airports = airport::all()->where('status', 'Yes');



        return view('frontend.airports', ['airports' => $airports, 'page' => $page]);

    }



    public function Search_IN_ARRAY($array, $key, $value)
    {

        $results = [];



        if (is_array($array)) {

            if (isset($array[$key]) && $array[$key] == $value) {

                $results[] = $array;

            }



            foreach ($array as $subarray) {

                $results = array_merge($results, $this->Search_IN_ARRAY($subarray, $key, $value));

            }

        }



        return $results;

    }



    public function addDayswithdate($date, $days)
    {

        $date = strtotime('+' . $days . ' days', strtotime($date));



        return date('m/d/Y', $date);

    }



    public function reviews()
    {



        $query = "SELECT b.id, b.username, b.review,b.rating,b.status,b.created_at, c.id as c_id,c.name as c_name,d.id as d_id,d.name as d_airport_name,s.id as s_id

            FROM reviews as b

            join companies as c ON b.type_id=c.id OR type_id = c.aph_id

            join airports as d ON c.airport_id = d.id

            join users as s ON c.admin_id = s.id where b.id !='' && b.status='Yes'

             ORDER BY b.id desc limit 12";



        $reviews = DB::select(DB::raw($query));

        $reviews = collect($reviews)->map(function ($x) {

            return (array) $x;

        })->toArray();



        $airports = airport::all()->where('status', 'Yes');



        //dd($reviews);

        return view('frontend.review', ['airports' => $airports, 'reviews' => $reviews]);

    }



    // function reviews2()

    // {



    //     // $query = "SELECT b.id, b.username, b.review,b.rating,b.status,b.created_at, c.id as c_id,c.name as c_name,d.id as d_id,d.name as d_airport_name,s.id as s_id

    //     //     FROM reviews as b

    //     //     join companies as c ON b.type_id=c.id OR type_id = c.aph_id

    //     //     join airports as d ON c.airport_id = d.id

    //     //     join users as s ON c.admin_id = s.id where b.id !='' && b.status='Yes'

    //     //      ORDER BY b.id desc limit 4";

    //   $reviews2 = reviews::all()->where("status", "Yes")->take(4);



    //     // $reviews = DB::select(DB::raw($query));

    //     // $reviews = collect($reviews)->map(function ($x) {

    //     //     return (array)$x;

    //     // })->toArray();



    //     // $airports = airport::all()->where("status", "Yes");

    //     // dd($reviews);

    //     return view("frontend.page", ["reviews"=>$reviews2]);

    // }



    public function addBookingForm(Request $request)
    {
        session(['parking_booking_context' => $this->buildParkingBookingRequestData($request)]);

        return redirect()->route('booking.show');
    }

    public function showBookingForm(Request $request)
    {
        $stored = session('parking_booking_context');

        if (!is_array($stored) || empty($stored['company_id'])) {
            return redirect('/')->with('error', 'Your booking session has expired. Please select parking again.');
        }

        $bookingRequest = new Request($stored);

        return view('frontend.booking1', $this->buildParkingBookingViewData($bookingRequest));
    }

    private function buildParkingBookingRequestData(Request $request): array
    {
        $dropdate = str_replace('/', '-', (string) $request->input('dropdate'));
        $pickdate = str_replace('/', '-', (string) $request->input('pickdate'));

        $request->merge([
            'dropdate' => date('m/d/Y', strtotime($dropdate)),
            'pickdate' => date('m/d/Y', strtotime($pickdate)),
        ]);

        $this->trackBookingReferral($request);

        return $request->except(['_token']);
    }

    private function buildParkingBookingViewData(Request $request): array
    {
        $email_name = $request->email;
        $aid = $request->input('airport');
        $airports = airport::where('status', 'Yes')->get();
        $company = DB::table('companies')->where('id', $request->company_id)->first();
        $terminals = airports_terminals::where('aid', $aid)->get();

        return [
            'data' => $request,
            'settings' => $this->_setting,
            'airports' => $airports,
            'terminals' => $terminals,
            'company' => $company,
            'email' => $email_name,
        ];
    }

    /**
     * Record referral traffic for booking funnel (non-blocking on failure).
     */
    private function trackBookingReferral(Request $request): void
    {
        try {
            ref_tracking::create([
                'ref_url' => session()->get('ref_url'),
                'traffic_src' => session()->get('bk_src') ?: 'ORG',
                'agentID' => '1',
                'user_ip' => $request->ip(),
                'current_url' => $request->fullUrl(),
                'email' => session()->get('userEmail'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Booking ref_tracking failed: ' . $e->getMessage());
        }
    }



    public function addBookingFormincomplete($id)
    {



        $airport_booking = airports_bookings::find($id);

        /// dd($airport_booking);

        //dd("i m in booking");

        $DepartDatetemp = explode(' ', $airport_booking->departDate);

        $returnDatatemp = explode(' ', $airport_booking->returnDate);

        $company_id = $airport_booking->companyId;

        $product_code = $airport_booking->product_code;

        //dd($product_code);



        $parking_type = '';

        $park_api = $airport_booking->park_api;

        if ($park_api == 'Opitech' || $park_api == 'global') {

            $g_quote = $airport_booking->g_quote;

            $g_token = $airport_booking->g_token;

        }



        $_token = '5kZQnmKV9unuKObedug3dDWK3R6ScEFrnXQUG34X';

        $parking_name = null;

        $aphactive = '0';

        $airport = $airport_booking->airportID;

        $dropdate = $DepartDatetemp[0];

        $pickdate = $returnDatatemp[0];

        $droptime = $DepartDatetemp[1];

        $picktime = $returnDatatemp[1];

        $total_days = $airport_booking->no_of_days;

        $discount_code = $airport_booking->discount_code;

        $discount_amount = $airport_booking->discount_amount;

        $booking_amount = $airport_booking->booking_amount;

        $booking_fee = $airport_booking->booking_fee;

        //dd(  $company_id);

        $request = [

            '_token' => '5kZQnmKV9unuKObedug3dDWK3R6ScEFrnXQUG34X',

            'title' => $airport_booking->title,

            'firstname' => $airport_booking->first_name,

            'lastname' => $airport_booking->last_name,

            'email' => $airport_booking->email,

            'company_id' => $company_id,

            'fulladdress' => $airport_booking->fulladdress,

            'postal_code' => $airport_booking->postal_code,

            'referenceNo' => $airport_booking->referenceNo,

            'product_code' => $product_code,

            'phone_number' => $airport_booking->phone_number,

            'parking_type' => 'Maple Parking Meet Greet Flex',

            'parking_name' => null,

            'airport' => $airport,

            'dropdate' => $dropdate,

            'pickdate' => $pickdate,

            'droptime' => $droptime,

            'picktime' => $picktime,

            'total_days' => $total_days,

            'discount_code' => $airport_booking->discount_code,

            'discount_amount' => $discount_amount,

            'booking_amount' => $booking_amount,

            'booking_fee' => $booking_fee,

            'bookingfor' => 'airport_parking',

            'pl_id' => null,

            'sku' => null,

            'site_codename' => null,

            'speed_park_active' => null,

            'edin_active' => null,

            'edin_search' => null,

            'park_api' => $park_api,

            'g_quote' => $g_quote ?? '0',

            'g_token' => $g_token ?? '0',



            'submitted' => 'airport_parking'

        ];



        //dd($request[dropdate']);

        $aid = $airport_booking->airportID;

        $airports = airport::all()->where('status', 'Yes');



        $terminals = airports_terminals::all()->where('aid', '=', $aid);



        return view('frontend.booking-incomplete', ['data' => $request, 'settings' => $this->_setting, 'airports' => $airports, 'terminals' => $terminals]);

    }



    //this function is used for post resl

    public function getSearchResult(Request $request)
    {
        $this->normalizeParkingSearchRequest($request);

        if ($request->ajax()) {
            return $this->ajaxSearchResults($request);
        }

        if ($request->isMethod('post')) {
            $this->persistSearchRequest('parking_search_request', $request);

            return redirect()->route('searchresult');
        }

        if (!$request->filled('airport_id')) {
            $this->applyStoredSearchRequest($request, 'parking_search_request');
        }

        if (!$request->filled('airport_id')) {
            return redirect()->route('main');
        }

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.search_result', ['airports' => $airports]);
    } // end of function

    private function normalizeParkingSearchRequest(Request $request): void
    {
        $request->merge([
            'dropoftime' => $request->input('dropoftime') ?: $request->input('dropofftime') ?: '09:00',
            'pickup_time' => $request->input('pickup_time') ?: $request->input('pickuptime') ?: '09:00',
        ]);
    }

    private function parkingSearchCacheKey(Request $request): string
    {
        return 'parking_search_v1_' . md5(json_encode([
            (string) $request->input('airport_id'),
            (string) $request->input('dropoffdate'),
            (string) $request->input('departure_date'),
            (string) $request->input('dropoftime'),
            (string) $request->input('pickup_time'),
            (string) $request->input('promo'),
            (string) $request->input('promo2'),
            (string) session()->get('bk_src', 'ORG'),
        ]));
    }



    //config('app.ABTANumber')



    public function getAphInfo()
    {

        //dd($request);exit;

        return ['ABTANumber' => config('app.ABTANumber'), 'Password' => config('app.Password'), 'Initials' => config('app.Initials'), 'aphurl' => config('app.aphurl'), 'aphurldetails' => config('app.aphurldetails')];

        //echo "reached";exit;



    }



    public function getSearchResultForTravelez(Request $request)
    {

        //dd($request);exit;

        return $this->ajaxSearchResults($request);

        //echo "reached";exit;



    }



    public function ajaxSearchResults($request)
    {

        set_time_limit(120);

        $this->normalizeParkingSearchRequest($request);

        if ($request->ajax() && $request->input('return_json') != 'Yes') {
            $cachedHtml = Cache::get($this->parkingSearchCacheKey($request));
            if (is_string($cachedHtml) && $cachedHtml !== '') {
                return response($cachedHtml);
            }
        }

        $promo_error_message = '';





        $messages = [

            'required' => 'This field is required.',

        ];

        $validatedData = Validator::make(request()->all(), [

            'airport_id' => 'required',

            'dropoffdate' => 'required',

            'departure_date' => 'required',



        ], $messages);



        $airport_id = $request->input('airport_id');

        $dropdate = $request->input('dropoffdate');

        $pickdate = $request->input('departure_date');

        $dropoftime = $request->input('dropoftime');

        $pickuptime = $request->input('pickup_time');

        $email = $request->input('email');

        $no_of_days = $request->input('no_of_days') + 1;

        // dd($no_of_days);

        $dropdate = str_replace('/', '-', $dropdate);

        $pickdate = str_replace('/', '-', $pickdate);



        $src = session()->get('bk_src') ?? 'ORG';

        $dropDateTime = date('Y-m-d H:i', strtotime($dropdate . ' ' . $dropoftime));

        $pickDateTime = date('Y-m-d H:i', strtotime($pickdate . ' ' . $pickuptime));





        $bookingfor = 'airport_parking';

        $promo = $request->input('promo');

        $promo2 = $request->input('promo2');

        $filter1 = $request->input('filter1');

        //$filter2 = $_POST['filter2'];

        $filter2 = ($request->input('filter2') != '') ? $request->input('filter2') : 'low-to-high';

        $filter3 = $request->input('filter3');

        $search_filter = '';

        $search_filter3 = '';

        $search_filter2 = 'order by sort_by asc';

        // $search_filter2 = 'order by parking_type asc';

        if ($filter1 != '' && $filter1 != 'All') {

            $search_filter .= "and parking_type = '" . $filter1 . "'";

        }

        if ($filter2 == 'low-to-high') {

            $search_filter2 = 'order by featured asc, recommended asc,parking_type asc, price asc';

            //$search_filter2 = 'ORDER BY ';

        } elseif ($filter2 == 'high-to-low') {

            $search_filter2 = 'order by price desc';

        } elseif ($filter2 == 'distance') {

            $search_filter2 = 'order by travel_time asc';

        }

        if ($filter3 != '') {

            $search_filter3 .= "and terminal = '" . $filter3 . "'";

        }



        if ($promo != '') {

            $discount = new discounts();

            $promo_verify = $discount->varifyPromoCode($promo);



            if ($promo_verify != 'Verify') {

                $validatedData->getMessageBag()->add('promo', $promo_verify);

            }

        }



        if ($promo2 != '') {

            $discount = new discounts();

            $promo_verify = $discount->varifyPromoCode($promo2);



            if ($promo_verify != 'Verify') {

                $promo_error_message = $promo_verify;

            }

            $promo = $promo2;

        }



        //dd($promo);

        $html = '';

        $i = 1;

        $j = 1;

        $inactive = 0;

        $inactiv = 0;



        $selected_date = strtotime($dropdate);

        $year = date('Y', $selected_date);

        $month = date('n', $selected_date);

        $day = date('j', $selected_date);



        $dropofdate = date('Y-m-d', strtotime($dropdate));

        $pickupdate = date('Y-m-d', strtotime($pickdate));



        $dStart = new DateTime($dropofdate);

        $dEnd = new DateTime($pickupdate);

        $dDiff = $dStart->diff($dEnd);



        $dDiff->format('%R');

        $no_of_days = $dDiff->days;

        // dd($no_of_days);

        $total_days = $no_of_days + 1;



        if ($no_of_days > 30) {

            $total_days = '30';

        } else {

            $total_days = $no_of_days + 1;

        }

        if ($total_days <= 0) {

            $total_days = 1;

        }



        /* $data = [];

        $data['email'] = $email;

        $data['aid'] = $airport_id;

        $data['traffic_src'] = $src;

        $data['dropoff_date'] = $dropDateTime;

        $data['pickup_date'] = $pickDateTime;

        $data['discount_code'] = $promo;

        // print_r($data);

        Email::create($data);*/



        $ip = request()->ip();

        $refData['ref_url'] = session()->get('ref_url');

        if (session()->get('bk_src') != '') {

            $refData["traffic_src"] = session()->get('bk_src');

        } else {

            $refData["traffic_src"] = 'ORG';

        }

        $refData["agentID"] = '1';

        $refData["user_ip"] = $ip;

        $refData["current_url"] = \Request::fullUrl();

        // $refData['email'] = $email;

        $update = ref_tracking::create($refData);





        // Calculate Days Difference From Now

        $dropdate1 = strtotime($dropdate . ' ' . $dropoftime);

        $c_time = date('Y-m-d H:i');

        $dropdate1 = date('Y-m-d H:i', $dropdate1);

        $datetime1 = new DateTime($c_time);

        $datetime2 = new DateTime($dropdate1);

        $interval = $datetime1->diff($datetime2);

        //$diff_date = $interval->format('%a%h');

        $hours = $interval->h;

        $hours = $hours + ($interval->days * 24);



        ////////********** END **********///////

        $percent_filter = '';

        if (session()->get('bk_src') == 'EM' || session()->get('bk_src') == 'BING') {

            $percent_filter = 'and share_percentage >= 12';

        } elseif (session()->get('bk_src') == 'PPC') {

            $percent_filter = 'and share_percentage >= 20';

        }

        $search_filter = $percent_filter . ' ' . $search_filter;

        $query = 'SELECT  distinct fapp.id,fc.admin_id,fc.company_code as product_code, fc.admin_id, fc.opening_time,fc.closing_time,fc.id as companyID,fc.aph_id,fc.name,fc.processtime,fc.awards,fc.featured,fc.recommended,fc.share_percentage,fc.special_features,fc.overview, IF( LENGTH(fc.returnfront) >0,fc.returnfront,fc.return_proc) AS return_proc,IF( LENGTH(fc.arivalfront) >0,fc.arivalfront,fc.arival) AS arival,fc.terms,fc.address,fc.town,fc.post_code,fc.message,fc.extra_charges,fc.parking_type,fc.logo,fc.travel_time,fc.miles_from_airport, fc.cancelable, fc.editable, fc.is_flex, fc.bookingspace, fasb.brand_name, fapb.after_30_days, fapp.id as pl_id, IF( fapb.day_' . $total_days . ' >0, fapb.day_' . $total_days . "+fapp.extra, 0.00) AS price FROM companies as fc

                left join companies_set_price_plans as fapp on fc.id = fapp.cid

                left join companies_set_assign_price_plans  as fasb on fapp.id = fasb.plan_id and fasb.day_no = 'day_" . $day . "'

                left join companies_product_prices as fapb on fapb.cid = fc.id and fapb.brand_name = fasb.brand_name

                WHERE fc.is_active = 'Yes' and fasb.brand_name != 'fully_closed' and fc.removed != 'Yes'  and fc.airport_id = '" . $airport_id . "' and fc.aph_id is null and fapp.cmp_month = '" . $month . "'  and fapp.cmp_year = '" . $year . "' and fc.processtime  < " . $hours . "

                $search_filter $search_filter2 $search_filter3

                ";



        $companies = DB::select($query);

        $offDaysAdmin = [];

        $offAdmins = [];

        $offDaysComp = [];

        $offComp = [];

        // dd($dropofdate, $pickupdate);

        // Retrieve off days for admins

        $offDayEntriesAdmin = Cache::remember('off_days_admin_v1', 3600, function () {
            return OffDays::where('off_type', 'Admin')->get();
        });

        foreach ($offDayEntriesAdmin as $offDayEntryAdmin) {

            $admin_id = $offDayEntryAdmin->admin_id;

            $offDays = explode(',', $offDayEntryAdmin->off_days);

            // Trim spaces from each date

            $offDays = array_map('trim', $offDays);

            // rsort($offDays);



            if (!isset($offDaysAdmin[$admin_id])) {

                $offDaysAdmin[$admin_id] = [];

            }



            $offDaysAdmin[$admin_id] = array_merge($offDaysAdmin[$admin_id], $offDays);

            $offAdmins[] = $admin_id;

        }



        // Retrieve off days for companies

        $offDayEntriesComp = Cache::remember('off_days_company_v1', 3600, function () {
            return OffDays::where('off_type', 'Company')->get();
        });

        foreach ($offDayEntriesComp as $offDayEntryComp) {

            $company_id = $offDayEntryComp->company_id;

            $offDays = explode(',', $offDayEntryComp->off_days);



            // Trim spaces from each date

            $offDays = array_map('trim', $offDays);



            // rsort($offDays);

            if (!isset($offDaysComp[$company_id])) {

                $offDaysComp[$company_id] = [];

            }



            $offDaysComp[$company_id] = array_merge($offDaysComp[$company_id], $offDays);

            $offComp[] = $company_id;

        }



        if ($companies !== false) {

            $array = []; // Initialize $array here

            $apiUrl = config('services.AVPS_API_URL');
            $token = config('services.AVPS_ACCESS_TOKEN');

            foreach ($companies as $index => $company) {

                $company = (array) $company;

                // Check if departure or return date matches the dates in $offDaysAdmin and $offAdmins

                if (isset($offDaysAdmin[$company['admin_id']]) && (in_array($dropofdate, $offDaysAdmin[$company['admin_id']]) || in_array($pickupdate, $offDaysAdmin[$company['admin_id']]))) {

                    continue;

                }



                // Check if departure or return date matches the dates in $offDaysComp and $offComp

                if (isset($offDaysComp[$company['companyID']]) && (in_array($dropofdate, $offDaysComp[$company['companyID']]) || in_array($pickupdate, $offDaysComp[$company['companyID']]))) {

                    continue;

                }



                // Price calculation logic...

                if ($no_of_days > 30) {

                    $after30Days = $company['after_30_days'];

                    $booking_price = number_format($company['price'], 2, '.', '');

                    $booking_price = $booking_price + $after30Days * ($no_of_days + 1 - 30);

                    $company['price'] = number_format($booking_price, 2, '.', '');

                } else {

                    $company['price'] = number_format($company['price'], 2, '.', '');

                }

                if (isset($company['product_code']) && strpos($company['product_code'], 'AVP') !== false && ! empty($apiUrl) && ! empty($token)) {
                    try {
                        $payLoad = [
                            'company_code' => $company['product_code'],
                            'drop_date' => $dropofdate,
                            'drop_time' => $dropoftime,
                            'return_date' => $pickupdate,
                            'return_time' => $pickuptime,
                        ];
                        $AVPS_response = Http::timeout(8)
                            ->connectTimeout(3)
                            ->withToken($token, 'Bearer')
                            ->post($apiUrl, $payLoad);

                        if ($AVPS_response->successful()) {
                            $responseBody = $AVPS_response->json();
                            $totalPrice = $responseBody['data']['total_price'] ?? 0;
                            $company['price'] = $totalPrice <= 0
                                ? number_format($company['price'], 2, '.', '')
                                : number_format($totalPrice, 2, '.', '');
                        }
                    } catch (\Throwable $e) {
                        \Log::warning('AVPS price lookup failed for parking search', [
                            'company_code' => $company['product_code'] ?? null,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }

                $dbProductCode = strtoupper(trim((string) ($company['product_code'] ?? '')));
                // BookFHR products must use live API price, not DB price plans.
                if ($dbProductCode !== '' && (str_starts_with($dbProductCode, 'BOOKFHR') || $dbProductCode === 'BOOKFHR')) {
                    continue;
                }

                $company['park_api'] = 'DB';
                $company['price_source'] = 'DB';

                $array[] = $this->array_flatten($company);

            }

        }





        $dbcompany = json_decode(json_encode((array) $array), false);

        $companies = ['original' => $companies];

        $dbcompany = ['merged' => $dbcompany];



        $result = array_merge($companies, $dbcompany);

        $companies = $result['merged'];



        //return view("frontend.ajax.result", []);

        $airports = airport::all()->where('status', 'Yes');

        $companies_special_features = companies_special_features::all();

        $airport_detail = airport::where('id', $airport_id)->first();

        //////////////////////////////////////////////

        //////////api working start//////////////////

        /////////////////////////////////////////////



        $apiairport = airport::find($airport_id);

        if ($apiairport) {
            $airport_name = $apiairport->name;
            $airport_code = $apiairport->iata_code;
            $airport_post_code = $apiairport->post_code;
            $airport_address = $apiairport->address;
            $airport_town = $apiairport->city;
        } else {
            $airport_name = '';
            $airport_code = '';
            $airport_post_code = '';
            $airport_address = '';
            $airport_town = '';
        }

        $ArrivalDate = date('dMy', strtotime($dropdate));

        $DepartDate = date('dMy', strtotime($pickdate));

        $ArrivalTime = date('Hi', strtotime($dropoftime));

        $DepartTime = date('Hi', strtotime($pickuptime));



        $xml = '<API_Request

                System="APH"

                Version="1.0"

                Product="CarPark"

                Customer="X"

                Session="000000003"

                RequestCode="11">

                <Agent>

                <ABTANumber>' . config('app.ABTANumber') . '</ABTANumber>

                <Password>' . config('app.Password') . '</Password>

                <Initials>' . config('app.Initials') . '</Initials>

                </Agent>

                <Itinerary>

                <ArrivalDate>' . $ArrivalDate . '</ArrivalDate>

                <DepartDate>' . $DepartDate . '</DepartDate>

                <ArrivalTime>' . $ArrivalTime . '</ArrivalTime>

                <DepartTime>' . $DepartTime . '</DepartTime>

                <Location>' . $airport_code . '</Location>

                <Terminals>ALL</Terminals>

                </Itinerary>

                </API_Request>';



        // $aph_functions = new aph_functions();

        // $api = new api();

        // if($airport_id != 20){

        // if ($this->_settings['a2z_api'] != 'Inactive') {

        //     $a2zCompanies = @$aph_functions->a2zListings($airport_code, $dropdate, $dropoftime, $pickdate, $pickuptime);

        //     //dd($a2zCompanies);

        //     $a2zRecord = @$api->a2z_record($a2zCompanies, $airport_id, $search_filter);

        //     if (! empty($a2zRecord)) {

        //         $a2zRecord = json_decode(json_encode($a2zRecord));

        //         $companies = array_merge((array) $companies, (array) $a2zRecord);

        //     }

        // }

        // if ($this->_settings['aph_api'] != 'Inactive') {

        //     $APHcompanies = @$aph_functions->AphBooking($xml, $airport_code);

        //     $search_filter .= 'and fc.processtime  < '.$hours;

        //     $aph_record = @$api->aph_record($APHcompanies, $airport_id, $search_filter);

        //     if (count($aph_record) > 0) {

        //         $aph_record = json_decode(json_encode($aph_record)); // convert to object

        //         $companies = array_merge((array) $companies, (array) $aph_record);

        //     }

        // }



        // if ($this->_settings['holiday_api'] != 'Inactive') {

        //     $holidaycompanies = @$aph_functions->HolidayExtraBooking($airport_code, $dropdate, $dropoftime, $pickdate, $pickuptime);

        //     $holiday_record = @$api->holiday_record($holidaycompanies, $airport_id, $search_filter);

        //     if (! empty($holiday_record)) {

        //         $holiday_record = json_decode(json_encode($holiday_record));

        //         $companies = array_merge((array) $companies, (array) $holiday_record);

        //     }

        // }

        // }






        // BookFHR Parking API — short timeout so results page still loads if API is slow
        if (!empty($airport_code)) {
            try {
                $api = new api();
                $bookFhrService = (new BookFhrService())->setRequestTimeout(20);
                $bookFhrResults = $bookFhrService->search([
                    'location' => $airport_code,
                    'dateFrom' => $dropofdate,
                    'timeFrom' => $dropoftime,
                    'dateTo' => $pickupdate,
                    'timeTo' => $pickuptime,
                    'type' => 'Parking',
                    'currency' => 'GBP',
                ]);

                if ($bookFhrResults['success'] && isset($bookFhrResults['data']['results'])) {
                    $bookFhrProducts = @$api->bookfhr_record($bookFhrResults['data'], $airport_id, $search_filter);
                    if (!empty($bookFhrProducts)) {
                        $bookFhrProducts = json_decode(json_encode($bookFhrProducts));
                        $bookFhrCompanyIds = [];
                        foreach ($bookFhrProducts as $bookFhrProduct) {
                            if (!empty($bookFhrProduct->companyID)) {
                                $bookFhrCompanyIds[(string) $bookFhrProduct->companyID] = true;
                            }
                        }
                        if (!empty($bookFhrCompanyIds)) {
                            $companies = array_values(array_filter((array) $companies, function ($company) use ($bookFhrCompanyIds) {
                                $id = is_array($company)
                                    ? (string) ($company['companyID'] ?? '')
                                    : (string) ($company->companyID ?? '');
                                $parkApi = is_array($company)
                                    ? strtolower((string) ($company['park_api'] ?? ''))
                                    : strtolower((string) ($company->park_api ?? ''));

                                return !($id !== '' && isset($bookFhrCompanyIds[$id]) && $parkApi === 'db');
                            }));
                        }
                        $companies = array_merge((array) $companies, (array) $bookFhrProducts);
                    }
                } else {
                    \Log::warning('BookFHR parking search returned no results', [
                        'success' => $bookFhrResults['success'] ?? false,
                        'error' => $bookFhrResults['error'] ?? null,
                        'airport' => $airport_code,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('BookFHR parking search failed: ' . $e->getMessage());
            }
        }

        //dd($companies);



        ////////view start////////////////////



        // if ($airport_id == '20') {

        //     if (($dropoftime >= '00:00') && ($dropoftime <= '04:45')) {

        //         $companies = [];

        //     }

        //     if (($pickuptime >= '00:00') && ($pickuptime <= '04:45')) {

        //         $companies = [];

        //     }

        // }



        usort($companies, function ($a, $b) {

            // Prioritize by recommended

            if ($a->recommended === 'Yes' && $b->recommended !== 'Yes') {

                return -1;

            } elseif ($a->recommended !== 'Yes' && $b->recommended === 'Yes') {

                return 1;

            }



            // Prioritize by featured

            if ($a->featured === 'Yes' && $b->featured !== 'Yes') {

                return -1;

            } elseif ($a->featured !== 'Yes' && $b->featured === 'Yes') {

                return 1;

            }



            // Prioritize by park_api

            if ($a->park_api === 'DB' && $b->park_api !== 'DB') {

                return -1;

            } elseif ($a->park_api !== 'DB' && $b->park_api === 'DB') {

                return 1;

            }



            // Sort by price in ascending order

            return $a->price <=> $b->price;

        });











        // closing qualtiy parking for 6 days

        $startDate = Carbon::create(2024, 6, 1);

        $endDate = Carbon::create(2024, 6, 6);

        $id = 1342134882;

        $companies = $this->hideCompany($companies, $startDate, $endDate, $dropdate, $pickdate, $id);



        //  closing peak and parking for 2 days

        $startDate = Carbon::create(2024, 5, 25);

        $endDate = Carbon::create(2024, 5, 26);

        $id = 1342134794;

        $companies = $this->hideCompany($companies, $startDate, $endDate, $dropdate, $pickdate, $id);



        // dd($companies);







        if ($request->input('return_json') != 'Yes') {

            $viewData = [
                'companies' => $companies,
                'companies_special_features' => $companies_special_features,
                'request' => $request,
                'no_of_days' => $no_of_days,
                'promo' => $promo,
                'bookingfor' => $bookingfor,
            ];

            if ($request->ajax()) {
                $html = view('frontend.ajax_search_result', $viewData)->render();
                Cache::put($this->parkingSearchCacheKey($request), $html, now()->addMinutes(5));

                return response($html);
            }

            return view('frontend.ajax_search_result', $viewData);

        } else {



            return response()->json(['companies' => $companies, 'companies_special_features' => $companies_special_features, 'request' => $request, 'no_of_days' => $no_of_days, 'promo' => $promo, 'bookingfor' => $bookingfor]);

        }

    }







    public function hideCompany($companies, $startDate, $endDate, $dropdate, $pickdate, $id)
    {

        $departureDate = Carbon::parse($dropdate);

        $returnDate = Carbon::parse($pickdate);

        $companiesCollection = collect($companies);

        $filteredCompanies = $companiesCollection->filter(function ($company) use ($startDate, $endDate, $departureDate, $returnDate, $id) {

            $companyID = is_array($company) ? $company['companyID'] : $company->companyID;

            $isWithinDateRange = ($departureDate->between($startDate, $endDate) || $returnDate->between($startDate, $endDate));



            return $companyID != $id || !$isWithinDateRange;

        });



        return $filteredCompanies->values()->toArray();

    }



    public function array_flatten($array)
    {

        if (!is_array($array)) {

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



    public function loadinfo(Request $request)
    {

        // Validate the input to ensure 'id' is present and is an integer

        $request->validate([

            'id' => 'required|integer',

        ]);



        $id = $request->input('id');



        // Use parameter binding in the query

        $query = "SELECT comp.overview, comp.arival, comp.return_proc, 

                         rev.rating, rev.username, rev.title, rev.review 

                  FROM companies AS comp 

                  LEFT JOIN reviews AS rev 

                  ON comp.id = rev.type_id 

                  WHERE comp.id = :id";



        // Execute the query with parameter binding

        $companies = DB::select($query, ['id' => $id]);



        // Debugging information (optional - remove in production)

        // dd($query, $id, $request->all(), $companies);



        // Return the companies data as JSON

        return response()->json($companies);

    }





    public function getSearchResultLounge(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxSearchResultsLounge($request);
        }

        if ($request->isMethod('post')) {
            $this->persistSearchRequest('lounge_search_request', $request);

            return redirect()->route('searchresult_lounge');
        }

        if (!$request->filled('airport_id')) {
            $this->applyStoredSearchRequest($request, 'lounge_search_request');
        }

        if (!$request->filled('airport_id')) {
            return redirect()->route('lounges');
        }

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.search_result_lounge', ['airports' => $airports]);
    }



    // end of function

    public function ajaxSearchResultsLounge(Request $request)
    {
        $loungeService = app(LoungeService::class);
        $promo_error_message = '';
        $discount_percentage = 0;
        $global_max_discount = 0;

        $activeLounges = Lounges::catalogueLookup();

        $messages = [
            'required' => 'This field is required.',
        ];

        $checkInDate = $request->input('checkIn_date', $request->input('checkin_date'));
        $checkInTime = $request->input('checkIn_time', $request->input('checkin_time'));
        $adultsInput = $request->input('aladults', $request->input('adults', 1));
        $childrenInput = $request->input('alchildren', $request->input('children', 0));
        $infantsInput = $request->input('alinfants', $request->input('infants', 0));

        $validatedData = Validator::make(array_merge($request->all(), [
            'checkIn_date' => $checkInDate,
            'checkIn_time' => $checkInTime,
            'aladults' => $adultsInput,
            'alchildren' => $childrenInput,
            'alinfants' => $infantsInput,
        ]), [
            'airport_id' => 'required',
            'checkIn_date' => 'required',
            'checkIn_time' => 'required',
            'aladults' => 'nullable|integer|min:1|max:9',
            'alchildren' => 'nullable|integer|min:0|max:9',
            'alinfants' => 'nullable|integer|min:0|max:9',
        ], $messages);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validatedData->errors(),
            ]);
        }

        $airport_id = $request->airport_id;

        $checkin_date = str_replace('/', '-', $checkInDate);
        $checkin_date = date('Y-m-d', strtotime($checkin_date));
        $checkin_time = date('H:i', strtotime($checkInTime));

        if ($request->filled('flight_time')) {
            $flight_time = date('H:i', strtotime($request->flight_time));
            $visitStart = strtotime($checkin_date.' '.$checkin_time);
            $visitEnd = strtotime($checkin_date.' '.$flight_time);
            if ($visitEnd <= $visitStart) {
                $visitEnd = strtotime('+1 day', $visitEnd);
            }
            $checkout_date = date('Y-m-d', $visitEnd);
        } else {
            $visitEnd = strtotime($checkin_date.' '.$checkin_time.' +4 hours');
            $checkout_date = date('Y-m-d', $visitEnd);
            $flight_time = date('H:i', $visitEnd);
        }

        $adults = max(1, (int) $adultsInput);
        $children = max(0, (int) $childrenInput);
        $infants = max(0, (int) $infantsInput);

        $bookingfor = 'lounges';
        $promo = $request->promo;
        $discountObj = null;

        if (!empty($promo)) {
            $discountObj = new discounts();
            $promo_verify = $discountObj->varifyPromoCode($promo);

            if ($promo_verify != 'Verify') {
                $promo_data = discounts::where('promo', $promo)->first();
                if ($promo_data) {
                    $discount_percentage = (float) ($promo_data->discount ?? 0);
                    $global_max_discount = (float) ($promo_data->max_discount ?? 0);
                }
            } else {
                $promo_data = discounts::where('promo', $promo)->first();
                if ($promo_data) {
                    $discount_percentage = (float) ($promo_data->discount_value ?? $promo_data->discount ?? 0);
                    $global_max_discount = (float) ($promo_data->max_discount ?? 0);
                }
            }
        }

        $airports = airport::where('status', 'Yes')->get();
        $airport_detail = airport::find($airport_id);

        if (!$airport_detail) {
            return response()->json([
                'success' => false,
                'message' => 'Airport not found',
            ]);
        }

        $result = $loungeService->search([
            'location' => $airport_detail->iata_code,
            'dateFrom' => $checkin_date,
            'timeFrom' => $checkin_time,
            'dateTo' => $checkout_date,
            'timeTo' => $flight_time,
            'type' => 'Lounge',
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'currency' => 'GBP',
        ] + (!empty($promo) ? ['voucherCode' => $promo] : []));

        if (empty($result['success'])) {
            Log::warning('BookFHR lounge search failed', [
                'airport' => $airport_detail->iata_code,
                'adults' => $adults,
                'children' => $children,
                'error' => $result['error'] ?? null,
            ]);
        }

        $companies = [];
        $searchId = $result['data']['searchId'] ?? null;

        foreach (($result['data']['results'] ?? []) as $item) {
            $product = $item['product'] ?? [];
            $options = $item['options'] ?? [];
            $loungeRecord = $this->findCatalogueMatch($activeLounges, $product);

            if (!$loungeRecord) {
                continue;
            }

            $lounge_max_discount = (float) ($loungeRecord->max_discount ?? 0);
            $final_max_discount_allowed = 0;
            if ($global_max_discount > 0 && $lounge_max_discount > 0) {
                $final_max_discount_allowed = min($global_max_discount, $lounge_max_discount);
            } else {
                $final_max_discount_allowed = $global_max_discount > 0 ? $global_max_discount : $lounge_max_discount;
            }

            foreach ($options as $option) {
                $original_price = (float) ($option['price']['amount'] ?? 0);
                $calculated_discount = ($original_price * $discount_percentage) / 100;

                if ($final_max_discount_allowed > 0 && $calculated_discount > $final_max_discount_allowed) {
                    $calculated_discount = $final_max_discount_allowed;
                }

                if (!empty($promo) && $calculated_discount <= 0 && $original_price > 0) {
                    $discountObj = $discountObj ?? new discounts();
                    $promoDiscount = (float) $discountObj->getPromoDiscount(
                        $promo,
                        $original_price,
                        'airport_lounges',
                        $loungeRecord->id ?? ''
                    );
                    if ($promoDiscount > 0) {
                        $calculated_discount = $promoDiscount;
                        if ($final_max_discount_allowed > 0 && $calculated_discount > $final_max_discount_allowed) {
                            $calculated_discount = $final_max_discount_allowed;
                        }
                    }
                }

                $new_price = $original_price - $calculated_discount;
                if ($new_price < 0) {
                    $new_price = 0;
                }

                $loungeDetails = $product['loungeDetails'] ?? [];
                $loungeCancellation = $option['cancellation'] ?? [];
                $loungeImages = Lounges::normalizeImageList(array_merge(
                    !empty($product['image']) ? [$product['image']] : [],
                    $loungeDetails['images'] ?? ($product['images'] ?? [])
                ));
                $loungePrimaryImage = $loungeImages[0]
                    ?? ($loungeRecord ? $loungeRecord->getImageUrl($product['image'] ?? null) : Lounges::formatImageUrl($product['image'] ?? null))
                    ?: asset('favicon.ico');
                $loungeOption = (object) [
                    'non_refundable' => (bool) ($loungeCancellation['nonRefundable'] ?? false),
                    'free_cancellation' => (bool) ($loungeCancellation['freeCancellation'] ?? false),
                    'cancellation_until' => $option['price']['cancellationsAllowedUntilDate'] ?? null,
                    'cancellation' => $loungeCancellation['text'] ?? '',
                ];

                $companies[] = (object) [
                    'companyID' => $loungeRecord->id ?? $product['id'],
                    'lounge_db_id' => $loungeRecord->id ?? null,
                    'searchId' => $searchId,
                    'product_id' => $product['id'],
                    'product_code' => $product['id'],
                    'option_id' => $option['id'],
                    'park_api' => 'bookfhr',
                    'name' => $product['name'] ?? ($loungeRecord->title ?? $loungeRecord->name ?? ''),
                    'displayName' => $product['displayName'] ?? ($product['name'] ?? ''),
                    'logo' => $loungePrimaryImage,
                    'image' => $loungePrimaryImage,
                    'images' => $loungeImages,
                    'terminal' => $this->resolveLoungeTerminal($product),
                    'price' => round($new_price, 2),
                    'original_price' => $original_price,
                    'discount_applied' => round($calculated_discount, 2),
                    'currency' => $option['price']['currency'] ?? 'GBP',
                    'facilities' => $loungeDetails['loungeFacilities'] ?? ($loungeRecord->facilities ?? ''),
                    'why_bookone' => $loungeRecord->why_bookone ?? '',
                    'why_booktwo' => $loungeRecord->why_booktwo ?? '',
                    'why_bookthree' => $loungeRecord->why_bookthree ?? '',
                    'why_bookfour' => $loungeRecord->why_bookfour ?? '',
                    'extraInfo' => $loungeDetails['extraInfo'] ?? '',
                    'locationInfo' => $loungeDetails['location'] ?? '',
                    'openTime' => isset($loungeDetails['openTime']) ? date('H:i', strtotime($loungeDetails['openTime'])) : null,
                    'closeTime' => isset($loungeDetails['closeTime']) ? date('H:i', strtotime($loungeDetails['closeTime'])) : null,
                    'childFrom' => $loungeDetails['childAgeFrom'] ?? null,
                    'childTo' => $loungeDetails['childAgeTo'] ?? null,
                    'cancellation' => $loungeOption->cancellation,
                    'free_cancellation' => $loungeOption->free_cancellation,
                    'non_refundable' => $loungeOption->non_refundable,
                    'cancellation_until' => $loungeOption->cancellation_until,
                    'cancellation_label' => $this->bookfhrCancellationLabel($loungeOption),
                    'deepLink' => $option['deepLink'] ?? null,
                ];
            }
        }

        $companies = Arr::sort($companies, function ($company) {
            return $company->price;
        });

        if ($request->input('return_json') != 'Yes') {
            return view('frontend.ajax_search_result_lounges', [
                'airports' => $airports,
                'companies' => $companies,
                'request' => $request,
                'promo' => $promo,
                'bookingfor' => $bookingfor,
                'airport_detail' => $airport_detail,
            ]);
        }

        return response()->json([
            'airports' => $airports,
            'companies' => $companies,
            'promo' => $promo,
            'bookingfor' => $bookingfor,
            'airport_detail' => $airport_detail,
        ]);
    }

    private function findCatalogueMatch(array $lookup, array $product)
    {
        foreach (['id', 'code', 'sku', 'productCode', 'product_code'] as $field) {
            $value = strtoupper(trim((string) ($product[$field] ?? '')));
            if ($value !== '' && isset($lookup[$value])) {
                return $lookup[$value];
            }
        }

        return null;
    }

    private function bookfhrCancellationLabel(object $option): ?string
    {
        if (!empty($option->non_refundable)) {
            return 'Non-refundable';
        }

        if (!empty($option->cancellation_until)) {
            try {
                return 'Cancellation allowed until: '.\Carbon\Carbon::parse($option->cancellation_until)->format('d/m/Y');
            } catch (\Throwable $e) {
                return 'Cancellation allowed until: '.$option->cancellation_until;
            }
        }

        if (!empty($option->free_cancellation)) {
            return 'Free cancellation';
        }

        $text = trim(strip_tags((string) ($option->cancellation ?? '')));
        if ($text !== '') {
            return \Illuminate\Support\Str::limit($text, 72);
        }

        return null;
    }

    private function resolveLoungeTerminal(array $product): string
    {
        $terminals = $product['terminals'] ?? [];

        if (is_array($terminals) && count($terminals)) {
            return implode(', ', array_filter($terminals));
        }

        if (!empty($product['terminal'])) {
            return (string) $product['terminal'];
        }

        $name = $product['displayName'] ?? $product['name'] ?? '';
        if (preg_match('/\(([^)]+)\)\s*$/', $name, $matches)) {
            return trim($matches[1]);
        }

        return 'Terminal Information Not Available';
    }

    public function getSearchResultTransfer(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxSearchResultTransfer($request);
        }

        if ($request->isMethod('post')) {
            $this->persistSearchRequest('transfer_search_request', $request);

            return redirect()->route('searchresult_transfer.show');
        }

        if (!$request->filled('airport_id')) {
            $this->applyStoredSearchRequest($request, 'transfer_search_request');
        }

        if (!$request->filled('airport_id')) {
            return redirect()->route('airporttransfer');
        }

        $airports = airport::all()->where('status', 'Yes');

        return view('frontend.search_result_transfer', ['airports' => $airports]);
    }



    // end of function

    public function ajaxSearchResultTransfer($request)
    {



        //dd($request->all());

        $promo_error_message = '';



        $bookingfor = 'transfer';



        $data = $request->all();



        $aph_functions = new aph_functions();

        $api = new \api();

        // APH / A2Z / Holiday Extra are disabled — parking uses DB, AVPS, BookFHR only.
        $holidaycompanies = [];

        //$holiday_record =  @$api->holiday_lounge_record($holidaycompanies, $airport_id, $search_filter);

        //echo "<pre>"; print_r($holidaycompanies); echo "</pre>";

        //exit;

        $companies = '';

        if (!empty($holidaycompanies)) {



            $companies = $holidaycompanies;

        }

        //echo "<pre>"; print_r($companies); echo "</pre>";

        //exit;

        // $companies= Arr::sort($companies, function($company)

        //     {

        //         // Sort the student's scores by their test score.

        //         return $company->price;

        //     });



        if ($request->input('return_json') != 'Yes') {

            return view('frontend.ajax_search_result_transfer', ['companies' => $companies, 'request' => $request, 'bookingfor' => $bookingfor]);

        } else {

            return response()->json(['companies' => $companies, 'request' => $request, 'bookingfor' => $bookingfor]);

        }

    }

    // Airport Hotels Functions Start

    public function getSearchResultHotel(Request $request)
    {
        // Lounge-style: full page shell (Edit Search + summary) on normal POST/GET;
        // AJAX only returns the listing partial.
        if ($request->ajax()) {
            return $this->ajaxSearchResultsHotel($request);
        }

        if ($request->isMethod('post')) {
            $this->persistSearchRequest('hotel_search_request', $request);

            return redirect()->route('searchresult_hotel');
        }

        if (!$request->filled('airport_id')) {
            $this->applyStoredSearchRequest($request, 'hotel_search_request');
        }

        if (!$request->filled('airport_id')) {
            return $this->redirectToHotelSearch();
        }

        $airports = airport::where('status', 'Yes')->get();

        return view('frontend.search_result_hotel', [
            'airports' => $airports,
            'request'  => $request,
        ]);
    }

    public function ajaxSearchResultsHotel(Request $request)
    {
        $hotelService = app(HotelService::class);
        $discount_percentage = 0;
        $global_max_discount = 0;

        $activeHotels = Hotel::catalogueLookup();

        $messages = [
            'required' => 'This field is required.',
        ];

        $validatedData = Validator::make($request->all(), [
            'airport_id'          => 'required',
            'hotel_checkin_date'  => 'required',
            'hotel_checkout_date' => 'required',
            'hotel_adults'        => 'required|integer|min:1',
            'hotel_children'      => 'required|integer|min:0',
            'hotel_infants'       => 'required|integer|min:0',
            'hotel_rooms'         => 'required|integer|min:1',
        ], $messages);

        if ($validatedData->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validatedData->errors(),
                ]);
            }

            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput()
                ->with('open_booking_tab', 'hotels');
        }

        $airport_id = $request->airport_id;
        $airport_detail = airport::find($airport_id);

        if (!$airport_detail) {
            return redirect()->back()
                ->withErrors(['airport_id' => 'Airport not found.'])
                ->withInput()
                ->with('open_booking_tab', 'hotels');
        }

        $checkin_date = str_replace('/', '-', $request->hotel_checkin_date);
        $checkin_date = date('Y-m-d', strtotime($checkin_date));

        if ($request->filled('hotel_checkout_date')) {
            $checkout_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->hotel_checkout_date)));
        } else {
            $checkout_date = date('Y-m-d', strtotime($checkin_date . ' +1 day'));
        }

        if ($checkout_date <= $checkin_date) {
            $checkout_date = date('Y-m-d', strtotime($checkin_date . ' +1 day'));
        }

        $checkin_time = $request->filled('hotel_checkin_time')
            ? date('H:i', strtotime($request->hotel_checkin_time))
            : '14:00';

        $checkout_time = $request->filled('hotel_checkout_time')
            ? date('H:i', strtotime($request->hotel_checkout_time))
            : '12:00';

        $adults   = (int) $request->hotel_adults;
        $children = (int) $request->hotel_children;
        $infants  = (int) $request->hotel_infants;
        $rooms    = max(1, (int) $request->hotel_rooms);
        $roomType = $request->hotel_room_type ?: $this->resolveHotelRoomType($adults, $children);
        $promo    = $request->hotel_promo;
        $bookingfor = 'hotels';
        $discountObj = null;

        if (!empty($promo)) {
            $discountObj = new discounts();
            $promo_verify = $discountObj->varifyPromoCode($promo);

            if ($promo_verify != 'Verify') {
                $promo_data = discounts::where('promo', $promo)->first();
                if ($promo_data) {
                    $discount_percentage = (float) ($promo_data->discount ?? 0);
                    $global_max_discount = (float) ($promo_data->max_discount ?? 0);
                }
            } else {
                $promo_data = discounts::where('promo', $promo)->first();
                if ($promo_data) {
                    $discount_percentage = (float) ($promo_data->discount_value ?? $promo_data->discount ?? 0);
                    $global_max_discount = (float) ($promo_data->max_discount ?? 0);
                }
            }
        }

        $locationCandidates = $this->resolveHotelSearchLocationCandidates($airport_detail);
        $location = $locationCandidates[0] ?? $airport_detail->iata_code;

        $searchParams = [
            'location'  => $location,
            'dateFrom'  => $checkin_date,
            'timeFrom'  => $checkin_time,
            'dateTo'    => $checkout_date,
            'timeTo'    => $checkout_time,
            'adults'    => $adults,
            'children'  => $children,
            'infants'   => $infants,
            'rooms'     => $rooms,
            'roomType'  => $roomType,
            'currency'  => 'GBP',
            'radius'    => (int) ($request->hotel_radius ?? 16000),
        ];

        if (!empty($promo)) {
            $searchParams['voucherCode'] = $promo;
        }

        // BookFHR returns zero hotel results when children > 0 unless children_ages is sent
        if ($children > 0) {
            $searchParams['children_ages'] = $this->resolveHotelChildrenAges($request, $children);
        }

        $result = $hotelService->searchWithFallbacks($searchParams, $locationCandidates, [$roomType]);

        if (!$result['success']) {
            return redirect()->back()
                ->withErrors(['hotel_search' => 'Unable to fetch hotel results. Please try again.'])
                ->withInput()
                ->with('open_booking_tab', 'hotels');
        }

        $companies = [];
        $apiRawCount = (int) ($result['meta']['api_raw_count'] ?? count($result['data']['results'] ?? []));
        $searchId = $result['data']['searchId'] ?? ('hotel_' . bin2hex(random_bytes(8)));
        $airports = airport::where('status', 'Yes')->get();
        $matchedCount = 0;
        $nights = max(1, (int) round((strtotime($checkout_date) - strtotime($checkin_date)) / 86400));

        foreach (($result['data']['results'] ?? []) as $item) {
            $product = $item['product'] ?? [];
            $options = $item['options'] ?? [];

            if (($product['type'] ?? '') !== 'Hotel' || empty($options)) {
                continue;
            }

            $hotelRecord = $this->findCatalogueMatch($activeHotels, $product);

            if (!$hotelRecord) {
                continue;
            }

            $matchedCount++;
            $hotel_max_discount = (float) ($hotelRecord->max_discount ?? 0);

            $final_max_discount_allowed = 0;
            if ($global_max_discount > 0 && $hotel_max_discount > 0) {
                $final_max_discount_allowed = min($global_max_discount, $hotel_max_discount);
            } else {
                $final_max_discount_allowed = $global_max_discount > 0 ? $global_max_discount : $hotel_max_discount;
            }

            $hotelDetails = $product['hotelDetails'] ?? [];
            $hotelAmenitiesList = is_array($hotelDetails['amenities'] ?? null) ? $hotelDetails['amenities'] : [];
            $processedOptions = [];
            $lowestPrice = null;

            foreach ($options as $option) {
                if (($option['available'] ?? '') !== 'Available') {
                    continue;
                }

                $original_price = (float) ($option['price']['amount'] ?? 0);
                $calculated_discount = ($original_price * $discount_percentage) / 100;

                if ($final_max_discount_allowed > 0 && $calculated_discount > $final_max_discount_allowed) {
                    $calculated_discount = $final_max_discount_allowed;
                }

                if (!empty($promo) && $calculated_discount <= 0 && $original_price > 0) {
                    $discountObj = $discountObj ?? new discounts();
                    $promoDiscount = (float) $discountObj->getPromoDiscount(
                        $promo,
                        $original_price,
                        'airport_hotel',
                        $hotelRecord->id ?? ''
                    );
                    if ($promoDiscount > 0) {
                        $calculated_discount = $promoDiscount;
                        if ($final_max_discount_allowed > 0 && $calculated_discount > $final_max_discount_allowed) {
                            $calculated_discount = $final_max_discount_allowed;
                        }
                    }
                }

                $new_price = max(0, $original_price - $calculated_discount);
                $optionName = HotelRoomFacilities::resolveOptionName($option);
                $roomTitle = HotelRoomFacilities::parseRoomTitle($optionName);
                $cancellation = $option['cancellation'] ?? [];
                $cancelUntil = $option['price']['cancellationsAllowedUntilDate'] ?? null;
                $optionPayload = HotelRoomFacilities::optionPayload($option);

                $processedOption = (object) [
                    'option_id'          => $option['id'] ?? null,
                    'name'               => $optionName,
                    'room_title'         => $roomTitle !== '' ? $roomTitle : $optionName,
                    'plan_type'          => HotelRoomFacilities::parsePlanType($optionName),
                    'max_guests'         => HotelRoomFacilities::parseMaxGuests($roomTitle ?: $optionName, (int) $request->input('hotel_adults', 2)),
                    'is_non_smoking'     => HotelRoomFacilities::isNonSmoking($option),
                    'price'              => round($new_price, 2),
                    'original_price'     => $original_price,
                    'discount_applied'   => round($calculated_discount, 2),
                    'currency'           => $option['price']['currency'] ?? 'GBP',
                    'image'              => $option['image'] ?? ($product['image'] ?? null),
                    'cancellation'       => $cancellation['text'] ?? '',
                    'free_cancellation'  => (bool) ($cancellation['freeCancellation'] ?? false),
                    'non_refundable'     => (bool) ($cancellation['nonRefundable'] ?? false),
                    'cancellation_until' => $cancelUntil,
                    'facilities'         => HotelRoomFacilities::fromOption($optionPayload, $hotelAmenitiesList),
                    'source_option'      => $optionPayload,
                    'deepLink'           => $option['deepLink'] ?? ($item['deepLink'] ?? null),
                ];

                $processedOptions[] = $processedOption;

                if ($lowestPrice === null || $new_price < $lowestPrice) {
                    $lowestPrice = $new_price;
                }
            }

            if (empty($processedOptions)) {
                continue;
            }

            usort($processedOptions, function ($a, $b) {
                return $a->price <=> $b->price;
            });

            $cheapest = $processedOptions[0];
            $amenities = $hotelDetails['amenities'] ?? [];
            $showAmenities = array_slice(is_array($amenities) ? $amenities : [], 0, 8);
            $boardTypes = [];
            foreach ($processedOptions as $opt) {
                $plan = trim((string) ($opt->plan_type ?? ''));
                if ($plan !== '') {
                    $boardTypes[$plan] = $plan;
                }
            }

            $gallery = Hotel::normalizeImageList($hotelDetails['images'] ?? ($product['images'] ?? []));
            $productImage = Hotel::formatImageUrl($product['image'] ?? null);
            if ($productImage !== '' && !in_array($productImage, $gallery, true)) {
                array_unshift($gallery, $productImage);
            }
            $hotelImage = $gallery[0]
                ?? ($hotelRecord ? $hotelRecord->getImageUrl(asset('favicon.ico')) : asset('favicon.ico'));

            $companies[] = (object) [
                'companyID'           => $hotelRecord->id ?? $product['id'],
                'hotel_db_id'         => $hotelRecord->id ?? null,
                'searchId'            => $searchId,
                'product_id'          => $product['id'],
                'option_id'           => $cheapest->option_id,
                'park_api'            => 'bookfhr',
                'name'                => $hotelRecord->display_name ?: ($product['displayName'] ?? $product['name'] ?? 'Hotel'),
                'displayName'         => $hotelRecord->display_name ?: ($product['displayName'] ?? $product['name'] ?? 'Hotel'),
                'image'               => $hotelImage,
                'starRating'          => $hotelDetails['starRating'] ?? null,
                'checkIn'             => isset($hotelDetails['checkIn']) ? date('H:i', strtotime($hotelDetails['checkIn'])) : $checkin_time,
                'checkOut'            => isset($hotelDetails['checkOut']) ? date('H:i', strtotime($hotelDetails['checkOut'])) : $checkout_time,
                'address'             => $hotelDetails['address'] ?? ($hotelRecord->address ?? ''),
                'description'         => $hotelDetails['description'] ?? ($product['sellingPoints'][0] ?? ''),
                'amenities'           => $showAmenities,
                'all_amenities'       => is_array($amenities) ? $amenities : [],
                'images'              => $gallery,
                'price'               => $cheapest->price,
                'original_price'      => $cheapest->original_price,
                'discount_applied'    => $cheapest->discount_applied,
                'currency'            => $cheapest->currency,
                'deepLink'            => $cheapest->deepLink ?? ($item['deepLink'] ?? null),
                'options'             => $processedOptions,
                'latitude'            => $hotelDetails['geo']['latitude'] ?? ($product['location']['latitude'] ?? null),
                'longitude'           => $hotelDetails['geo']['longitude'] ?? ($product['location']['longitude'] ?? null),
                'featured'            => (bool) ($item['featured'] ?? false),
                'nights'              => $nights,
                'room_type_count'     => count($processedOptions),
                'board_types'         => array_values($boardTypes),
                'cancellation_label'  => $this->bookfhrCancellationLabel($cheapest),
                'hotel_stats'         => $this->hotelStatsFromDetails($hotelDetails),
            ];
        }

        usort($companies, function ($a, $b) {
            if ($a->featured && !$b->featured) {
                return -1;
            }
            if (!$a->featured && $b->featured) {
                return 1;
            }

            return $a->price <=> $b->price;
        });

        if ($request->return_json === 'Yes') {
            return response()->json([
                'airports'       => $airports,
                'companies'      => $companies,
                'promo'          => $promo,
                'bookingfor'     => $bookingfor,
                'airport_detail' => $airport_detail,
                'searchId'       => $searchId,
            ]);
        }

        $this->storeHotelSearchSession($request, $companies, $searchId, $airport_detail, $promo);

        return view('frontend.ajax_search_result_hotels', [
            'airports'       => $airports,
            'companies'      => $companies,
            'request'        => $request,
            'promo'          => $promo,
            'bookingfor'     => $bookingfor,
            'airport_detail' => $airport_detail,
            'searchId'       => $searchId,
            'hotel_search_meta' => [
                'api_raw_count'   => $apiRawCount,
                'matched_count'   => $matchedCount,
                'listed_count'    => count($companies),
                'active_db_count' => count($activeHotels),
            ],
        ]);
    }

    public function hotelSearchResultsFromSession(Request $request)
    {
        $context = $this->resolveHotelSearchContext($request->query('searchId'));

        if (!$context) {
            return $this->redirectToHotelSearch();
        }

        $airports = airport::where('status', 'Yes')->get();
        $airport_detail = airport::find($context['airport_detail_id'] ?? null);
        $contextRequest = new Request($context['request'] ?? []);
        $companies = $this->hydrateHotelCompanies($context['companies'] ?? []);

        $preloadedResults = view('frontend.ajax_search_result_hotels', [
            'airports'       => $airports,
            'companies'      => $companies,
            'request'        => $contextRequest,
            'promo'          => $context['promo'] ?? '',
            'bookingfor'     => 'hotels',
            'airport_detail' => $airport_detail,
            'searchId'       => $context['searchId'] ?? null,
        ])->render();

        // Merge saved search into the current request so the Edit Search shell +
        // hotels_form old()/request() values match the session context.
        $request->merge($context['request'] ?? []);

        return view('frontend.search_result_hotel', [
            'airports'          => $airports,
            'request'           => $request,
            'airport_detail'    => $airport_detail,
            'promo'             => $context['promo'] ?? '',
            'searchId'          => $context['searchId'] ?? null,
            'preloadedResults'  => $preloadedResults,
        ]);
    }

    public function hotelDetail($productId, Request $request)
    {
        $context = $this->resolveHotelSearchContext($request->query('searchId'));

        if (!$context) {
            return $this->redirectToHotelSearch(['hotel_search' => 'Please run a hotel search first.']);
        }

        $company = $this->findHotelCompanyFromContext($context['companies'] ?? [], $productId);

        if (!$company) {
            abort(404);
        }

        if (!$this->findCatalogueMatch(Hotel::catalogueLookup(), ['id' => $productId])) {
            abort(404);
        }

        $company = $this->enrichHotelCompanyOptions($company);

        $airport_detail = airport::find($context['airport_detail_id'] ?? null);
        $request = new Request($context['request'] ?? []);
        $airports = airport::where('status', 'Yes')->get();

        return view('frontend.hotel_detail', [
            'hotel'          => $company,
            'request'        => $request,
            'promo'          => $context['promo'] ?? '',
            'airport_detail' => $airport_detail,
            'searchId'       => $context['searchId'] ?? null,
            'airports'       => $airports,
        ]);
    }

    private function storeHotelSearchSession(Request $request, array $companies, ?string $searchId, $airport_detail, ?string $promo): void
    {
        $payload = [
            'request'           => $request->except(['_token']),
            'searchId'          => $searchId,
            'airport_detail_id' => $airport_detail->id ?? null,
            'promo'             => $promo,
            'companies'         => json_decode(json_encode($companies), true),
        ];

        session(['hotel_search_context' => $payload]);

        $cacheKey = $this->hotelSearchCacheKey($searchId);
        if ($cacheKey) {
            Cache::put($cacheKey, $payload, now()->addHours(6));
        }
    }

    private function hotelSearchCacheKey(?string $searchId): ?string
    {
        if (!$searchId) {
            return null;
        }

        return 'hotel_search_context_' . $searchId;
    }

    private function resolveHotelSearchContext(?string $searchId = null): ?array
    {
        $cacheKey = $this->hotelSearchCacheKey($searchId);
        if ($cacheKey) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached)) {
                if (!session()->has('hotel_search_context')) {
                    session(['hotel_search_context' => $cached]);
                }

                return $cached;
            }
        }

        $sessionContext = session('hotel_search_context');

        return is_array($sessionContext) ? $sessionContext : null;
    }

    private function persistSearchRequest(string $sessionKey, Request $request): void
    {
        session([$sessionKey => $request->except(['_token'])]);
    }

    private function applyStoredSearchRequest(Request $request, string $sessionKey): bool
    {
        $stored = session($sessionKey);

        if (!is_array($stored) || empty($stored)) {
            return false;
        }

        $request->merge($stored);

        return true;
    }

    private function redirectToHotelSearch($errors = null)
    {
        $redirect = redirect()->route('airport-hotels')->with('open_booking_tab', 'hotels');

        if ($errors) {
            $redirect->withErrors($errors);
        }

        return $redirect;
    }

    private function hydrateHotelCompanies(array $companies): array
    {
        return array_map(function ($company) {
            $company = (object) $company;
            $company->options = array_map(fn ($option) => (object) $option, $company->options ?? []);

            return $company;
        }, $companies);
    }

    private function findHotelCompanyFromContext(array $companies, $productId): ?object
    {
        foreach ($this->hydrateHotelCompanies($companies) as $company) {
            if ((string) $company->product_id === (string) $productId) {
                return $company;
            }
        }

        return null;
    }

    private function enrichHotelCompanyOptions(object $company): object
    {
        $amenities = $company->all_amenities ?? $company->amenities ?? [];
        $amenities = is_array($amenities) ? $amenities : [];

        $company->options = array_map(function ($option) use ($amenities) {
            $option = is_array($option) ? (object) $option : $option;

            $payload = is_array($option->source_option ?? null)
                ? $option->source_option
                : [];

            if (empty($payload['name']) && !empty($option->name)) {
                $payload['name'] = $option->name;
            }

            if (!empty($payload)) {
                $option->facilities = HotelRoomFacilities::fromOption($payload, $amenities);
            } elseif (empty($option->facilities)) {
                $option->facilities = HotelRoomFacilities::fromOption([], $amenities);
            }

            $optionName = HotelRoomFacilities::resolveOptionName($payload ?: ['name' => $option->name ?? '']);
            if ($optionName !== '') {
                $option->name = $optionName;
                $option->room_title = HotelRoomFacilities::parseRoomTitle($optionName) ?: $optionName;
                $option->plan_type = HotelRoomFacilities::parsePlanType($optionName);
                $option->max_guests = HotelRoomFacilities::parseMaxGuests($option->room_title, (int) ($option->max_guests ?? 2));
            }

            if (!isset($option->room_title) || $option->room_title === '') {
                $option->room_title = $option->name ?? 'Room';
            }

            if (!isset($option->plan_type) || $option->plan_type === '') {
                $option->plan_type = HotelRoomFacilities::parsePlanType($option->name ?? '');
            }

            if (!isset($option->max_guests)) {
                $option->max_guests = HotelRoomFacilities::parseMaxGuests($option->room_title, 2);
            }

            if (!isset($option->is_non_smoking) && !empty($payload['details'])) {
                $option->is_non_smoking = HotelRoomFacilities::isNonSmoking($payload);
            }

            return $option;
        }, $company->options ?? []);

        return $company;
    }

    /**
     * BookFHR requires roomType. Infer it from occupancy when the form omits it.
     */
    private function resolveHotelRoomType(int $adults, int $children): string
    {
        $occupants = max(1, $adults + $children);

        if ($occupants <= 1) {
            return 'Single';
        }
        if ($occupants === 2) {
            return 'Double';
        }
        if ($occupants === 3) {
            return 'Triple';
        }

        return 'Family';
    }

    /**
     * BookFHR requires children_ages when children > 0.
     * Prefer posted ages; pad missing ages with a sensible default (8).
     */
    private function resolveHotelChildrenAges(Request $request, int $children): array
    {
        $raw = $request->input('children_ages', []);
        if (!is_array($raw)) {
            $raw = [];
        }

        $ages = [];
        foreach ($raw as $age) {
            if ($age === null || $age === '') {
                continue;
            }
            $ages[] = max(1, min(17, (int) $age));
        }

        while (count($ages) < $children) {
            $ages[] = 8;
        }

        return array_slice($ages, 0, $children);
    }

    private function resolveHotelSearchLocationCandidates(airport $airport_detail): array
    {
        $candidates = [];

        if (!empty($airport_detail->Latitude) && !empty($airport_detail->Longitude)) {
            $candidates[] = trim($airport_detail->Latitude) . ',' . trim($airport_detail->Longitude);
        }

        $iata = strtoupper(trim((string) $airport_detail->iata_code));

        if ($iata !== '' && isset($this->hotelAirportCoordinates()[$iata])) {
            $candidates[] = $this->hotelAirportCoordinates()[$iata];
        }

        if ($iata !== '') {
            $candidates[] = $iata;
        }

        return array_values(array_unique(array_filter($candidates)));
    }

    private function hotelAirportCoordinates(): array
    {
        return [
            'LGW' => '51.1537,-0.1821',
            'LHR' => '51.4700,-0.4543',
            'STN' => '51.8860,0.2389',
            'LTN' => '51.8747,-0.3683',
            'MAN' => '53.3537,-2.2750',
            'BHX' => '52.4539,-1.7480',
            'EDI' => '55.9500,-3.3725',
            'GLA' => '55.8642,-4.4331',
            'BRS' => '51.3827,-2.7191',
            'LPL' => '53.3336,-2.8497',
            'NCL' => '55.0375,-1.6917',
            'EMA' => '52.8311,-1.3281',
            'ABZ' => '57.2019,-2.1978',
            'BFS' => '54.6575,-6.2158',
            'CWL' => '51.3967,-3.3433',
            'SOU' => '50.9503,-1.3568',
            'EXT' => '50.7344,-3.4139',
            'NWI' => '52.6758,1.2828',
        ];
    }

    private function firstHotelDetailValue(array $details, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $details)) {
                continue;
            }

            $value = $details[$key];
            if ($value === null || $value === '' || $value === false || is_array($value)) {
                continue;
            }

            return $value;
        }

        return null;
    }

    private function hotelStatsFromDetails(array $hotelDetails): array
    {
        $map = [
            'renovated'        => ['yearRenovated', 'yearOfRenovation', 'renovated', 'renovationYear', 'yearOfConstruction', 'constructionYear'],
            'min_checkin_age'  => ['minCheckInAge', 'minimumCheckInAge', 'minAge', 'checkInAge'],
            'rooms'            => ['rooms', 'numberOfRooms', 'totalRooms', 'roomCount'],
            'floors'           => ['floors', 'numberOfFloors', 'totalFloors'],
            'twin_rooms'       => ['twinRooms', 'numberOfTwinRooms', 'twinRoomCount'],
            'double_rooms'     => ['doubleRooms', 'numberOfDoubleRooms', 'doubleRoomCount'],
            'executive_rooms'  => ['executiveRooms', 'numberOfExecutiveRooms', 'executiveRoomCount'],
        ];

        $stats = [];
        foreach ($map as $outKey => $keys) {
            $value = $this->firstHotelDetailValue($hotelDetails, $keys);
            if ($value === null) {
                continue;
            }

            $stats[$outKey] = is_numeric($value) ? (int) $value : $value;
        }

        $roomsBlock = $hotelDetails['rooms'] ?? $hotelDetails['roomCounts'] ?? null;
        if (is_array($roomsBlock)) {
            $nestedMap = [
                'rooms'           => ['total', 'count', 'number', 'rooms'],
                'twin_rooms'      => ['twin', 'twinRooms', 'twin_rooms'],
                'double_rooms'    => ['double', 'doubleRooms', 'double_rooms'],
                'executive_rooms' => ['executive', 'executiveRooms', 'executive_rooms'],
            ];
            foreach ($nestedMap as $outKey => $keys) {
                if (isset($stats[$outKey])) {
                    continue;
                }
                $value = $this->firstHotelDetailValue($roomsBlock, $keys);
                if ($value !== null && is_numeric($value)) {
                    $stats[$outKey] = (int) $value;
                }
            }
        }

        return $stats;
    }

    // Airport Hotels Functions End

}

