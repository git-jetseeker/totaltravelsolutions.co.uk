@php $meta = function_exists('crm_page_meta') ? crm_page_meta('lounges') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: (optional($page ?? null)->meta_title ?: 'Airport Lounges | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: (optional($page ?? null)->meta_keyword ?: 'airport lounges, UK airport lounge, pre-book lounge'))
@section('meta_description', ($meta->meta_description ?? null) ?: (optional($page ?? null)->meta_description ?: 'Compare and pre-book airport lounges across all major UK airports with Total Travel Solutions.'))

@include('layouts.header')
@include('layouts.nav')
@include('layouts.search_form')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-product-landing.css?v=20261001product') }}">

<main class="js-product-page">
    <div class="js-container">
        <section class="js-product-intro" aria-labelledby="js-lounges-intro-title">
            <span class="js-product-intro__badge">{{ crm('lounges.intro_eyebrow', 'Airport lounges') }}</span>
            <h2 id="js-lounges-intro-title">{{ crm('lounges.intro_title', 'Airport Lounges We Are Serving') }}</h2>
            <p>{{ crm('lounges.intro_p1', 'Save money by booking your airport lounge in advance. Escape the terminal crowds and enjoy snacks, drinks, WiFi, and a quiet environment before your flight departs.') }}</p>
        </section>
    </div>

    <section class="js-product-section">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('lounges.when_title', 'When Would I Use An Airport Lounge?') }}</h2>
                <p>{{ crm('lounges.when_lead', 'Airport lounges are ideal for travellers who want comfort, value, and a calmer start to their journey.') }}</p>
            </header>

            <div class="js-product-features">
                <article class="js-product-feature">
                    <img src="{{ asset('assets/images/early.jpg') }}" alt="{{ crm('lounges.when_1_title', 'Arrive Early and Unwind') }}" loading="lazy" width="400" height="180">
                    <div class="js-product-feature__body">
                        <h3>{{ crm('lounges.when_1_title', 'Arrive Early and Unwind') }}</h3>
                        <p>{{ crm('lounges.when_1_text', 'Lounge access is available for all age groups and travel purposes — perfect if you arrive at the airport ahead of schedule.') }}</p>
                    </div>
                </article>
                <article class="js-product-feature">
                    <img src="{{ asset('assets/images/family.png') }}" alt="{{ crm('lounges.when_2_title', 'Treat the Family') }}" loading="lazy" width="400" height="180">
                    <div class="js-product-feature__body">
                        <h3>{{ crm('lounges.when_2_title', 'Treat the Family') }}</h3>
                        <p>{{ crm('lounges.when_2_text', 'Lounges provide a safe, relaxed space for children and quality family time before boarding your flight.') }}</p>
                    </div>
                </article>
                <article class="js-product-feature">
                    <img src="{{ asset('assets/images/money.jpg') }}" alt="{{ crm('lounges.when_3_title', 'Get More For Your Money') }}" loading="lazy" width="400" height="180">
                    <div class="js-product-feature__body">
                        <h3>{{ crm('lounges.when_3_title', 'Get More For Your Money') }}</h3>
                        <p>{{ crm('lounges.when_3_text', 'Lounge access is often cheaper than buying food, drinks, and WiFi separately in the main terminal.') }}</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section js-product-section--alt">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('lounges.offer_title', 'What Do Lounges Offer?') }}</h2>
                <p>{{ crm('lounges.offer_lead', 'Most airport lounges include amenities designed to help you relax and recharge before you fly.') }}</p>
            </header>

            <div class="js-product-perks">
                <article class="js-product-perk">
                    <img src="{{ asset('assets/images/peace.png') }}" alt="" width="48" height="48" loading="lazy">
                    <h3>{{ crm('lounges.perk_1', 'Peace & Quiet') }}</h3>
                </article>
                <article class="js-product-perk">
                    <img src="{{ asset('assets/images/family.png') }}" alt="" width="48" height="48" loading="lazy">
                    <h3>{{ crm('lounges.perk_2', 'Free Food') }}</h3>
                </article>
                <article class="js-product-perk">
                    <img src="{{ asset('assets/images/privacy.png') }}" alt="" width="48" height="48" loading="lazy">
                    <h3>{{ crm('lounges.perk_3', 'Privacy') }}</h3>
                </article>
                <article class="js-product-perk">
                    <img src="{{ asset('assets/images/early.jpg') }}" alt="" width="48" height="48" loading="lazy">
                    <h3>{{ crm('lounges.perk_4', 'Cushy Chairs') }}</h3>
                </article>
                <article class="js-product-perk">
                    <img src="{{ asset('assets/images/family.png') }}" alt="" width="48" height="48" loading="lazy">
                    <h3>{{ crm('lounges.perk_5', 'Fast WiFi') }}</h3>
                </article>
                <article class="js-product-perk">
                    <img src="{{ asset('assets/images/newspaper.jpg') }}" alt="" width="48" height="48" loading="lazy">
                    <h3>{{ crm('lounges.perk_6', 'Newspapers') }}</h3>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('lounges.steps_title', 'How Lounge Booking Works') }}</h2>
                <p>{{ crm('lounges.steps_lead', 'Book airport lounges in a few simple steps with Total Travel Solutions.') }}</p>
            </header>

            <div class="js-product-steps">
                <article class="js-product-step">
                    <span class="js-product-step__num">1</span>
                    <h3>{{ crm('lounges.step_1_title', 'Search Your Airport') }}</h3>
                    <p>{{ crm('lounges.step_1_text', 'Choose your airport, date of visit, and flight time in the lounge search form above.') }}</p>
                </article>
                <article class="js-product-step">
                    <span class="js-product-step__num">2</span>
                    <h3>{{ crm('lounges.step_2_title', 'Compare Lounges') }}</h3>
                    <p>{{ crm('lounges.step_2_text', 'Browse available lounges, compare facilities and prices, then pick the best option for your trip.') }}</p>
                </article>
                <article class="js-product-step">
                    <span class="js-product-step__num">3</span>
                    <h3>{{ crm('lounges.step_3_title', 'Book & Relax') }}</h3>
                    <p>{{ crm('lounges.step_3_text', 'Complete your booking online and enjoy a comfortable lounge experience before your flight.') }}</p>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section js-product-section--alt">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>{{ crm('lounges.tips_title', 'Good To Know Before You Book') }}</h2>
                <p>{{ crm('lounges.tips_lead', 'Helpful tips to make your airport lounge booking quick and hassle-free.') }}</p>
            </header>

            <div class="js-product-tips">
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-sign-in"></i></span>
                    <h3>{{ crm('lounges.tip_1_title', 'Lounge Entry') }}</h3>
                    <p>{{ crm('lounges.tip_1_text', 'Entry is usually allowed up to 3 hours before your flight — check your lounge details at booking.') }}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-child"></i></span>
                    <h3>{{ crm('lounges.tip_2_title', 'Age Note') }}</h3>
                    <p>{{ crm('lounges.tip_2_text', 'Some lounges have age requirements for children — guest details are confirmed during your search.') }}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-check-circle"></i></span>
                    <h3>{{ crm('lounges.tip_3_title', 'Instant Confirmation') }}</h3>
                    <p>{{ crm('lounges.tip_3_text', 'Receive booking confirmation straight away with the details you need before you travel.') }}</p>
                </article>
            </div>
        </div>
    </section>

    <div class="js-container">
        <section class="js-product-cta">
            <h2>{{ crm('lounges.cta_title', 'Ready To Pre-Book Your Lounge?') }}</h2>
            <p>{{ crm('lounges.cta_text', 'Compare airport lounge options and reserve your access before you travel.') }}</p>
            <a href="#lounges" class="js-btn js-btn--accent">{{ crm('lounges.cta_btn', 'Find Lounges') }}</a>
        </section>
    </div>
</main>

@include('layouts.footer')
