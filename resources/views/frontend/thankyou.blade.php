<?php
use App\Models\ref_tracking;
?>
@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-thankyou.css?v=20250901') }}">
<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
<style>
    .section {
        margin-top: 74px;
        padding: 40px;
    }

    #myfont {
        border-radius: 48px;
        padding: 0px;
    }

    .search1 {
        box-shadow: 0px 0px 13px -5px #F79F02;

    }

    #thankyou h1 {
        color: black !important;

    }

    .first-h1 {
        font-size: 35px;
        font-weight: bold;
    }

    .first-p {
        font-size: 18px;
    }

    @media only screen and (min-width: 992px) {
        .first-p {
            padding-left: 98px;
            padding-right: 98px;
        }
    }

    .li-h {
        font-size: 18px;
        font-weight: 600;
        color: black;
    }

    .li-p {
        font-size: 18px;
        font-weight: 500;
        color: black;
    }

    hr {}

    .rad {
        border-top-right-radius: 48px;
        border-top-left-radius: 48px;
    }

    .rad2 {
        border-bottom-right-radius: 48px;
        border-bottom-left-radius: 48px;
    }

    @media only screen and (max-width: 368px) {
        .section {
            margin-top: 120px;
        }
    }

    @media screen and (min-device-width: 331px) and (max-device-width: 400px) {
        .section {
            margin-top: 120px;
            padding: 0px;
        }
    }

    @media only screen and (max-width: 330px) {
        #myfont {
            padding-left: 16px;
            padding-right: 16px;
        }

        #print {
            margin-left: -28px !important;
            margin-right: -28px !important;
        }

        .row-m {
            margin-left: 15px !important;
        }
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    label,
    b,
    li {
        font-family: "Poppins", Sans-serif !important;
    }
</style>

<?php
$ip = request()->ip();
$refData['ref_url'] = session()->get('ref_url');
if (session()->get('bk_src') != '') {
    $refData['traffic_src'] = session()->get('bk_src');
} else {
    $refData['traffic_src'] = 'ORG';
}
$refData['agentID'] = '1';
$refData['user_ip'] = $ip;
$refData['current_url'] = \Request::fullUrl();
$refData['email'] = session()->get('userEmail');
$update = ref_tracking::create($refData);
?>

<div class="js-thankyou-page">
<section class="section">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="booking-result">
                    <div id="print" style="margin: 0px; padding: 0px;">
                        <div class="col-md-12 search1 js-thankyou-card" id="myfont" style="float: unset; margin: 0 auto;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <img src="{{ asset('thankuf.webp') }}" class="rad" style="width:100%"
                                        alt="thankuf">
                                </div>
                                <div id="thankyou" class="main-area"style="float: left;width: 100%;">
                                    <div class="top-bar">
                                        <div class="top-bar-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <h1 class="first-h1" style="text-align: center;">PAYMENT HAS NOW BEEN <span
                                                    style="color:#C2185B">CONFIRMED</span></h1>
                                        </div>
                                    </div>
                                    <div class="confirm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <p class="first-p" style="text-align: center;">
                                            Thank you for completing booking this is your booking confirmation with all
                                            your traveling details, Email has been sent to you in regards to your
                                            booking.
                                            If you do have any questions in the mean time please do not hesitate to
                                            contact us on bookings@totaltravelsolutions.co.uk</p>
                                        <div>
                                            <div class="detail col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="sub-detail" style="float: left; width: 100%;">
                                                    <div class="">
                                                        <h2 class="first-h1" style="text-align: center;">Your <span
                                                                style="color:#C2185B">Detail</h2>
                                                        <hr
                                                            style="border:1px solid #4D2375; border-top: none !important;">
                                                        <div class="row row-m">

                                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Name:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->first_name . ' ' . $booking->last_name }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Mobile number:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->phone_number }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Email:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->email }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <hr
                                                            style="border:1px solid #4D2375; border-top: none !important;">
                                                        <table class="table" border="0">
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                        <h2 class="first-h1" style="text-align: center;">Vehicle <span
                                                                style="color:#C2185B">Detail</h2>
                                                        <!--<h2 style="text-align: center;">Vehicle Detail</h2>-->
                                                        <hr
                                                            style="border:1px solid #4D2375; border-top: none !important;">
                                                        <div class="row row-m">

                                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Make:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->make }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Color:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->color }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Model:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->model }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Registration:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->registration }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <hr
                                                            style="border:1px solid #4D2375; border-top: none !important;">

                                                        <h2 class="first-h1" style="text-align: center;">Booking <span
                                                                style="color:#C2185B">Detail</h2>
                                                        <hr
                                                            style="border:1px solid #4D2375; border-top: none !important;">
                                                        <div class="row row-m">

                                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Booking Refrence
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->referenceNo }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Description
                                                                    </li>
                                                                    <li class="li-p">
                                                                        {{ $booking->airport->name }} airport parking
                                                                        {{ $booking->no_of_days }} Days
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                                <ul>
                                                                    <li class="li-h">
                                                                        Price:
                                                                    </li>
                                                                    <li class="li-p">
                                                                        £ {{ $booking->total_amount }}
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                        </div>
                                                        <hr
                                                            style="border:1px solid #4D2375; border-top: none !important;">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <img src="{{ asset('thankuss.webp') }}" class="rad2" style="width:100%"
                                    alt="thankuss">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if ($booking->traffic_src == 'WG')
        <script>
            (function(src) {
                var o = 'ITCVRQ';
                window[o] = window[o] || function() {
                    (window[o].q = window[o].q || []).push(arguments)
                };
                window[o].l = 1 * new Date();
                var s = document.createElement('script');
                var f = document.getElementsByTagName('script')[0];
                s.async = 1;
                s.src = src;
                f.parentNode.insertBefore(s, f);
            })('https://analytics.webgains.io/304298/main.min.js');
            ITCVRQ('set', 'trk.programId', '304298');
            ITCVRQ('set', 'cvr', {
                value: '{{ $booking->booking_amount }}',
                currency: 'GBP',
                language: 'EN',
                eventId: '',
                orderReference: '{{ $booking->referenceNo }}',
                comment: '',
                multiple: '',
                checksum: '',
                items: '{{ $booking->company->name }}',
                customerId: '',
                voucherId: 'NA'
            });
            ITCVRQ('conversion');
        </script>
    @endif




</section>
</div>

@if ($booking->traffic_src == 'POR')
    <script language=JavaScript
        src="https://portgk.com/create-sale?client=java&MerchantID=1234&SaleID={{ $booking->referenceNo }}&OrderValue={{ $booking->total_amount }}">
    </script>
    <noscript><img
            src="https://portgk.com/create-sale?client=img&MerchantID=1234&SaleID={{ $booking->referenceNo }}&OrderValue={{ $booking->total_amount }}"
            width="10" height="10" border="0"></noscript>
@endif

@if ($booking->traffic_src == 'PPC')
    !--Event snippet
    for Confirm Purchase of JET SEEKER conversion page-- >

    < script>
        gtag('event', 'conversion', {
        'send_to': 'AW-953599715/rK5QCLT88qcZEOON28YD',
        'transaction_id': '{{ $booking->referenceNo }}',
        'value': '{{ $booking->total_amount }}',
        'currency': 'GBP'
        });
        </script>
@endif

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all()->where('agent_id', '9');
    foreach ($settingsAll as $setting) {
        if ($setting->agent_id == '9') {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }
@endphp

@if (isset($site_settings_main['site_confirm_page_analytics']))
    {!! $site_settings_main['site_confirm_page_analytics'] !!}
@endif
@include('layouts.footer')
