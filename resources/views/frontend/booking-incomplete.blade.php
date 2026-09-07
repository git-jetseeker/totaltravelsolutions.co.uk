@extends('layouts.main')

@include('layouts.header')
@include('layouts.nav')

@section('stylesheets')
    <link property="stylesheet" rel='stylesheet' href='{{ asset('assets/page.css') }}' type='text/css' media='all' />
    <link rel="stylesheet" href='{{ asset('assets/payzone/payzone_gateway.css?v=1.2') }}' />
@endsection

@section('content')
    <style>
        /* Premium Layout - Matching Homepage Theme */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Poppins', Sans-serif;
            background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
        }

        #footer {
            display: none;
        }

        .navbar-nav {
            display: none;
        }

        .navbar-toggle {
            display: none;
        }

        .paybutton {
            display: none;
        }

        /* Premium Hero Banner */
        .sec-bac {
            height: 250px;
            background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(49,18,75,0.8)), url(./theme-new/images/result-banne.webp);
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
        }

        .sec-bac::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }

        .searc {
            margin-top: 0 !important;
            position: relative;
            z-index: 2;
        }

        /* Premium Card Styling */
        .payment-section {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .bgwhite {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }

        .booking-card {
            background: white;
        }

        .room-text {
            background: white;
            border-radius: 16px !important;
            border: 2px solid #f0f0f0 !important;
            padding: 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .room-text::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            /* background: linear-gradient(180deg, orange, #ff8c00); */
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .room-text:hover::before {
            opacity: 1;
        }

        .room-text:hover {
            border-color: orange !important;
            box-shadow: 0 8px 25px rgba(247,159,2,0.15);
            transform: translateY(-3px);
        }

        .booking-vehdetal {
            padding: 30px 20px !important;
        }

        /* Premium Headings */
        .speedy-hding {
            color: black;
            font-weight: 700;
            padding-bottom: 15px;
            font-size: 1.5rem !important;
            position: relative;
            padding-left: 15px;
        }

        .speedy-hding::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 4px;
            height: 25px;
            background: linear-gradient(180deg, orange, #ff8c00);
            border-radius: 2px;
        }

        .card-hding {
            color: #000;
            font-weight: 600;
        }

        /* Premium Form Inputs */
        .form-control {
            height: 48px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: orange;
            background: white;
            box-shadow: 0 0 0 3px rgba(247,159,2,0.1);
            outline: none;
        }

        .bf-inptfld, .bf-slctfld {
            transition: all 0.3s ease;
        }

        /* Labels */
        label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Stripe Elements */
        .StripeElement {
            box-sizing: border-box;
            height: 48px;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fafafa;
            box-shadow: none;
            transition: all 0.3s ease;
        }

        .StripeElement--focus {
            border-color: orange;
            background: white;
            box-shadow: 0 0 0 3px rgba(247,159,2,0.1);
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        /* Premium Sidebar */
        .payment {
            border: 2px solid #f0f0f0 !important;
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .payment:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }

        /* Premium Buttons */
        .btn-book, .cnf_booking {
            background: linear-gradient(135deg, orange 0%, #ff8c00 100%) !important;
            border: none !important;
            border-radius: 8px;
            padding: 14px 32px !important;
            font-size: 16px !important;
            font-weight: 700;
            color: white !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(247,159,2,0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-book:hover, .cnf_booking:hover {
            background: black !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        /* Price Display */
        .img-div {
            padding: 15px;
            border-radius: 8px;
            background: #fafafa;
            margin-bottom: 15px;
        }

        .total-hding {
            color: black;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .accept {
            color: black;
            font-weight: 700;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Error Messages */
        .error {
            color: #dc3545;
            font-weight: 600;
        }

        /* HR Styling */
        hr {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, orange, transparent);
            margin: 20px 0;
        }

        /* Checkboxes */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: orange;
        }

        input[type="radio"] {
            accent-color: orange;
            cursor: pointer;
        }

        /* Price Highlight */
        .badge-danger {
            background-color: orange !important;
            padding: 8px 16px;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media only screen and (min-width: 1384px) {
            .row-mlr {
                margin-left: -100px !important;
            }
        }

        @media only screen and (max-width: 991px) {
            .sec-bac {
                height: 200px;
            }

            .bgwhite {
                padding: 30px 20px;
            }

            .room-text {
                padding: 20px;
            }

            .payment-section {
                margin-top: -60px;
            }
        }

        @media only screen and (max-width: 767px) {
            .sec-bac {
                height: 180px;
            }

            .bgwhite {
                padding: 20px 15px;
                border-radius: 12px;
            }

            .room-text {
                padding: 20px 15px;
                margin-top: 20px !important;
            }

            .payment-section {
                margin-top: -40px;
            }

            .speedy-hding {
                font-size: 1.2rem !important;
            }
        }

        .btn-cs:focus {
            outline: none !important;
        }

        iframe {
            width: 100%;
        }

        p {
            color: #555;
            line-height: 1.7;
        }

        .primary-color {
            color: black !important;
        }

        .figure-img {
            margin-bottom: 20px;
        }

        .listing-images {
            object-fit: contain;
            width: 100%;
            height: 100%;
        }

        /* Smooth Fade In Animation */
        .booking-card > div {
            animation: fadeInUp 0.5s ease-out backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Orange accent color throughout */
        .orangeClr {
            color: orange;
        }
    </style>
    <section class="sec-bac" style="background:url(./theme-new/images/result-banne.webp);background-size: cover;">
        <div class="container">
            <div class="row row-mlr">
                <div class="col-lg-12 col-md-12">
                    <!--<p class="searc" style="color: white;margin: 0 0 0px;">Search result for</p>-->
                    @php $airport = \App\Models\airport::find($data["airport"]); @endphp
                    <h1 class="searc"
                        style="font-size: 36px;font-weight: bold;color: white;margin-top: 10px;margin-bottom: 10px;">
                        {{ $airport->name }}</h1>
                    <p style="color: white;margin: 0 0 0px;">Home &nbsp; <i class="fas fa-chevron-right"
                            style="font-size: 11px;"></i> &nbsp; Search result &nbsp; <i class="fas fa-chevron-right"
                            style="font-size: 11px;"></i> &nbsp; Confirm booking</p>
                </div>
            </div>
        </div>
    </section>
    <div class="wrapper bg-layer pd-b15 pd-t15">
        <section class="payment-section ">
            <div class="container bgwhite">
                <div class="row">
                    <div class="col-md-8">
                        
                        <div class="col-sm-12 mb-30">
                            <div class="row booking-card">
                                <div class="col-sm-12 mb15">
                                    <form id="personal_details_form">
                                        <div class="room-list-block">
                                            <div class="row">
                                                <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12 room-text room-text-padding"
                                                    style="border-radius: 30px;border: 1px solid #330a4c;">
                                                    <div class="">
                                                        <h3 class="speedy-hding" style="font-size: 20px;">Email Confirmation
                                                            To</h3>
                                                        
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-6 form-group">
                                                                    <label>Email Address</label>
                                                                   
                                                                    <input email="true" class="form-control bf-inptfld"
                                                                        placeholder="Email" type="text" name="email"
                                                                        id="email" required
                                                                        value="{{ $data['email'] }}">
                                                                </div>

                                                              	
                                                            </div>
                                                        </div><!-- end room-info -->
                                                    </div><!-- end div -->

                                                </div><!-- end columns -->


                                                <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12 room-text room-text-padding"
                                                    style="border-radius: 30px;margin-top: 25px;border: 1px solid #330a4c;margin-top: 25px;">
                                                    <div class="">
                                                        <h3 class="speedy-hding" style="font-size: 20px;">Personal Details
                                                        </h3>
                                                        
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-2 setgenderwidth form-group"
                                                                    style="display:none">
                                                                    <label>Title</label>
                                                                    <select required
                                                                        class=" bf-slctfld bookingselect_height form-control"
                                                                        name="gender" id="title">
                                                                        <option value="Mr">Mr</option>
                                                                        <option value="Mrs">Mrs</option>
                                                                        <option value="Miss">Miss</option>
                                                                        <option value="Ms">Ms</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 form-group">
                                                                    <label>First Name</label>
                                                                    <!--<span class="required-field">*</span>-->
                                                                    <input class="form-control bf-inptfld" required
                                                                        type="text" placeholder="First Name"
                                                                        name="firstname" id="firstname"
                                                                        value="{{ $data['firstname'] }}

">
                                                                </div>
                                                                <div class="col-lg-6 form-group">
                                                                    <label>Last Name</label>
                                                                    <!--<span class="required-field">*</span>-->
                                                                    <input class="form-control bf-inptfld" type="text"
                                                                        placeholder="Last Name" name="lastname" required
                                                                        id="lastname" value="{{ $data['lastname'] }}">
                                                                </div>
                                                                <div class="col-lg-6 form-group">
                                                                    <label>Mobile Number</label>
                                                                    <!--<span class="required-field">*</span>-->
                                                                    <input class="form-control bf-inptfld" type="text"
                                                                        placeholder="Mobile" name="contactno"
                                                                        id="contactno" required
                                                                        value="{{ $data['phone_number'] }}


">
                                                                </div>
                                                            </div>
                                                        </div><!-- end room-info -->
                                                    </div><!-- end div -->
                                                </div><!-- end columns -->
                                            </div>
                                        </div><!-- end room-list-block -->
                                    </form>
                                </div>
                                <hr>
                                <div class="col-sm-12 mb15">
                                    <form id="vechile_detail">
                                        <div class="row">
                                            <div class="col-xs-12  booking-vehdetal col-sm-12  col-md-12  col-lg-12 room-text"
                                                style="border-radius: 30px;margin-top: 25px;border: 1px solid #330a4c;margin-top: 25px;">
                                                <div class="">
                                                    <h3 class="speedy-hding" style="font-size: 20px;">Vehicle Details</h3>
                                                    
                                                    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 "
                                                        style="display:none;">
                                                        <label class="inputdefault">
                                                            <span class="fa fa-info-circle" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="We will set your vehicle details to be confirmed if you select No. You can add these details at a later stage by either logging in to your account or by calling customer services."></span>

                                                            Do you have Vehicle details? &nbsp;&nbsp;
                                                        </label>
                                                        <br class="hidde-md hidden-lg">
                                                        <label for="inputdefault">
                                                            <input class="flightdetailsyes1" name="vehdetails"
                                                                id="yes" type="radio" checked value="Yes" />
                                                            Yes
                                                        </label>
                                                        <label for="inputdefault">
                                                            <input class="flightdetailsyes1" name="vehdetails"
                                                                id="no" type="radio" value="No" /> No
                                                        </label>
                                                        <p class="hidden-md hidden-lg"></p>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col-lg-12 margin15" id="vechile-detail">
                                                        <div class="row">
                                                            <div class="col-lg-6 form-group">
                                                                <label class="normal-font">Vehicle Registration</label>
                                                                <!--<span class="required-field">*</span>-->
                                                                <input class="form-control bf-inptfld" type="text"
                                                                    required name="registration" id="registration"
                                                                    placeholder="Registration Number" value=""/
                                                                    required>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="normal-font">Vehicle Make</label>
                                                                <!--<span class="required-field">*</span>-->
                                                                <input class="form-control bf-inptfld" type="text"
                                                                    required name="make" id="make"
                                                                    placeholder="Make" value=""/ required>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="normal-font">Vehicle Colour</label>
                                                                <!--<span class="required-field">*</span>-->
                                                                <input class="form-control bf-inptfld" type="text"
                                                                    required name="color" id="color"
                                                                    placeholder="Colour" value=""/ required>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="normal-font">Vehicle Model</label>
                                                                <!--<span class="required-field">*</span>-->
                                                                <input class="form-control bf-inptfld" type="text"
                                                                    required name="model" id="model"
                                                                    placeholder="Model" value=""/ required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end room-info -->
                                            </div><!-- end columns -->
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-12 mb15">
                                    <form id="travel_detail">
                                        <div class="room-list-block">
                                            <div class="row">
                                                <div class="col-xs-12 booking-vehdetal  col-sm-12  col-md-12  col-lg-12 room-text"
                                                    style="border-radius: 30px;margin-top: 25px;border: 1px solid #330a4c;margin-top: 25px;">
                                                    <div class="">
                                                        <h3 class="speedy-hding" style="font-size: 20px;">Travel Details
                                                        </h3>
                                                        
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
                                                            <label class="inputdefault">
                                                                <!--<span-->
                                                                <!--    class="fa fa-info-circle cls-pointer"-->
                                                                <!--    data-toggle="tooltip" data-placement="top"-->
                                                                <!--    title="We will set your travel details to be confirmed if you select No. You can add these details at a later stage by either logging in to your account or by calling customer services."></span>-->
                                                                Do you have Travel details?
                                                            </label>&nbsp;&nbsp;
                                                            <br class="hidde-md hidden-lg">
                                                            <label for="inputdefault">
                                                                <input class="flightdetailsyes" name="flightdetails"
                                                                    id="yes" type="radio" checked
                                                                    value="Yes" />
                                                                Yes
                                                            </label>
                                                            <label for="inputdefault">
                                                                <input class="flightdetailsyes" name="flightdetails"
                                                                    id="no" type="radio" value="No" />
                                                                No
                                                            </label>.
                                                            <br class="hidde-md hidden-lg">
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="col-lg-12 margin15" id="travel-detail">
                                                            <div class="row">
                                                                <div class="col-lg-6 margin-travel form-group">
                                                                    <label class="normal-font">Drop-Off Terminal:</label>
                                                                    <!--<span class="required-field"> *</span>-->
                                                                    <select class="form-control bf-slctfld p-0"
                                                                        id="departterminal" name="departterminal">
                                                                        <option value="" selected="">Select
                                                                            Terminal</option>
                                                                        @foreach ($terminals as $terminal)
                                                                            <option value="{{ $terminal->id }}">
                                                                                {{ $terminal->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 form-group " id="return_terminal1">
                                                                    <label class="normal-font">Return Terminal:</label>
                                                                    <!--<span class="required-field"> *</span>-->
                                                                    <select class="form-control bf-slctfld p-0"
                                                                        id="arrivalterminal" name="arrivalterminal">
                                                                        <option value="" selected="">Select
                                                                            Terminal</option>
                                                                        @foreach ($terminals as $terminal)
                                                                            <option value="{{ $terminal->id }}">
                                                                                {{ $terminal->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-12 form-group" id="return_terminal">
                                                                    <label class="normal-font">Return Flight
                                                                        Number:</label>
                                                                    <input type="text" class="form-control bf-inptfld"
                                                                        name="returnflight" id="returnflight"
                                                                        placeholder="Optional" value="" />
                                                                </div>
                                                            </div>
                                                        </div><!-- end room-info -->
                                                    </div><!-- end columns -->
                                                </div><!-- end room-list-block -->
                                            </div>
                                        </div><!-- end room-list-block -->
                                    </form>
                                </div>
                            </div>
                            <div class="col-xs-12 " style="padding-left:0;padding-right:0;">
                                <div class="room-list-block">
                                    <div class="row">
                                        <div class="col-xs-12 booking-vehdetal col-sm-12  col-md-12  col-lg-12 room-text"
                                            style="border-radius: 30px;margin-top: 25px;border: 1px solid #330a4c;margin-top: 25px;">
                                            <div class="">
                                                <h3 class="speedy-hding" style="font-size: 20px;">Payment Detail</h3>
                                                

                                                @if ($settings['payment_type'] == 'stripe')
                                                    <div class="weaccept-marginleft">
                                                        <h4 style="text-align:center;" class="accept">We Accept</h4>
                                                        <img class="img-responsive"
                                                            style="display: block; max-width: 50%; height: auto; margin: auto; box-shadow:none;"
                                                            src="{{ asset('assets/payzone/images/payzone_cards_accepted.png') }}">
                                                    </div>
                                                    <div class="paymentFrm" id="paymentFrm">
                                                        <form method="post">
                                                            {{ csrf_field() }}
                                                            <div id="creditDiv">
                                                                <a class="reset" href="#"></a>
                                                                <div class="col-lg-12 margin15">
                                                                    <div class="col-lg-6" style="display: none;">
                                                                        <label>Card Number </label>
                                                                        <span class="required-field">*</span>
                                                                        <div class="form-control bf-inptfld empty"
                                                                            type="text" id="cc_card_no" name="card_no"
                                                                            style=""></div>
                                                                        <div class="baseline"></div>
                                                                    </div>
                                                                    <div class="col-lg-6" style="display: none;">
                                                                        <label>Card Holder Name </label>
                                                                        <span class="required-field">*</span>
                                                                        <input class="form-control empty" type="text"
                                                                            id="cc_card_title" name="card_title"> </input>
                                                                        <div class="baseline"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 margin15" style="display: none;">
                                                                    <div class="col-lg-6">
                                                                        <label>Expiry Month / Year</label>
                                                                        <span class="required-field">*</span>
                                                                        <div class="form-control empty bf-inptfld"
                                                                            id="expiry_year">Expiration</div>
                                                                        <div class="baseline"></div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <label>Security Code</label>
                                                                        <span class="required-field">*</span>
                                                                        <div class="form-control bf-inptfld empty"
                                                                            type="text" id="cc_security_code"
                                                                            name="security_code"></div>

                                                                        <small>Enter the last 3 digit code on the back of
                                                                            your card</small>
                                                                        <div class="baseline"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="card_chrge">
                                                                    <input type="hidden" value="airportParkingBooking"
                                                                        name="action" id="action">
                                                                    <input type="hidden" id="bookID"
                                                                        name="booking_id" value="0">
                                                                    <input type="hidden" id="referenceNo"
                                                                        name="referenceNo"
                                                                        value="{{ $data['referenceNo'] }}">
                                                                    <input type="hidden" id="speed_park_active"
                                                                        name="speed_park_active" value="">
                                                                    <input type="hidden" id="site_codename"
                                                                        name="site_codename" value="">
                                                                    <input type="hidden" id="edinactive"
                                                                        name="edinactive" value="">
                                                                    <input type="hidden" id="edin_search"
                                                                        name="edin_search" value="">
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="alert alert-danger" id="c_error"
                                                                        style="display: none; margin-top: 10px; font-weight: bold;">
                                                                        Could not submit your request this time, please
                                                                        check your Card details and try again.
                                                                    </div>
                                                                </div>
                                                            </div><!--#creditDiv-->
                                                            <div class="card_chrge">
                                                                <input type="hidden" id="intent_secret"
                                                                    name="intent_secret" value="">
                                                                <!-- placeholder for Elements -->
                                                                <div id="card-element"></div>
                                                                <center>
                                                                    <h4 style="margin-top:10px;">
                                                                        <span class="badge badge-warning"
                                                                            style="background-color:transparent;color: black;">Your
                                                                            Card Will Be Charged</span>
                                                                        <span class="badge badge-danger"
                                                                            style="background-color: #1773b9; color: white;">
                                                                            <strong>£<span id="ccPrice">0</span></strong>
                                                                        </span>
                                                                    </h4>
                                                                </center>
                                                            </div>
                                                            <br>
                                                            <div id="imgloader"
                                                                style="display:none; text-align:center; margin:5px;">
                                                                <img src="{{ asset('theme/images/timeloader.gif') }}"
                                                                    style="width:50px;">
                                                            </div>
                                                            <div class="row" style="justify-content: center;">
                                                                <div class="col-md-4 col-md-offset-4 mg-b15 text-center">
                                                                    <button type="submit" id="bookingButton1"
                                                                        class="btn btn-book"
                                                                        style="height: auto;background-color: #f79f02;border: 2px solid #f79f02;padding: 10px 16px;font-size: 18px;">
                                                                        <!--<i class="fa fa-lock"></i> -->
                                                                        <span id="pb">Confirm Booking</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="error" role="alert">
                                                                <span class="message"></span>
                                                            </div>
                                                            <div id="error_personal_detail"
                                                                style="color:#f20; font-weight:bold; text-align:center;">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="styledpadding">

                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif

                                                @if ($settings['payment_type'] == 'payzone')
                                                    <img class="img-responsive"
                                                        src="{{ asset('assets/payzone/images/payzone_cards_accepted.png') }}">
                                                    {{-- PAYZONE FORM --}}
                                                    <form id="payzone_form">
                                                        <div class='payzone-form-section'>
                                                            {{ csrf_field() }}
                                                            <input type="hidden" id="CrossReferenceTransaction"
                                                                name="CrossReferenceTransaction" value="false" />
                                                            <div id='CardSectionTop' class="col-lg-12 margin15">
                                                                <div class="col-lg-6">
                                                                    <label for='CardName'>Card Name</label>
                                                                    <input class="form-control bf-inptfld" type="text"
                                                                        name="CardName" required value="" />
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for='CardNumber'>Card Number</label>
                                                                    <input class="form-control bf-inptfld" type="tel"
                                                                        name="CardNumber" id="CardNumber" value=""
                                                                        required placeholder="XXXX XXXX XXXX XXXX"
                                                                        data-inputmask="'mask': '9999 9999 9999 9999'"
                                                                        pattern="\d{4} \d{4} \d{4} \d{4}"
                                                                        class="masked" />
                                                                </div>
                                                            </div>
                                                            <div id='CardSectionTop' class="col-lg-12 margin15">
                                                                <div class="col-lg-6">
                                                                    <label for='CV2'>CV2</label>
                                                                    <input class="form-control bf-inptfld" type="text"
                                                                        name="CV2" value="" required
                                                                        maxlength="4"
                                                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                                                </div>
                                                                <div class="col-lg-6 exp_monthdd">
                                                                    <label for="ExpiryDateMonth" class="col-lg-12">Expiry
                                                                        Date</label>
                                                                    <div class="col-lg-6">
                                                                        <select required name="ExpiryDateMonth"
                                                                            class='exp_monthdd_height'>
                                                                            <option value=''></option>
                                                                            <option value="01">01</option>
                                                                            <option value="02">02</option>
                                                                            <option value="03">03</option>
                                                                            <option value="04">04</option>
                                                                            <option value="05">05</option>
                                                                            <option value="06">06</option>
                                                                            <option value="07">07</option>
                                                                            <option value="08">08</option>
                                                                            <option value="09">09</option>
                                                                            <option value="10">10</option>
                                                                            <option value="11">11</option>
                                                                            <option value="12">12</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <select required name="ExpiryDateYear"
                                                                            class='exp_monthdd_height'>
                                                                            <option value=''></option>
                                                                            <option value="18">2018</option>
                                                                            <option value="19">2019</option>
                                                                            <option value="20">2020</option>
                                                                            <option value="21">2021</option>
                                                                            <option value="22">2022</option>
                                                                            <option value="23">2023</option>
                                                                            <option value="24">2024</option>
                                                                            <option value="25">2025</option>
                                                                            <option value="26">2026</option>
                                                                            <option value="27">2027</option>
                                                                            <option value="28">2028</option>
                                                                            <option value="28">2029</option>
                                                                            <option value="28">2030</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card_chrge">
                                                            <h3 class="total_charged">
                                                                Your Card Will Be Charged
                                                                <strong>£<span id="ccPrice">00.00</span></strong>
                                                            </h3>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="styledpadding">
                                                                <label for="subscribe">
                                                                    <input type="checkbox" name="subscribe"
                                                                        class="styled" value="1">
                                                                    Subscribe for Regular Customer discount Code.
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="card_chrge">
                                                            <input type="hidden" value="airportParkingBooking"
                                                                name="action" id="action">

                                                            <input type="hidden" id="bookID" name="booking_id"
                                                                value="0">

                                                            <input type="hidden" id="referenceNo" name="referenceNo"
                                                                value="{{ $data['referenceNo'] }}">


                                                            <input type="hidden" id="speed_park_active"
                                                                name="speed_park_active" value="">

                                                            <input type="hidden" id="site_codename" name="site_codename"
                                                                value="">

                                                            <input type="hidden" id="edinactive" name="edinactive"
                                                                value="">

                                                            <input type="hidden" id="edin_search" name="edin_search"
                                                                value="">
                                                        </div>
                                                        <div id="form_errors"></div>
                                                        <div id="error_personal_detail"
                                                            style="color:red; padding-left: 27px;"></div>
                                                        <button id="booking_button"
                                                            class="btn btn-lg btn-yellow center-block cnf_booking"
                                                            type="button" onclick="payzone_submit()"
                                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing">Confirm
                                                            Booking</button>
                                                    </form>

                                                    {{-- PAYZONE FORM END --}}
                                                @endif
                                            </div><!-- end room-info -->
                                        </div><!-- end columns -->
                                    </div><!-- end row -->
                                </div><!-- end room-list-block -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="show_cart">
                        <div class="col-xs-12 payment mb-15" style="padding: 26px;">

                            <div class="multi-option">
                                <div class="img-div centered-align">
                                    <div class="col-xs-12 pd-l0">
                                        <p>
                                        <h4 class="primary-color" style="font-weight: 600; font-size: 16px;">
                                            {{ $data['parking_name'] }}</h4>

                                        <table>
                                            <tr>
                                                <td>
                                                    <p style="margin-top: 20px;font-size: 14px;"><b>Drop-Off Date &
                                                            Time:</b> </p>
                                                </td>
                                                <td>
                                                    <p style="margin-top: 20px;font-size: 14px;">
                                                        {{ \Carbon\Carbon::parse($data['dropdate'])->format('D d M Y') }}
                                                        at {{ $data['droptime'] }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p style="margin-top: 20px;font-size: 14px;"><b>Pick-Up Date &
                                                            Time:</b> </p>
                                                </td>
                                                <td>
                                                    <p style="margin-top: 20px;font-size: 14px;">
                                                        {{ \Carbon\Carbon::parse($data['pickdate'])->format('D d M Y') }}
                                                        at {{ $data['picktime'] }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        </p>
                                    </div>
                                    <!--<div class="col-xs-4 text-right pd-l0">-->
                                    <!--	<h4 class="price">£98.92</h4>-->
                                    <!--</div>-->
                                </div>
                            </div>

                            <div class="row vertical-align img-div"
                                style="border: 1px solid #330a4c;margin-bottom: 10px;">
                                <div class="col-sm-8">
                                    <p style="margin-top: 10px; margin-bottom: 10px;font-weight: bold;font-size:18px">No of
                                        Days</p>
                                </div>
                                <div class="col-sm-4 ">
                                    <p class="text-right"
                                        style="margin-top: 10px; margin-bottom: 10px;font-size:18px;color:#330a4c">
                                        {{ $data['total_days'] }}</p>
                                </div>
                            </div>

                            <div class="row vertical-align img-div"
                                style="border: 1px solid #330a4c;margin-bottom: 10px;">
                                <div class="col-sm-8">
                                    <p style="margin-top: 10px; margin-bottom: 10px;font-weight: bold;font-size:18px">
                                        Booking Price</p>
                                </div>
                                <div class="col-sm-4 ">
                                    <p class="text-right" id="booking_price"
                                        style="margin-top: 10px; margin-bottom: 10px;font-size:18px;color:#330a4c">£ 0.00
                                    </p>
                                </div>
                            </div>
                            @if ($data['discount_amount'] > 0)
                                <div class="row vertical-align img-div"
                                    style="border: 1px solid #330a4c;margin-bottom: 10px;">
                                    <div class="col-sm-8">
                                        <p style="margin-top: 10px; margin-bottom: 10px;">Discount Price</p>
                                    </div>
                                    <div class="col-sm-4 ">

                                        <p class="text-right" style="margin-top: 10px; margin-bottom: 10px;">-
                                            £{{ $data['discount_amount'] }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="row vertical-align img-div"
                                style="border: 1px solid #330a4c;margin-bottom: 10px;">
                                <div class="col-sm-8">
                                    <p style="margin-top: 10px; margin-bottom: 10px;font-weight: bold;font-size:18px">
                                        Booking Fee</p>
                                </div>
                                <div class="col-sm-4 ">
                                    @php
                                        $booking_fee = DB::table('settings')
                                            ->where('field_name', 'booking_fee')
                                            ->first();
                                        $booking_fee = $booking_fee->field_value;
                                    @endphp
                                    <p class="text-right"
                                        style="margin-top: 10px; margin-bottom: 10px;font-size:18px;color:#330a4c">
                                        £{{ $booking_fee }}</p>
                                </div>
                            </div>
                            <div class="row clear-padding add-extra-smscncl" style="padding-left: 10px;">
                                <label><input class="feeinput" type="checkbox" id="smsfee" name="smsfee"
                                        value='{{ $settings['sms_notification_fee'] }}'> Add SMS
                                    confirmation at only £{{ $settings['sms_notification_fee'] }} &nbsp;
                                    <!--<span-->
                                    <!--    class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top"-->
                                    <!--    title="Why not have your booking details sent direct to your mobile, for a quick and easy check in."></span>-->
                                </label>
                            </div>
                            <div class="row  clear-padding add-extra-smscncl" style="padding-left: 10px;">

                                <label> <input class="feeinput" type="checkbox" id="cancelfee" name="cancelfee"
                                        value='{{ $settings['cancellation_fee'] }}'> Add Cancellation
                                    Cover at only £{{ $settings['cancellation_fee'] }}&nbsp;
                                    <!--<span-->
                                    <!--    class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top"-->
                                    <!--    title="Our cancellation cover protects you if you do need to cancel or amend your booking."></span>-->

                                </label>

                            </div>


                            <div class="row" style="border: 1px solid #330a4c;margin-bottom: 10px;">
                                <div class="total"
                                    style="margin-top: 10px; margin-bottom: 10px; width: 100%; padding: 0 10px;">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h4 class="info-text mag-total total-hding" style="margin-bottom: 0;">Total:
                                            </h4>
                                        </div>
                                        <div class="col-sm-6">
                                            <h4 class="info-text mag-total text-right total-hding"
                                                style="color:#330a4c;margin-bottom: 0;">&pound;<span id="totalPrice"
                                                    style="color:#330a4c">0.00</span></h4>
                                        </div>
                                    </div>
                                </div>
                                <div id="bookingDetails">
                                    <input type="hidden" id="bookingprice" value="{{ $data['booking_amount'] }}" />

                                    <input type="hidden" id="alltotal" value="{{ $data['booking_amount'] }}" />

                                    <input type="hidden" id="disAmount" name="discount_amount"
                                        value="{{ $data['discount_amount'] }}">

                                    <input type="hidden" name="company_id" value="{{ $data['company_id'] }}">
                                    <input type="hidden" name="referenceNo" value="{{ $data['referenceNo'] }}">
                                    <input type="hidden" name="product_code" value="{{ $data['product_code'] }}">

                                    <input type="hidden" name="parking_type" value="{{ $data['parking_type'] }}">

                                    <input type="hidden" name="pickdate" value="{{ $data['pickdate'] }}">

                                    <input type="hidden" name="dropdate" value="{{ $data['dropdate'] }}">

                                    <input type="hidden" name="droptime" value="{{ $data['droptime'] }}">

                                    <input type="hidden" name="picktime" value="{{ $data['picktime'] }}">

                                    <input type="hidden" name="total_days" value="{{ $data['total_days'] }}">

                                    <input type="hidden" name="airport" value="{{ $data['airport'] }}">

                                    <input type="hidden" name="promo" value="{{ $data['discount_code'] }}">

                                    <input type="hidden" name="pl_id" value="{{ $data['pl_id'] }}">

                                    {{-- <input type="hidden" name="sku" value="{{ $settings['airport'] }}"> --}}

                                    <input type="hidden" name="site_codename" value="">

                                    {{-- <input type="hidden" name="speed_park_active" value="{{ $settings['airport'] }}"> --}}

                                    {{-- <input type="hidden" name="edin_active" value="{{ $settings['airport'] }}"> --}}

                                    {{-- <input type="hidden" name="edin_search" value="{{ $settings['airport'] }}"> --}}

                                    <input type="hidden" name="bookingfor" value="airport_parking">

                                    <input type="hidden" name="incomplete" id="incomplete" value="yes">

                                    <input type="hidden" name="park_api" value="{{ $data['park_api'] }}">
                                    <input type="hidden" name="new_price" value="{{ $data['new_price'] ?? $data['booking_amount'] }}">
                                    <input type="hidden" name="bookfhrSearchId" id="bookfhrSearchId" value="{{ $data['bookfhrSearchId'] ?? '' }}">
                                    <input type="hidden" name="bookfhrOptionId" id="bookfhrOptionId" value="{{ $data['bookfhrOptionId'] ?? '' }}">
                                    <input type="hidden" name="g_token" value="{{ $data['g_token'] }}">
                                    <input type="hidden" name="g_quote" value="{{ $data['g_quote'] }}">
                                </div>
                                <div class="col-xs-12 paybutton">
                                    <a class="btn-small btn btn-listing mg-b15" href="/booking/payment/"><b>Continue to
                                            payment</b></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @php
        $total_amount = $data['booking_amount'] + $data['discount_amount'];
    @endphp
@endsection
<div class="overlay" style="display: none;"></div>
@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.js"></script>







    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js" data-autoinit='true'></script>



    <script src="https://getaddress.io/js/jquery.getAddress-2.0.8.min.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script> --}}

    <script type="text/javascript">
        // jquery code for disable copy and page option from email

        $(document).ready(function() {
            $('input.disablecopypaste').bind('copy paste', function(e) {
                e.preventDefault();
            });
        });

        //Next Code

        $(":input").inputmask();

        $("#vechile_detail").validate({

            rules: {

                registration: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=vehdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

                make: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=vehdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

                color: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=vehdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

                model: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=vehdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

            }

        });

        $("#travel_detail").validate({

            rules: {

                departterminal: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=flightdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

                arrivalterminal: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=flightdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

                returnflight: {

                    required: {

                        depends: function(element) {

                            var id = $('input[name=flightdetails]:checked').attr('id');

                            if (id == 'yes') {

                                return true;

                            } else {

                                return false;

                            }



                        }

                    }



                },

            }

        });


        $(".feeinput").click(function(event) {

            ap_processCheckout();

        });

        function showErrorMsg(obj) {

            obj.addClass('border-red');

            obj.after('<span class="error error-massage">Required</span>');

            //$(obj).scrollintoview();

        }

        function hideErrorMsg(obj) {

            obj.find('.border-red').removeClass('border-red');

            obj.find('span.error').remove();

        }

        $(document).ready(function() {

            ap_processCheckout();

            $('input[name=flightdetails]').on('change', function() {

                var id = $('input[name=flightdetails]:checked').attr('id');

                if (id == 'no') {

                    $('#flightnumber').val('TBA');

                    $('#returnflight').val('TBA');

                    $('#travel-detail').slideUp(1000);

                } else {

                    $('#flightnumber').val('');

                    $('#returnflight').val('');

                    $('#travel-detail').slideDown(1000);

                }

            });

            $('input[name=vehdetails]').on('change', function() {

                var id = $('input[name=vehdetails]:checked').attr('id');

                if (id == 'yes') {

                    $('#make').val('');

                    $('#model').val('');

                    $('#color').val('');

                    $('#registration').val('');

                    $('#vechile-detail').slideDown(1000);

                } else {

                    $('#make').val('TBA');

                    $('#model').val('TBA');

                    $('#color').val('TBA');

                    $('#registration').val('TBA');

                    $('#vechile-detail').slideUp(1000);

                }

            });





            $('#contactno').change(function() {



                //hideMsgDiv($("#checkoutPageError"));

                //if ($("#personal_details_form").valid()) {

                var data = {};
                // data['discount'] = $('#disamount input[name="discount_amount"]').val();
                data['discount'] = "{{ $data['discount_amount'] }}";
                //  data['discount2'] = $('#disamount').val();
                data['title'] = $('#title').val();

                data['firstname'] = $('#firstname').val();

                data['lastname'] = $('#lastname').val();

                data['email'] = $('#email').val();

                data['contactno'] = $('#contactno').val();

                data['action'] = $('#action').val();

                data['referenceNo'] = $('#bookingDetails input[name="referenceNo"]').val();

                data['booking_fee'] = "{{ $settings['booking_fee'] }}";

                data['_token'] = "{{ csrf_token() }}";

                data['refr'] = $('#refr').val();

                //alert(data['refr']);

                data['booking_id'] = $('#bookID').val();



                data['total_amount'] = $('#bookingprice').val();

                data['park_api'] = $('#bookingDetails input[name="park_api"]').val();

                if (data['action'] == 'airportParkingBooking') {

                    //
                    data['discount'] = "{{ $data['discount_amount'] }}";

                    data['referenceNo'] = $('#bookingDetails input[name="referenceNo"]').val();

                    data['company_id'] = $('#bookingDetails input[name="company_id"]').val(),
                        data['product_code'] = $('#bookingDetails input[name="product_code"]').val(),
                        data['parking_type'] = $('#bookingDetails input[name="parking_type"]').val(),

                        data['pickdate'] = $('#bookingDetails input[name="pickdate"]').val(),

                        data['dropdate'] = $('#bookingDetails input[name="dropdate"]').val(),

                        data['droptime'] = $('#bookingDetails input[name="droptime"]').val(),

                        data['picktime'] = $('#bookingDetails input[name="picktime"]').val(),

                        data['total_days'] = $('#bookingDetails input[name="total_days"]').val(),

                        data['airport'] = $('#bookingDetails input[name="airport"]').val(),

                        data['bookingfor'] = $('#bookingDetails input[name="bookingfor"]').val(),

                        data['promo'] = $('#bookingDetails input[name="promo"]').val(),

                        data['smsfee'] = $("#smsfee").is(':checked') ? 'Yes' : 'No',

                        data['cancelfee'] = $("#cancelfee").is(':checked') ? 'Yes' : 'No',

                        data['passenger'] = $('#passenger').val(),

                        data['incomplete'] = $('#bookingDetails input[name="incomplete"]').val(),

                        data['pl_id'] = $('#bookingDetails input[name="pl_id"]').val(),

                        data['speed_park_active'] = $('#bookingDetails input[name="speed_park_active"]')
                        .val(),

                        data['site_codename'] = $('#bookingDetails input[name="site_codename"]').val(),

                        data['sku'] = $('#bookingDetails input[name="sku"]').val(),

                        data['src'] = $('#src').val(),

                        data['edin_active'] = $('#bookingDetails input[name="edin_active"]').val()



                    //data['debug']       = 1

                }


                $.post('/booking/incomplete/booking/checkBooking', data, function(data) {

                    console.log("data===", data);





                    if (data.booking_id > 0) {



                        if (data.available == "Yes") {

                            $("#bookID").val(data.booking_id);

                            $("#bookID1").val(data.booking_id);

                            $("#referenceNo").val(data.referenceNo);

                            $("#referenceNo1").val(data.referenceNo);

                            $("#incomplete").val('no');

                        } else {



                        }


                    }



                }, 'json');

                //}

            });





        });


        function showSpinner() {
            $(".overlay").show();
            $("#imgloader").show();
        }

        function hideSpinner() {

            $(".overlay").hide();
            $("#imgloader").hide();
        }



        function ap_processCheckout(argument) {

            var smsfee = $("#smsfee").is(':checked') ? 'Yes' : 'No';

            var canfee = $("#cancelfee").is(':checked') ? 'Yes' : 'No';

            CheckoutData(smsfee, canfee);

            //$('[data-toggle="tooltip"]').tooltip();

        }



        function CheckoutData(smsfee, canfee) {

            showSpinner();

            var data = {
                discount: 1,
                discount: $('#disamount input[name="discount_amount"]').val(),
                airport: $('#bookingDetails input[name="airport"]').val(),

                company_id: $('#bookingDetails input[name="company_id"]').val(),

                product_code: $('#bookingDetails input[name="product_code"]').val(),

                total_days: $('#bookingDetails input[name="total_days"]').val(),

                dropdate: $('#bookingDetails input[name="dropdate"]').val(),

                pickdate: $('#bookingDetails input[name="pickdate"]').val(),

                droptime: $('#bookingDetails input[name="droptime"]').val(),

                picktime: $('#bookingDetails input[name="picktime"]').val(),

                pl_id: $('#bookingDetails input[name="pl_id"]').val(),

                sku: $('#bookingDetails input[name="sku"]').val(),

                edin_active: $('#bookingDetails input[name="edin_active"]').val(),

                speed_park_active: $('#bookingDetails input[name="speed_park_active"]').val(),

                site_codename: $('#bookingDetails input[name="site_codename"]').val(),

                passenger: $('#passenger').val(),

                promo: $('#bookingDetails input[name="promo"]').val(),

                bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),


                total_amount: {{ $total_amount }},

                park_api: $('#bookingDetails input[name="park_api"]').val(),

                smsfee: smsfee,

                canfee: canfee,

                action: 'booking_checkout'

            };

            data['_token'] = "{{ csrf_token() }}";

            //setProcessBar(75);

            $.post('booking/checkout', data, function(data) {

                console.log(data);

                $("#totalPrice").text(data.total_amount);
                $("#booking_price").text(data.booking_amount);
                $("#ccPrice").text(data.total_amount);

                $("#ddPrice").val(data.total_amount);

                $("#alltotal").val(data.total_amount);

                $("#disAmount").val(data.discount_amount);
                $("#company_name").text(data.company_name);
                $("#intent_secret").val(data.intent_secret);



                if (data.booking_amount > 0) {

                    $("#bookingPriceDiv").text(data.booking_amount);

                    $("#bookingprice").val(data.booking_amount);

                } else {

                    //  showalert();

                }

                if (data.discount_amount > 0) {

                    $("#disfeeprice").text(data.discount_amount);

                    $("#disfee").show();

                    $(".promodiscont").hide();

                } else {

                    $("#disfee").hide();

                }

                if (data.booking_fee > 0) {

                    $("#bookfeeprice").text(data.booking_fee);

                    $("#bookfee").show();

                } else {

                    $("#bookfee").hide();

                }

                if (data.sms_notification > 0) {

                    $("#smsNotificationprice").text(data.sms_notification);

                    $("#smsNotification").show();

                } else {

                    $("#smsNotification").hide();

                }

                if (data.cancellation_fee > 0) {

                    $("#canfeeprice").text(data.cancellation_fee);

                    $("#canfee").show();

                } else {

                    $("#canfee").hide();

                }

                hideSpinner();

            }, 'json');

        }



        function validate_vechiledetail() {

            var html = '<label class="error error-vech" >This field is required.</label>';

            var id = $('input[name=vehdetails]:checked').attr('id');



            $(".error-vech").remove();

            if (id == 'yes') {

                if ($("#registration").val() == "") {

                    $("#registration").after(html);

                    return false;

                }

                if ($("#make").val() == "") {

                    $("#make").after(html);

                    return false;

                }

                if ($("#color").val() == "") {

                    $("#color").after(html);

                    return false;

                }

                if ($("#model").val() == "") {

                    $("#model").after(html);

                    return false;

                }



            }





            var id2 = $('input[name=flightdetails]:checked').attr('id');

            if (id2 == 'yes') {

                if ($("#departterminal").val() == "") {

                    $("#departterminal").after(html);

                    return false;

                }

                if ($("#arrivalterminal").val() == "") {

                    $("#arrivalterminal").after(html);

                    return false;

                }



            }

            return true;

        }





        function valid_address() {

            var html = '<label class="error error-vech" >This field is required.</label>';





            $(".error-vech").remove();



            //            if ($("#address").val() == "") {

            //                $("#ad_field").after(html);

            //                return false;

            //            }

            //            if ($("#address2").val() == "") {

            //                $("#ad_field").after(html);

            //                return false;

            //            }

            if ($("#town").val() == "") {

                $("#ad_field").after(html);

                return false;

            }

            if ($("#post_code").val() == "") {

                $("#ad_field").after(html);

                return false;

            }





            return true;

        }
    </script>



    @if ($settings['payment_type'] == 'payzone')
        <script type="text/javascript">
            $('#postcode_lookup').getAddress({

                api_key: '{{ $settings['address_key'] }}',

                //            <!--  Or use your own endpoint - api_endpoint:https://your-web-site.com/getAddress, -->

                output_fields: {

                    line_1: '#line1',

                    line_2: '#address',

                    line_3: '#address2',

                    post_town: '#town',

                    county: '#county',

                    postcode: '#post_code'

                },

                button_class: 'btn btn-yellow',

                input_class: 'form-control my-class',

                dropdown_class: 'form-control  my-class',



                <
                !--Optionally register callbacks at specific stages-- >

                onLookupSuccess: function(data) {
                    /* Your custom code */

                    $('#ad_field').hide();

                },

                onLookupError: function() {
                    /* Your custom code */



                    // $('#postcode_lookup').hide();

                    $('#ad_field').hide();





                    $("#postcode_lookup").append(
                        '<button id="nolist" type="button" class="btn text-center btn-yellow" >Address Not Listed</button><script>$("#nolist").bind("click",function(){$("#ad_field").show(); $("#getaddress_input").hide(); $("#getaddress_button").hide(); $("#getaddress_error_message").hide(); });<\/script>'
                    );





                },

                onAddressSelected: function(elem, index) {
                    /* Your custom code */

                    //$('#ad_field').show();

                }

            });
            // $("#payzone_form").valid();


            function payzone_submit() {

                //debugger;

                // e.preventDefault();

                //        var cartForm = document.getElementById('payzone_form');

                //        var validated = validateForm(cartForm, 'direct', 'true');

                //        if (validated) {
                showSpinner();
                if ($("#payzone_form").valid()) {

                    if ($("#personal_details_form").valid()) {



                        if (valid_address()) {



                            if (validate_vechiledetail()) {

                                var smsfee = 0;

                                if ($("#smsfee").prop("checked") == true) {

                                    smsfee = $("#smsfee").val();

                                }



                                var cancelfee = 0;

                                if ($("#cancelfee").prop("checked") == true) {

                                    cancelfee = $("#cancelfee").val();

                                }



                                var data = {};
                                data['discount'] = $('#disamount input[name="discount_amount"]').val();
                                data['discount'] = 1;
                                data['title'] = $('#title').val();

                                data['firstname'] = $('#firstname').val();

                                data['lastname'] = $('#lastname').val();

                                data['email'] = $('#email').val();

                                data['contactno'] = $('#contactno').val();

                                data['action'] = $('#action').val();

                                data['referenceNo'] = $('#bookingDetails input[name="referenceNo"]').val();



                                data['_token'] = $('input[name="_token"]').val();

                                data['refr'] = $('#refr').val();

                                //alert(data['refr']);

                                data['booking_id'] = $('#bookID').val();

                                data['alltotal'] = $('#alltotal').val();





                                data['CardName'] = $('#payzone_form input[name="CardName"]').val();

                                data['CardNumber'] = $('#payzone_form input[name="CardNumber"]').val();

                                data['CV2'] = $('#payzone_form input[name="CV2"]').val();

                                data['ExpiryDateMonth'] = $('#payzone_form select[name="ExpiryDateMonth"]').val();

                                data['ExpiryDateYear'] = $('#payzone_form select[name="ExpiryDateYear"]').val();



                                // if (data['action'] == 'airportParkingBooking') {

                                //

                                data['company_id'] = $('#bookingDetails input[name="company_id"]').val();

                                data['product_code'] = $('#bookingDetails input[name="product_code"]').val();

                                data['parking_type'] = $('#bookingDetails input[name="parking_type"]').val();

                                data['pickdate'] = $('#bookingDetails input[name="pickdate"]').val()

                                data['dropdate'] = $('#bookingDetails input[name="dropdate"]').val();

                                data['droptime'] = $('#bookingDetails input[name="droptime"]').val();

                                data['picktime'] = $('#bookingDetails input[name="picktime"]').val();

                                data['total_days'] = $('#bookingDetails input[name="total_days"]').val();

                                data['airport'] = $('#bookingDetails input[name="airport"]').val();

                                data['bookingfor'] = $('#bookingDetails input[name="bookingfor"]').val();

                                data['promo'] = $('#bookingDetails input[name="promo"]').val();



                                data['smsfee'] = smsfee;

                                //}

                                data['cancelfee'] = cancelfee;

                                data['passenger'] = $('#passenger').val();

                                data['incomplete'] = $('#bookingDetails input[name="incomplete"]').val();

                                data['pl_id'] = $('#bookingDetails input[name="pl_id"]').val();

                                data['speed_park_active'] = $('#bookingDetails input[name="speed_park_active"]').val();

                                data['site_codename'] = $('#bookingDetails input[name="site_codename"]').val(),

                                    data['sku'] = $('#bookingDetails input[name="sku"]').val();

                                data['model'] = $('#vechile_detail input[name="model"]').val();

                                data['color'] = $('#vechile_detail input[name="color"]').val();

                                data['make'] = $('#vechile_detail input[name="make"]').val();

                                data['registration'] = $('#vechile_detail input[name="registration"]').val();

                                data['subscribe'] = $('#bookingDetails input[name="subscribe"]').val();

                                data['departterminal'] = $('#departterminal ').val();

                                data['arrivalterminal'] = $('#arrivalterminal').val();
                                data['discount'] = $('#disAmount').val();

                                data['returnflight'] = $('#returnflight').val();

                                data['address'] = $('#address').val();



                                data['fulladdress'] = $('#getaddress_dropdown option:selected').text();

                                data['address2'] = $('#address2').val();

                                data['town'] = $('#town').val();



                                data['post_code'] = $('#post_code').val();






                                //data['debug']       = 1

                                //}

                                try {

                                    //
                                    $('#booking_button').button('loading');

                                    $.post('booking/paymentwithPayzone', data, function(data) {

                                        console.log("data===", data);

                                        if (data.StatusCode == 0) {

                                            hideSpinner();

                                            var refid = $('#referenceNo').val();

                                            window.location.href = "https://" + window.location.hostname +
                                                "/booking/thankyou/" + refid;

                                        } else {
                                            hideSpinner();
                                            alert(data.Message);
                                            $('#booking_button').button('reset');
                                            $("#error_personal_detail").html(data.Message);

                                        }

                                    }, 'json');

                                } catch (err) {

                                    hideSpinner();

                                    $('#booking_button').button('reset');
                                    console.log("error in catch ", err);

                                }

                            } else {

                                hideSpinner();

                                //vechile_detail

                                var top = $('#vechile_detail').position().top;

                                $(window).scrollTop(top);

                            }

                        } else {

                            hideSpinner();

                            //$("#ad_field").after('<label class="error error-vech" >This field is required.</label>');

                            var top = $('#personal_details_form').position().top;

                            $(window).scrollTop(top);

                        }





                    } else {

                        hideSpinner();

                        //$("#error_personal_detail").html("<p>Some Personal Detail Field are missing</p>");

                        //$(document).scrollTo('#error_personal_detail');

                        var top = $('#personal_details_form').position().top;

                        $(window).scrollTop(top);



                    }

                }

                //}

                //$('#booking_button').button('reset');

                return false;

            }
        </script>
    @endif
    @if ($settings['payment_type'] == 'stripe')
        <script src="https://js.stripe.com/v3/"></script>
        <script>window.STRIPE_PUBLIC_KEY = @json(config('services.stripe.key'));</script>

        <script src="{{ asset('assets/stripe/index.js') }}"></script>

        <script src="{{ asset('assets/stripe/example2.js') }}"></script>

        <script src="{{ asset('assets/stripe/l10n.js') }}"></script>
    @endif
@endsection

<style type="text/css">
    .menu_item a {
        display: inline-block;
        position: relative;
        font-family: sans-serif !important;
        font-size: 36px;
        color: #FFFFFF;
        font-weight: 400;
    }
</style>
