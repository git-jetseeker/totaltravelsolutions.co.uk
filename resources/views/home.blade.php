@include('layouts.header')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20260907noblue2') }}">

@include('layouts.nav')



<style>
    

    a.linked:hover {
        background-color: transparent;
    }
    .copyright {

        /*height: 80px;*/

        padding-top: 10px;

    }



    /*#home_search_form .search_item .select2-container .select2-selection--single {*/



    @media only screen and (max-width: 991px) {

        .home_slider {

            display: none !important;

        }

    }



    @media only screen and (max-width: 767px) {

        .for-offres-intro .intro_center {

            min-height: 160px !important;

        }

    }



    .stepsSec .h2tag span {

        color: #C2185B;

    }



    .orangeClr {

        color: #C2185B;

    }



    .mt-60 {

        margin-top: 60px;

    }



    .icon-holder {

        margin-bottom: 15px;

        transition: transform 0.3s ease;

    }



    .benefit-card:hover .icon-holder {

        transform: scale(1.1) rotate(5deg);

    }



    .icon-holder img {

        width: 90px;

        height: 82px;

        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));

    }



    .icon-holder img.businessImg {

        width: 80px;

        height: 80px;

        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));

    }



    .icon-holder .beatenImg {

        width: 96px;

        height: 75.73px;

        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));

    }



    .meetGreetSec .loyalityImg img,

    .meetGreetSec .parkRide img,

    .meetGreetSec .onsite img {

        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.15));

        transition: transform 0.3s ease;

    }



    .parking-card:hover .loyalityImg img,

    .parking-card:hover .parkRide img,

    .parking-card:hover .onsite img {

        transform: scale(1.15);

    }



    .meetGreetSec .loyalityImg img {

        width: 70px;

        height: 70px;

    }



    .meetGreetSec .parkRide img {

        width: 66.98px;

        height: 68.31px;

    }



    .meetGreetSec .onsite img {

        width: 70px;

        height: 69.97px;

    }



    .offers .offers_items {

        font-family: 'Open Sans', sans-serif;

    }



    .offers .offers_item.more1 {

        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);

        border-radius: 8px;

        overflow: hidden;

        transition: transform 0.3s ease, box-shadow 0.3s ease;

        background: white;

        height: 100%;

        display: none;

    }



    .offers .offers_item.more1.display {

        display: block;

    }



    .offers .offers_item.more1:hover {

        transform: translateY(-5px);

        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);

    }







    .offers .offers_image_container {

        height: 220px;

        overflow: hidden;

        position: relative;

        background: #f0f0f0;

        width: 100%;

    }



    .offers_image_container img {

        position: unset !important;

        height: 150px;

    }



    .offers .offers_image_background {

        width: 100%;

        height: 100%;

        object-fit: cover;

        transition: transform 0.3s ease;

        display: block;

    }



    .offers .offers_item.more1:hover .offers_image_background {

        transform: scale(1.08);

    }



    .row-a {

        margin: 0 !important;

    }



    .row-a .col-lg-12 {

        padding: 0 !important;

    }



    .p-12 {

        padding: 12px;

    }



    .offers_content {

        padding: 25px 20px;

    }



    .offers_content .airport-h a {

        color: black;

        font-weight: 700;

        font-size: 1.25rem;

        text-decoration: none;

        transition: all 0.3s ease;

        display: inline-block;

    }



    .offers_content .airport-h a:hover {

        color: #C2185B;

        transform: translateX(3px);

    }



    .offers_icons .offers_icons_list li a img {

        height: 27px;

    }



    .offers #loadMore,

    .butn-div .btn {

        background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);

        padding: 15px 40px;

        color: white;

        font-size: 18px;

        font-weight: 700;

        border: none;

        border-radius: 6px;

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        text-decoration: none;

        display: inline-block;

        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);

        text-transform: uppercase;

        letter-spacing: 0.5px;

    }



    .offers #loadMore:hover,

    .butn-div .btn:hover {

        background: black;

        transform: translateY(-3px) scale(1.05);

        box-shadow: 0 8px 25px rgba(49, 18, 75, 0.4);

        color: white;

    }



    .offers_col {

        margin-bottom: 35px;

    }





    .offers_price {

        font-size: 0.9rem;

        color: #999;

        font-weight: 500;

    }



    .offers_price1 {

        font-size: 1.5rem;

        font-weight: 800;

        color: #C2185B;

        margin-top: 5px;

    }



    .offers_link {

        border-radius: 6px;

    }



    .offers_link a {

        color: var(--js-primary, #C2185B);

        font-weight: 700;

        text-decoration: none;

        transition: all 0.3s ease;

        border-bottom: 2px solid transparent;

        padding-bottom: 2px;

        text-transform: uppercase;

        font-size: 0.7rem;

        letter-spacing: 0.5px;



    }



    .offers_link a:hover {

        color: var(--js-primary-dark, #1A1A1A);

        border-bottom-color: var(--js-primary-dark, #1A1A1A);

    }



    .rating_r i {

        color: #C2185B;

        font-size: 1rem;

    }



    .ui-datepicker .ui-datepicker-prev span {

        transform: rotate(-135deg);

        border-style: solid;

        border-width: 3px 3px 0 0;

        height: 7px;

        width: 7px;

        cursor: pointer;

        content: "";

        display: inline-block;

        top: 68% !important;

        left: 63% !important;

        border-color: #fff !important;



    }



    .ui-datepicker .ui-datepicker-prev span {

        display: block;

        position: absolute;

        margin-left: -8px;

        margin-top: -8px;

    }



    .ui-datepicker .ui-datepicker-next span {

        transform: rotate(45deg);

        border-style: solid;

        border-width: 3px 3px 0 0;

        height: 7px;

        width: 7px;

        cursor: pointer;

        content: "";

        display: inline-block;

        top: 68% !important;

        left: 63% !important;

        border-color: #fff !important;

    }



    .ui-state-active,

    .ui-widget-content .ui-state-active,

    .ui-widget-header .ui-state-active,

    a.ui-button:active,

    .ui-button:active,

    .ui-button.ui-state-active:hover {

        border: 1px solid #4d256e;

        background: #4d256e;

        color: #fff;

    }



    .datepicker {

        display: none !important;

    }



    /* Premium Layout Styles */

    html {

        scroll-behavior: smooth;

    }



    body {

        font-family: 'Open Sans', sans-serif;

        color: #333;

        overflow-x: hidden;

        background: #ffffff;

    }



    * {

        -webkit-font-smoothing: antialiased;

        -moz-osx-font-smoothing: grayscale;

    }



    .section-spacing {

        padding: 80px 0;

    }



    .hero-section {

        background: linear-gradient(135deg, #f5f7fa 0%, #E5E5E5 100%);

        padding: 60px 0 80px;

        position: relative;

        overflow: hidden;

    }



    .hero-section::before {

        content: '';

        position: absolute;

        top: 0;

        left: 0;

        right: 0;

        bottom: 0;

        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="rgba(49,18,75,0.05)"/></svg>');

        opacity: 0.3;

    }



    .hero-headline {

        text-align: center;

        font-size: 3rem;

        font-weight: 800;

        color: black;

        margin-bottom: 25px;

        line-height: 1.2;

        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);

        position: relative;

        z-index: 1;

    }



    .hero-subheadline {

        text-align: center;

        font-size: 1.3rem;

        color: #555;

        margin-bottom: 50px;

        max-width: 800px;

        margin-left: auto;

        margin-right: auto;

        font-weight: 400;

        position: relative;

        z-index: 1;

    }



    .benefits-section {

        background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);

        position: relative;

    }



    .benefits-grid {

        display: grid;

        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));

        gap: 30px;

        margin-top: 50px;

    }



    .benefit-card {

        background: white;

        padding: 28px 10px;

        border-radius: 12px;

        text-align: center;

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        box-shadow: 0 4px 20px rgba(49, 18, 75, 0.08);

        height: 100%;

        border: 1px solid rgba(49, 18, 75, 0.05);

        position: relative;

        overflow: hidden;

    }



    .benefit-card::before {

        content: '';

        position: absolute;

        top: 0;

        left: 0;

        width: 100%;

        height: 4px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        transform: scaleX(0);

        transition: transform 0.4s ease;

    }



    .benefit-card:hover::before {

        transform: scaleX(1);

    }



    .benefit-card:hover {

        transform: translateY(-10px);

        box-shadow: 0 12px 40px rgba(49, 18, 75, 0.2);

        border-color: #C2185B;

    }



    .benefit-card h3 {

        font-size: 1.4rem;

        margin: 25px 0 18px;

        color: black;

        font-weight: 700;

    }



    .benefit-card p {

        color: #666;

        font-size: 1rem;

        line-height: 1.7;

    }



    .section-title {

        text-align: center;

        font-size: 2.8rem;

        font-weight: 800;

        color: black;

        margin-bottom: 20px;

        position: relative;

        display: inline-block;

        width: 100%;

    }



    .section-title::after {

        content: '';

        position: absolute;

        bottom: -10px;

        left: 50%;

        transform: translateX(-50%);

        width: 80px;

        height: 4px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        border-radius: 2px;

    }



    .section-subtitle {

        text-align: center;

        color: #666;

        font-size: 1.2rem;

        max-width: 850px;

        margin: 0 auto 60px;

        line-height: 1.8;

        font-weight: 400;

    }



    .featured-airports-grid {

        display: grid;

        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

        gap: 30px;

        margin-top: 50px;

    }



    .how-it-works-grid {

        display: grid;

        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));

        gap: 40px;

        margin-top: 50px;

        position: relative;

    }



    .step-card {

        text-align: center;

        padding: 30px 20px;

        background: white;

        border-radius: 12px;

        transition: all 0.3s ease;

        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);

    }



    .step-card:hover {

        transform: translateY(-5px);

        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);

    }



    .step-number {

        width: 90px;

        height: 90px;

        border-radius: 50%;

        background: black;

        color: white;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 2.2rem;

        font-weight: 800;

        margin: 0 auto 30px;

        box-shadow: 0 4px 20px rgba(49, 18, 75, 0.3);

        position: relative;

    }



    .step-number::before {

        content: '';

        position: absolute;

        width: 100%;

        height: 100%;

        border-radius: 50%;

        border: 3px solid #C2185B;

        opacity: 0;

        transform: scale(1.2);

        transition: all 0.3s ease;

    }



    .step-card:hover .step-number::before {

        opacity: 1;

        transform: scale(1.15);

    }



    .step-card h3 {

        font-size: 1.5rem;

        color: black;

        margin-bottom: 18px;

        font-weight: 700;

    }



    .step-card p {

        color: #666;

        line-height: 1.7;

        font-size: 1rem;

    }



    .parking-types-grid {

        display: grid;

        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

        gap: 30px;

        margin-top: 50px;

    }



    .parking-card {

        background: white;

        border-radius: 16px;

        padding: 50px 35px;

        text-align: center;

        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        border: 2px solid transparent;

        position: relative;

        overflow: hidden;

    }



    .parking-card::after {

        content: '';

        position: absolute;

        bottom: 0;

        left: 0;

        right: 0;

        height: 5px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        transform: scaleX(0);

        transition: transform 0.4s ease;

    }



    .parking-card:hover::after {

        transform: scaleX(1);

    }



    .parking-card:hover {

        transform: translateY(-12px) scale(1.02);

        box-shadow: 0 12px 50px rgba(49, 18, 75, 0.25);

        border-color: #C2185B;

    }



    .parking-card h4 {

        font-size: 1.7rem;

        color: black;

        margin: 30px 0 22px;

        font-weight: 700;

    }



    .parking-card p {

        color: #666;

        line-height: 1.8;

        font-size: 1.05rem;

    }



    .compare-section {

        background: black;

        padding: 100px 0;

        position: relative;

        overflow: hidden;

    }



    .compare-section::before {

        content: '';

        position: absolute;

        top: -50%;

        right: -10%;

        width: 600px;

        height: 600px;

        background: rgba(255, 165, 0, 0.1);

        border-radius: 50%;

        animation: pulse 8s ease-in-out infinite;

    }



    @keyframes pulse {



        0%,

        100% {

            transform: scale(1);

            opacity: 0.1;

        }



        50% {

            transform: scale(1.1);

            opacity: 0.15;

        }

    }



    .compare-content {

        display: flex;

        align-items: center;

        gap: 60px;

        position: relative;

        z-index: 1;

    }



    .compare-text h2 {

        font-size: 2.8rem;

        font-weight: 800;

        color: white;

        margin-bottom: 30px;

        text-shadow: 0 3px 15px rgba(0, 0, 0, 0.3);

    }



    .compare-text p {

        font-size: 1.15rem;

        color: rgba(255, 255, 255, 0.95);

        line-height: 1.9;

        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

    }



    .compare-content img {

        filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));

    }



    .testimonialSec {

        background: #f8f9fa;

    }



    @media (max-width: 991px) {

        .hero-headline {

            font-size: 2.2rem;

        }



        .hero-subheadline {

            font-size: 1.1rem;

        }



        .section-title {

            font-size: 2.2rem;

        }



        .section-spacing {

            padding: 60px 0;

        }



        .compare-content {

            flex-direction: column;

            gap: 40px;

        }



        .how-it-works-grid {

            gap: 25px;

        }



        .step-number {

            width: 75px;

            height: 75px;

            font-size: 1.8rem;

        }

    }



    @media (max-width: 767px) {

        .hero-headline {

            font-size: 1.8rem;

        }



        .hero-subheadline {

            font-size: 1rem;

        }



        .section-title {

            font-size: 1.8rem;

        }



        .section-subtitle {

            font-size: 1rem;

        }



        .section-spacing {

            padding: 50px 0;

        }



        .benefits-grid,

        .featured-airports-grid,

        .parking-types-grid {

            grid-template-columns: 1fr;

        }



        .benefit-card,

        .parking-card,

        .step-card {

            padding: 30px 20px;

        }



        .compare-text h2 {

            font-size: 2rem;

        }



        .compare-text p {

            font-size: 1rem;

        }

    }







    /* Fix for show more/show less functionality */

    .offers .offers_item.more1 {

        display: none;

        /* Hide all by default */

    }



    .offers .offers_item.more1.display {

        display: block;

        /* Show when has display class */

    }



    /* Ensure smooth transitions */

    .offers_item.more1 {

        transition: all 0.3s ease-in-out;

    }



    /* Fix for the offers section */

    .offers {

        width: 100%;

    }
    .cap-text {
        text-transform: capitalize;
    }

</style>

@php

    $site_settings_main = [];

    $settingsAll = App\Models\settings::all();

    foreach ($settingsAll as $setting) {

        if ($setting->agent_id == '1') {

            $site_settings_main[$setting->field_name] = $setting->field_value;

        }

    }



    $sliders = [];
    if (!empty($site_settings_main['sliders'])) {
        $decoded = @unserialize($site_settings_main['sliders']);
        if (is_array($decoded)) {
            $sliders = $decoded;
        }
    }



@endphp







@include('layouts.search_form')



<!-- How It Works Section -->

<section class="js-works-section section-spacing">
    <div class="container">
        <div class="js-section-head js-reveal">
            <span class="js-section-head__badge js-section-head__badge--light">Simple process</span>
            <h2 class="js-section-title">How Total Travel Solutions <span class="orangeClr">Works</span></h2>
            <p class="js-section-subtitle">Book your airport parking in four simple steps</p>
        </div>

        <ol class="js-works-path">
            <li class="js-works-step js-reveal" style="--reveal-delay: 0ms">
                <div class="js-works-step__node" aria-hidden="true">
                    <span class="js-works-step__num">01</span>
                    <i class="fa fa-search"></i>
                </div>
                <div class="js-works-step__body">
                    <h3>Search</h3>
                    <p>Enter your airport and travel dates</p>
                </div>
            </li>
            <li class="js-works-step js-reveal" style="--reveal-delay: 120ms">
                <div class="js-works-step__node" aria-hidden="true">
                    <span class="js-works-step__num">02</span>
                    <i class="fa fa-exchange"></i>
                </div>
                <div class="js-works-step__body">
                    <h3>Compare</h3>
                    <p>Live prices and services — compare and choose a deal</p>
                </div>
            </li>
            <li class="js-works-step js-reveal" style="--reveal-delay: 240ms">
                <div class="js-works-step__node" aria-hidden="true">
                    <span class="js-works-step__num">03</span>
                    <i class="fa fa-lock"></i>
                </div>
                <div class="js-works-step__body">
                    <h3>Book Securely</h3>
                    <p>Add your details and pay via our secure payment gateways</p>
                </div>
            </li>
            <li class="js-works-step js-reveal" style="--reveal-delay: 360ms">
                <div class="js-works-step__node" aria-hidden="true">
                    <span class="js-works-step__num">04</span>
                    <i class="fa fa-plane"></i>
                </div>
                <div class="js-works-step__body">
                    <h3>Travel Confidently</h3>
                    <p>Your car is in safe hands — enjoy a stress-free start to your journey</p>
                </div>
            </li>
        </ol>
    </div>
</section>



<!-- Benefits Section -->

<section class="js-why-section section-spacing">
    <div class="js-why-section__mesh" aria-hidden="true"></div>
    <div class="container">
        <div class="js-section-head js-reveal">
            <span class="js-section-head__badge">Why choose us</span>
            <h2 class="js-section-title">Why Book With <span class="orangeClr">Total Travel Solutions?</span></h2>
            <p class="js-section-subtitle">We offer trusted <a class="linked" href="{{ url('airports') }}">airport parking</a> solutions, guaranteed value and a nation-wide coverage. <strong>Pre-book</strong> now to enjoy great <strong>car park deals!</strong></p>
        </div>

        <div class="js-why-grid">
            <article class="js-why-item js-reveal" style="--reveal-delay: 0ms">
                <div class="js-why-item__icon-wrap">
                    <img src="{{ asset('assets/images/Customer Satisfaction.png') }}" alt="" loading="lazy" width="56" height="56">
                </div>
                <div class="js-why-item__content">
                    <span class="js-why-item__index">01</span>
                    <h3 class="cap-text">Park Mark Certified Operators</h3>
                    <p>Every parking partner meets strict safety and security standards, ensuring your vehicle is in safe 24/7 monitored car parks.</p>
                </div>
            </article>

            <article class="js-why-item js-reveal" style="--reveal-delay: 80ms">
                <div class="js-why-item__icon-wrap">
                    <img src="{{ asset('assets/images/Easy Cancellation & Amendments.png') }}" alt="" loading="lazy" width="56" height="56">
                </div>
                <div class="js-why-item__content">
                    <span class="js-why-item__index">02</span>
                    <h3 class="cap-text">Best Price Promise</h3>
                    <p>We monitor market rates daily so you get the best price on all <a class="linked" href="{{ url('airport-parking') }}">airport car park</a> deals — fair, transparent, and free from hidden charges.</p>
                </div>
            </article>

            <article class="js-why-item js-reveal" style="--reveal-delay: 160ms">
                <div class="js-why-item__icon-wrap">
                    <img src="{{ asset('assets/images/Years of Experience.png') }}" alt="" loading="lazy" width="56" height="56">
                </div>
                <div class="js-why-item__content">
                    <span class="js-why-item__index">03</span>
                    <h3 class="cap-text">Seamless Booking Experience</h3>
                    <p>Real-time booking and availability with instant confirmation. Easy amendments and cancellation — no guesswork.</p>
                </div>
            </article>

            <article class="js-why-item js-reveal" style="--reveal-delay: 240ms">
                <div class="js-why-item__icon-wrap">
                    <img src="{{ asset('assets/images/Best Price Guaranteed.png') }}" alt="" loading="lazy" width="56" height="56">
                </div>
                <div class="js-why-item__content">
                    <span class="js-why-item__index">04</span>
                    <h3 class="cap-text">Experience Quality Services</h3>
                    <p>Years of industry experience across 28+ UK airports, with professionalism and quality service you can rely on.</p>
                </div>
            </article>
        </div>
    </div>
</section>



<!-- Parking Types Section -->

<section class="js-parking-section section-spacing">
    <div class="js-parking-section__runway" aria-hidden="true"></div>
    <div class="container">
        <div class="js-section-head js-reveal">
            <span class="js-section-head__badge js-section-head__badge--light">Parking types</span>
            <h2 class="js-section-title">Parking Options to Suit <span class="orangeClr">Every Traveller</span></h2>
            <p class="js-section-subtitle">We cover <a class="linked" href="{{ url('airport-parking') }}">Top UK Airports</a> including Heathrow, Gatwick, Manchester, Stansted, Birmingham, Luton, Edinburgh, and more.</p>
        </div>

        <div class="js-parking-gates">
            <article class="js-parking-gate js-reveal" style="--reveal-delay: 0ms">
                <header class="js-parking-gate__header">
                    <span class="js-parking-gate__code">MG</span>
                    <span class="js-parking-gate__lane">Lane 01</span>
                    <span class="js-parking-gate__tag js-parking-gate__tag--premium">Premium</span>
                </header>
                <div class="js-parking-gate__icon">
                    <img src="{{ asset('assets/images/Meet & Greet.png') }}" alt="" loading="lazy" width="64" height="64">
                </div>
                <div class="js-parking-gate__body">
                    <h3>Meet &amp; Greet</h3>
                    <p>A seamless start to your journey. Drive to the terminal, hand over your keys, and let a professional park your vehicle while you head straight to departures.</p>
                </div>
                <div class="js-parking-gate__footer">
                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> Fastest check-in</span>
                </div>
            </article>

            <article class="js-parking-gate js-parking-gate--featured js-reveal" style="--reveal-delay: 100ms">
                <header class="js-parking-gate__header">
                    <span class="js-parking-gate__code">OS</span>
                    <span class="js-parking-gate__lane">Lane 02</span>
                    <span class="js-parking-gate__tag js-parking-gate__tag--convenient">Most popular</span>
                </header>
                <div class="js-parking-gate__icon">
                    <img src="{{ asset('assets/images/On Site.png') }}" alt="" loading="lazy" width="64" height="64">
                </div>
                <div class="js-parking-gate__body">
                    <h3>On-Site Airport Parking</h3>
                    <p>Park close to the terminal within walking distance. Direct access, high security, and maximum convenience for short or busy trips.</p>
                </div>
                <div class="js-parking-gate__footer">
                    <span><i class="fa fa-map-marker" aria-hidden="true"></i> Walk to terminal</span>
                </div>
            </article>

            <article class="js-parking-gate js-reveal" style="--reveal-delay: 200ms">
                <header class="js-parking-gate__header">
                    <span class="js-parking-gate__code">PR</span>
                    <span class="js-parking-gate__lane">Lane 03</span>
                    <span class="js-parking-gate__tag js-parking-gate__tag--value">Best value</span>
                </header>
                <div class="js-parking-gate__icon">
                    <img src="{{ asset('assets/images/Park & Ride.png') }}" alt="" loading="lazy" width="64" height="64">
                </div>
                <div class="js-parking-gate__body">
                    <h3>Park &amp; Ride</h3>
                    <p>Great value for longer stays. Park securely and take a complimentary shuttle straight to your terminal without delays.</p>
                </div>
                <div class="js-parking-gate__footer">
                    <span><i class="fa fa-bus" aria-hidden="true"></i> Free shuttle included</span>
                </div>
            </article>
        </div>
    </div>
</section>



<!-- Compare Section -->

<section class="js-compare-section">
    <div class="js-compare-section__glow" aria-hidden="true"></div>
    <div class="container">
        <div class="js-compare-layout">
            <div class="js-compare-copy js-reveal">
                <span class="js-compare-eyebrow"><i class="fa fa-shield" aria-hidden="true"></i> Park Mark accredited</span>
                <h2 class="js-compare-title">
                    <span class="js-compare-title__line">Compare <span class="js-compare-title__highlight">Airport</span></span>
                    <span class="js-compare-title__line">Parking</span>
                </h2>
                <p class="js-compare-lead">We prioritise delivering peace of mind and aim to provide you with the best price available to meet your travel and parking needs — whether you choose UK <strong>Meet &amp; Greet</strong>, <strong>Park &amp; Ride</strong>, or <strong>on-airport parking</strong>.</p>
                <ul class="js-compare-checklist">
                    <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>Regular security patrols</li>
                    <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>CCTV operation 24/7</li>
                    <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>Bright and well-maintained environment</li>
                    <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>Insurance cover validation</li>
                </ul>
                <p class="js-compare-footnote">At Total Travel Solutions you park with professionals.</p>
            </div>
            <div class="js-compare-visual js-reveal" style="--reveal-delay: 120ms">
                <div class="js-compare-visual__frame">
                    <img src="{{ asset('assets/images/COMPARE AIRPORT PARKING.png') }}" alt="Compare airport parking" class="img-fluid" loading="lazy" width="400" height="290">
                </div>
                <div class="js-compare-metrics">
                    <div class="js-compare-metric">
                        <strong>28+</strong>
                        <span>UK airports</span>
                    </div>
                    <div class="js-compare-metric">
                        <strong>24/7</strong>
                        <span>Secure parking</span>
                    </div>
                    <div class="js-compare-metric">
                        <strong>100%</strong>
                        <span>Park Mark listed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Airports Section -->

<section class="js-airports-section section-spacing" id="js-airports-section">

    <div class="container">

        <div class="js-section-head js-reveal">
            <span class="js-section-head__badge js-section-head__badge--light">Nationwide coverage</span>
            <h2 class="js-section-title">We Are Operating at the <span class="orangeClr">Following Airports</span></h2>
            <p class="js-section-subtitle">Compare and book airport parking at all major UK airports</p>
        </div>



        <div class="row offers_items js-airports-grid">

            @php

                $airports = app\Models\airport::where('status', 'yes')->orderBy('priority', 'asc')->get();

                $price = 28;
                $p=rand(25,30);
                
            @endphp

            @foreach ($airports as $airport)
            
            @if($airport->id == '20')
            
            @php    $p=4; @endphp
            @endif
            @if($airport->id == '27')
            
            @php    $p=5; @endphp
            @endif
            @if($airport->id == '26')
            
            @php    $p=5; @endphp
            @endif
            @if($airport->id == '40')
            
            @php    $p=9; @endphp
            @endif
            @if($airport->id == '24')
            
            @php    $p=4; @endphp
            @endif
            @if($airport->id == '1')
            
            @php    $p=4; @endphp
            @endif

                @php

                    if (preg_match('/\s/', $airport->name)) {

                        $name = str_replace(' ', '-', strtolower($airport->name));

                    } else {

                        $name = trim(strtolower($airport->name));

                    }

                    $url = str_replace(' ', '-', $name);

                    $url = $url . '-' . 'airport-parking';

                @endphp

                <div class="col-lg-3 col-md-6 offers_col js-airport-col">

                    <div class="offers_item more1 js-airport-pass js-reveal">

                        <div class="row row-a">

                            <div class="col-lg-12" style="">

                                <div class="offers_image_container">

                                    <!-- Image by https://unsplash.com/@kensuarez -->

                                    <img class="offers_image_background" loading="lazy" alt="{{ $airport->name }}"

                                        height="200" width="340"

                                        src='{{ url('https://www.dashboard.jetseekergroup.com/storage/' . str_replace('public/','',$airport->profile_image)) }}'>





                                </div>

                            </div>

                            <div class="col-lg-12">

                                <div class="offers_content">

                                    <div class="row p-12">

                                        <div class="col-sm-6">

                                            <div class="airport-h">

                                                <a

                                                    href="{{ route('page', ['slug' => $url]) }}">{{ $airport->name }}</a>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 text-right">

                                            <div class="offers_price"> <span>Starting from</span></div>

                                            <div class="offers_price1"><span>£ {{$p}}</span></div>

                                        </div>

                                    </div>

                                    <br>

                                    <div class="row p-12">

                                        <div class="col-sm-6">

                                            <div>

                                                <i class="fa fa-map-marker" aria-hidden="true"

                                                    style="display: inline;"></i>

                                                <p style="display: inline;">{{ $airport->name }}</p>

                                            </div>

                                            <div class="rating_r rating_r_4 offers_rating">

                                                <i class="fa fa-star checked"></i>

                                                <i class="fa fa-star checked"></i>

                                                <i class="fa fa-star checked"></i>

                                                <i class="fa fa-star checked"></i>

                                                <i class="fa fa-star unchecked"></i>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 text-right">

                                            <div class="offers_link"><a

                                                    href="{{ route('page', ['slug' => $url]) }}">read more</a></div>

                                        </div>

                                    </div>



                                    @php

                                        $companies = DB::table('companies')->where('airport_id', $airport->id)->get();

                                    @endphp







                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                @php $price = $price+2; @endphp

            @endforeach

        </div>

        <div class="text-center js-airports-actions js-reveal">

            <a href="#" id="loadMore" class="js-btn js-btn--primary js-airports-load-more">Load More</a>

        </div>

    </div>

</section>



<!-- Testimonials Section -->

<section class="js-reviews-section section-spacing">

    <div class="container">

        <div class="js-section-head js-reveal">
            <span class="js-section-head__badge js-section-head__badge--light">Trusted reviews</span>
            <h2 class="js-section-title cap-text">What Do Our <span class="orangeClr">Customers Say</span></h2>
            <p class="js-section-subtitle">Trusted travel park for thousands of customer reviews</p>
        </div>
    </div>

    <div class="js-reviews-section__slider js-reveal">
        @include('frontend.slider-main')
    </div>

</section>



<!-- Top Tips Section -->

<section class="js-tips-section section-spacing">

    <div class="container">

        <div class="js-section-head js-reveal">
            <span class="js-section-head__badge js-section-head__badge--light">Expert advice</span>
            <h2 class="js-section-title"><span class="orangeClr">Top Tips</span> By Experts</h2>
            <p class="js-section-subtitle">Expert advice to make your airport parking experience seamless</p>
        </div>

        <div class="js-tips-grid js-reveal">
            <article class="js-tip-card">
                <div class="js-tip-card__icon">
                    <img src="{{ asset('assets/images/Book in Advance.png') }}" alt="" loading="lazy" width="40" height="40">
                </div>
                <h3 class="js-tip-card__title">Book in Advance</h3>
                <p class="js-tip-card__text">Reserving your parking spot in advance often means lower rates. Take advantage of pre-booking discounts offered by parking facilities.</p>
            </article>

            <article class="js-tip-card">
                <div class="js-tip-card__icon">
                    <img src="{{ asset('assets/images/Compare Price.png') }}" alt="" loading="lazy" width="40" height="40">
                </div>
                <h3 class="js-tip-card__title">Compare Prices</h3>
                <p class="js-tip-card__text">Don't rush your decision. Compare prices across providers and find the best deal that fits your budget and travel plans.</p>
            </article>

            <article class="js-tip-card">
                <div class="js-tip-card__icon">
                    <img src="{{ asset('assets/images/Of side Parking.png') }}" alt="" loading="lazy" width="40" height="40">
                </div>
                <h3 class="js-tip-card__title">Off-Site Parking</h3>
                <p class="js-tip-card__text">Consider off-site parking near the airport. These facilities often offer lower rates compared to on-site airport parking.</p>
            </article>
        </div>

    </div>

</section>



@include('layouts.footer')



<script>

    $(document).ready(function() {

        let visibleItems = 8;

        const allItems = $(".more1");

        const loadMoreBtn = $("#loadMore");



        // Initially hide all items

        allItems.hide();



        // Show first batch

        allItems.slice(0, visibleItems).show().addClass('display');



        // Hide button if fewer items than visibleItems

        if (allItems.length <= visibleItems) {

            loadMoreBtn.hide();

            return;

        }



        loadMoreBtn.on('click', function(e) {

            e.preventDefault();



            if ($(this).text() === 'Load More') {

                // Show next batch

                const hiddenItems = allItems.filter(':hidden');

                hiddenItems.slice(0, visibleItems).show().addClass('display');



                // If no more hidden items, change button text

                if (allItems.filter(':hidden').length === 0) {

                    $(this).text('Show Less');

                }

            } else {

                // Hide all except first batch

                allItems.slice(visibleItems).hide().removeClass('display');

                $(this).text('Load More');



                // Scroll to section

                $('html, body').animate({

                    scrollTop: $("#js-airports-section").offset().top - 100

                }, 800);

            }

        });

    });

</script>

<script>

    //display datepicker on top side if page is not scrolled down

    function getVisible() {

        var $el = $('#home_search_form'),

            scrollTop = $(this).scrollTop();

        var top_height = $(window).height() - ($('#home_search_form').offset().top + $('#home_search_form').height());

        $('#notification').text(top_height - scrollTop);

        if ((top_height - scrollTop) > (-300)) {

            $('div.cal').addClass('calendor_top');

        } else {

            $('div.cal').removeClass('calendor_top');

        }



    }

</script>



<script>

    $(function() {

        $(".div").slice(0, 6).show(); // select the first ten

        $("#load").click(function(e) { // click event for load more

            e.preventDefault();

            $(".div:hidden").slice(0, 15).show(); // select next 10 hidden divs and show them



        });

    });

</script>





<script src="{{ asset('theme/js/select2.min.js') }}"></script>





<script type="text/javascript">

    $(document).ready(function() {

        $('.select2me').select2();

    });

</script>



<script type="text/javascript">

    var popup_open = false;

    document.addEventListener("mouseleave", function(event) {

        if (!popup_open) {

            //alert('popped');

            document.getElementById('pain').style.display = 'block';

            popup_open = true;

        }

    }, false);

    document.getElementById('reset_btn').addEventListener("click", function(event) {

        document.getElementById('pain').style.display = 'none';

        popup_open = false;

    }, false);



    document.querySelector("#close").addEventListener("click", function() {

        document.querySelector(".JoinUs").style.display = "none";

    });

    document.querySelector("#close2").addEventListener("click", function() {

        document.querySelector(".JoinUs").style.display = "none";

    });

</script>

