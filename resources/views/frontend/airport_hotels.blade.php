@extends('layouts.main')

@section('title', optional($page ?? null)->meta_title ?: 'Airport Hotels | Total Travel Solutions')
@section('meta_keyword', optional($page ?? null)->meta_keyword ?: '')
@section('meta_description', optional($page ?? null)->meta_description ?: 'Compare and pre-book airport hotels across all major UK airports with Total Travel Solutions.')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .hotels-page {
            --ht-blue: #C2185B;
            --ht-blue-2: #3050c8;
            --ht-text: #1e2a33;
            --ht-muted: #5f7382;
            --ht-line: #dce6ed;
        }

        .hotels-hero {
            background: linear-gradient(rgba(31, 30, 30, 0.55), rgba(0, 0, 0, 0.7)), url('{{ asset('assets/images/banner16.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 60px 0 50px;
        }

        .hotels-hero h1 {
            color: #fff !important;
            font-weight: 900 !important;
            text-align: center;
            margin: 0 0 8px;
            font-size: 32px !important;
        }

        .hotels-hero p {
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            font-size: 17px;
            margin-bottom: 26px;
        }

        .hotels-hero__panel {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 25px;
        }

        .hotels-intro {
            padding: 36px 0;
            background: linear-gradient(180deg, #f4f8fb 0%, #ffffff 100%);
        }

        .hotels-intro__card {
            overflow: hidden;
            border: 1px solid var(--ht-line);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(65, 105, 225, 0.08);
        }

        .hotels-intro__head {
            margin: 0;
            padding: 16px 22px;
            background: linear-gradient(135deg, var(--ht-blue) 0%, var(--ht-blue-2) 100%);
            color: #fff !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            line-height: 1.35 !important;
        }

        .hotels-intro__body {
            padding: 24px 22px;
        }

        .hotels-intro__body p {
            margin: 0;
            color: var(--ht-muted);
            font-size: 15px;
            line-height: 1.75;
        }

        .hotels-section {
            padding: 42px 0;
            background: #f3f6f9;
        }

        .hotels-section__head {
            max-width: 820px;
            margin: 0 auto 28px;
            text-align: center;
        }

        .hotels-section__head h2 {
            margin: 0 0 10px;
            color: var(--ht-text) !important;
            font-size: 28px !important;
            font-weight: 700 !important;
        }

        .hotels-section__head p {
            margin: 0;
            color: var(--ht-muted);
            font-size: 15px;
            line-height: 1.65;
        }

        .hotels-grid {
            display: flex;
            flex-wrap: wrap;
            margin-left: -10px;
            margin-right: -10px;
        }

        .hotels-grid > [class*="col-"] {
            display: flex;
            float: none;
            margin-bottom: 20px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .hotels-feature {
            display: flex;
            flex-direction: column;
            width: 100%;
            min-height: 100%;
            padding: 22px 18px;
            border: 1px solid var(--ht-line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(65, 105, 225, 0.05);
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hotels-feature:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 32px rgba(65, 105, 225, 0.12);
        }

        .hotels-feature img {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            padding: 8px;
            border-radius: 50%;
            object-fit: contain;
            background: #f4f8fb;
            border: 3px solid rgba(65, 105, 225, 0.3);
            box-sizing: border-box;
        }

        .hotels-feature h3 {
            margin: 0 0 8px;
            color: var(--ht-blue) !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .hotels-feature p {
            margin: 0;
            flex: 1 1 auto;
            color: var(--ht-muted);
            font-size: 13.5px;
            line-height: 1.6;
        }

        .hotels-perk {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100%;
            padding: 26px 14px 22px;
            border: 1px solid rgba(65, 105, 225, 0.12);
            border-radius: 18px;
            background: #fff;
            text-align: center;
            box-shadow: 0 12px 28px rgba(65, 105, 225, 0.07);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .hotels-perk:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 36px rgba(65, 105, 225, 0.15);
        }

        .hotels-perk__icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            border-radius: 50%;
            background: #FDF2F8;
            box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.25);
            overflow: hidden;
        }

        .hotels-perk__icon i {
            color: var(--ht-blue);
            font-size: 26px;
            line-height: 1;
        }

        .hotels-perk h3 {
            margin: 0;
            color: var(--ht-blue) !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            line-height: 1.35;
        }

        .hotels-steps {
            padding: 48px 0;
            background: linear-gradient(180deg, #fff 0%, #f3f7fb 100%);
        }

        .hotels-steps__item {
            width: 100%;
            min-height: 100%;
            padding: 24px 20px 22px;
            border: 1px solid var(--ht-line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(65, 105, 225, 0.06);
        }

        .hotels-steps__num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            margin-bottom: 12px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ht-blue) 0%, var(--ht-blue-2) 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.22);
        }

        .hotels-steps__item h3 {
            margin: 0 0 8px;
            color: var(--ht-blue) !important;
            font-size: 17px !important;
            font-weight: 700 !important;
        }

        .hotels-steps__item p {
            margin: 0;
            color: var(--ht-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .hotels-cta {
            margin: 28px 0 40px;
            padding: 34px 28px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--ht-blue) 0%, var(--ht-blue-2) 100%);
            box-shadow: 0 16px 34px rgba(65, 105, 225, 0.22);
            text-align: center;
        }

        .hotels-cta h2 {
            margin: 0 0 8px;
            color: #fff !important;
            font-size: 26px !important;
            font-weight: 700 !important;
        }

        .hotels-cta p {
            margin: 0 auto 18px;
            max-width: 620px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            line-height: 1.6;
        }

        .hotels-cta__btn {
            display: inline-block;
            padding: 12px 26px;
            border-radius: 8px;
            background: #fff;
            color: var(--ht-blue) !important;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none !important;
            transition: transform 0.2s ease;
        }

        .hotels-cta__btn:hover,
        .hotels-cta__btn:focus {
            transform: translateY(-2px);
            color: var(--ht-blue-2) !important;
        }

        @media (max-width: 767px) {
            .hotels-hero h1 {
                font-size: 25px !important;
            }

            .hotels-section__head h2 {
                font-size: 22px !important;
            }

            .hotels-section {
                padding: 28px 0;
            }

            .hotels-cta h2 {
                font-size: 22px !important;
            }
        }
    </style>

    <div class="home-container home-background">
        @include('frontend.header')
    </div>

    <div class="hotels-page">
        <section class="hotels-hero" id="hotel-search">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1>Airport Hotels</h1>
                        <p>Pre-book comfortable hotels minutes from the terminal at all major UK airports.</p>
                        <div class="hotels-hero__panel">
                            @include('layouts.hotels_form')
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="hotels-intro">
            <div class="container">
                <div class="hotels-intro__card">
                    <h2 class="hotels-intro__head">Hotels Near Every UK Airport</h2>
                    <div class="hotels-intro__body">
                        <p>Find comfortable airport hotels with easy terminal access. Choose overnight stays, early breakfast options, and hotel packages that make your journey smoother and stress-free.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="hotels-section">
            <div class="container">
                <div class="hotels-section__head">
                    <h2>Why Book An Airport Hotel?</h2>
                    <p>Whether you have an early flight, a late arrival, or a long layover, an airport hotel helps you rest well and travel with less stress.</p>
                </div>

                <div class="row hotels-grid">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <article class="hotels-feature">
                            <img src="{{ asset('assets/images/early.jpg') }}" alt="Early Flights Made Easy">
                            <h3>Early Flights Made Easy</h3>
                            <p>Stay close to the terminal the night before departure and avoid early-morning travel stress.</p>
                        </article>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <article class="hotels-feature">
                            <img src="{{ asset('assets/images/family.png') }}" alt="Ideal For Families">
                            <h3>Ideal For Families</h3>
                            <p>Book family-friendly rooms near the airport so everyone can rest and start the journey refreshed.</p>
                        </article>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <article class="hotels-feature">
                            <img src="{{ asset('assets/images/money.jpg') }}" alt="Better Value Packages">
                            <h3>Better Value Packages</h3>
                            <p>Compare hotel deals and packages that can work out cheaper than last-minute city stays.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="hotels-section">
            <div class="container">
                <div class="hotels-section__head">
                    <h2>What Airport Hotels Offer</h2>
                    <p>Many airport hotels include comforts that make short stays and overnight stops easier before or after your flight.</p>
                </div>

                <div class="row hotels-grid">
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <article class="hotels-perk">
                            <span class="hotels-perk__icon" aria-hidden="true"><i class="fas fa-bed"></i></span>
                            <h3>Quiet Rooms</h3>
                        </article>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <article class="hotels-perk">
                            <span class="hotels-perk__icon" aria-hidden="true"><i class="fas fa-utensils"></i></span>
                            <h3>Breakfast Options</h3>
                        </article>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <article class="hotels-perk">
                            <span class="hotels-perk__icon" aria-hidden="true"><i class="fas fa-wifi"></i></span>
                            <h3>Free WiFi</h3>
                        </article>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <article class="hotels-perk">
                            <span class="hotels-perk__icon" aria-hidden="true"><i class="fas fa-shuttle-van"></i></span>
                            <h3>Easy Access</h3>
                        </article>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <article class="hotels-perk">
                            <span class="hotels-perk__icon" aria-hidden="true"><i class="far fa-clock"></i></span>
                            <h3>24h Reception</h3>
                        </article>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <article class="hotels-perk">
                            <span class="hotels-perk__icon" aria-hidden="true"><i class="fas fa-concierge-bell"></i></span>
                            <h3>Comfort Stays</h3>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="hotels-steps">
            <div class="container">
                <div class="hotels-section__head">
                    <h2>How Hotel Booking Works</h2>
                    <p>Book airport hotels in a few simple steps with Total Travel Solutions.</p>
                </div>

                <div class="row hotels-grid">
                    <div class="col-xs-12 col-sm-4">
                        <article class="hotels-steps__item">
                            <span class="hotels-steps__num">1</span>
                            <h3>Search Your Airport</h3>
                            <p>Choose your airport, check-in and check-out dates, and guest details in the hotel search form above.</p>
                        </article>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <article class="hotels-steps__item">
                            <span class="hotels-steps__num">2</span>
                            <h3>Compare Hotels</h3>
                            <p>Browse hotel options near the terminal, compare facilities and prices, then pick the best stay for your trip.</p>
                        </article>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <article class="hotels-steps__item">
                            <span class="hotels-steps__num">3</span>
                            <h3>Book &amp; Relax</h3>
                            <p>Complete your booking online and enjoy a comfortable stay before or after your flight.</p>
                        </article>
                    </div>
                </div>

                <div class="hotels-cta">
                    <h2>Ready To Pre-Book Your Hotel?</h2>
                    <p>Compare airport hotel options and reserve your stay before you travel.</p>
                    <a href="#hotel-search" class="hotels-cta__btn">Find Hotels</a>
                </div>
            </div>
        </section>
    </div>

@endsection
