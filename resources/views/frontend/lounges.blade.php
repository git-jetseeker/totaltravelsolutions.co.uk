@php $meta = function_exists('crm_page_meta') ? crm_page_meta('lounges') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: (optional($page ?? null)->meta_title ?: 'Airport Lounges | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: (optional($page ?? null)->meta_keyword ?: 'airport lounges, UK airport lounge, pre-book lounge'))
@section('meta_description', ($meta->meta_description ?? null) ?: (optional($page ?? null)->meta_description ?: 'Compare and pre-book airport lounges across all major UK airports with Total Travel Solutions.'))

@include('layouts.header')
@include('layouts.nav')
@include('layouts.search_form')

<div class="pz-lounges-content">
    <div class="container">
        <section class="pz-lounges-banner" aria-labelledby="pz-lounges-banner-title">
            <h2 id="pz-lounges-banner-title">{{ crm('lounges.intro_title', 'Airport Lounges We Are Serving') }}</h2>
            <p>{{ crm('lounges.intro_p1', 'Save money by booking your airport lounge in advance. Escape the terminal crowds and enjoy snacks, drinks, WiFi, and a quiet environment before your flight departs.') }}</p>
        </section>

        <section class="pz-lounges-section">
            <div class="pz-lounges-section__head">
                <h2>{{ crm('lounges.when_title', 'When Would I Use An Airport Lounge?') }}</h2>
                <p>{{ crm('lounges.when_lead', 'Airport lounges are ideal for travellers who want comfort, value, and a calmer start to their journey.') }}</p>
            </div>

            <div class="row pz-lounges-grid">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <article class="pz-lounges-feature">
                        <img src="{{ asset('assets/images/early.jpg') }}" alt="{{ crm('lounges.when_1_title', 'Arrive Early and Unwind') }}">
                        <h3>{{ crm('lounges.when_1_title', 'Arrive Early and Unwind') }}</h3>
                        <p>{{ crm('lounges.when_1_text', 'Lounge access is available for all age groups and travel purposes — perfect if you arrive at the airport ahead of schedule.') }}</p>
                    </article>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <article class="pz-lounges-feature">
                        <img src="{{ asset('assets/images/family.png') }}" alt="{{ crm('lounges.when_2_title', 'Treat the Family') }}">
                        <h3>{{ crm('lounges.when_2_title', 'Treat the Family') }}</h3>
                        <p>{{ crm('lounges.when_2_text', 'Lounges provide a safe, relaxed space for children and quality family time before boarding your flight.') }}</p>
                    </article>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <article class="pz-lounges-feature">
                        <img src="{{ asset('assets/images/money.jpg') }}" alt="{{ crm('lounges.when_3_title', 'Get More For Your Money') }}">
                        <h3>{{ crm('lounges.when_3_title', 'Get More For Your Money') }}</h3>
                        <p>{{ crm('lounges.when_3_text', 'Lounge access is often cheaper than buying food, drinks, and WiFi separately in the main terminal.') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="pz-lounges-section">
            <div class="pz-lounges-section__head">
                <h2>{{ crm('lounges.offer_title', 'What Do Lounges Offer?') }}</h2>
                <p>{{ crm('lounges.offer_lead', 'Most airport lounges include amenities designed to help you relax and recharge before you fly.') }}</p>
            </div>

            <div class="row pz-lounges-grid">
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <article class="pz-lounges-perk">
                        <img src="{{ asset('assets/images/peace.png') }}" alt="{{ crm('lounges.perk_1', 'Peace & Quiet') }}">
                        <h3>{{ crm('lounges.perk_1', 'Peace & Quiet') }}</h3>
                    </article>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <article class="pz-lounges-perk">
                        <img src="{{ asset('assets/images/family.png') }}" alt="{{ crm('lounges.perk_2', 'Free Food') }}">
                        <h3>{{ crm('lounges.perk_2', 'Free Food') }}</h3>
                    </article>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <article class="pz-lounges-perk">
                        <img src="{{ asset('assets/images/privacy.png') }}" alt="{{ crm('lounges.perk_3', 'Privacy') }}">
                        <h3>{{ crm('lounges.perk_3', 'Privacy') }}</h3>
                    </article>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <article class="pz-lounges-perk">
                        <img src="{{ asset('assets/images/early.jpg') }}" alt="{{ crm('lounges.perk_4', 'Cushy Chairs') }}">
                        <h3>{{ crm('lounges.perk_4', 'Cushy Chairs') }}</h3>
                    </article>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <article class="pz-lounges-perk">
                        <img src="{{ asset('assets/images/family.png') }}" alt="{{ crm('lounges.perk_5', 'Fast WiFi') }}">
                        <h3>{{ crm('lounges.perk_5', 'Fast WiFi') }}</h3>
                    </article>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <article class="pz-lounges-perk">
                        <img src="{{ asset('assets/images/newspaper.jpg') }}" alt="{{ crm('lounges.perk_6', 'Newspapers') }}">
                        <h3>{{ crm('lounges.perk_6', 'Newspapers') }}</h3>
                    </article>
                </div>
            </div>
        </section>

        <section class="pz-lounges-section">
            <div class="pz-lounges-section__head">
                <h2>{{ crm('lounges.steps_title', 'How Lounge Booking Works') }}</h2>
                <p>{{ crm('lounges.steps_lead', 'Book airport lounges in a few simple steps with Total Travel Solutions.') }}</p>
            </div>

            <div class="row pz-lounges-grid">
                <div class="col-xs-12 col-sm-4">
                    <article class="pz-lounges-steps__item">
                        <span class="pz-lounges-steps__num">1</span>
                        <h3>{{ crm('lounges.step_1_title', 'Search Your Airport') }}</h3>
                        <p>{{ crm('lounges.step_1_text', 'Choose your airport, date of visit, and flight time in the lounge search form above.') }}</p>
                    </article>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <article class="pz-lounges-steps__item">
                        <span class="pz-lounges-steps__num">2</span>
                        <h3>{{ crm('lounges.step_2_title', 'Compare Lounges') }}</h3>
                        <p>{{ crm('lounges.step_2_text', 'Browse available lounges, compare facilities and prices, then pick the best option for your trip.') }}</p>
                    </article>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <article class="pz-lounges-steps__item">
                        <span class="pz-lounges-steps__num">3</span>
                        <h3>{{ crm('lounges.step_3_title', 'Book & Relax') }}</h3>
                        <p>{{ crm('lounges.step_3_text', 'Complete your booking online and enjoy a comfortable lounge experience before your flight.') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="pz-lounges-know">
            <div class="pz-lounges-section__head">
                <h2>{{ crm('lounges.tips_title', 'Good To Know Before You Book') }}</h2>
                <p>{{ crm('lounges.tips_lead', 'Helpful tips to make your airport lounge booking quick and hassle-free.') }}</p>
            </div>

            <div class="row pz-lounges-grid">
                <div class="col-xs-12 col-sm-4">
                    <article class="pz-lounges-know__card">
                        <span class="pz-lounges-know__icon" aria-hidden="true"><i class="fa fa-sign-in"></i></span>
                        <h3>{{ crm('lounges.tip_1_title', 'Lounge Entry') }}</h3>
                        <p>{{ crm('lounges.tip_1_text', 'Entry is usually allowed up to 3 hours before your flight — check your lounge details at booking.') }}</p>
                    </article>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <article class="pz-lounges-know__card">
                        <span class="pz-lounges-know__icon" aria-hidden="true"><i class="fa fa-child"></i></span>
                        <h3>{{ crm('lounges.tip_2_title', 'Age Note') }}</h3>
                        <p>{{ crm('lounges.tip_2_text', 'Some lounges have age requirements for children — guest details are confirmed during your search.') }}</p>
                    </article>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <article class="pz-lounges-know__card">
                        <span class="pz-lounges-know__icon" aria-hidden="true"><i class="fa fa-check-circle"></i></span>
                        <h3>{{ crm('lounges.tip_3_title', 'Instant Confirmation') }}</h3>
                        <p>{{ crm('lounges.tip_3_text', 'Receive booking confirmation straight away with the details you need before you travel.') }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="pz-lounges-cta">
            <h2>{{ crm('lounges.cta_title', 'Ready To Pre-Book Your Lounge?') }}</h2>
            <p>{{ crm('lounges.cta_text', 'Compare airport lounge options and reserve your access before you travel.') }}</p>
            <a href="#lounges" class="pz-lounges-cta__btn">{{ crm('lounges.cta_btn', 'Find Lounges') }}</a>
        </section>
    </div>
</div>

@include('layouts.footer')
