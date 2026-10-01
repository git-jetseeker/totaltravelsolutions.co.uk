@php $meta = function_exists('crm_page_meta') ? crm_page_meta('airports') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: 'Total Travel Solutions | Compare Affordable & Convenient Airport Parking Options')
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: 'airports')
@section('meta_description', ($meta->meta_description ?? null) ?: 'Compare top airport parking services for the best deals on secure, affordable, and convenient parking options near major airports.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261001light') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261001mock') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-airports-list.css?v=20261001airports') }}">

@include('partials.page-hero', [
    'eyebrow' => crm('airports.hero_eyebrow', 'Nationwide coverage'),
    'title' => crm('airports.hero_title', 'Airport Parking Across the UK'),
    'subtitle' => crm('airports.hero_subtitle', 'Compare secure, affordable parking at every major UK airport'),
    'lead' => crm('airports.hero_lead', 'Pre-book Meet & Greet, Park & Ride, and on-site parking with trusted Park Mark operators.'),
    'heroClass' => 'js-page-hero--enhanced js-page-hero--airports-list',
    'withBookingWidget' => true,
    'bookingCardId' => 'airports_search_form',
])

<main class="js-airports-page">
    @include('partials.why-choose')

    @include('partials.parking-gates', [
        'badge' => 'Parking types',
        'title' => 'Parking Options to Suit Every Traveller',
        'subtitle' => 'We cover Top UK Airports including Heathrow, Gatwick, Manchester, Stansted, Birmingham, Luton, Edinburgh, and more.',
    ])

    <section class="js-airports-list">
        <div class="js-container">
            <header class="js-airports-list__head">
                <span class="js-airports-list__eyebrow">{{ crm('airports.intro_eyebrow', 'All airports') }}</span>
                <h2 class="js-airports-list__title">{{ crm('airports.intro_title', 'All Major UK Airports') }}</h2>
                <p class="js-airports-list__lead">{{ crm('airports.intro_text', 'Compare and book secure, affordable parking at every major UK airport with best-price deals and trusted operators.') }}</p>
            </header>

            <div class="js-airports-grid">
                @php $p = 28; @endphp
                @foreach ($airports as $index => $airport)
                    @if($airport->id == '20')
                        @php $p = 4; @endphp
                    @endif
                    @if($airport->id == '27')
                        @php $p = 5; @endphp
                    @endif
                    @if($airport->id == '26')
                        @php $p = 5; @endphp
                    @endif
                    @if($airport->id == '40')
                        @php $p = 9; @endphp
                    @endif
                    @if($airport->id == '24')
                        @php $p = 4; @endphp
                    @endif
                    @if($airport->id == '1')
                        @php $p = 4; @endphp
                    @endif
                    @php
                        if (preg_match('/\s/', $airport->name)) {
                            $name = str_replace(' ', '-', strtolower($airport->name));
                        } else {
                            $name = trim(strtolower($airport->name));
                        }
                        $url = str_replace(' ', '-', $name) . '-airport-parking';

                        $airportImage = ttss_dashboard_asset_url($airport->profile_image);
                        $airportImageFile = basename(str_replace('\\', '/', (string) $airport->profile_image));
                        $localAirportImage = public_path('assets/images/airports/' . $airportImageFile);
                        if ($airportImageFile !== '' && is_file($localAirportImage)) {
                            $airportImage = asset('assets/images/airports/' . $airportImageFile);
                        }
                    @endphp

                    <div class="airport-item {{ $index < 9 ? 'show' : '' }}">
                        <article class="airport-card">
                            <div class="airport-image-container">
                                <img src="{{ $airportImage }}"
                                    alt="{{ $airport->name }} airport parking"
                                    loading="lazy"
                                    width="400"
                                    height="200"
                                    onerror="this.onerror=null;this.src='{{ asset('theme/images/logo-black.png') }}';">
                                <span class="airport-badge">{{ $airport->name }}</span>
                            </div>
                            <div class="airport-content">
                                <h3 class="airport-name">{{ $airport->name }} Parking</h3>
                                <div class="airport-rating" aria-label="5 star rated">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                                <div class="airport-features">
                                    <span class="airport-feature-icon" title="24/7 CCTV">
                                        <img src="{{ asset('theme/images/CCTV.png') }}" alt="" width="40" height="40">
                                    </span>
                                    <span class="airport-feature-icon" title="Disability Access">
                                        <img src="{{ asset('theme/images/disability.png') }}" alt="" width="40" height="40">
                                    </span>
                                    <span class="airport-feature-icon" title="Security Barriers">
                                        <img src="{{ asset('theme/images/barrier.png') }}" alt="" width="40" height="40">
                                    </span>
                                    <span class="airport-feature-icon" title="24 Hour Service">
                                        <img src="{{ asset('theme/images/24_hours.png') }}" alt="" width="40" height="40">
                                    </span>
                                </div>
                                <div class="airport-footer">
                                    <div>
                                        <span class="airport-price-label">{{ crm('airports.card_price_label', 'Starting from') }}</span>
                                        <span class="airport-price-value">£{{ $p }}</span>
                                    </div>
                                    <a href="{{ route('page', ['slug' => $url]) }}" class="airport-link">
                                        {{ crm('airports.card_cta', 'View Deals') }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @php $p = $p + 2; @endphp
                @endforeach
            </div>

            @if(count($airports) > 9)
                <div class="btn-container">
                    <button type="button" id="showMoreBtn" class="btn-show-more">{{ crm('airports.show_more', 'Show More') }}</button>
                </div>
            @endif
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('.airport-item');
    var btn = document.getElementById('showMoreBtn');
    var limit = 9;

    if (!btn || items.length <= limit) {
        if (btn) btn.style.display = 'none';
        return;
    }

    btn.addEventListener('click', function () {
        var hidden = Array.prototype.filter.call(items, function (el) {
            return !el.classList.contains('show');
        });

        if (hidden.length) {
            hidden.forEach(function (el) { el.classList.add('show'); });
            btn.textContent = 'Show Less';
        } else {
            Array.prototype.forEach.call(items, function (el, i) {
                if (i >= limit) el.classList.remove('show');
            });
            btn.textContent = 'Show More';
            var head = document.querySelector('.js-airports-list__head');
            if (head) head.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

@include('layouts.footer')
