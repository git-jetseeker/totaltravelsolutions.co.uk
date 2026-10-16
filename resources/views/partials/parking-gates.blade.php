{{-- Shared Meet & Greet / On-Site / Park & Ride gate cards (home design) --}}
@php
    $badge = $badge ?? 'Parking types';
    $title = $title ?? 'Parking Options to Suit Every Traveller';
    $subtitle = $subtitle ?? 'We cover Top UK Airports including Heathrow, Gatwick, Manchester, Stansted, Birmingham, Luton, Edinburgh, and more.';
    $sectionId = $sectionId ?? null;
    $sectionClass = trim('js-parking-section section-spacing ' . ($sectionClass ?? ''));
    $withReveal = !empty($withReveal);

    $meetTitle = $meetTitle ?? 'Meet & Greet';
    $meetText = $meetText ?? 'A seamless start to your journey. Drive to the terminal, hand over your keys, and let a professional park your vehicle while you head straight to departures.';
    $meetFooter = $meetFooter ?? 'Fastest check-in';

    $onsiteTitle = $onsiteTitle ?? 'On-Site Airport Parking';
    $onsiteText = $onsiteText ?? 'Park close to the terminal within walking distance. Direct access, high security, and maximum convenience for short or busy trips.';
    $onsiteFooter = $onsiteFooter ?? 'Walk to terminal';

    $parkTitle = $parkTitle ?? 'Park & Ride';
    $parkText = $parkText ?? 'Great value for longer stays. Park securely and take a complimentary shuttle straight to your terminal without delays.';
    $parkFooter = $parkFooter ?? 'Free shuttle included';

    $wrapHtml = function ($html): string {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        if (preg_match('/<(p|div|ul|ol|br)\b/i', $html)) {
            return $html;
        }
        return '<p>' . e($html) . '</p>';
    };
@endphp

<section class="{{ $sectionClass }}"@if($sectionId) id="{{ $sectionId }}"@endif>
    <div class="js-parking-section__runway" aria-hidden="true"></div>
    <div class="container">
        <div class="js-section-head{{ $withReveal ? ' js-reveal' : '' }}">
            <span class="js-section-head__badge js-section-head__badge--light">{{ $badge }}</span>
            <h2 class="js-section-title">{!! $title !!}</h2>
            <div class="js-section-subtitle">{!! $wrapHtml($subtitle) !!}</div>
        </div>

        <div class="js-parking-gates">
            <article class="js-parking-gate{{ $withReveal ? ' js-reveal' : '' }}"@if($withReveal) style="--reveal-delay: 0ms"@endif>
                <header class="js-parking-gate__header">
                    <span class="js-parking-gate__code">MG</span>
                    <span class="js-parking-gate__lane">Lane 01</span>
                    <span class="js-parking-gate__tag js-parking-gate__tag--premium">Premium</span>
                </header>
                <div class="js-parking-gate__icon">
                    <img src="{{ asset('assets/images/Meet & Greet.png') }}" alt="" loading="lazy" width="64" height="64">
                </div>
                <div class="js-parking-gate__body">
                    <h3>{{ $meetTitle }}</h3>
                    {!! $wrapHtml($meetText) !!}
                </div>
                <div class="js-parking-gate__footer">
                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $meetFooter }}</span>
                </div>
            </article>

            <article class="js-parking-gate js-parking-gate--featured{{ $withReveal ? ' js-reveal' : '' }}"@if($withReveal) style="--reveal-delay: 100ms"@endif>
                <header class="js-parking-gate__header">
                    <span class="js-parking-gate__code">OS</span>
                    <span class="js-parking-gate__lane">Lane 02</span>
                    <span class="js-parking-gate__tag js-parking-gate__tag--convenient">Most popular</span>
                </header>
                <div class="js-parking-gate__icon">
                    <img src="{{ asset('assets/images/On Site.png') }}" alt="" loading="lazy" width="64" height="64">
                </div>
                <div class="js-parking-gate__body">
                    <h3>{{ $onsiteTitle }}</h3>
                    {!! $wrapHtml($onsiteText) !!}
                </div>
                <div class="js-parking-gate__footer">
                    <span><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $onsiteFooter }}</span>
                </div>
            </article>

            <article class="js-parking-gate{{ $withReveal ? ' js-reveal' : '' }}"@if($withReveal) style="--reveal-delay: 200ms"@endif>
                <header class="js-parking-gate__header">
                    <span class="js-parking-gate__code">PR</span>
                    <span class="js-parking-gate__lane">Lane 03</span>
                    <span class="js-parking-gate__tag js-parking-gate__tag--value">Best value</span>
                </header>
                <div class="js-parking-gate__icon">
                    <img src="{{ asset('assets/images/Park & Ride.png') }}" alt="" loading="lazy" width="64" height="64">
                </div>
                <div class="js-parking-gate__body">
                    <h3>{{ $parkTitle }}</h3>
                    {!! $wrapHtml($parkText) !!}
                </div>
                <div class="js-parking-gate__footer">
                    <span><i class="fa fa-bus" aria-hidden="true"></i> {{ $parkFooter }}</span>
                </div>
            </article>
        </div>
    </div>
</section>
