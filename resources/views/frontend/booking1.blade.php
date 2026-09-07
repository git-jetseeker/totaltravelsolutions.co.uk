@extends('layouts.booking-shell')

@section('title', 'Confirm Booking')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/payzone/payzone_gateway.css?v=1.2') }}">
@endsection

@section('content')

@php
    $apiBookingAmount = (float) ($data['booking_amount'] ?? 0);
    $customerParkingPrice = $apiBookingAmount;
    if (($data['park_api'] ?? '') === 'bookfhr' && isset($data['new_price']) && $data['new_price'] !== '' && $data['new_price'] !== null) {
        $customerParkingPrice = (float) $data['new_price'];
    }
@endphp

    {{-- Legacy inline booking styles disabled — see jetseeker-booking.css --}}
    <style type="text/css">
        @media not all {
        /* ============================================

           PREMIUM BOOKING PAGE STYLES

           ============================================ */



        .vehicleReg{

            margin-top: 10px;

        }



        .main_nav_col {

            height: 80px;

        }



        .mble-menu,.top-bar-contact,.main_nav_book {

            display: none !important;

        }



        .menu_content {

            display: none !important;

        }



        .menu.trans_500.active {

            display: none !important;

        }



        .hamburger i {

            display: none !important

        }



        .menu.trans_500 {

          display:   none !important;

        }



        .side-bar .row div[class*="col-"] {

            margin-top: 15px;

            font-weight: bold !important;

            color: #fff;

        }



        #getaddress_button {

            float: left !important;

            height: 50px;

        }



        /* Premium Stripe Elements */

        .StripeElement {

            box-sizing: border-box;

            height: 50px;

            padding: 12px 15px;

            border: 2px solid rgba(0, 0, 0, 0.1);

            border-radius: 10px;

            background-color: white;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

            transition: all 0.3s ease;

        }



        .StripeElement:hover {

            border-color: rgba(36, 58, 124, 0.377);

        }



        .StripeElement--focus {

            box-shadow: 0 0 0 3px rgba(36, 58, 124, 0.377);

            border-color: #C2185B;

            background-color: #fffbf5;

        }



        .StripeElement--invalid {

            border-color: #fa755a;

            box-shadow: 0 0 0 3px rgba(250, 117, 90, 0.1);

        }



        .StripeElement--webkit-autofill {

            background-color: #fffbf5 !important;

        }



        /* Button base styles handled by premium button styles below */



        .email {

            height: 22px;

            width: 25% !important;

            margin-right: 1%;

            margin: 1.6%;

            border: none;

            outline: none;

            padding-left: 15px;

            padding-right: 15px;

            font-size: 13px;

            font-weight: 600;

            color: #929191;

            border-radius: 0px;

        }



        #creditDiv hr {

            margin-top: 20px !important;

            border-top: 1px solid #ccc !important;

        }



        /* Premium Order Button */

        .order {

            margin: 5px;

            height: 28px;

            position: absolute;

            text-align: center;

            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%) !important;

            color: #fff;

            border-radius: 6px;

            box-shadow: 0 4px 10px rgba(36, 58, 124, 0.377);

            -webkit-animation: bounce .3s infinite alternate;

            -moz-animation: bounce .3s infinite alternate;

            animation: bounce .3s infinite alternate;

            -webkit-animation-iteration-count: 1;

            -moz-animation-iteration-count: 1;

            animation-iteration-count: 1;

        }



        /* Premium Select Dropdowns */

        select.form22 {

            height: 45px;

            border-radius: 10px;

            border: 2px solid rgba(0, 0, 0, 0.1);

            color: #333;

            width: 100%;

            padding: 8px 15px;

            background: white;

            font-weight: 500;

            transition: all 0.3s ease;

        }



        select.form22:focus {

            border-color: #C2185B;

            box-shadow: 0 0 0 3px rgba(36, 58, 124, 0.377);

            outline: none;

            background: #fffbf5;

        }



        select.form22:hover {

            border-color: rgba(36, 58, 124, 0.377);

        }



        .paymentFrm {

            padding: 15px;

        }



        .btn-group-lg>.btn,

        .btn-lg {

            line-height: 0.5;

        }



        .col-lg-4 {

            margin-top: 12px;

        }



        input[type="number"]::-webkit-outer-spin-button,

        input[type="number"]::-webkit-inner-spin-button {

            -webkit-appearance: none;

            margin: 0;

        }



        input[type="number"] {

            -moz-appearance: textfield;

        }



        .main_nav_container {

            display: none;

        }



        .trans_500 {

            display: none;

        }



        .footer {

            display: none;

        }



        /* Premium Page Background */

        body {

            background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%) !important;

        }



        .home-background {

            margin-top: 50px;

        }



        /* Premium Inner Page Wrapper */

        .innerpage-wrapper {

            background: transparent;

        }



        /* Premium Error Messages */

        .error,

        .error-massage,

        .error-vech {

            color: #dc3545;

            font-size: 13px;

            margin-top: 5px;

            font-weight: 600;

            animation: fadeInUp 0.3s ease;

        }



        @keyframes fadeInUp {

            from {

                opacity: 0;

                transform: translateY(10px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }



        /* Premium Alert Boxes */

        .alert-danger {

            background: linear-gradient(135deg, #fff5f5, #ffe5e5);

            border: 2px solid #dc3545;

            border-radius: 10px;

            color: #721c24;

            font-weight: 600;

        }



        /* Premium Loader */

        #imgloader img {

            filter: drop-shadow(0 4px 10px rgba(36, 58, 124, 0.377));

        }



        /* Premium Required Field Star */

        .required-field {

            color: #dc3545;

            font-weight: bold;

            margin-left: 3px;

        }



        .list-unstyled {

            /*background-color: white;*/

            /*padding: 20px;*/

            /*box-shadow: 2px 5px 4px #00000070 !important;*/

            /*margin-top: 19px;*/

            /*margin-bottom: 19px;*/

            /*border-radius: 24px;*/

            /*background:none;*/

            padding: 0px;

            padding-left: 20px;

            padding-right: 20px;

            padding-top: 20px;

            box-shadow: none;

            background: none;

        }



        /* Premium Section Headers */

        .room-name {

            color: #fff;

            padding: 18px 25px;

            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);

            font-size: 22px;

            border-radius: 14px 14px 0 0;

            font-weight: 700;

            letter-spacing: 0.5px;

            box-shadow: 0 4px 15px rgba(36, 58, 124, 0.377);

            position: relative;

            overflow: hidden;

        }



        .room-name::after {

            content: '';

            position: absolute;

            top: 0;

            right: -50px;

            width: 100px;

            height: 100%;

            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);

            animation: shimmer 3s infinite;

        }



        @keyframes shimmer {

            0% {

                transform: translateX(-100px);

            }

            100% {

                transform: translateX(400px);

            }

        }



        /* Premium Labels */

        .lable {

            font-size: 15px;

            font-weight: 600;

            color: #4D2375;

            margin-bottom: 8px;

            display: block;

            transition: color 0.3s ease;

        }



        .scp {

            padding-left: 25px;

            padding-right: 42px;

        }



        .h3book {

            padding-top: 20px;

            text-align: center;

            font-size: 25px;

            color: black;

        }



        .h4booking {

            color: #4D2375;

            font-size: 20px;

            font-weight: 600px;

        }



        .amount-s {

            color: #C2185B;

            font-size: 22px;

            font-weight: 600px;

        }



        /* Premium Secondary Header */

        .room-name2 {

            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);

            color: #4D2375;

            padding: 15px 20px;

            border: 2px solid #4D2375;

            font-size: 20px;

            margin-bottom: 35px;

            border-radius: 10px;

            font-weight: 700;

            box-shadow: 0 4px 15px rgba(36, 58, 124, 0.377);

        }



        .main-paragraph {

            color: white;

            font-size: 16px;

            margin-top: 125px;

            margin-bottom: 3px;

        }



        @media only screen and (max-width: 575px) {

            .main-paragraph {

                margin-top: 100px !important;

            }



            .search1 {

                height: 380px !important;

            }

        }



        .main-paragraph1 {

            color: white;

            font-size: 16px;

            margin-top: -3px;

        }



        .main-heading {

            font-size: 30px;

            color: white;

        }



        .search1 {



            background-image: linear-gradient(to right, #00000080, #00000042), url(public/bannarSearch.webp);



            width: 100%;



            height: 380px;



            background-size: cover;



        }



        .margintop110 {

            margin-bottom: 20px;

        }



        @media only screen and (max-width: 991px) {

            .main-paragraph1 {

                /*margin-left: -14px;*/

            }



            .main-paragraph {

                /*margin-left: -14px;*/

            }



            .hamburger {

                display: none !important;

            }



            p {

                padding: 0 0px !important;

            }

            .pl-mob-15{

                padding-left: 15px;

            }

            .room-name{font-size: 18px}

            li#room-list-1 {

                    margin: 0;

                }

            #room-list {

                    margin-top: 30px;

                }



            .side-bar .scp{

                padding-left: 20px;

                padding-right: 22px;

            }

            #bookingButton1{

                margin-bottom: 10px!important;

            }

            .section-borders-sidebar {

                margin: 0;

            }

        }



        @media only screen and (min-width: 992px) {

            .div-width {

                width: 100%;

            }



            .row-wr {

                width: 94% !important;

                padding:15px;

            }



            .row-wr2 {

                /*width: 87% !important;*/

            }

        }



        @media screen and (min-device-width: 992px) and (max-device-width: 1540px) {

            .travel-css {

                font-size: 13px !important;

                height: 39px !important;

            }



            .lable {

                font-size: 13px !important;

            }

        }



        .img-crd {

            max-width: 100%;

        }



        @media only screen and (min-width: 992px) {

            .img-crd {

                max-width: 132%;

            }



        }

        @media only screen and (max-width: 330px) {

            .row-mob-padding15{padding: 0 15px;}

        }

        h/*1, h2, h3, h4, h5, h6,p,label,b{

            font-family: "Poppins", Sans-serif !important;

        }*/

        .booking-det{

            color: #fff;

            font-size: 20px;

            text-align: center;

            width: 100%;

            text-transform: uppercase;

        }

        @media only screen and (max-width: 1344px) {

            .margintop110{margin-top: auto;}

        }

        /* Premium Section Cards */

        .section-borders{

            margin: 15px 0;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);

            border-radius: 16px;

            border: 2px solid transparent;

            background: linear-gradient(white, white) padding-box,

                        linear-gradient(135deg, rgba(36, 58, 124, 0.377), rgba(77, 39, 109, 0.3)) border-box;

            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

            overflow: hidden;

            position: relative;

        }



        .section-borders::before {

            content: '';

            position: absolute;

            top: 0;

            left: 0;

            right: 0;

            height: 4px;

            background: linear-gradient(90deg, #C2185B, #C2185B);

            opacity: 0;

            transition: opacity 0.3s ease;

        }



        .section-borders:hover::before {

            opacity: 1;

        }



        .section-borders:hover {

            box-shadow: 0 12px 40px rgba(255, 165, 0, 0.2);

            transform: translateY(-3px);

        }



        .section-borders-sidebar{

            margin: 15px 0;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);

            border-radius: 16px;

            border: 2px solid transparent;

            background: linear-gradient(white, white) padding-box,

                        linear-gradient(135deg, rgba(36, 58, 124, 0.377), rgba(77, 39, 109, 0.3)) border-box;

            overflow: hidden;

            transition: all 0.3s ease;

        }



        .section-borders-sidebar:hover {

            box-shadow: 0 12px 40px rgba(255, 165, 0, 0.2);

        }

        .section-borders-sidebar .support-block1{

            background: #C2185B;

            padding-left: 15px;

            padding-right: 15px;

        }

        .section-borders-sidebar .support-block1 p{

            color: #fff;

        }

        .booking-second-sec-h2{

            color:black;

        }

        .frombgbooking {

            border: none;

            border-radius: 0;

        }

        .shield-p {

            margin-left: 62px;

            max-width: 90%;

            display: flex;

        }

        .shield-p svg {

            margin-left: -58px;

        }

        .shield-p p {

            margin-left: 15px;

            line-height: 1.3;

            margin-bottom: 0;

            align-content: center;

        }



        /* Premium Form Controls */

        .form-control.bf-inptfld,

        .form22.bf-slctfld,

        select.form22 {

            border: 2px solid rgba(0, 0, 0, 0.1);

            border-radius: 10px;

            padding: 12px 15px;

            transition: all 0.3s ease;

            background: white;

            font-weight: 500;

        }



        .form-control.bf-inptfld:focus,

        .form22.bf-slctfld:focus,

        select.form22:focus {

            border-color: #C2185B;

            box-shadow: 0 0 0 3px rgba(36, 58, 124, 0.377);

            outline: none;

            background: #fffbf5;

        }



        .form-control.bf-inptfld:hover,

        .form22.bf-slctfld:hover,

        select.form22:hover {

            border-color: rgba(36, 58, 124, 0.377);

        }



        /* Premium Buttons */

        .btn-warning,

        .btn-yellow,

        .cnf_booking {

            background: linear-gradient(135deg, black 0%, black 100%) !important;

            color: white !important;

            border: none;

            border-radius: 10px;

            padding: 15px 40px;

            font-weight: 700;

            letter-spacing: 0.5px;

            text-transform: uppercase;

            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

            box-shadow: 0 4px 15px rgba(36, 58, 124, 0.377);

            position: relative;

            overflow: hidden;

        }



        .btn-warning::before,

        .btn-yellow::before,

        .cnf_booking::before {

            content: '';

            position: absolute;

            top: 50%;

            left: 50%;

            width: 0;

            height: 0;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.3);

            transform: translate(-50%, -50%);

            transition: width 0.6s, height 0.6s;

        }



        .btn-warning:hover::before,

        .btn-yellow:hover::before,

        .cnf_booking:hover::before {

            width: 300px;

            height: 300px;

        }



        .btn-warning:hover,

        .btn-yellow:hover,

        .cnf_booking:hover {

            background: linear-gradient(135deg, black 0%, #1a1a1a 100%) !important;

            transform: translateY(-3px) scale(1.05);

            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);

        }



        .btn-warning:disabled,

        .btn-yellow:disabled,

        .cnf_booking:disabled {

            opacity: 0.6;

            cursor: not-allowed;

            transform: none;

        }



        /* Premium Checkboxes */

        input[type="checkbox"] {

            width: 20px;

            height: 20px;

            cursor: pointer;

            accent-color: #C2185B;

            transition: transform 0.2s ease;

        }



        input[type="checkbox"]:hover {

            transform: scale(1.1);

        }



        /* Premium Radio Buttons */

        input[type="radio"] {

            width: 18px;

            height: 18px;

            cursor: pointer;

            accent-color: #C2185B;

            transition: transform 0.2s ease;

        }



        input[type="radio"]:hover {

            transform: scale(1.1);

        }



        /* Premium Booking Summary Header */

        .booking-det {

            background: linear-gradient(135deg, black 0%, black 100%);

            padding: 15px;

            border-radius: 10px;

            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);

            box-shadow: 0 4px 15px rgba(77, 35, 117, 0.3);

        }



        /* Premium Booking Details Section */

        .support-block1 {

            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%) !important;

            padding: 20px;

            border-radius: 0 0 14px 14px;

        }



        .support-block1 .row {

            margin-bottom: 12px;

        }



        .support-block1 .row div {

            transition: transform 0.2s ease;

        }



        .support-block1 .row:hover div {

            transform: translateX(3px);

        }



        /* Premium Extra Services Checkboxes */

        .add-extra-smscncl {

            transition: all 0.3s ease;

            padding: 10px;

            border-radius: 8px;

        }



        .add-extra-smscncl:hover {

            background: rgba(255, 255, 255, 0.1);

        }



        /* Premium Shield Section */

        .shield-p {

            background: linear-gradient(135deg, rgba(0, 214, 111, 0.1), rgba(0, 214, 111, 0.05));

            border-left: 4px solid #00d66f;

            padding: 15px;

            border-radius: 10px;

            transition: all 0.3s ease;

        }



        .shield-p:hover {

            box-shadow: 0 4px 20px rgba(0, 214, 111, 0.2);

            transform: translateY(-2px);

        }



        /* Premium Terms Checkbox Container */

        #terms_condition {

            margin-right: 10px;

        }



        /* Premium Company Logo */

        .img-fluid {

            transition: transform 0.3s ease;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);

        }



        .img-fluid:hover {

            transform: scale(1.05);

        }



        /* Premium Amount Display */

        .amount-s {

            background: linear-gradient(135deg, #C2185B, #C2185B);

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;

            background-clip: text;

            font-weight: 800;

            text-shadow: none;

        }



        /* Premium Total Price */

        #totalPrice,

        #ccPrice {

            font-weight: 800;

            color: #C2185B;

            text-shadow: 0 2px 4px rgba(36, 58, 124, 0.377);

        }



        /* Premium Tooltip Icons */

        .fa-info-circle {

            color: #C2185B;

            cursor: pointer;

            transition: all 0.3s ease;

        }



        .fa-info-circle:hover {

            color: #C2185B;

            transform: scale(1.2);

        }



        /* Premium Hint Text */

        .checkout_hint {

            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));

            border-left: 4px solid #dc3545;

            padding: 12px 15px;

            border-radius: 8px;

            margin-top: 10px;

            animation: fadeInUp 0.3s ease;

        }



        /* Premium HR Separators */

        hr {

            border: none;

            height: 2px;

            background: linear-gradient(90deg, transparent, rgba(36, 58, 124, 0.377), transparent);

            margin: 20px 0;

        }



        /* Premium Smooth Scroll */

        html {

            scroll-behavior: smooth;

        }



        /* Premium Heading Styles */

        .h3book {

            color: #4D2375;

            font-weight: 700;

            text-shadow: 0 2px 4px rgba(77, 35, 117, 0.1);

        }



        .h4booking {

            color: #4D2375;

            font-weight: 700;

        }



        .booking-second-sec-h2 {

            color: #1a1a1a;

            font-weight: 700;

            transition: color 0.3s ease;

        }



        .booking-second-sec-p {

            color: #666;

            font-weight: 500;

        }



        /* Premium Focus Outline */

        *:focus {

            outline: none;

        }



        /* Premium Overlay */

        .overlay {

            background: rgba(0, 0, 0, 0.7);

            backdrop-filter: blur(5px);

            z-index: 9998;

        }



        /* Premium Card Images in Sidebar */

        .img-crd {

            border-radius: 10px;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);

        }



        /* Premium Travel Details CSS */

        .travel-css {

            transition: all 0.3s ease;

        }



        /* Premium Link Styles */

        a {

            color: #C2185B;

            transition: color 0.3s ease;

        }



        a:hover {

            color: #C2185B;

            text-decoration: none;

        }

        }
    </style>

    <section id="room-listings" class="innerpage-wrapper booking_section js-booking-page apb-booking-page">



        <div id="room-listing-blocks" class="innerpage-section-padding">



            <div class="container container-min-w-booking">



                <div class="row frombgbooking">





                    

                    @include('partials.booking-summary-card')

                    <div class="booking-card">

                        <ul id="room-list" class="list-unstyled">



                            <!-- <li id="room-list-1">-->

                            <!--    <form id="personal_details_form">-->

                            <!--        <div class="room-list-block">-->

                            <!--            <div class="row">-->



                            <!--            </div>-->

                            <!--    </form>-->

                            <!--</li>-->









                            <li id="room-list-1">



                                <form id="personal_details_form">



                                    <div class="room-list-block">



                                        <div class="row">

                                            <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12 room-text section-borders p-0">



                                                <div class="">



                                                    <div class="booking-heading apb-personal-heading">
                                                        <h3 class="speedy-hding">Personal Details</h3>
                                                        <p class="apb-heading-sub">Booking reference and parking procedures will be sent to the below email address.</p>
                                                    </div>

                                                    <div class="col-lg-12 apb-personal-form">

                                                        <div class="apb-field-row apb-field-row--email">
                                                            <div class="apb-field apb-field--email">
                                                                <label class="lable" for="email">
                                                                    Email Address<span class="required-field">*</span>
                                                                    <span class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top" title="This is the email address you will receive confirmation which includes booking reference and parking procedures"></span>
                                                                </label>
                                                                <input type="email" class="form-control bf-inptfld" name="email" id="email" value="{{ old('email', $email ?? '') }}" required oninput="checkFields()">
                                                            </div>
                                                        </div>

                                                        <div class="apb-field-row apb-field-row--name">
                                                            <div class="apb-field apb-field--title">
                                                                <label class="lable" for="title">Title</label>
                                                                <select required class="bf-slctfld bookingselect_height form-control" name="gender" id="title">
                                                                    <option value="Mr">Mr</option>
                                                                    <option value="Mrs">Mrs</option>
                                                                    <option value="Miss">Miss</option>
                                                                    <option value="Ms">Ms</option>
                                                                </select>
                                                            </div>
                                                            <div class="apb-field apb-field--firstname">
                                                                <label class="lable" for="firstname">First Name<span class="required-field">*</span></label>
                                                                <input class="form-control bf-inptfld" required type="text" placeholder="First Name" name="firstname" id="firstname" oninput="checkFields()" value="">
                                                            </div>
                                                            <div class="apb-field apb-field--lastname">
                                                                <label class="lable" for="lastname">Last Name<span class="required-field">*</span></label>
                                                                <input class="form-control bf-inptfld" type="text" placeholder="Last Name" name="lastname" required id="lastname" oninput="checkFields()" value="">
                                                            </div>
                                                        </div>

                                                        <div class="apb-field-row apb-field-row--mobile">
                                                            <div class="apb-field apb-field--mobile">
                                                                <label class="lable" for="contactno">
                                                                    Mobile Number<span class="required-field">*</span>
                                                                    <span class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top" title="We may contact you on this number regarding your booking"></span>
                                                                </label>
                                                                <input class="form-control bf-inptfld" type="number" placeholder="Mobile" name="contactno" id="contactno" required disabled value="">
                                                            </div>
                                                        </div>

                                                        <p class="checkout_hint filled-hidden">Please enter your first name, last name and email address to enable the mobile number field.</p>

                                                    </div><!-- end apb-personal-form -->



                                                </div>



                                            </div><!-- end columns -->



                                        </div><!-- end row -->



                                    </div><!-- end room-list-block -->



                                </form>



                            </li><!-- end list-item -->





                            <li id="room-list-1">



                                <form id="vechile_detail">



                                    <div class="room-list-block">



                                        <div class="row">



                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 room-text section-borders p-0">



                                                <div class="">



                                                    <div class="booking-heading apb-vehicle-heading">
                                                        <h3 class="speedy-hding">Vehicle Details</h3>
                                                        <div class="booking-heading-paragh">
                                                            <small>
                                                                <span class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top" title="We will set your vehicle details to be confirmed if you select No. You can add these details at a later stage by either logging in to your account or by calling customer services."></span>
                                                                Do you have Vehicle details?
                                                                <label class="select-label text-white" for="veh_yes">
                                                                    <input class="flightdetailsyes1" name="vehdetails" id="veh_yes" type="radio" checked value="Yes"> Yes
                                                                </label>
                                                                <label class="select-label text-white" for="veh_no">
                                                                    <input class="flightdetailsyes1" name="vehdetails" id="veh_no" type="radio" value="No"> No
                                                                </label>
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="apb-vehicle-form" id="vechile-detail">
                                                        <div class="apb-field-row apb-field-row--vehicle">
                                                            <div class="apb-field apb-field--reg">
                                                                <label class="lable" for="registration">
                                                                    Vehicle Registration
                                                                    <span class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top" title="Enter your vehicle registration number. You can update this later if needed."></span>
                                                                </label>
                                                                <input class="form-control bf-inptfld" type="text" required name="registration" id="registration" placeholder="Registration No" value="" />
                                                            </div>
                                                            <div class="apb-field apb-field--make">
                                                                <label class="lable" for="make">Vehicle Make</label>
                                                                <input class="form-control bf-inptfld" type="text" required name="make" id="make" placeholder="Make" value="" />
                                                            </div>
                                                            <div class="apb-field apb-field--color">
                                                                <label class="lable" for="color">Vehicle Color</label>
                                                                <input class="form-control bf-inptfld" type="text" required name="color" id="color" placeholder="Color" value="" />
                                                            </div>
                                                            <div class="apb-field apb-field--model">
                                                                <label class="lable" for="model">Vehicle Model</label>
                                                                <input class="form-control bf-inptfld" type="text" required name="model" id="model" placeholder="Model" value="" />
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>



                                            </div><!-- end columns -->



                                        </div><!-- end row -->



                                    </div><!-- end room-list-block -->



                                </form>



                            </li><!-- end list-item -->



                            <li id="room-list-1">



                                <form id="travel_detail">



                                    <div class="room-list-block">



                                        <div class="row">



                                            <div class="col-xs-12   col-sm-12  col-md-12  col-lg-12 room-text section-borders p-0">

                                                <div class="">



                                                    <div class="booking-heading apb-travel-heading">
                                                        <h3 class="speedy-hding">Travel Details</h3>
                                                        <div class="booking-heading-paragh">
                                                            <small>
                                                                <span class="fa fa-info-circle cls-pointer" data-toggle="tooltip" data-placement="top" title="We will set your travel details to be confirmed if you select No. You can add these details at a later stage by either logging in to your account or by calling customer services."></span>
                                                                Do you have Travel details?
                                                                <label class="select-label text-white" for="travel_yes">
                                                                    <input class="flightdetailsyes" name="flightdetails" id="travel_yes" type="radio" checked value="Yes"> Yes
                                                                </label>
                                                                <label class="select-label text-white" for="travel_no">
                                                                    <input class="flightdetailsyes" name="flightdetails" id="travel_no" type="radio" value="No"> No
                                                                </label>
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="apb-travel-form" id="travel-detail">
                                                        <div class="apb-field-row apb-field-row--travel">
                                                            <div class="apb-field apb-field--depart-terminal">
                                                                <label class="lable" for="departterminal">Drop-Off Terminal:</label>
                                                                <select class="form22 bf-slctfld form-control" id="departterminal" name="departterminal">
                                                                    <option value="" selected="">Select Terminal</option>
                                                                    @foreach ($terminals as $terminal)
                                                                        <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="apb-field apb-field--return-terminal" id="return_terminal1">
                                                                <label class="lable" for="arrivalterminal">Return Terminal:</label>
                                                                <select class="form22 bf-slctfld form-control" id="arrivalterminal" name="arrivalterminal">
                                                                    <option value="" selected="">Select Terminal</option>
                                                                    @foreach ($terminals as $terminal)
                                                                        <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="apb-field apb-field--return-flight" id="return_terminal">
                                                                <label class="lable" for="returnflight">Return Flight Number:</label>
                                                                <input type="text" class="form-control bf-inptfld" name="returnflight" id="returnflight" placeholder="Optional" value="" />
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>



                                            </div><!-- end columns -->



                                        </div><!-- end row -->



                                    </div><!-- end room-list-block -->



                                </form>



                            </li><!-- end list-item -->

                            

                            <li id="room-list-1">
                                <div class="room-list-block">
                                    <div class="shield-p">
                                     <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 361 424.85" style="height: 45px;width: 67px;"><defs><style>.cls-1{fill:#00d66f;}.cls-2{fill:#fff;}</style></defs><path class="cls-1" d="M357.39,88.45C275.26,71.07,286.32,0,286.32,0H74.68S85.74,71.07,3.61,88.45c0,0-44.22,200.58,176.89,336.4C401.61,289,357.39,88.45,357.39,88.45Z"></path><path class="cls-2" d="M91.37,216.14c-2-46.14,32-87.28,78.93-92.51,52.2-5.82,93.77,32.54,98.83,78.43A89.23,89.23,0,1,1,91.37,216.14Zm69.26,14.28a10.59,10.59,0,0,1-1.44-.69c-4.44-4.39-8.84-8.83-13.31-13.19-6.23-6-14.38-6.38-20-.88s-5.38,14,.83,20.34q11.07,11.25,22.3,22.35c7.07,7,14.59,7.1,21.6.1q30.93-30.85,61.72-61.85c6.31-6.35,6.69-14.7,1.12-20.36-5.81-5.9-13.87-5.48-20.59,1.23Q191,199.28,169.24,221.18C166.24,224.2,163.43,227.41,160.63,230.42Z"></path></svg>
                                     <p> We do not store Card information, entered card detail is SSL Secure &amp; Encrypted.</p>
                                    </div>
                                </div>
                            </li><!-- end list-item -->





                            <!--<hr/>-->





                            <li id="room-list-1">

 

                                <div class="room-list-block">

 

                                    <div class="row ">



                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 room-text section-borders p-0">
                                                <div class="apb-payment-heading">
                                                    <h3 class="speedy-hding">Payment Detail</h3>
                                                </div>

                                                <div class="apb-payment-body">
                                                    <div class="apb-payment-accept">
                                                        <h4 class="apb-payment-accept__title">We Accept</h4>
                                                        <div class="apb-payment-logos">
                                                            <img class="img-crd" src="{{ asset('assets/payzone/images/payzone_cards_accepted.png') }}" alt="We accept Visa, Mastercard, Amex and more">
                                                        </div>
                                                    </div>

                                                    @if ($settings['payment_type'] == 'stripe')

                                                <div class="paymentFrm " id="paymentFrm">

                                                    <div class="row-wr2">

                                                        <form method="post" class="">



                                                            {{ csrf_field() }}



                                                            <div id="creditDiv">



                                                                <a class="reset" href="#">

                                                                </a>



                                                                <div class="col-lg-12 margin15" style="display:none;">







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

                                                                            id="expiry_year">Expiration



                                                                        </div>



                                                                        <div class="baseline"></div>



                                                                    </div>



                                                                    <div class="col-lg-6">



                                                                        <label>Security Code</label>



                                                                        <span class="required-field">*</span>



                                                                        <div class="form-control bf-inptfld empty"

                                                                            type="text" id="cc_security_code"

                                                                            name="security_code"></div>



                                                                        <small>Enter the last 3 digit code on the back



                                                                            of



                                                                            your



                                                                            card



                                                                        </small>



                                                                        <div class="baseline"></div>



                                                                    </div>



                                                                </div>

                                                                <div class="apb-payment-card-block">
                                                                    <div id="checkoutPageError" class="dg-danger text-danger apb-payment-error hidden">Could not submit your request this time, please try again.</div>
                                                                    <div class="alert alert-danger apb-payment-error" id="c_error" style="display: none;">Could not submit your request this time, please check your Card details and try again.</div>

                                                                    <div class="card_chrge">
                                                                        <input type="hidden" value="airportParkingBooking" name="action" id="action">
                                                                        <input type="hidden" id="bookID" name="booking_id" value="0">
                                                                        <input type="hidden" id="referenceNo" name="reference_no" value="">
                                                                        <input type="hidden" id="aphactivestripe" name="aphactive" value="{{ $data['aphactive'] }}">
                                                                        <input type="hidden" id="speed_park_active" name="speed_park_active" value="">
                                                                        <input type="hidden" id="site_codename" name="site_codename" value="">
                                                                        <input type="hidden" id="edinactive" name="edinactive" value="">
                                                                        <input type="hidden" id="edin_search" name="edin_search" value="">
                                                                    </div>

                                                                    <div class="card_chrge apb-card-element-wrap">
                                                                        <input type="hidden" id="intent_secret" name="intent_secret" value="">
                                                                        <div id="card-element"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="apb-payment-charge">
                                                                    <span class="apb-payment-charge__label">Your Card Will Be Charged</span>
                                                                    <span class="apb-payment-charge__badge">£<span id="ccPrice">0</span></span>
                                                                </div>



                                                            </div><!--#creditDiv-->

                                                            <div id="imgloader" style="display:none; text-align:center; margin:5px;">
                                                                <img src="{{ asset('theme/images/timeloader.gif') }}" style="width:50px;" alt="">
                                                            </div>

                                                            <div class="apb-payment-terms">
                                                                <input type="checkbox" id="terms_condition" name="terms_condition" value="terms_condition" required>
                                                                <label for="terms_condition">I agree to the <a href="{{ url('terms-and-conditions') }}" target="_blank">terms and conditions</a></label>
                                                            </div>

                                                            <button class="btn cnf_booking btn-lg btn-warning" type="submit" id="bookingButton1">
                                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                                Make Secure Payment
                                                            </button>


                                                            <div class="error" role="alert">



                                                                <svg xmlns="http://www.w3.org/2000/svg" width="17"

                                                                    height="17" viewBox="0 0 17 17">



                                                                    <path class="base" fill="#000"

                                                                        d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z">

                                                                    </path>



                                                                    <path class="glyph" fill="#FFF"

                                                                        d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z">

                                                                    </path>



                                                                </svg>



                                                                <span class="message"></span>

                                                            </div>



                                                            <div id="error_personal_detail"

                                                                style="color:#f20; font-weight:bold; text-align:center;">

                                                            </div>







                                                            <div class="col-md-12">



                                                                <div class="styledpadding">



                                                                    <!--<label for="subscribe">



                                                                                <input type="checkbox" name="subscribe"



                                                                                       class="styled"



                                                                                       value="1">



                                                                                Subscribe for Regular Customer discount Code.



                                                                            </label>-->



                                                                </div>



                                                            </div>







                                                        </form>

                                                    </div>











                                                </div>

                                                @endif



                                                @if ($settings['payment_type'] == 'payzone')

                                                    <img class="img-responsive"

                                                        src="{{ asset('assets/payzone/images/payzone_cards_accepted.png') }}">

                                            </div>



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

                                                                name="CardNumber" id="CardNumber" value="" required

                                                                placeholder="XXXX XXXX XXXX XXXX"

                                                                data-inputmask="'mask': '9999 9999 9999 9999'"

                                                                pattern="\d{4} \d{4} \d{4} \d{4}" class="masked" />



                                                        </div>



                                                    </div>



                                                    <div id='CardSectionTop' class="col-lg-12 margin15">



                                                        <div class="col-lg-6">



                                                            <label for='CV2'>CV2</label>



                                                            <input class="form-control bf-inptfld" type="text"

                                                                name="CV2" value="" required maxlength="4"

                                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57' />



                                                            </span>



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



                                                            </span>







                                                        </div>



                                                    </div>











                                                </div>



                                                <div class="card_chrge">



                                                    <h3 class="total_charged">Your Card Will Be Charged <strong>£<span

                                                                id="ccPrice">00.00</span></strong></h3>



                                                    <!--<label><strong>Your Booking Will Be Subject to Our <a href="http://localhost/fly/terms-and-conditions" target="_blank">Terms and Conditions</a></strong></label>-->



                                                </div>



                                                <div class="col-md-12">



                                                    <div class="styledpadding">



                                                        <label for="subscribe">



                                                            <input type="checkbox" name="subscribe" class="styled"

                                                                value="1">



                                                            Subscribe for Regular Customer discount Code.



                                                        </label>



                                                    </div>



                                                </div>



                                                <div class="card_chrge">



                                                    <input type="hidden" value="airportParkingBooking" name="action"

                                                        id="action">



                                                    <input type="hidden" id="bookID" name="booking_id"

                                                        value="0">



                                                    <input type="hidden" id="referenceNo" name="reference_no"

                                                        value="">



                                                    <input type="hidden" id="aphactivepayzone" name="aphactive"

                                                        value="{{ $data['aphactive'] }}">



                                                    <input type="hidden" id="speed_park_active" name="speed_park_active"

                                                        value="">



                                                    <input type="hidden" id="site_codename" name="site_codename"

                                                        value="">



                                                    <input type="hidden" id="edinactive" name="edinactive"

                                                        value="">



                                                    <input type="hidden" id="edin_search" name="edin_search"

                                                        value="">



                                                </div>



                                                <div id="form_errors"></div>



                                                <div id="error_personal_detail" style="color:red; padding-left: 27px;">

                                                </div>







                                                <button id="booking_button"

                                                    class="btn btn-lg btn-yellow center-block cnf_booking" type="button"

                                                    onclick="payzone_submit()"

                                                    data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing"><i class="fa fa-lock" aria-hidden="true"></i> Make Secure Payment</button>



                                            </form>



                                            {{-- PAYZONE FORM END --}}

                                            @endif

                                                </div><!-- end apb-payment-body -->











                                        </div><!-- end room-info -->







                                    </div><!-- end columns -->



                                </div><!-- end row -->



                    </div><!-- end room-list-block -->

                            



                    </li><!-- end list-item -->











                    </ul>







                </div><!-- end booking-card -->



            </div><!-- end row frombgbooking -->

        </div><!-- end container -->



        </div><!-- end room-listing-blocks -->







    </section>







    @php
        $apiBookingAmount = (float) ($data['booking_amount'] ?? 0);
        $customerParkingPrice = $apiBookingAmount;
        if (($data['park_api'] ?? '') === 'bookfhr' && isset($data['new_price']) && $data['new_price'] !== '' && $data['new_price'] !== null) {
            $customerParkingPrice = (float) $data['new_price'];
        }
        $total_amount = $customerParkingPrice + ($data['discount_amount'] ?? 0);
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

        $(document).ready(function () {

            function isMobile() {

                return window.innerWidth <= 768; // Adjust breakpoint if needed

            }



            if (isMobile()) {

                $('[data-toggle="tooltip"]').tooltip({ trigger: 'click' });

            } else {

                $('[data-toggle="tooltip"]').tooltip();

            }

        });

    </script>

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



                            var id = $('input[name=vehdetails]:checked').val();



                            if (id == 'Yes') {



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



                            var id = $('input[name=vehdetails]:checked').val();



                            if (id == 'Yes') {



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



                            var id = $('input[name=vehdetails]:checked').val();



                            if (id == 'Yes') {



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



                            var id = $('input[name=vehdetails]:checked').val();



                            if (id == 'Yes') {



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



                            var id = $('input[name=flightdetails]:checked').val();



                            if (id == 'Yes') {



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



                            var id = $('input[name=flightdetails]:checked').val();



                            if (id == 'Yes') {



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



                            var id = $('input[name=flightdetails]:checked').val();



                            if (id == 'Yes') {



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



             showSpinner();



            ap_processCheckout();



            $('input[name=flightdetails]').on('change', function() {



                var id = $('input[name=flightdetails]:checked').val();



                if (id == 'No') {



                    $('#flightnumber').val('TBA');



                    $('#returnflight').val('TBA');



                    $('#travel-detail').stop(true, true).slideUp(300, function () {
                        $(this).css({ height: '', overflow: '', display: 'none' });
                    });



                } else {



                    $('#flightnumber').val('');



                    $('#returnflight').val('');



                    $('#travel-detail').stop(true, true).slideDown(300, function () {
                        $(this).css({ height: '', overflow: '' });
                    });



                }



            });



            $('input[name=vehdetails]').on('change', function() {



                var id = $('input[name=vehdetails]:checked').val();



                if (id == 'Yes') {



                    $('#make').val('');



                    $('#model').val('');



                    $('#color').val('');



                    $('#registration').val('');



                    $('#vechile-detail').stop(true, true).slideDown(300, function () {
                        $(this).css({ height: '', overflow: '' });
                    });



                } else {



                    $('#make').val('TBA');



                    $('#model').val('TBA');



                    $('#color').val('TBA');



                    $('#registration').val('TBA');



                    $('#vechile-detail').stop(true, true).slideUp(300, function () {
                        $(this).css({ height: '', overflow: '', display: 'none' });
                    });



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



                data['reference_no'] = $('#referenceNo').val();



                data['booking_fee'] = "{{ $settings['booking_fee'] }}";



                data['_token'] = "{{ csrf_token() }}";



                data['refr'] = $('#refr').val();



                //alert(data['refr']);



                data['booking_id'] = $('#bookID').val();



                data['aphactive'] = $('#aphactivebook').val();





                data['total_amount'] = $('#bookingprice').val();



                data['park_api'] = $('#bookingDetails input[name="park_api"]').val();

                data['new_price'] = $('#bookingDetails input[name="new_price"]').val();

                data['bookfhrSearchId'] = $('#bookfhrSearchId').val();

                data['bookfhrOptionId'] = $('#bookfhrOptionId').val();



                if (data['action'] == 'airportParkingBooking') {



                    //

                    data['discount'] = "{{ $data['discount_amount'] }}";







                    data['company_id'] = $('#bookingDetails input[name="company_id"]').val(),

                        data['product_code'] = $('#bookingDetails input[name="product_code"]').val(),

                        data['parking_type'] = $('#bookingDetails input[name="parking_type"]').val(),



                        data['pickdate'] = $('#bookingDetails input[name="pickdate"]').val(),



                        data['dropdate'] = $('#bookingDetails input[name="dropdate"]').val(),



                        data['droptime'] = $('#bookingDetails input[name="droptime"]').val(),



                        data['picktime'] = $('#bookingDetails input[name="picktime"]').val(),

                         data['email'] = $('#personal_details_form input[name="email"]').val(),



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




                $.post('{{ route('checkBooking') }}', data, function(data) {



                   











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

                 email: $('#bookingDetails input[name="email"]').val(),



                pl_id: $('#bookingDetails input[name="pl_id"]').val(),



                sku: $('#bookingDetails input[name="sku"]').val(),



                edin_active: $('#bookingDetails input[name="edin_active"]').val(),



                speed_park_active: $('#bookingDetails input[name="speed_park_active"]').val(),



                site_codename: $('#bookingDetails input[name="site_codename"]').val(),



                passenger: $('#passenger').val(),



                promo: $('#bookingDetails input[name="promo"]').val(),



                bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),



                aphactive: $('#bookingDetails input[name="aphactive"]').val(),



                total_amount: {{ $total_amount }},



                park_api: $('#bookingDetails input[name="park_api"]').val(),

                new_price: $('#bookingDetails input[name="new_price"]').val(),

                bookfhrSearchId: $('#bookfhrSearchId').val(),

                bookfhrOptionId: $('#bookfhrOptionId').val(),



                smsfee: smsfee,



                canfee: canfee,



                action: 'booking_checkout'



            };



            data['_token'] = "{{ csrf_token() }}";



            //setProcessBar(75);



            $.post('{{ route('checkout') }}', data, function(data) {



                //console.log(data);



                $("#totalPrice").text(data.total_amount);



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



            }, 'json').fail(function() {
                hideSpinner();
            });



        }







        function validate_vechiledetail() {



            var html = '<label class="error error-vech" >This field is required.</label>';



            var id = $('input[name=vehdetails]:checked').val();







            $(".error-vech").remove();



            if (id == 'Yes') {



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











            var id2 = $('input[name=flightdetails]:checked').val();



            if (id2 == 'Yes') {



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



                                data['reference_no'] = $('#referenceNo').val();







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

                                data['email'] = $('#bookingDetails input[name="email"]').val();



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



                                data['aphactive'] = $('#aphactivebook').val();











                                //data['debug']       = 1



                                //}



                                try {



                                    //

                                    $('#booking_button').button('loading');



                                    $.post('{{ route('paymentwithPayzone') }}', data, function(data) {



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

        <!--Stripe Link — local path (avoid ASSET_URL live CDN mismatch)-->

        <script src="{{ url('/assets/stripe/index.js') }}?v={{ @filemtime(public_path('assets/stripe/index.js')) }}"></script>



        <script src="{{ url('/assets/stripe/example2.js') }}"></script>



        <script src="{{ url('/assets/stripe/l10n.js') }}"></script>

    @endif





    <script>

        function checkFields() {

            var nameValue = document.getElementById('firstname').value.trim();

            var name2Value = document.getElementById('lastname').value.trim();

            var emailValue = document.getElementById('email').value.trim();

            var phoneField = document.getElementById('contactno');



            // Disable phone field if both name and email are empty

            if (nameValue !== '' && emailValue !== '' && name2Value !== '') {

                phoneField.disabled = false;

            } else {

                phoneField.disabled = true;

            }

        }

    </script>

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

