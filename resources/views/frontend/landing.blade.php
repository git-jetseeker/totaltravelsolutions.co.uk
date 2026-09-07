<!DOCTYPE HTML>
<html>
<head>
	@include('layouts.header')
</head>
<body>
	<header>
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
	    @php
            $site_settings_main=[];
            $settingsAll = App\Models\settings::all()->where("agent_id","1");
            foreach ($settingsAll as $setting) {
                $site_settings_main[$setting->field_name] = $setting->field_value;
            }
        @endphp

        @if(isset($site_settings_main["site_body_analytics"]))
        {!!   $site_settings_main["site_body_analytics"] !!}
        @endif
        
        @php
            $site_settings_main=[];
            $settingsAll = App\Models\settings::all();
            foreach ($settingsAll as $setting) {
             if($setting->agent_id == '1')
               {
                $site_settings_main[$setting->field_name] = $setting->field_value;
                }
            }
            @endphp
            @php
                $site_settings_main = [];
                $settingsAll = App\Models\settings::all();
                foreach ($settingsAll as $setting) {
                    if ($setting->agent_id == '1') {
                        $site_settings_main[$setting->field_name] = $setting->field_value;
                    }
                }
            @endphp

<style>
    body{background: #fff !important;}
    .pd-lr0{
        color: #fff !important;
    }
    .destop-nav-ul-1{
        list-style: none;
        display: flex;
        font-size: 17px;
        
    }
    .destop-nav-ul-1 li a{
        margin-left: 25px;
        color: white;
    }
    .destop-model-btn-landing{
        background: white;
        color: #000000 !important;
        padding: 8px;
        border-radius: 12px;
    }
    .destop-nav-ul-2{
        list-style: none;
        display: flex;
        font-size: 17px;
    }
    .destop-nav-ul-2 li a,.destop-nav-ul-2 li a:focus{
        color: black !important;
    }
    .box1 {
        height: 278px;
        overflow-y: auto;
        width: 290px !important;
        padding-left: 20px;
    }
    .box1::-webkit-scrollbar {
        width: 5px;
    }
    .box1::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .box1::-webkit-scrollbar-thumb {
        background: #888;
    }
    .landing-h1-tag{
        background: linear-gradient(90deg, rgba(238, 108, 32, 1) 18%, rgba(255, 184, 0, 1) 68%);
        font-size: 45px;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 600;
    }
    .landing-h1-tag span{
        font-size: 15px;
        -webkit-text-fill-color: #F4A273 !important;
    }
    .service-span-landing{
        color: #000;
        font-size: 16px;
        width:100%;
    }
    .fram-row{
        background: #F1F1E8;
        border-radius: 22px;
        border: 1px solid #B3B3B3;
        margin-top: 9px;
    }
    .subscribe_input{
        border-radius: 12px;
        color:#000;
        border: 1px solid #B3B3B3;
    }
        .subscribe_input::-webkit-input-placeholder,
        textarea::-webkit-input-placeholder {
          color: #000 !important;
        }
        
        .subscribe_input:-moz-placeholder,
        textarea:-moz-placeholder {
          color: #000 !important;
        }
        .btn.subscriber-btn{
            position: absolute;
        right: 20px;
        top: 4px;
        background: #1A1A1A;
        color: white;
        padding: 3px 9px 2px;
        margin: 0;
        border-radius: 12px;
        font-size: 14px;
        height: 34px;
        margin-top: 3px;
        }
    @media screen and (min-device-width: 991px) and (max-device-width: 1199px) {
        .destop-nav-ul-1 li a{margin-left: 13px;font-size: 15px;}
        .destop-nav-ul-2 li a, .destop-nav-ul-2 li a:focus{font-size: 15px;font-weight: 500;}
        .landing-h1-tag{font-size: 40px;}
        .color-cssnav-btn{display:none !important;}
    }
    .second-h2 {
        margin-bottom: 25px;
        color: #1A1A1A;
        font-family: "Poppins", Sans-serif !important;
        font-weight: 600 !important;
    }
    .second-h2{color:black;}
    .second-p{
        font-size: 15px;
        color: #1D1A1A;
        max-width: 897px;
        margin-bottom: 40px;
    }
    .services-card-h4{
        color: black;
        font-weight: 600;
        font-size: 20px;
    }
    .services-card{
        background: #F1F1E8;
        padding: 28px 12px;
        border-radius: 22px;
        margin-bottom: 20px;
    }
    .top-to-search{
        background: #EE6C20 !important;
        border-radius: 12px !important;
            border: none !important;
    color: white !important;
    padding: 8px 20px;
    }
    @media  screen and (min-width: 681px){
        .services-card-row{
            justify-content: center;display: flex;
        }
    }
    @media  screen and (max-width: 991px){
        .search-landing{
            margin-top: 103px;
        }
    }
    @media  screen and (max-width: 350px){
        .search-landing{
            padding: 4px !important;
        }
        .pd-lr0{padding: 0 !important;}
        .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto{
            padding-left: 15px;
        padding-right: 15px;
        }
    }
    .rev-21{
        display: flex;
        justify-content: center;
        border:none;
    }
    .rev-1{
        color: #000;
        font-size: 29px;
        font-weight: 600;
    }
    .subscribe_input_footer::-webkit-input-placeholder,
        textarea::-webkit-input-placeholder {
          color: #fff !important;
        }
        
        .subscribe_input_footer::-moz-placeholder,
        textarea:-moz-placeholder {
          color: #fff !important;
        }
	.subscriber-btn-footer{background: #EE6C20 !important}
	.subscribe_input_footer{background: rgb(255 255 255 / 30%);}
	.navbar.header{background: #fff;border-bottom: none;}
	.navbar-collapse.collapse{padding-bottom: 13px;}
    .destop-nav-ul-2 li .color-cssnav-btn, .destop-nav-ul-2 li .color-cssnav-btn:focus
    {
	    color: #EE6C20 !important;
        font-weight: 600;
        font-size: 20px;
        border: 1px solid;
        border-radius: 10px;
	}
	.second-col {
    justify-content: center;
    display: grid;
    text-align: center;
}
.mt-15 {
    margin-top: 15px;
}
.body-ul li{
    list-style: disc !important;
}
.list-inline>li{
    
}
.text-light1 {
    font-size: 14px;
    text-decoration: underline !important;
    color: #fff;
}
h1, h2, h3, h4, h5, h6,p,label,b,li,span,a,button{
            font-family: "Poppins", Sans-serif !important;
        }
</style>
	</header>
	<div class='cursor' id="cursor"></div>
	<br  class="hidden-sm hidden-xs">
	<section class="hidden-md hidden-lg hidden-xl">
	    @include('layouts.nav')
	</section>
	<section>
	    <div class="container">
	        <div class="row" style="">
	            <div class="col-md-12">
	                <nav class="hidden-sm hidden-xs">
                        <div class="navbar-header" style="padding: 22px 0;">
                            <a id="ar-brand" class=" " href="/"><img style="width:175px" src="{{ asset('theme/images/logo-black.png') }}" alt="Total Travel Solutions"></a>
                        </div> <!-- navbar-header -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav destop-nav-ul-2" style="justify-content: end;margin-top: 10px;">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle color-css" data-toggle="dropdown" data-hover="dropdown" aria-expanded="false">Airport Parking <i class="fas fa-caret-down" style="color: #FF7A00;"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-left animated-2x animated fadeIn box1">
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"gatwick-airport-parking"]) }}'>Gatwick Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"heathrow-airport-parking"]) }}'>Heathrow Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"stansted-airport-parking"]) }}'>Stansted Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"birmingham-airport-parking"]) }}'>Birmingham Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"edinburgh-airport-parking"]) }}'>Edinburgh Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"southampton-airport-parking"]) }}'>Southampton Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"liverpool-airport-parking"]) }}'>Liverpool Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"aberdeen-airport-parking"]) }}'>Aberdeen Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"belfast-airport-parking"]) }}'>Belfast Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"bristol-airport-parking"]) }}'>Bristol Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"east-midlands-airport-parking"]) }}'>East Midlands Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"glasgow-airport-parking"]) }}'>Glasgow Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"leeds-bradford-airport-parking"]) }}'>Leeds Bradford Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"luton-airport-parking"]) }}'>Luton Airport Parking</a></li>
                                        <li><a class="li-menu" href='{{ route("page",["slug"=>"manchester-airport-parking"]) }}'>Manchester Airport Parking</a></li>
                                    </ul>
                                </li>
                                <li><a href=" {{ route('airport_types') }}" class="color-css">Parking Types</a></li>
                                <li><a href="{{ route('faqs') }}" class="color-css">FAQs</a></li>
                                <li><a href="{{ url('about-us') }}" class="color-css">About Us</a></li>
                                <li><a href="{{ route('support') }}" class="color-css">Customer Support</a></li>
                                <li><a href="tel:{{ $site_settings_main['footer_phone_no'] }}" class="color-cssnav-btn">{{ $site_settings_main['footer_phone_no'] }}</a></li>
                            </ul>
                        </div><!-- navbar-collapse -->
                    </nav>
	            </div>
	            <div class="col-md-7 hidden-sm hidden-xs">
                    
                    
                    <h1 class="landing-h1-tag">
                        Airport Parking <br> Across UK <span>Enjoy savings of up to 60% Off</span>
                    </h1>
                    <p style="color: black;color: black;font-size: 16px;margin-top: 26px;margin-bottom: 17px;">JET SEEKER provides its clients with a seamless end-to-end airport experience. From the car parking, including cheap Stansted short stay airport parking, to the transfer, we have covered everything for our customers.<b> Don't forget to use your Stansted parking discount code for extra savings.</b></p>
	                <!--<div class="row">-->
	                <!--    <div class="col-lg-12">-->
                 <!--           <form id="subscribe_user" action='javascript:;' method="post">-->
                 <!--               @csrf-->
                 <!--               <input type="email" id="subscribe_user_email" class="form-control subscribe_input" required placeholder="Enter your email to get exclusive offers">-->
                 <!--                   <i class="far fa-envelope" style="position: absolute;left: 23px;top: 15px;color: #000;font-size: 19px;"></i>-->
                 <!--               <button class="btn subscriber-btn" type="submit" id="button-addon2">Subscribe</button>-->
                 <!--           </form>-->
                 <!--       <div id="subscriber_resp"></div>-->
                 <!--       </div>-->
	                <!--</div>-->
	                <div class="row fram-row">
	                    <div class="col-md-4" style="margin-top: 17px;margin-bottom: 17px;">
	                        <div class="row text-center" style="justify-content: center;">
	                            
                                <img alt="" src="{{ asset('theme/images/Frame.png') }}">
                                <br>
                                <span class="service-span-landing">Cheap Airport <br> Parking</span>
                            </div>
	                    </div>
	                    <div class="col-md-4" style="margin-top: 17px;margin-bottom: 17px;">
	                        <div class="row text-center" style="justify-content: center;">
                                <img alt="" src="{{ asset('theme/images/Frame (1).png') }}">
                                <br>
                                <span class="service-span-landing">Never Beaten <br>on Price</span>
                            </div>
	                    </div>
	                    <div class="col-md-4" style="margin-top: 17px;margin-bottom: 17px;">
	                        <div class="row text-center" style="justify-content: center;">
                                <img alt="" src="{{ asset('theme/images/Frame (2).png') }}">
                                <br>
                                <span class="service-span-landing">Best Deals on <br>Internet</span>
                            </div>
	                    </div>
	                    <div class="col-md-4" style="margin-top: 17px;margin-bottom: 17px;">
	                        <div class="row text-center" style="justify-content: center;">
                                <img alt="" src="{{ asset('theme/images/Frame (3).png') }}">
                                <br>
                                <span class="service-span-landing">Safe and <br>Secure</span>
                            </div>
	                    </div>
	                    <div class="col-md-4" style="margin-top: 17px;margin-bottom: 17px;">
	                        <div class="row text-center" style="justify-content: center;">
                                <img alt="" src="{{ asset('theme/images/Frame (4).png') }}">
                                <br>
                                <span class="service-span-landing">Trusted Service <br> Providers   </span>
                            </div>
	                    </div>
	                    <div class="col-md-4" style="margin-top: 17px;margin-bottom: 17px;">
	                        <div class="row text-center" style="justify-content: center;">
                                <img alt="" src="{{ asset('theme/images/Frame (5).png') }}">
                                <br>
                                <span class="service-span-landing">User <br>Friendly</span>
                            </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-lg-5 col-md-12">
	                <div class="search-landing">
	                    <!--<ul class="destop-nav-ul-1 hidden-sm hidden-xs">-->
	                        
	                    <!--    <li>-->
	                    <!--        <a href="{{ route('faqs') }}">FAQs</a>-->
	                    <!--    </li>-->
	                    <!--    <li>-->
	                    <!--        <a href="{{ url('about-us') }}">About Us</a>-->
	                    <!--    </li>-->
	                    <!--    <li>-->
	                    <!--        <a href="{{ route('support') }}">Customer Support</a>-->
	                    <!--    </li>-->
	                    <!--    <li>-->
	                    <!--        <a class="destop-model-btn-landing" data-toggle="modal" data-target="#myModal">Manage Booking</a>-->
	                    <!--    </li>-->
	                    <!--</ul>-->
	                    <!--<br>-->
	                     @include('layouts.compains_parking_form_inner')
	                </div>
	               
	            </div>
	        </div>
	    </div>
	</section>
	<div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header manage-booking-modal-header">
                    <button type="button" class="close manage-close-modal-header" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title manage-title-modal-header">Manage Bookings</h4>
                </div>
                <div class="modal-body">
                    @if (!$errors->isEmpty())
                   <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form id="js_contact-form" action="{{ route("booking_search") }}" class="contact-form" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label style="color:#000;">Booking Reference No.<span class="required-field">*</span></label>
                                    <input type="text" class="form-control manage-booking-input" id="ref_no" name="ref_no" placeholder="MG-XXXXXXXXX" required="" value="{{ Request::old("ref_no") }}" autofocus="">
                                </div>
                            </div>
                            <div class="col-12 col-md-12 mt-3">
                                <div class="form-group">
                                    <label style="color:#000;">Last Name <span class="required-field">*</span></label>
                                    <input type="text" class="form-control manage-booking-input" id="last_name" name="last_name" placeholder="Last Name" required="" value="{{ Request::old("last_name") }}" autofocus="">
                                </div>
                            </div>
                            <div class="col-12 col-md-12 mt-3">
                                <div class="form-group">
                                    <label style="color:#000;">Email Address <span class="required-field">*</span></label>
                                    <input type="text" class="form-control manage-booking-input" id="email" name="email" placeholder="Email" required="" value="{{ Request::old("email") }}" autofocus="">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="submit" class="btn manage-booking-button"> Manage Bookings </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>

<section class="our-services" style="padding: 35px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12 second-col">
                <h2 class="second-h2">Why Choose JET SEEKER?</h2>
                <p class="second-p">We're not just another parking comparison site. We're your trusted travel partners committed to enhancing your travel experience. Here, you'll find competitive prices, a user-friendly platform, and a steadfast commitment to superior customer service. We believe in making airport parking comparisons as straightforward and hassle-free as possible.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-1 ">
            </div>
            <div class="col-6 col-sm-4 col-md-2 " style="margin-bottom: 20px;">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <img alt="" src="{{ asset('theme/images/Vector (1).png') }}">
                        </div>
                        <div class="col-12 mt-15" style="min-height: 52px;">
                            <span class="service-span-landing">Best Deals On The Market</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-md-2 " style="margin-bottom: 20px;">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <img alt="" src="{{ asset('theme/images/Vector (2).png') }}">
                        </div>
                        <div class="col-12 mt-15" style="min-height: 52px;">
                            <span class="service-span-landing">User Friendly</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-md-2 " style="margin-bottom: 20px;">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{ asset('theme/images/Vector (3).png') }}">
                        </div>
                        <div class="col-12 mt-15" style="min-height: 52px;">
                            <span class="service-span-landing">Transparent</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-md-2 " style="margin-bottom: 20px;">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{ asset('theme/images/Group 4.png') }}">
                        </div>
                        <div class="col-12 mt-15" style="min-height: 52px;">
                            <span class="service-span-landing">Competitive Prices</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-2 " style="margin-bottom: 20px;">
                <div class="text-center">
                    <div class="row">
                        <div class="col-12">
                            <img src="{{ asset('theme/images/Vector (4).png') }}">
                        </div>
                        <div class="col-12 mt-15" style="min-height: 52px;">
                            <span class="service-span-landing">Trusted Service Providers   </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-1 ">
            </div>
        </div>
    </div>
</section>

<section class="our-services">
    <div class="container">
        <div class="row">
            <div class="col-12 second-col">
                <h2 class="second-h2">Our Services</h2>
            </div>
        </div>
        <div class="row services-card-row" style="">
            <div class="col-lg-3">
                <div class=" services-card text-center">
                    <h4 class="services-card-h4">Park & Ride</h4>
                    <p style="color:black">Park & Ride is the most efficient parking choice. With a short 5 to 10-minute shuttle transfer, reach the airport terminal from the secure parking facility. Park and Ride offer state-of-the-art security with effective customer service.</p>
                    <div style="margin-top: 20px;"><a href="#top" class="top-to-search">Book Now</a></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class=" services-card text-center">
                    <h4 class="services-card-h4">Meet & Greet</h4>
                    <p style="color:black">It is the fastest and most convenient solution for parking your car. Just drive to the terminal, meet the driver, grab your luggage, and head towards the plane. On return, call the driver; he will be waiting with your car at the terminal.</p>
                    <div style="margin-top: 20px;"><a href="#top" class="top-to-search">Book Now</a></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class=" services-card text-center">
                    <h4 class="services-card-h4">On Airport</h4>
                    <p style="color:black">On-Site is the parking nearest the airport, usually within the premises. On-site features long-stay car parking, involving free shuttle service. It also features short-stay parking, generally at walking distance from the terminal.</p>
                    <div style="margin-top: 20px;"><a href="#top" class="top-to-search">Book Now</a></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section style="background: #F1F1E8;padding: 47px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-4">
                            
                            <div class="rev-21">
                                <img alt="" src="{{ asset('theme/images/Frame 40030.png') }}" class="img-icon-done hidden-xs">
                                <div style="margin-left: 20px;text-align: left;">
                                    <span class="rev-1">30k+</span>
                                    <br>
                                    <span style="font-size: 16px;color: #000;">Car Parked</span>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rev-21">
                                <img alt="" src="{{ asset('theme/images/Frame 40031.png') }}" class="img-icon-done hidden-xs">
                                <div style="margin-left: 20px;text-align: left;">
                                    <span class="rev-1">9k+</span>
                                    <br>
                                    <span style="font-size: 16px;color: #000;">Award Winning</span>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rev-21">
                                <img alt="" src="{{ asset('theme/images/Frame 40032.png') }}" class="img-icon-done hidden-xs">
                                <div style="margin-left: 20px;text-align: left;">
                                    <span class="rev-1">15k+</span>
                                    <br>
                                    <span style="font-size: 16px;color: #000;">Happy Clients</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="our-services">
        <div class="container">
            <div class="row">
                <div class="col-12 second-col">
                    <h2 class="second-h2">Choosing the Right Parking</h2>
                </div>
            </div>
            <div class="row services-card-row" style="">
                <div class="col-md-6">
                    <p style="color: black;font-size: 16px;line-height: 2;">JET SEEKER emerges as your trusty companion, offering a seamless way to compare and choose the best airport parking options. We simplify your search for the ideal parking solution, whether you're looking for park-and-ride services, the convenience of Meet and Greet, or the security of Official Airport Parking.</p>
                    <ul class="body-ul" style="color: black;font-size: 16px;line-height: 2;margin-left: 17px;">
                        <li>
                            Know your parking needs.
                        </li>
                        <li>
                            Understand parking types.
                        </li>
                        <li>
                            Parking location & Transfer time.
                        </li>
                        <li>
                            Compare prices for various parking options.
                        </li>
                        <li>
                            Secure payment and booking process.
                        </li>
                        <li>
                            Easily accessible customer support
                        </li>
                        <li>
                            Additional services e.g car wash, EV charging.
                        </li>
                    </ul>
                    <div style="margin-top: 20px;"><a href="#top" class="top-to-search">Book Now</a></div>
                </div>
                <div class="col-md-4">
                    <img alt="" src="{{ asset('theme/images/car-park 1.png') }}" class="img-reponsive hidden-xs" style="height: 445px;width: 336px;">
                </div>
            </div>
        </div>
    </section>
    <section style="padding: 38px 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 second-col">
                    <h2 class="second-h2">Our Happy Clients</h2>
                </div>
            </div>
            <div class="row parking-row" style="justify-content: center;">
                <div class="col-md-9 col-sm-12">
                    <div id="testimonial-slider" class="owl-carousel">
                        @foreach($reviews as $review)
                        
                        
                        <div class="review">
                            <span class="star-rating" >
                			<ul class="list-inline" style="display: contents;">
                				<li class="list-inline-item" style="margin: 14px 0px;"><i class="fa fa-star"></i></li>
                				<li class="list-inline-item" style="margin: 14px 0px;"><i class="fa fa-star"></i></li>
                				<li class="list-inline-item" style="margin: 14px 0px;"><i class="fa fa-star"></i></li>
                				<li class="list-inline-item" style="margin: 14px 0px;"><i class="fa fa-star"></i></li>
                				<li class="list-inline-item" style="margin: 14px 0px;"><i class="fa fa-star"></i></li>
                				<li class="list-inline-item" style="margin: 14px 0px;"><i class="fa fa-star-o"></i></li>
                			</ul>
                		</span>
                            <!--<div class="pic">-->
                            <!--    <img src="images/img-1.jpg">-->
                            <!--</div>-->
                            <p style="font-size: 18px;">
                                {!! $review->review !!}
                            </p>
                            <div class="title">
                                <span class="review-span-name">{{$review->username}}</span>
                                
                    		</div>
                            
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    
	@if(isset($site_settings_main["site_footer_analytics"]))
    
    {!!   $site_settings_main["site_footer_analytics"] !!}
    @endif
<section id="footer" style="background: #4c256ad6;padding-top: 30px;">
		<div class="clearfix"></div>
		<div class="container">
			<div class="row pd-t15">
				
				<div class="col-md-12">
					<div class="row">
						<div class="col-12 col-sm-12 col-lg-6 col-md-12 header-full-title">
						<p class="text-light12">
                        JET SEEKER provides its clients with a seamless end-to-end airport experience. From the car parking, including cheap Stansted short stay airport parking, to the transfer, we have covered everything for our customers. Don't forget to use your Stansted parking discount code for extra savings.
						</p>
						    <h4 class="text-white footer-h4-tag hidden-sm hidden-xs" style="font-weight: 600;">Helpline</h4>
							<ul class="widget-list list-type-none hidden-sm hidden-xs">
								<li class="li-footer" style="color:white">(Mon- Fri 9AM -5PM)</li>
								<li class="li-footer"><a href='tel:{{ $site_settings_main['footer_phone_no'] }}' style="display: flex;color:white;"> <p style="color:white;width: 100%;word-wrap: break-word;"> {{ $site_settings_main['footer_phone_no'] }}</p></a></li>
							</ul>
							<h4 class="text-white footer-h4-tag hidden-sm hidden-xs" style="font-weight: 600;">Email</h4>
						    <a class="hidden-sm hidden-xs" href='mailto:{{ $site_settings_main['footer_email'] }}' style="display: flex;color:white;"><i class="fas fa-envelope" style="font-size: 15px;margin-top: 4px;"></i> &nbsp; <p style="color:white;width: 100%;word-wrap: break-word;"> {{ $site_settings_main['footer_email'] }}</p></a>
						    <h4 class="text-white footer-h4-tag hidden-sm hidden-xs" style="font-weight: 600;">Socials</h4>
						    <ul class="list-inline list-inline1 hidden-sm hidden-xs" style="margin-top: 16px;display: inline-flex;">
                				    @if(array_key_exists("facebook",$site_settings_main) && $site_settings_main["facebook"] !="" && $site_settings_main["facebook_status"] =="active")
                                	<li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["facebook"]}}'><i class=" fab fa-facebook-f" style="background: #1A1A1A;color: white;margin-right: 10px;font-size: 14px;padding: 9px 17px;border-radius: 4px;"></i></a> </li>
                                    @endif
                    				@if(array_key_exists("twitter",$site_settings_main) && $site_settings_main["twitter"] !="" && $site_settings_main["twitter_status"] =="active")
                                    <li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["twitter"]}}'><i class="icon-top fab fa-twitter" style="background: #C2185B;color: white;margin-right: 10px;font-size: 14px;padding: 9px 14px;border-radius: 4px;"></i></a></li>
                                    @endif
                                    @if(array_key_exists("instagram",$site_settings_main) && $site_settings_main["instagram"] !="" && $site_settings_main["instagram_status"] =="active")
                                    <li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["instagram"]}}'><i class="icon-top fab fa-instagram" style="background: #1A1A1A;color: white;margin-right: 10px;font-size: 14px;padding: 9px 15px;border-radius: 4px;"></i></a></li>
                                    @endif
                                    @if(array_key_exists("linkedin",$site_settings_main) && $site_settings_main["linkedin"] !="" && $site_settings_main["linkedin_status"] =="active")
                                    <li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["linkedin"]}}'><i class="icon-top fab fa-linkedin"  style="background: #C2185B;color: white;margin-right: 10px;font-size: 14px;padding: 9px 15px;border-radius: 4px;"></i></a></li>
                                    @endif
                                    @if(array_key_exists("youtube",$site_settings_main) && $site_settings_main["youtube"] !="" && $site_settings_main["youtube_status"] =="active")
                                    <li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["youtube"]}}'><i class="icon-top fab fa-youtube" style="background: #bb0000;color: white;margin-right: 10px;font-size: 14px;padding: 9px 15px;border-radius: 4px;"></i></a></li>
                                    @endif
                                    @if(array_key_exists("google_plus",$site_settings_main) && $site_settings_main["google_plus"] !="" && $site_settings_main["google_plus_status"] =="active" )
                                    <li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["google_plus"]}}'><i class="icon-top fab fa-google_plus" style="background: #dd4b39;color: white;margin-right: 10px;font-size: 14px;padding: 9px 15px;border-radius: 4px;"></i></a></li>
                                    @endif
                                    @if(array_key_exists("pinterest",$site_settings_main) && $site_settings_main["pinterest"] !="" && $site_settings_main["pinterest_status"] =="active")
                                    <li class="border-right"><a class="black-font"  target="_blank" href='{{$site_settings_main["pinterest"]}}'><i class="icon-top fab fa-pinterest" style="background: #CB1F27;color: white;margin-right: 10px;font-size: 14px;padding: 9px 15px;border-radius: 4px;"></i></a></li>
                                    @endif
                				</ul>
						    
						</div>
						<div class="col-12 col-sm-12 col-lg-1 col-md-12 account">
						</div>
						<div class="col-12 col-sm-12 col-md-12 col-lg-4 account">
						    <div class="row">
						        <div class="col-6">
						            <h4 class="text-white footer-h4-tag" style="font-weight: 600;">Quick links</h4>
    								<ul class="widget-list list-type-none">
    									<li class="li-footer"><a href="{{ url('/') }}" class="text-light1">Home</a></li>
    									<li class="li-footer"><a href="{{ url('about-us') }}" class="text-light1">About Us</a></li>
    									<li class="li-footer"><a href="{{ url('support') }}" class="text-light1">Support</a></li>
    									<!--<li><a href="javascript:void(0);" class="text-light">Blog</a></li>-->
    									<li class="li-footer"><a href="{{ url('faqs') }}" class="text-light1">FAQs</a></li>
    									<li class="li-footer"><a href="{{ url('terms-and-conditions') }}" class="text-light1">Terms & Conditions</a></li>
    									<li class="li-footer"><a href="{{ url('privacy-policy') }}" class="text-light1">Privacy Policy</a></li>
    									<!--<li class="li-footer"><a href="{{ url('cookies') }}" class="text-light1">Cookies</a></li>-->
    									<li class="li-footer"><a href="{{ url('affiliates') }}" class="text-light1">Become An Affiliate</a></li>
    								</ul>
						        </div>
						        <div class="col-6">
						            <h4 class="text-white footer-h4-tag" style="font-weight: 600;">Airport Parking</h4>
        							<ul class="text-light" style="list-style:none;">
        							    <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"gatwick-airport-parking"]) }}'>Gatwick Airport</a></li>
                                        <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"heathrow-airport-parking"]) }}'>Heathrow Airport</a></li>
                                        <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"stansted-airport-parking"]) }}'>Stansted Airport</a></li>
                                        <li class="li-footer"><a class="text-light1" href='{{ route("page",["slug"=>"birmingham-airport-parking"]) }}'>Birmingham Airport</a></li>
                                        <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"edinburgh-airport-parking"]) }}'>Edinburgh Airport</a></li>
                                        <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"southampton-airport-parking"]) }}'>Southampton Airport</a></li>
                                        <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"luton-airport-parking"]) }}'>Luton Airport</a></li>
                                        <li class="li-footer"><a class="text-light1"  href='{{ route("page",["slug"=>"manchester-airport-parking"]) }}'>Manchester Airport</a></li>
        							</ul>
						        </div>
						        <!--<div class="col-lg-12 hidden-sm hidden-xs">-->
						        <!--    <h4 class="text-white footer-h4-tag" style="font-weight: 600;margin-bottom: 7px;">Subscribe Our Newsletter</h4>-->
        						<!--	<div class="subscribe-div ">-->
              <!--                          <div class="row">-->
              <!--                              <div class="col-lg-12">-->
              <!--                                      <form id="subscribe_user" action='javascript:;' method="post">-->
              <!--                                          @csrf-->
              <!--                                          <input type="email" id="subscribe_user_email" class="form-control subscribe_input subscribe_input_footer"-->
              <!--                                              required placeholder="Enter your email">-->
              <!--                                          <button class="btn subscriber-btn subscriber-btn-footer" type="submit" id="button-addon2">Subscribe</button>-->
              <!--                                      </form>-->
              <!--                                  <div id="subscriber_resp"></div>-->
              <!--                              </div>-->
              <!--                          </div>-->
              <!--                      </div>-->
						        <!--</div>-->
						      <!--  <div class="col-lg-12">-->
						      <!--      <h4 class="text-white footer-h4-tag" style="font-weight: 600;margin-bottom: 7px;margin-top: 10px;">We Accept</h4>-->
        				<!--			<ul class="list-inline list-inline2 text-center">-->
    								<!--	<li>-->
    								<!--	    <div class="tooltip"><img class="img-responsive ficon-img" src="{{ asset('theme/images/mgr-vise.png') }}" loading="lazy">-->
            <!--                                  <span class="tooltiptext">Visa Card</span>-->
            <!--                                </div>-->
    								<!--	</li>-->
    								<!--	<li>-->
    								<!--	    <div class="tooltip"><img class="img-responsive ficon-img" src="{{ asset('theme/images/mgr-mastro.png') }}" loading="lazy">-->
            <!--                                  <span class="tooltiptext">Maestro Card</span>-->
            <!--                                </div>-->
            <!--                            </li>-->
    								<!--	<li>-->
    								<!--	    <div class="tooltip"><img class="img-responsive ficon-img" src="{{ asset('theme/images/mgr-master.png') }}" loading="lazy">-->
            <!--                                  <span class="tooltiptext">Maestro Card</span>-->
            <!--                                </div>-->
    								<!--	</li>-->
    								<!--	<li>-->
    								<!--	     <div class="tooltip"><img class="img-responsive ficon-img" src="{{ asset('theme/images/mgr-jcb.png') }}" loading="lazy">-->
            <!--                                  <span class="tooltiptext">Japan Credit Bureau</span>-->
            <!--                                </div>-->
    								<!--	</li>-->
    								<!--</ul>-->
						      <!--  </div>-->
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="text-white" style="border-top: 1px solid;background: #4c256ad6;">
		
		<div class="container">
			<div class="row flex-display">
				<div class="col-12 text-center">
					<p class="text-light12" style="margin-top: 1rem;">&copy; JET SEEKER. All Rights Reserved. Registration No. {{$site_settings_main["footer_company_reg_no"]}}</p>
				</div>
			</div>
			
		</div>
	</section>
	<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/responsive.css') }}">


<script async src="{{ asset('theme/js/jquery-3.2.1.min.js') }}"></script>
<script defer src="{{ asset('theme/styles/bootstrap4/popper.js') }}"></script>
<script defer src="{{ asset('theme/styles/bootstrap4/bootstrap.min.js') }}"></script>
<script async src="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('theme/plugins/easing/easing.js') }}"></script>
<script async src="{{ asset('theme/js/custom.js') }}"></script>
<script async src="{{ asset('assets/front/js/bootstrap-datepicker.js') }}"></script>
<script async src="{{ asset('assets/front/js/custom-date-picker.js') }}"></script>


<!--script type='text/javascript'>
window.__lo_site_id = 248579;

 (function() {
  var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
  wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
 })();
</script-->



<!--<script async type="text/javascript" src="{{ asset('assets/front/parkingzone/js/trx_addons.js') }}"></script>-->


<script src="{{ asset('theme/tinyCalender/index.js') }}"></script>
@if (substr(strrchr(url()->current(), '/'), 1) != 'result')
    <script>
        var startdate = new Date();
        startdate.setDate(startdate.getDate() + 1);

        var enddate = new Date();
        enddate.setDate(startdate.getDate() + 8);

        var startDateInput = document.getElementById('startDate');
        var endDateInput = document.getElementById('endDate');
        var form = document.getElementById('search_form_1'); // replace 'yourFormId' with your actual form ID

        var picker = new TinyPicker({
            format: 'dd-mm-yyyy',
            firstBox: startDateInput,
            lastBox: endDateInput,
            startDate: startdate,
            endDate: enddate,
            allowPast: false,
            useCache: true,
            orientation: 'top auto',
            horizontal: 'auto',
            vertical: 'auto',
        });

        // Event listener for the departure date input
        startDateInput.addEventListener('change', function() {
            var newStartDate = new Date(startDateInput.value);

            if (!isNaN(newStartDate.getTime())) {
                // If a valid date is selected, calculate the new end date
                var newEndDate = new Date(newStartDate);
                newEndDate.setDate(newStartDate.getDate() + 8);

                // Update the end date in the picker
                picker.setEndDate(newEndDate);
            } else {
                // If the input is empty or not a valid date, you may choose to clear the end date
                picker.setEndDate(null);
            }
        });

        // Event listener for the form submission
        form.addEventListener('submit', function(event) {
            if (!endDateInput.value) {
                // If the arrival date is not selected, prevent the default form submission
                event.preventDefault();
                // Display a custom message or handle it in your preferred way
                alert('Please select Pick UP date.');
            }
        });

        // Initialize the TinyPicker
        picker.init();
    </script>
@else
    @php

        $dropdate = str_replace('/', '-', request()->dropoffdate);
        $pickdate = str_replace('/', '-', request()->departure_date);

        $dropofdate = date('m/d/Y', strtotime($dropdate));
        $pickupdate = date('m/d/Y', strtotime($pickdate));

    @endphp
    <script>
        $(document).ajaxStop(function() {
            //var dropDate = '{{ request()->dropoffdate }}';
            //var departureDate = '{{ request()->departure_date }}';


            var dropDate = '{{ $dropofdate }}';
            var departureDate = '{{ $pickupdate }}';


            var enddate = new Date(departureDate);
            // enddate.setDate(enddate);
            new TinyPicker({
                firstBox: document.getElementById(
                    'startDate'), // Required -- Overrides us finding the first input box
                lastBox: document.getElementById(
                    'endDate'), // Required -- Overrides us finding the last input box
                startDate: new Date(dropDate), // Needs to be a valid instance of Date
                endDate: enddate, // Needs to be a valid instance of Date
                allowPast: false, // If you want the user to be able to select past dates
                useCache: true,
                orientation: "top auto",
                horizontal: 'auto',
                success: function(startDate, endDate) {}, // callback function when user inputs dates,
                vertical: 'auto',

            }).init();
        });
    </script>
@endif

<script async type="text/javascript">
    $(document).ready(function() {



        // process the form
        $('#subscribe_user').submit(function(event) {

            var formData = {
                'name': $('#subscribe_user_name').val(),
                'email': $('#subscribe_user_email').val(),
                '_token': '{{ @csrf_token() }}'
            };

            // process the form
            $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '{{ route('subscribe_user') }}', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true
                })
                // using the done promise callback
                .done(function(data) {


                    // log data to the console so we can see
                    console.log(data);
                    if (data.success == 0) {
                        if (data.errors == 'validation.unique') {


                            $("#modal-text").html('This email already subscribed');
                            $("#modal-text").css("color", "red");
                            $('#myModal').modal('show');
                        } else {

                            $("#modal-text").html(data.errors);
                            $("#modal-text").css("color", "red");
                            $('#myModal').modal('show');
                        }
                        //  $("#error_message").html(data.errors);
                        // $("#error_message").css("color", "red");
                    } else {
                        $("#modal-text").html(data.data);
                        // alert(data.data);
                        $('#myModal').modal('show');
                    }

                    // here we will handle errors and validation messages
                });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });
</script>

<script async type="text/javascript">
    $(".accordion-toggle").on('click', function(e) {
        e.preventDefault();
        $($(this).attr("href")).toggleClass('collapse');
        var condition = false;
        if ($($(this).attr("href")).attr("aria-expanded") == false) {
            condition = true;
        }
    });

    // $(function() {
    //     $("#startDate").datepicker({
    //         numberOfMonths: 1
    //     });
    // });
    $(document).mouseleave(function() {
        console.log('out');
    });
</script>

@section('footer-script')

@show


<!-- paid on result -->
<script language="JavaScript" src="//porjs.com/1747.js"></script>

<script>
    jQuery.event.special.touchstart = {
        setup: function(_, ns, handle) {
            this.addEventListener("touchstart", handle, {
                passive: !ns.includes("noPreventDefault")
            });
        }
    };
    jQuery.event.special.touchmove = {
        setup: function(_, ns, handle) {
            this.addEventListener("touchmove", handle, {
                passive: !ns.includes("noPreventDefault")
            });
        }
    };
    jQuery.event.special.wheel = {
        setup: function(_, ns, handle) {
            this.addEventListener("wheel", handle, {
                passive: true
            });
        }
    };
    jQuery.event.special.mousewheel = {
        setup: function(_, ns, handle) {
            this.addEventListener("mousewheel", handle, {
                passive: true
            });
        }
    };
</script>
<script>
    $(document).ready(function() {
        // 	$('#search-box').on('keyup', function () {
        // 		var given_name = $(this).val();
        // 		$.ajax({
        // 			url: '{{ url('get_location_suggestion') }}',
        // 			type: 'POST',
        // 			dataType: 'html',
        // 			data:'keyword='+given_name
        // 		}).success(function (data) {
        // 		   $("#suggesstion-box").show();
        // 			$("#suggesstion-box").html(data);
        // 			$("#search-box").css("background","transparent");
        // 		});
        // 	})
        $("#search-box").keyup(function(e) {
            setTimeout(getSuggestion($(this).val()), 500);
        });

        function getSuggestion(keyword) {
            $("#loc_type").val('');
            $("#loc_code").val('');
            $("#loc_name").val('');
            $("#loc_lat").val('');
            $("#loc_long").val('');
            $("#loc_country").val('');
            $("#loc_id").val('');

            var formData = {
                'keyword': keyword
            };
            $.ajax({
                type: "POST",
                url: '{{ url('get_location_suggestion') }}',
                data: formData,
                beforeSend: function() {
                    $("#search-box").css("background",
                        "#FFF url({{ asset('theme/images/placeholder.svg') }}) no-repeat 110px"
                    );
                },
                success: function(data) {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                }
            });
        }

        $("#search-box-dropoff").keyup(function(e) {
            setTimeout(getSuggestionDropoff($(this).val()), 500);
        });

        function getSuggestionDropoff(keyword) {
            $("#loc_type_drop").val('');
            $("#loc_code_drop").val('');
            $("#loc_name_drop").val('');
            $("#loc_lat_drop").val('');
            $("#loc_long_drop").val('');
            $("#loc_country_drop").val('');
            $("#loc_id_drop").val('');

            var formData = {
                'keyword': keyword,
                'loc_type': $("#loc_type").val()
            };
            $.ajax({
                type: "POST",
                url: '{{ url('get_location_suggestion_drop') }}',
                data: formData,
                beforeSend: function() {
                    $("#search-box-drop").css("background",
                        "#FFF url({{ asset('theme/images/placeholder.svg') }}) no-repeat 110px"
                    );
                },
                success: function(data) {
                    $("#suggesstion-box-drop").show();
                    $("#suggesstion-box-drop").html(data);
                    $("#search-box-drop").css("background", "#FFF");
                }
            });
        }
    });

    function selectRegion(loc_type, loc_name, loc_code, loc_lat, loc_long, loc_country, loc_id) {
        $("#search-box").val(loc_name);
        $("#loc_type").val(loc_type);
        $("#loc_code").val(loc_code);
        $("#loc_name").val(loc_name);
        $("#loc_lat").val(loc_lat);
        $("#loc_long").val(loc_long);
        $("#loc_country").val(loc_country);
        $("#loc_id").val(loc_id);

        $("#suggesstion-box").hide();
    }

    function selectHotel(loc_type, loc_name, loc_code, loc_lat, loc_long) {
        $("#search-box").val(loc_name);
        $("#loc_type").val(loc_type);
        $("#loc_code").val(loc_code);
        $("#loc_name").val(loc_name);
        $("#loc_lat").val(loc_lat);
        $("#loc_long").val(loc_long);
        $("#loc_country").val('');
        $("#loc_id").val('');

        $("#suggesstion-box").hide();
    }

    function selectRegionDrop(loc_type, loc_name, loc_code, loc_lat, loc_long, loc_country, loc_id) {
        $("#search-box-dropoff").val(loc_name);
        $("#loc_type_drop").val(loc_type);
        $("#loc_code_drop").val(loc_code);
        $("#loc_name_drop").val(loc_name);
        $("#loc_lat_drop").val(loc_lat);
        $("#loc_long_drop").val(loc_long);
        $("#loc_country_drop").val(loc_country);
        $("#loc_id_drop").val(loc_id);

        $("#suggesstion-box-drop").hide();
    }

    function selectHotelDrop(loc_type, loc_name, loc_code, loc_lat, loc_long) {
        $("#search-box-dropoff").val(loc_name);
        $("#loc_type_drop").val(loc_type);
        $("#loc_code_drop").val(loc_code);
        $("#loc_name_drop").val(loc_name);
        $("#loc_lat_drop").val(loc_lat);
        $("#loc_long_drop").val(loc_long);
        $("#loc_country_drop").val('');
        $("#loc_id_drop").val('');

        $("#suggesstion-box-drop").hide();
    }
</script>
	<style>
	    
	.li-footer{padding: 3px 0;}
	.text-light1{font-size: 14px;text-decoration: underline !important;}
	.footer-h4-tag{
	    margin: 0;
	    font-size: 17px;
	}
	 .new_footerdesign{
        margin-left: 15%;
    }
    @media  screen and (max-width: 767px){
        .new_footerdesign{
            margin-left: 2%;
        }
    }
    .busniues{
        font-size: 14px;
        font-weight: 600;
    }
    .li-footer a:hover {
        color: #fff !important;
        text-decoration: none;
        cursor: pointer;
    }
    .social .fab{
        color: #fff;
    }
    .text-light12{
        color: white;
    }
   .account li a:hover {
        outline: none;
        text-decoration: none;
        color: #fff;
    }
    #footer a:hover, a:focus {
        outline: none !important;
        text-decoration: none !important;
        color: #fff !important;
    }
    .navbar-brand{float: initial !important;}
    @media screen and (min-device-width: 279px) and (max-device-width: 418px){
    .list-inline1>li {
         width: 11% !important; 
    } 
    .list-inline2>li {
         width: auto !important; 
    } 
    }
    .list-inline{
        padding-left: 0 !important;
    }
    .ar-brand {max-width: 78% !important;}
    @media screen and (min-device-width: 458px) and (max-device-width: 991px){
    .ar-brand {max-width: 50% !important;} 
    }
    
    
    .tooltip {
      position: relative;
      display: inline-block;
      border-bottom: 1px dotted black;
      opacity: 1 !important;
    }
    
    .tooltip .tooltiptext {
      visibility: hidden;
      width: 120px;
      background-color: black;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 5px 0;
      position: absolute;
      z-index: 1;
      top: 100%;
      left: 50%;
      margin-left: -60px;
      margin-top: 5px;
    }
    
    .tooltip:hover .tooltiptext {
      visibility: visible;
    }
	#testimonial-slider.owl-theme .owl-pagination{float: none;}
	#testimonial-slider.owl-theme .owl-controls .owl-page span{background: black !important;}
		#testimonial-slider{text-align: center; color: black;background: #F1F1E8;}
		.review-span-name{color: #000;font-weight: 500 !important;}
        .owl-theme .owl-controls{ margin-top: 0; }
        .owl-theme .owl-controls .owl-page span{
            background: #C2185B;
            opacity: 0.4;
            transition: all 0.3s ease 0s;
        }
        .owl-theme .owl-controls .owl-page.active span{ background: #C2185B; }
        .row-cen-jus{
            justify-content: center;
            display: flex;
        }
        .description p{
            font-size: 16px;
        }
        
        .description::after{
            width: 0;
            height: 0;
            border: 10px solid #C2185B;
            border-bottom-color: #fff;
            border-left-color: #fff;
        }
        .description::before, .description::after {
            content: "";
            display: inline-block;
            position: absolute;
            left: 19px;
            bottom: 82px;
        }
        .list-inline{
            padding-left: 46px;
        }
        .list-inline>li{width:auto !important;}
	</style>
	

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
 <script>
     $(document).ready(function(){
    $("#testimonial-slider").owlCarousel({
        items:1,
        itemsDesktop:[1000,1],
        itemsDesktopSmall:[979,1],
        itemsTablet:[768,1],
        pagination:true,
        transitionStyle:"backSlide",
        // autoPlay:true
    });
});
 </script>
</body>
	
</html>
