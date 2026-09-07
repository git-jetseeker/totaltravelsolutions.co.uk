@extends('layouts.main')

@section('stylesheets')
    <link property="stylesheet" rel='stylesheet' href='{{ asset('assets/page.css') }}' type='text/css' media='all' />


    <link rel="stylesheet" href='{{ asset('assets/payzone/payzone_gateway.css?v=1.2') }}' />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking.css?v=20250901') }}">
@endsection
@include('layouts.nav')

@section('content')
    <style type="text/css">
        .StripeElement {
            box-sizing: border-box;

            height: 40px;

            padding: 10px 12px;

            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fde4e4;

            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>


    <section id="lounge-sec224" class="js-booking-page">
        <div class="container" id="booking-loung">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="booking-heading">Booking Detail Form</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 box-div">
                    <form id="personal_details_form">
                        <h2 class="personal">Personal Information</h2>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>Title</label>
                                <select class="form-control" name="title" id="title">
                                    <option value="Mr">Mr</option>

                                    <option value="Mrs">Mrs</option>

                                    <option value="Miss">Miss</option>

                                    <option value="Ms">Ms</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label>First Name</label>
                                <input type="text" class="form-control" required placeholder="First Name"
                                    name="firstname" id="firstname" value="">
                            </div>
                            <div class="form-group col-md-5">
                                <label>Last Name</label>
                                <input type="text" class="form-control"placeholder="Last Name" name="lastname" required
                                    id="lastname" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="info@gmail.com" name="email"
                                    id="email" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Confirm Email</label>
                                <input type="email" class="form-control" placeholder="Confirm Email" email="true"
                                    equalTo="#email" name="c_email" id="c_email" required value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Phone Number</label>
                                <input type="number" class="form-control" placeholder="542 853 6584" name="contactno"
                                    id="contactno" required value="">
                            </div>
                        </div>
                    </form>


                    <hr class="hr">

                    <form id="travel_details_form">
                        <h2 class="personal">Flight Information</h2>
                        <div class="row">
                            <div class="col-lg-4 margin-travel">
                                <label class="normal-font">Flight arriving between:</label><span class="required-field">
                                    *</span>
                                <select class="form-control" name="flight_time" id="flight_time">
                                    <option value="01:00">01:00 - 02:00</option>
                                    <option value="02:00">02:00 - 03:00</option>
                                    <option value="03:00">03:00 - 04:00</option>
                                    <option value="04:00">04:00 - 05:00</option>
                                    <option value="05:00">05:00 - 06:00</option>
                                    <option value="06:00">06:00 - 07:00</option>
                                    <option value="07:00">07:00 - 08:00</option>
                                    <option value="08:00">08:00 - 09:00</option>
                                    <option value="09:00" selected="">09:00 - 10:00</option>
                                    <option value="10:00">10:00 - 11:00</option>
                                    <option value="11:00">11:00 - 12:00</option>
                                    <option value="12:00">12:00 - 13:00</option>
                                    <option value="13:00">13:00 - 14:00</option>
                                    <option value="14:00">14:00 - 15:00</option>
                                    <option value="15:00">15:00 - 16:00</option>
                                    <option value="16:00">16:00 - 17:00</option>
                                    <option value="17:00">17:00 - 18:00</option>
                                    <option value="18:00">18:00 - 19:00</option>
                                    <option value="19:00">19:00 - 20:00</option>
                                    <option value="20:00">20:00 - 21:00</option>
                                    <option value="21:00">21:00 - 22:00</option>
                                    <option value="22:00">22:00 - 23:00</option>
                                    <option value="23:00">23:00 - 00:00</option>
                                </select>
                            </div>

                            <div class="col-lg-4" id="return_terminal1">

                                <label class="normal-font">Arrival Flight Number:</label><span class="required-field">
                                    *</span>

                                <input type="text" class="form-control bf-inptfld" name="arrival_flight"
                                    id="arrival_flight" placeholder="Arrival Flight Number" required value="" />

                            </div>

                            <div class="col-lg-4" id="return_terminal">

                                <label class="normal-font">Return Flight Number:</label>

                                <input type="text" class="form-control bf-inptfld" name="return_flight"
                                    id="return_flight" placeholder="Return Flight Number" required value="" />

                            </div>

                        </div>
                    </form>

                    <hr class="hr">


                    <div class="paymentFrm" id="paymentFrm">
                        <h2 class="personal">Payment</h2>
                        <img class="img-responsive"
                            style="display: block;  max-width: 50%; margin-bottom: 12px !important; height: auto; margin: auto;"
                            src="{{ asset('assets/payzone/images/payzone_cards_accepted.png') }}">
                        <form method="post">

                            {{ csrf_field() }}

                            <div id="creditDiv">
                                <a class="reset" href="#"></a>

                                <div class="col-lg-12 margin15">

                                    <div class="col-lg-6" style="display: none;">

                                        <label>Card Number </label>

                                        <span class="required-field">*</span>

                                        <div class="form-control bf-inptfld empty" type="text" id="cc_card_no"
                                            name="card_no" style=""></div>

                                        <div class="baseline"></div>

                                    </div>

                                    <div class="col-lg-6" style="display: none;">

                                        <label>Card Holder Name </label>

                                        <span class="required-field">*</span>

                                        <input class="form-control empty" type="text" id="cc_card_title"
                                            name="card_title"> </input>

                                        <div class="baseline"></div>

                                    </div>

                                </div>

                                <div class="col-lg-12 margin15" style="display: none;">

                                    <div class="col-lg-6">

                                        <label>Expiry Month / Year</label>

                                        <span class="required-field">*</span>

                                        <div class="form-control empty bf-inptfld" id="expiry_year">Expiration</div>

                                        <div class="baseline"></div>

                                    </div>

                                    <div class="col-lg-6">

                                        <label>Security Code</label>

                                        <span class="required-field">*</span>

                                        <div class="form-control bf-inptfld empty" type="text" id="cc_security_code"
                                            name="security_code"></div>

                                        <small>Enter the last 3 digit code on the back of your card </small>

                                        <div class="baseline"></div>

                                    </div>

                                </div>

                                <div class="card_chrge">

                                    <input type="hidden" value="airportParkingBooking" name="action" id="action">

                                    <input type="hidden" id="bookID" name="booking_id" value="0">

                                    <input type="hidden" id="referenceNo" name="reference_no" value="">


                                    <input type="hidden" id="speed_park_active" name="speed_park_active"
                                        value="">

                                    <input type="hidden" id="site_codename" name="site_codename" value="">

                                    <input type="hidden" id="edinactive" name="edinactive" value="">

                                    <input type="hidden" id="edin_search" name="edin_search" value="">

                                </div>

                                <div class="col-lg-12">

                                    <div class="alert alert-danger" id="c_error"
                                        style="display: none; margin-top: 10px; font-weight: bold;">

                                        Could not submit your request this time, please check your Card details and try
                                        again.

                                    </div>

                                </div>
                            </div><!--#creditDiv-->

                            <div class="card_chrge">
                                <input type="hidden" id="intent_id" name="intent_id" value="">
                                <input type="hidden" id="intent_secret" name="intent_secret" value="">
                                <!-- placeholder for Elements -->
                                <div id="card-element"></div>
                                <center>
                                    <h4 style="margin-top:10px;">
                                        <span class="badge badge-warning" style="background-color: #31124b;">Your Card
                                            Will Be Charged</span>
                                        <span class="badge badge-danger" style="background-color: #1773b9; color: white;">
                                            <strong>£<span id="ccPrice">0</span></strong>
                                        </span>
                                    </h4>
                                </center>
                            </div>
                            <br>
                            <div id="imgloader" style="display:none; text-align:center; margin:5px;">
                                <img src="{{ asset('theme/images/timeloader.gif') }}" style="width:50px;">
                            </div>
                            <center>
                                <button class="btn btn-info" type="submit" id="bookingButton">Confirm Booking</button>
                            </center>

                            <div class="error" role="alert">
                                <span class="message"></span>
                            </div>
                            <div id="error_personal_detail" style="color:#f20; font-weight:bold; text-align:center;">
                            </div>

                            <div class="col-md-12">
                                <div class="styledpadding"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="booking-info">
                        <div class="booking-detail">
                            <h3>Booking Detail</h3>
                        </div>
                        <table class="table">
                            <!--img src='{{ $data['logo'] }}' class="img-responsive" -->
                            <h6 class="lounge-name">{{ $data['transfer_name'] }}</h6>
                            <tbody>
                                <tr>
                                    <td class="bold">Arrival Date</td>
                                    <td class="text-right">{{ $data['arrival_date'] }} at {{ $data['arrival_time'] }}</td>
                                </tr>
                                <tr>
                                    <td class="bold">Return Date</td>
                                    <td class="text-right">{{ $data['return_date'] }} at {{ $data['return_time'] }}</td>
                                </tr>

                                <tr>
                                    <td class="bold">Booking Price</td>
                                    <td class="text-right">£{{ $data['booking_amount'] }}</td>
                                </tr>

                                <tr>
                                    <td class="bold">Booking Fee</td>
                                    <td class="text-right">£{{ $settings['booking_fee'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="booking-detail" id="bookingDetails">
                            <h3>Total Pay : £ <span id="totalPrice">0</span></h3>
                            <input type="hidden" id="bookingprice" value="{{ $data['booking_amount'] }}" />

                            <input type="hidden" id="alltotal" value="{{ $data['booking_amount'] }}" />



                            <input type="hidden" name="company_id" value="{{ $data['company_id'] }}">
                            <input type="hidden" name="product_code" value="{{ $data['product_code'] }}">

                            <input type="hidden" name="transfer_name" value="{{ $data['transfer_name'] }}">

                            <input type="hidden" name="booking_url" value="{{ $data['booking_url'] }}">

                            <input type="hidden" name="arrival_date" value="{{ $data['arrival_date'] }}">

                            <input type="hidden" name="arrival_time" value="{{ $data['arrival_time'] }}">


                            <input type="hidden" name="return_date" value="{{ $data['return_date'] }}">

                            <input type="hidden" name="return_time" value="{{ $data['return_time'] }}">

                            <input type="hidden" name="adults" value="{{ $data['adults'] }}">

                            <input type="hidden" name="children" value="{{ $data['children'] }}">

                            <input type="hidden" name="infants" value="{{ $data['infants'] }}">

                            <input type="hidden" name="loc_type" value='{{ $data['loc_type'] }}'>

                            <input type="hidden" name="loc_code" value='{{ $data['loc_code'] }}'>

                            <input type="hidden" name="loc_name" value='{{ $data['loc_name'] }}'>

                            <input type="hidden" name="loc_lat" value='{{ $data['loc_lat'] }}'>

                            <input type="hidden" name="loc_long" value='{{ $data['loc_long'] }}'>

                            <input type="hidden" name="loc_id" value='{{ $data['loc_id'] }}'>

                            <input type="hidden" name="loc_country" value='{{ $data['loc_country'] }}'>


                            <input type="hidden" name="loc_type_drop" value='{{ $data['loc_type_drop'] }}'>

                            <input type="hidden" name="loc_code_drop" value='{{ $data['loc_code_drop'] }}'>

                            <input type="hidden" name="loc_name_drop" value='{{ $data['loc_name_drop'] }}'>

                            <input type="hidden" name="loc_lat_drop" value='{{ $data['loc_lat_drop'] }}'>

                            <input type="hidden" name="loc_long_drop" value='{{ $data['loc_long_drop'] }}'>

                            <input type="hidden" name="loc_id_drop" value='{{ $data['loc_id_drop'] }}'>

                            <input type="hidden" name="loc_country_drop" value='{{ $data['loc_country_drop'] }}'>

                            <input type="hidden" name="site_codename" value="">

                            <input type="hidden" name="park_api" value="{{ $data['park_api'] }}">

                            <input type="hidden" name="bookingfor" value="transfer">

                            <input type="hidden" name="incomplete" id="incomplete" value="yes">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    @php
        $total_amount = $data['booking_amount'];
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
        $("#bookingButton1").click(function(event) {
            //$("#personal_details_form").valid();
            if ($("#personal_details_form").valid() && $("#vechile_detail").valid() && $("#travel_detail")
                .valid()) {
                $('.error').html(
                    '<div class="alert alert-success" style=" width: 40%; margin: auto;text-align: center;padding: 10px;margin-top: 16px;"><strong>Success!</strong> This is test booking.</div>'
                );
            }
        });


        $(":input").inputmask();


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




            $('#contactno').change(function() {
                //hideMsgDiv($("#checkoutPageError"));

                if ($("#personal_details_form").valid()) {

                    var data = {};

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

                    data['booking_id'] = $('#bookID').val();


                    data['booking_amount'] = $('#bookingprice').val();

                    data['park_api'] = $('#bookingDetails input[name="park_api"]').val();

                    data['company_id'] = $('#bookingDetails input[name="company_id"]').val(),
                        data['product_code'] = $('#bookingDetails input[name="product_code"]').val(),
                        data['transfer_name'] = $('#bookingDetails input[name="transfer_name"]').val(),
                        data['booking_url'] = $('#bookingDetails input[name="booking_url"]').val(),


                        data['arrival_date'] = $('#bookingDetails input[name="arrival_date"]').val(),

                        data['arrival_time'] = $('#bookingDetails input[name="arrival_time"]').val(),


                        data['return_date'] = $('#bookingDetails input[name="return_date"]').val(),

                        data['return_time'] = $('#bookingDetails input[name="return_time"]').val(),

                        data['adults'] = $('#bookingDetails input[name="adults"]').val(),

                        data['children'] = $('#bookingDetails input[name="children"]').val(),

                        data['infants'] = $('#bookingDetails input[name="infants"]').val(),

                        data['arrival_time'] = $('#bookingDetails input[name="arrival_time"]').val(),


                        data['loc_type'] = $('#bookingDetails input[name="loc_type"]').val();
                    data['loc_code'] = $('#bookingDetails input[name="loc_code"]').val();
                    data['loc_name'] = $('#bookingDetails input[name="loc_name"]').val();
                    data['loc_lat'] = $('#bookingDetails input[name="loc_lat"]').val();
                    data['loc_long'] = $('#bookingDetails input[name="loc_long"]').val();
                    data['loc_id'] = $('#bookingDetails input[name="loc_id"]').val();
                    data['loc_country'] = $('#bookingDetails input[name="loc_country"]').val();

                    data['loc_type_drop'] = $('#bookingDetails input[name="loc_type_drop"]').val();
                    data['loc_code_drop'] = $('#bookingDetails input[name="loc_code_drop"]').val();
                    data['loc_name_drop'] = $('#bookingDetails input[name="loc_name_drop"]').val();
                    data['loc_lat_drop'] = $('#bookingDetails input[name="loc_lat_drop"]').val();
                    data['loc_long_drop'] = $('#bookingDetails input[name="loc_long_drop"]').val();
                    data['loc_id_drop'] = $('#bookingDetails input[name="loc_id_drop"]').val();
                    data['loc_country_drop'] = $('#bookingDetails input[name="loc_country_drop"]').val();

                    data['bookingfor'] = $('#bookingDetails input[name="bookingfor"]').val(),

                        data['smsfee'] = $("#smsfee").is(':checked') ? 'Yes' : 'No',

                        data['cancelfee'] = $("#cancelfee").is(':checked') ? 'Yes' : 'No',

                        data['incomplete'] = $('#bookingDetails input[name="incomplete"]').val(),


                        data['sku'] = $('#bookingDetails input[name="sku"]').val();

                    data['intent_id'] = $('#intent_id').val();

                    $.post('{{ route('checkBookingTransfer') }}', data, function(data) {

                        //console.log("data===", data);

                        if (data.booking_id > 0) {

                            if (data.available == "Yes") {

                                $("#bookID").val(data.booking_id);

                                $("#referenceNo").val(data.reference_no);

                                $("#incomplete").val('no');

                            } else {

                            }
                        }
                    }, 'json');

                }

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

                company_id: $('#bookingDetails input[name="company_id"]').val(),


                bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),

                total_amount: {{ $total_amount }},

                park_api: $('#bookingDetails input[name="park_api"]').val(),

                smsfee: smsfee,

                canfee: canfee,

                action: 'booking_checkout'

            };

            data['_token'] = "{{ csrf_token() }}";

            //setProcessBar(75);

            $.post('{{ route('transfer_checkout') }}', data, function(data) {

                //console.log(data);

                $("#totalPrice").text(data.total_amount);

                $("#ccPrice").text(data.total_amount);

                $("#ddPrice").val(data.total_amount);

                $("#alltotal").val(data.total_amount);

                $("#disAmount").val(data.discount_amount);
                $("#company_name").text(data.company_name);
                $("#intent_secret").val(data.intent_secret);
                $("#intent_id").val(data.intent_id);



                if (data.booking_amount > 0) {

                    $("#bookingPriceDiv").text('£' + data.booking_amount);

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

                    $("#bookfeeprice").text('£' + data.booking_fee);

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
    </script>


    @if ($settings['payment_type'] == 'stripe')
        <script src="https://js.stripe.com/v3/"></script>
        <script>window.STRIPE_PUBLIC_KEY = @json(config('services.stripe.key'));</script>

        <script src="{{ asset('assets/stripe/checkout_transfer.js?123') }}"></script>

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
