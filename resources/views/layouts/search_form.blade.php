@php
    $hideBookingWidget = !empty($hideBookingWidget);
@endphp

@unless($hideBookingWidget)
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261001whitetabs') }}">
@endunless

<div style="display:none;" id="notification"></div>

@php
    $heroTitle = isset($heroTitle) && trim((string) $heroTitle) !== ''
        ? trim((string) $heroTitle)
        : trim((string) crm('home.hero_title', 'Book Easy, Park Safe, Travel Happy!'));
    $heroTitleHtml = e($heroTitle);
    if (stripos($heroTitle, 'Travel Happy') !== false) {
        $heroTitleHtml = preg_replace(
            '/(Travel Happy!?)/i',
            '<span class="js-hero__accent">$1</span>',
            $heroTitleHtml,
            1
        );
    } elseif (preg_match('/\b(Parking)\b/i', $heroTitle)) {
        $heroTitleHtml = preg_replace(
            '/\b(Parking)\b/i',
            '<span class="js-hero__accent">$1</span>',
            $heroTitleHtml,
            1
        );
    } elseif (preg_match('/^About\s+(.+)$/i', $heroTitle, $aboutMatch)) {
        $heroTitleHtml = 'About <span class="js-hero__accent">' . e($aboutMatch[1]) . '</span>';
    } elseif (preg_match('/\b(Support)\b/i', $heroTitle)) {
        $heroTitleHtml = preg_replace(
            '/\b(Support)\b/i',
            '<span class="js-hero__accent">$1</span>',
            $heroTitleHtml,
            1
        );
    } elseif (preg_match('/\b(Questions)\b/i', $heroTitle)) {
        $heroTitleHtml = preg_replace(
            '/\b(Questions)\b/i',
            '<span class="js-hero__accent">$1</span>',
            $heroTitleHtml,
            1
        );
    } elseif (preg_match('/\b(Booking)\b/i', $heroTitle)) {
        $heroTitleHtml = preg_replace(
            '/\b(Booking)\b/i',
            '<span class="js-hero__accent">$1</span>',
            $heroTitleHtml,
            1
        );
    }

    $heroSubtitleHtml = isset($heroSubtitle) && trim((string) $heroSubtitle) !== ''
        ? e(trim((string) $heroSubtitle))
        : strip_tags((string) crm_html('home.hero_subtitle', 'Amazing Airport car park deals across all major UK airports'), '<b><strong><em><i><span>');

    $heroEyebrow = isset($heroEyebrow) && trim((string) $heroEyebrow) !== ''
        ? trim((string) $heroEyebrow)
        : 'Total Travel Solutions';
@endphp

<section class="search1 js-hero js-hero--mock{{ $hideBookingWidget ? ' js-hero--no-widget' : '' }}">
    <div class="js-hero__bg" aria-hidden="true"></div>
    <div class="js-hero__scrim" aria-hidden="true"></div>
    <div class="js-hero__blob" aria-hidden="true"></div>
    <div class="js-hero__flight" aria-hidden="true">
        <svg viewBox="0 0 220 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M8 72 C60 68, 90 20, 150 28 C180 32, 198 40, 210 48" stroke="currentColor" stroke-width="2" stroke-dasharray="5 7" stroke-linecap="round"/>
            <g transform="translate(204,44) rotate(28)">
                <path d="M0 0 L14 -4 L18 0 L14 4 Z" fill="currentColor"/>
                <path d="M2 0 L-6 -8 L-4 0 L-6 8 Z" fill="currentColor"/>
            </g>
        </svg>
    </div>

    <div class="container js-hero__inner">
        <div class="js-hero__copy">
            <p class="js-hero__eyebrow">
                <span class="js-hero__eyebrow-line" aria-hidden="true"></span>
                {{ $heroEyebrow }}
                <span class="js-hero__eyebrow-line" aria-hidden="true"></span>
            </p>

            <h1 class="main-heading js-hero__title">{!! $heroTitleHtml !!}</h1>

            <p class="main-paragraph js-hero__subtitle">{!! $heroSubtitleHtml !!}</p>

            <ul class="js-hero__features">
                <li class="js-hero__feature">
                    <span class="js-hero__feature-icon" aria-hidden="true"><i class="fa fa-shield"></i></span>
                    <span class="js-hero__feature-text">
                        <strong>{{ crm('home.hero_feat_1_title', 'Secure Parking') }}</strong>
                    </span>
                </li>
                <li class="js-hero__feature">
                    <span class="js-hero__feature-icon" aria-hidden="true"><i class="fa fa-tag"></i></span>
                    <span class="js-hero__feature-text">
                        <strong>{{ crm('home.hero_feat_2_title', 'Best Prices') }}</strong>
                    </span>
                </li>
                <li class="js-hero__feature">
                    <span class="js-hero__feature-icon" aria-hidden="true"><i class="fa fa-plane"></i></span>
                    <span class="js-hero__feature-text">
                        <strong>{{ crm('home.hero_feat_3_title', 'Multiple Airports') }}</strong>
                    </span>
                </li>
                <li class="js-hero__feature">
                    <span class="js-hero__feature-icon" aria-hidden="true"><i class="fa fa-headphones"></i></span>
                    <span class="js-hero__feature-text">
                        <strong>{{ crm('home.hero_feat_4_title', 'Expert Support') }}</strong>
                    </span>
                </li>
            </ul>
        </div>

        @unless($hideBookingWidget)
            <div class="js-hero__widget">
                @include('partials.booking-widget', [
                    'selectedAirportId' => $selectedAirportId ?? null,
                    'bookingCardId' => $bookingCardId ?? 'home_search_form',
                ])
            </div>
        @endunless
    </div>
</section>
