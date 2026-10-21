@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-thankyou.css?v=20250901') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
<style>
    .section {
        margin-top: 74px;
        padding: 40px;
    }

    #myfont {
        border-radius: 48px;
        padding: 0;
    }

    .search1 {
        box-shadow: 0 0 13px -5px #F79F02;
    }

    .first-h1 {
        font-size: 35px;
        font-weight: bold;
        color: #000;
    }

    .first-p {
        font-size: 18px;
        color: rgba(0, 0, 0, 0.75);
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

    .rad {
        border-top-right-radius: 48px;
        border-top-left-radius: 48px;
    }

    .thankyou-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
        margin: 18px 0 10px;
    }

    .thankyou-actions .btn {
        min-width: 160px;
        padding: 10px 18px;
        border-radius: 6px;
        font-weight: 600;
    }

    .btn-primary-js {
        background: #C2185B;
        color: #fff;
        border: 1px solid #C2185B;
    }

    .btn-primary-js:hover {
        background: #9a1349;
        color: #fff;
    }

    .btn-default-js {
        background: #fff;
        color: #333;
        border: 1px solid #ccc;
    }

    h1, h2, h3, h4, h5, h6, p, label, b, li {
        font-family: "Poppins", Sans-serif !important;
    }

    @media only screen and (max-width: 400px) {
        .section {
            margin-top: 120px;
            padding: 0;
        }
        .first-h1 {
            font-size: 26px;
        }
    }
</style>

@php
    $pickupTs = $booking->pickup_datetime ? strtotime($booking->pickup_datetime) : null;
    $dropoffTs = $booking->dropoff_datetime ? strtotime($booking->dropoff_datetime) : null;
    $pickupLabel = $pickupTs ? date('l, d M Y', $pickupTs) . ' at ' . date('H:i', $pickupTs) : '—';
    $dropoffLabel = $dropoffTs ? date('l, d M Y', $dropoffTs) . ' at ' . date('H:i', $dropoffTs) : '—';
    $driverName = trim(($booking->title ? $booking->title . ' ' : '') . ($booking->first_name ?? '') . ' ' . ($booking->last_name ?? ''));
    $vehicleMeta = collect([
        $booking->vehicle_type,
        $booking->transmission,
        $booking->fuel_type,
    ])->filter()->implode(' · ');
@endphp

<div class="js-thankyou-page">
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="booking-result">
                    <div id="print" style="margin: 0; padding: 0;">
                        <div class="col-md-12 search1 js-thankyou-card" id="myfont" style="float: unset; margin: 0 auto;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <img src="{{ asset('thankuf.webp') }}" class="rad" style="width:100%" alt="Thank you">
                                </div>
                                <div id="thankyou" class="main-area" style="float: left; width: 100%;">
                                    <div class="top-bar">
                                        <div class="top-bar-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <h1 class="first-h1" style="text-align: center;">
                                                PAYMENT HAS NOW BEEN <span style="color:#C2185B">CONFIRMED</span>
                                            </h1>
                                            <div class="thankyou-actions">
                                                <a href="{{ route('carhire') }}" class="btn btn-primary-js">Book another car</a>
                                                <a href="{{ url('manage-booking') }}" class="btn btn-default-js">Manage booking</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="confirm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <p class="first-p" style="text-align: center;">
                                            Thank you for completing booking. This is your car hire confirmation with all your booking details.
                                            An email has been sent regarding your booking.
                                            If you have any questions please contact us on bookings@totaltravelsolutions.co.uk
                                        </p>

                                        <div class="detail col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="sub-detail" style="float: left; width: 100%;">

                                                <h2 class="first-h1" style="text-align: center;">
                                                    Your <span style="color:#C2185B">Detail</span>
                                                </h2>
                                                <hr style="border:1px solid #C2185B; border-top: none !important;">
                                                <div class="row row-m">
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Name:</li>
                                                            <li class="li-p">{{ $driverName }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Mobile number:</li>
                                                            <li class="li-p">{{ $booking->phone_number }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Email:</li>
                                                            <li class="li-p">{{ $booking->email }}</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <hr style="border:1px solid #C2185B; border-top: none !important;">
                                                <h2 class="first-h1" style="text-align: center;">
                                                    Vehicle <span style="color:#C2185B">Detail</span>
                                                </h2>
                                                <hr style="border:1px solid #C2185B; border-top: none !important;">
                                                <div class="row row-m">
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Vehicle:</li>
                                                            <li class="li-p">{{ $booking->vehicle_name ?: 'Car hire' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Supplier:</li>
                                                            <li class="li-p">{{ $booking->supplier_name ?: '—' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Vehicle type:</li>
                                                            <li class="li-p">{{ $vehicleMeta ?: '—' }}</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <hr style="border:1px solid #C2185B; border-top: none !important;">
                                                <h2 class="first-h1" style="text-align: center;">
                                                    Booking <span style="color:#C2185B">Detail</span>
                                                </h2>
                                                <hr style="border:1px solid #C2185B; border-top: none !important;">
                                                <div class="row row-m">
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Booking Reference</li>
                                                            <li class="li-p">{{ $booking->referenceNo }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Pick-up</li>
                                                            <li class="li-p">
                                                                {{ $pickupLabel }}<br>
                                                                {{ $booking->pickup_location ?: '—' }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                                        <ul>
                                                            <li class="li-h">Drop-off</li>
                                                            <li class="li-p">
                                                                {{ $dropoffLabel }}<br>
                                                                {{ $booking->dropoff_location ?: '—' }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top: 16px;">
                                                        <ul>
                                                            <li class="li-h">Total Price:</li>
                                                            <li class="li-p">£{{ number_format((float) $booking->total_amount, 2) }}</li>
                                                        </ul>
                                                    </div>
                                                    @if (!empty($booking->ext_ref))
                                                        <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top: 16px;">
                                                            <ul>
                                                                <li class="li-h">Supplier Ref:</li>
                                                                <li class="li-p">{{ $booking->ext_ref }}</li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    @if (!empty($booking->driver_age))
                                                        <div class="col-lg-4 col-md-4 col-sm-6" style="margin-top: 16px;">
                                                            <ul>
                                                                <li class="li-h">Driver age:</li>
                                                                <li class="li-p">{{ $booking->driver_age }}</li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                                <hr style="border:1px solid #C2185B; border-top: none !important;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

@include('layouts.footer')
