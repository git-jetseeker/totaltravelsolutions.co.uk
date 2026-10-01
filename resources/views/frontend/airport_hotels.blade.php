@php $meta = function_exists('crm_page_meta') ? crm_page_meta('airport-hotels') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: (optional($page ?? null)->meta_title ?: 'Airport Hotels | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: (optional($page ?? null)->meta_keyword ?: 'airport hotels, UK airport hotel, pre-book hotel'))
@section('meta_description', ($meta->meta_description ?? null) ?: (optional($page ?? null)->meta_description ?: 'Compare and pre-book airport hotels across all major UK airports with Total Travel Solutions.'))

@include('layouts.header')
@include('layouts.nav')
@include('layouts.search_form')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-product-landing.css?v=20261001product') }}">

<main class="js-product-page">
    <div class="js-container">
        <section class="js-product-intro" aria-labelledby="js-hotels-intro-title">
            <span class="js-product-intro__badge">{{ crm('airport-hotels.intro_eyebrow', 'Airport hotels') }}</span>
            <h2 id="js-hotels-intro-title">{{ crm('airport-hotels.intro_title', 'Hotels Near Every UK Airport') }}</h2>
            <p>{{ crm('airport-hotels.intro_p1', 'Find comfortable airport hotels with easy terminal access. Choose overnight stays, early breakfast options, and hotel packages that make your journey smoother and stress-free.') }}</p>
        </section>
    </div>

    <section class="js-product-section">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('airport-hotels.why_title', 'Why Book An Airport Hotel?') }}</h2>
                <p>{{ crm('airport-hotels.why_lead', 'Whether you have an early flight, a late arrival, or a long layover, an airport hotel helps you rest well and travel with less stress.') }}</p>
            </header>

            <div class="js-product-features">
                <article class="js-product-feature">
                    <img src="{{ asset('assets/images/early.jpg') }}" alt="{{ crm('airport-hotels.why_1_title', 'Early Flights Made Easy') }}" loading="lazy" width="400" height="180">
                    <div class="js-product-feature__body">
                        <h3>{{ crm('airport-hotels.why_1_title', 'Early Flights Made Easy') }}</h3>
                        <p>{{ crm('airport-hotels.why_1_text', 'Stay close to the terminal the night before departure and avoid early-morning travel stress.') }}</p>
                    </div>
                </article>
                <article class="js-product-feature">
                    <img src="{{ asset('assets/images/family.png') }}" alt="{{ crm('airport-hotels.why_2_title', 'Ideal For Families') }}" loading="lazy" width="400" height="180">
                    <div class="js-product-feature__body">
                        <h3>{{ crm('airport-hotels.why_2_title', 'Ideal For Families') }}</h3>
                        <p>{{ crm('airport-hotels.why_2_text', 'Book family-friendly rooms near the airport so everyone can rest and start the journey refreshed.') }}</p>
                    </div>
                </article>
                <article class="js-product-feature">
                    <img src="{{ asset('assets/images/money.jpg') }}" alt="{{ crm('airport-hotels.why_3_title', 'Better Value Packages') }}" loading="lazy" width="400" height="180">
                    <div class="js-product-feature__body">
                        <h3>{{ crm('airport-hotels.why_3_title', 'Better Value Packages') }}</h3>
                        <p>{{ crm('airport-hotels.why_3_text', 'Compare hotel deals and packages that can work out cheaper than last-minute city stays.') }}</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section js-product-section--alt">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('airport-hotels.amenities_title', 'What Airport Hotels Offer') }}</h2>
                <p>{{ crm('airport-hotels.amenities_lead', 'Many airport hotels include comforts that make short stays and overnight stops easier before or after your flight.') }}</p>
            </header>

            <div class="js-product-perks">
                <article class="js-product-perk">
                    <span class="js-product-perk__icon" aria-hidden="true"><i class="fa fa-bed"></i></span>
                    <h3>{{ crm('airport-hotels.amenity_1', 'Quiet Rooms') }}</h3>
                </article>
                <article class="js-product-perk">
                    <span class="js-product-perk__icon" aria-hidden="true"><i class="fa fa-cutlery"></i></span>
                    <h3>{{ crm('airport-hotels.amenity_2', 'Breakfast Options') }}</h3>
                </article>
                <article class="js-product-perk">
                    <span class="js-product-perk__icon" aria-hidden="true"><i class="fa fa-wifi"></i></span>
                    <h3>{{ crm('airport-hotels.amenity_3', 'Free WiFi') }}</h3>
                </article>
                <article class="js-product-perk">
                    <span class="js-product-perk__icon" aria-hidden="true"><i class="fa fa-bus"></i></span>
                    <h3>{{ crm('airport-hotels.amenity_4', 'Easy Access') }}</h3>
                </article>
                <article class="js-product-perk">
                    <span class="js-product-perk__icon" aria-hidden="true"><i class="fa fa-clock-o"></i></span>
                    <h3>{{ crm('airport-hotels.amenity_5', '24h Reception') }}</h3>
                </article>
                <article class="js-product-perk">
                    <span class="js-product-perk__icon" aria-hidden="true"><i class="fa fa-bell"></i></span>
                    <h3>{{ crm('airport-hotels.amenity_6', 'Comfort Stays') }}</h3>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('airport-hotels.steps_title', 'How Hotel Booking Works') }}</h2>
                <p>{{ crm('airport-hotels.steps_lead', 'Book airport hotels in a few simple steps with Total Travel Solutions.') }}</p>
            </header>

            <div class="js-product-steps">
                <article class="js-product-step">
                    <span class="js-product-step__num">1</span>
                    <h3>{{ crm('airport-hotels.step_1_title', 'Search Your Airport') }}</h3>
                    <p>{{ crm('airport-hotels.step_1_text', 'Choose your airport, check-in and check-out dates, and guest details in the hotel search form above.') }}</p>
                </article>
                <article class="js-product-step">
                    <span class="js-product-step__num">2</span>
                    <h3>{{ crm('airport-hotels.step_2_title', 'Compare Hotels') }}</h3>
                    <p>{{ crm('airport-hotels.step_2_text', 'Browse hotel options near the terminal, compare facilities and prices, then pick the best stay for your trip.') }}</p>
                </article>
                <article class="js-product-step">
                    <span class="js-product-step__num">3</span>
                    <h3>{{ crm('airport-hotels.step_3_title', 'Book & Relax') }}</h3>
                    <p>{{ crm('airport-hotels.step_3_text', 'Complete your booking online and enjoy a comfortable stay before or after your flight.') }}</p>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section js-product-section--alt">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('airport-hotels.tips_title', 'Good To Know Before You Book') }}</h2>
                <p>{{ crm('airport-hotels.tips_lead', 'Helpful tips to make your airport hotel booking quick and hassle-free.') }}</p>
            </header>

            <div class="js-product-tips">
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-map-marker"></i></span>
                    <h3>{{ crm('airport-hotels.tip_1_title', 'Close To Terminal') }}</h3>
                    <p>{{ crm('airport-hotels.tip_1_text', 'Many hotels are just minutes from the terminal with shuttle or walkable access for early departures.') }}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-calendar"></i></span>
                    <h3>{{ crm('airport-hotels.tip_2_title', 'Flexible Timing') }}</h3>
                    <p>{{ crm('airport-hotels.tip_2_text', 'Choose check-in and check-out dates that suit your flight schedule, including late arrivals and early starts.') }}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-check-circle"></i></span>
                    <h3>{{ crm('airport-hotels.tip_3_title', 'Instant Confirmation') }}</h3>
                    <p>{{ crm('airport-hotels.tip_3_text', 'Receive booking confirmation straight away so you can travel knowing your stay is secured.') }}</p>
                </article>
            </div>
        </div>
    </section>

    <div class="js-container">
        <section class="js-product-cta">
            <h2>{{ crm('airport-hotels.cta_title', 'Ready To Pre-Book Your Hotel?') }}</h2>
            <p>{{ crm('airport-hotels.cta_text', 'Compare airport hotel options and reserve your stay before you travel.') }}</p>
            <a href="#hotels" class="js-btn js-btn--accent">{{ crm('airport-hotels.cta_btn', 'Find Hotels') }}</a>
        </section>
    </div>
</main>

@include('layouts.footer')
