@php $meta = function_exists('crm_page_meta') ? crm_page_meta('car-hire') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: (optional($page ?? null)->meta_title ?: 'Car Hire | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: (optional($page ?? null)->meta_keyword ?: 'car hire, airport car rental, UK car hire'))
@section('meta_description', ($meta->meta_description ?? null) ?: (optional($page ?? null)->meta_description ?: 'Compare and book airport car hire with Total Travel Solutions.'))

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-parking-services.css?v=20261001ps4') }}">

@include('layouts.search_form', [
    'heroEyebrow' => crm('car-hire.hero_eyebrow', 'Total Travel Solutions'),
    'heroTitle' => crm('car-hire.hero_title', 'Book Easy, Hire Smart, Travel Happy!'),
    'heroSubtitle' => carhire_enabled()
        ? crm('car-hire.hero_subtitle', 'Compare live airport car hire deals from trusted suppliers')
        : crm('car-hire.hero_subtitle_soon', 'Coming soon — search, compare and book airport car hire'),
    'activeBookingTab' => $activeBookingTab ?? 'carhire',
    'bookingCardId' => 'carhire_search_form',
    'heroFeatures' => [
        ['icon' => 'fa-car', 'title' => crm('car-hire.hero_feat_1', 'Live car rates')],
        ['icon' => 'fa-tag', 'title' => crm('car-hire.hero_feat_2', 'Best prices')],
        ['icon' => 'fa-map-marker', 'title' => crm('car-hire.hero_feat_3', 'Flexible drop-off')],
        ['icon' => 'fa-headphones', 'title' => crm('car-hire.hero_feat_4', 'Expert support')],
    ],
])

<main class="js-carhire-page">

    {{-- How it works — same path layout as homepage --}}
    <section class="js-works-section section-spacing">
        <div class="container">
            <div class="js-section-head js-reveal">
                <span class="js-section-head__badge js-section-head__badge--light">{{ crm('car-hire.steps_badge', 'Simple process') }}</span>
                <h2 class="js-section-title">{{ crm('car-hire.steps_title', 'How Car Hire Booking Works') }}</h2>
                <p class="js-section-subtitle">{{ crm('car-hire.steps_lead', 'Search, compare and book airport car hire in four simple steps') }}</p>
            </div>

            <ol class="js-works-path">
                <li class="js-works-step js-reveal" style="--reveal-delay: 0ms">
                    <div class="js-works-step__node" aria-hidden="true">
                        <span class="js-works-step__num">01</span>
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="js-works-step__body">
                        <h3>{{ crm('car-hire.step_1_head', 'Search') }}</h3>
                        <p>{{ crm('car-hire.step_1_text', 'Enter pick-up location, dates and times in the form above') }}</p>
                    </div>
                </li>
                <li class="js-works-step js-reveal" style="--reveal-delay: 120ms">
                    <div class="js-works-step__node" aria-hidden="true">
                        <span class="js-works-step__num">02</span>
                        <i class="fa fa-exchange"></i>
                    </div>
                    <div class="js-works-step__body">
                        <h3>{{ crm('car-hire.step_2_head', 'Compare') }}</h3>
                        <p>{{ crm('car-hire.step_2_text', 'Browse live vehicles, inclusions and prices from trusted suppliers') }}</p>
                    </div>
                </li>
                <li class="js-works-step js-reveal" style="--reveal-delay: 240ms">
                    <div class="js-works-step__node" aria-hidden="true">
                        <span class="js-works-step__num">03</span>
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="js-works-step__body">
                        <h3>{{ crm('car-hire.step_3_head', 'Book Securely') }}</h3>
                        <p>{{ crm('car-hire.step_3_text', 'Add your details and pay via our secure payment gateway') }}</p>
                    </div>
                </li>
                <li class="js-works-step js-reveal" style="--reveal-delay: 360ms">
                    <div class="js-works-step__node" aria-hidden="true">
                        <span class="js-works-step__num">04</span>
                        <i class="fa fa-road"></i>
                    </div>
                    <div class="js-works-step__body">
                        <h3>{{ crm('car-hire.step_4_head', 'Collect & Drive') }}</h3>
                        <p>{{ crm('car-hire.step_4_text', 'Pick up your car with your confirmation reference and hit the road') }}</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    {{-- Why choose — shared homepage dark strip --}}
    @include('partials.why-choose', [
        'withReveal' => true,
        'badge' => crm('car-hire.why_eyebrow', 'Why book with us'),
        'title' => crm('car-hire.why_title', 'Why Customers Choose Our Car Hire'),
        'subtitle' => crm('car-hire.why_lead', 'Live supplier rates, flexible drop-off, and support when you need it.'),
        'items' => [
            [
                'icon' => 'fa-tags',
                'title' => crm('car-hire.why_1_title', 'Live rates'),
                'text' => crm('car-hire.why_1_text', 'Compare up-to-date car hire prices from trusted suppliers so you can book with confidence.'),
            ],
            [
                'icon' => 'fa-shield',
                'title' => crm('car-hire.why_2_title', 'Trusted suppliers'),
                'text' => crm('car-hire.why_2_text', 'We work with established rental brands and show clear inclusions before you pay.'),
            ],
            [
                'icon' => 'fa-map-marker',
                'title' => crm('car-hire.why_3_title', 'Flexible drop-off'),
                'text' => crm('car-hire.why_3_text', 'Return to the same location or choose a different drop-off point when you need it.'),
            ],
            [
                'icon' => 'fa-headphones',
                'title' => crm('car-hire.why_4_title', 'Support'),
                'text' => crm('car-hire.why_4_text', 'Our team is a phone call or email away if you need help with a booking or a change to your trip.'),
            ],
        ],
    ])

    {{-- Hire options — reuse homepage gate card layout with car-hire copy --}}
    @include('partials.parking-gates', [
        'withReveal' => true,
        'sectionId' => 'carhire-options',
        'badge' => crm('car-hire.options_badge', 'Vehicle options'),
        'title' => crm('car-hire.options_title', 'Cars to Suit Every Trip'),
        'subtitle' => crm('car-hire.options_lead', 'From compact city cars to larger family vehicles — compare live options and pick what fits your journey.'),
        'meetTitle' => crm('car-hire.option_1_title', 'Economy & Compact'),
        'meetText' => crm('car-hire.option_1_text', 'Great value for city breaks and short trips. Easy to park, light on fuel, and ideal for one or two travellers.'),
        'meetFooter' => crm('car-hire.option_1_footer', 'Best everyday value'),
        'meetCode' => 'EC',
        'meetLane' => 'Class 01',
        'meetTag' => 'Value',
        'onsiteTitle' => crm('car-hire.option_2_title', 'Family & Intermediate'),
        'onsiteText' => crm('car-hire.option_2_text', 'Extra space for luggage and passengers. A comfortable choice for longer drives and family holidays.'),
        'onsiteFooter' => crm('car-hire.option_2_footer', 'Most popular'),
        'onsiteCode' => 'FM',
        'onsiteLane' => 'Class 02',
        'onsiteTag' => 'Most popular',
        'parkTitle' => crm('car-hire.option_3_title', 'SUV & Automatic'),
        'parkText' => crm('car-hire.option_3_text', 'Higher seating position, more room, and automatic options when available — built for longer journeys.'),
        'parkFooter' => crm('car-hire.option_3_footer', 'Extra comfort'),
        'parkCode' => 'SV',
        'parkLane' => 'Class 03',
        'parkTag' => 'Comfort',
    ])

    {{-- Compare strip — same homepage compare layout --}}
    <section class="js-compare-section">
        <div class="js-compare-section__glow" aria-hidden="true"></div>
        <div class="container">
            <div class="js-compare-layout">
                <div class="js-compare-copy js-reveal">
                    <span class="js-compare-eyebrow"><i class="fa fa-car" aria-hidden="true"></i> {{ crm('car-hire.choose_eyebrow', 'Airport car hire') }}</span>
                    <h2 class="js-compare-title">
                        <span class="js-compare-title__line">{{ crm('car-hire.choose_title', 'Compare Airport Car Hire') }}</span>
                    </h2>
                    <p class="js-compare-lead">{{ crm('car-hire.choose_p1', 'We help you compare live car hire rates from trusted suppliers, with clear inclusions, flexible pick-up and drop-off, and secure checkout on Total Travel Solutions.') }}</p>
                    <ul class="js-compare-checklist">
                        <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>{{ crm('car-hire.choose_perk_1', 'Live supplier availability') }}</li>
                        <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>{{ crm('car-hire.choose_perk_2', 'Transparent pay-now pricing') }}</li>
                        <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>{{ crm('car-hire.choose_perk_3', 'Same or different drop-off') }}</li>
                        <li><span class="js-compare-checklist__icon"><i class="fa fa-check" aria-hidden="true"></i></span>{{ crm('car-hire.choose_perk_4', 'Secure online booking') }}</li>
                    </ul>
                    <p class="js-compare-footnote">{{ crm('car-hire.choose_footnote', 'At Total Travel Solutions you hire with confidence.') }}</p>
                </div>
                <div class="js-compare-visual js-reveal" style="--reveal-delay: 120ms">
                    <div class="js-compare-visual__frame">
                        <img src="{{ asset('assets/images/COMPARE AIRPORT PARKING.png') }}" alt="{{ crm('car-hire.choose_img_alt', 'Compare airport car hire') }}" class="img-fluid" loading="lazy" width="400" height="290">
                    </div>
                    <div class="js-compare-metrics">
                        <div class="js-compare-metric">
                            <strong>{{ crm('car-hire.stat_1_value', 'Live') }}</strong>
                            <span>{{ crm('car-hire.stat_1_label', 'Supplier rates') }}</span>
                        </div>
                        <div class="js-compare-metric">
                            <strong>{{ crm('car-hire.stat_2_value', 'Flex') }}</strong>
                            <span>{{ crm('car-hire.stat_2_label', 'Drop-off options') }}</span>
                        </div>
                        <div class="js-compare-metric">
                            <strong>{{ crm('car-hire.stat_3_value', '24/7') }}</strong>
                            <span>{{ crm('car-hire.stat_3_label', 'Booking support') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tips — homepage tip cards --}}
    <section class="js-tips-section section-spacing">
        <div class="container">
            <div class="js-section-head js-reveal">
                <span class="js-section-head__badge js-section-head__badge--light">{{ crm('car-hire.tips_badge', 'Expert advice') }}</span>
                <h2 class="js-section-title">{!! strip_tags((string) crm_html('car-hire.tips_title', '<span class="orangeClr">Top Tips</span> For Car Hire'), '<span>') !!}</h2>
                <p class="js-section-subtitle">{{ crm('car-hire.tips_lead', 'Simple advice to make your car hire booking smoother') }}</p>
            </div>

            <div class="js-tips-grid js-reveal">
                <article class="js-tip-card">
                    <div class="js-tip-card__icon">
                        <img src="{{ asset('assets/images/Book in Advance.png') }}" alt="" loading="lazy" width="40" height="40">
                    </div>
                    <h3 class="js-tip-card__title">{{ crm('car-hire.tip_1_title', 'Book in Advance') }}</h3>
                    <p class="js-tip-card__text">{{ crm('car-hire.tip_1_text', 'Reserving earlier often means better choice of vehicles and clearer prices for your travel dates.') }}</p>
                </article>

                <article class="js-tip-card">
                    <div class="js-tip-card__icon">
                        <img src="{{ asset('assets/images/Compare Price.png') }}" alt="" loading="lazy" width="40" height="40">
                    </div>
                    <h3 class="js-tip-card__title">{{ crm('car-hire.tip_2_title', 'Compare Inclusions') }}</h3>
                    <p class="js-tip-card__text">{{ crm('car-hire.tip_2_text', 'Check mileage, insurance and extras on each deal so you know what is included before you book.') }}</p>
                </article>

                <article class="js-tip-card">
                    <div class="js-tip-card__icon">
                        <img src="{{ asset('assets/images/Of side Parking.png') }}" alt="" loading="lazy" width="40" height="40">
                    </div>
                    <h3 class="js-tip-card__title">{{ crm('car-hire.tip_3_title', 'Check Drop-off') }}</h3>
                    <p class="js-tip-card__text">{{ crm('car-hire.tip_3_text', 'Confirm whether you need same-location return or a different drop-off before you finalise the hire.') }}</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="js-ps-banner" style="margin-bottom: 0;">
        <div class="container">
            <div class="js-ps-banner__inner js-reveal">
                <div class="js-ps-banner__copy">
                    <h2 class="js-ps-banner__title">{{ crm('car-hire.cta_title', 'Ready to compare car hire?') }}</h2>
                    <p class="js-ps-banner__text">{{ crm('car-hire.cta_text', 'Search your pick-up location and reserve a vehicle in minutes with transparent pricing.') }}</p>
                </div>
                <a href="#carhire" class="js-btn js-btn--accent" id="js-carhire-cta-scroll">{{ crm('car-hire.cta_btn', 'Find Cars') }}</a>
            </div>
        </div>
    </section>

</main>

@include('layouts.footer')

<script>
(function () {
    function ready(fn) {
        if (window.jQuery) { fn(window.jQuery); return; }
        setTimeout(function () { ready(fn); }, 40);
    }
    ready(function ($) {
        // Homepage-style reveal
        var $items = $('.js-reveal');
        if ($items.length && 'IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            $items.each(function () { io.observe(this); });
        } else {
            $items.addClass('is-visible');
        }

        $('#js-carhire-cta-scroll').on('click', function (e) {
            e.preventDefault();
            var $widget = $('[data-js-booking-widget]').first();
            if ($widget.length) {
                $widget.find('.js-product-tab[data-product="carhire"]').trigger('click');
                $('html, body').animate({ scrollTop: $widget.offset().top - 24 }, 420);
            } else {
                window.location.hash = 'carhire';
            }
        });
    });
})();
</script>
