@section('title', 'JET SEEKER | Compare Affordable & Convenient Airport Parking Options')
@section('meta_keyword', 'airports')
@section('meta_description', 'Compare top airport parking services for the best deals on secure, affordable, and convenient parking options near major airports.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-airports-list.css?v=20251020') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">

@include('partials.page-hero', [
    'eyebrow' => 'All UK airports',
    'title' => 'UK Airport Parking',
    'subtitle' => 'Every major UK airport, one simple search',
    'lead' => 'Compare and book secure, affordable parking at all major UK airports with the best prices guaranteed.',
    'heroClass' => 'js-page-hero--enhanced js-page-hero--airports-list',
    'withBookingWidget' => true,
])

<section class="js-airports-trust">
    <div class="js-container">
        <div class="js-airports-trust__grid">
            <div class="js-airports-trust__item">
                <span class="js-airports-trust__icon"><img src="{{ asset('assets/images/serviceicon3.webp') }}" alt="" loading="lazy"></span>
                <p class="js-airports-trust__label">Years of Experience</p>
            </div>
            <div class="js-airports-trust__item">
                <span class="js-airports-trust__icon"><img src="{{ asset('assets/images/serviceicon1.webp') }}" alt="" loading="lazy"></span>
                <p class="js-airports-trust__label">Free Cancellation</p>
            </div>
            <div class="js-airports-trust__item">
                <span class="js-airports-trust__icon"><img src="{{ asset('assets/images/serviceicon2.webp') }}" alt="" loading="lazy"></span>
                <p class="js-airports-trust__label">Never Beaten on Price</p>
            </div>
        </div>
    </div>
</section>

<main class="js-airports-page">
    <section class="js-airports-list" id="airports-list">
        <div class="js-container">
            <header class="js-airports-list__head">
                <span class="js-airports-list__eyebrow">Browse airports</span>
                <h2 class="js-airports-list__title">Find parking at <span>your airport</span></h2>
                <p class="js-airports-list__lead">Select an airport below to compare Meet &amp; Greet, Park &amp; Ride and on-site parking options.</p>
            </header>

            <div class="js-airports-grid">
            @php $p = 28; @endphp
            @foreach ($airports as $index => $airport)
                    @if ($airport->id == '20')
                        @php $p = 4; @endphp
            @endif
                    @if ($airport->id == '27')
                        @php $p = 5; @endphp
            @endif
                    @if ($airport->id == '26')
                        @php $p = 5; @endphp
            @endif
                    @if ($airport->id == '40')
                        @php $p = 9; @endphp
            @endif
                    @if ($airport->id == '24')
                        @php $p = 4; @endphp
            @endif
                    @if ($airport->id == '1')
                        @php $p = 4; @endphp
            @endif
                @php
                    if (preg_match('/\s/', $airport->name)) {
                        $name = str_replace(' ', '-', strtolower($airport->name));
                    } else {
                        $name = trim(strtolower($airport->name));
                        }
                        $url = str_replace(' ', '-', $name) . '-airport-parking';
                @endphp
                <div class="airport-item {{ $index < 9 ? 'show' : '' }}">
                        <article class="airport-card">
                        <div class="airport-image-container">
                                <img src="{{ url('https://www.dashboard.jetseekergroup.com/storage/' . str_replace('public/', '', $airport->profile_image)) }}"
                                     alt="{{ $airport->name }} airport parking"
                                 loading="lazy">
                                <span class="airport-badge">{{ $airport->name }}</span>
                            </div>
                            <div class="airport-content">
                                <h3 class="airport-name">{{ $airport->name }} Parking</h3>
                                <div class="airport-rating" aria-label="5 star rating">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                                <div class="airport-features">
                                    <div class="airport-feature-icon" title="24/7 CCTV">
                                        <img src="{{ asset('theme/images/CCTV.png') }}" alt="CCTV">
                                    </div>
                                <div class="airport-feature-icon" title="Disability Access">
                                        <img src="{{ asset('theme/images/disability.png') }}" alt="Disability access">
                                </div>
                                <div class="airport-feature-icon" title="Security Barriers">
                                        <img src="{{ asset('theme/images/barrier.png') }}" alt="Security barriers">
                                </div>
                                <div class="airport-feature-icon" title="24 Hour Service">
                                        <img src="{{ asset('theme/images/24_hours.png') }}" alt="24 hour service">
                                    </div>
                                </div>
                            <div class="airport-footer">
                                <div class="airport-price">
                                    <span class="airport-price-label">Starting from</span>
                                        <span class="airport-price-value">&pound;{{ $p }}</span>
                                    </div>
                                    <a href="{{ route('page', ['slug' => $url]) }}" class="airport-link">
                                        View Deals <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @php $p = $p + 2; @endphp
            @endforeach
        </div>

            @if (count($airports) > 9)
        <div class="btn-container">
                    <button type="button" id="showMoreBtn" class="btn-show-more">Show More</button>
                </div>
            @endif
        </div>
</section>
</main>

@include('layouts.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof jQuery === 'undefined') {
        return;
    }

    var $ = jQuery;
    var $airportItems = $('.airport-item');
    var $showMoreBtn = $('#showMoreBtn');
    var itemsToShow = 9;

    if ($airportItems.length <= itemsToShow) {
        $showMoreBtn.hide();
    }

    $showMoreBtn.on('click', function () {
        var $hiddenItems = $airportItems.filter(':not(.show)');

        if ($hiddenItems.length > 0) {
            $hiddenItems.addClass('show');
            $(this).text('Show Less');
        } else {
            $airportItems.slice(itemsToShow).removeClass('show');
            $(this).text('Show More');

            $('html, body').animate({
                scrollTop: $('#airports-list').offset().top - 100
            }, 800);
        }
    });
});
</script>
