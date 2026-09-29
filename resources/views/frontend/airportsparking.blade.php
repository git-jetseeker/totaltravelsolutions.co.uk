@php $meta = function_exists('crm_page_meta') ? crm_page_meta('parking-services') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: ($page->meta_title ?? 'Parking Services - Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: ($page->meta_keyword ?? 'airport parking, meet and greet, park and ride'))
@section('meta_description', ($meta->meta_description ?? null) ?: ($page->meta_description ?? 'Compare Meet & Greet, Park & Ride, and On-Airport parking across major UK airports.'))
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-parking-services.css?v=20260929ps3') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all();
    $agentId = (string) (function_exists('current_site_agent_id') ? current_site_agent_id() : current_agent_id());

    foreach ($settingsAll as $setting) {
        if ((string) $setting->agent_id === $agentId) {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }

    // Fall back to shared agent-1 settings when this agent has no services copy.
    $neededKeys = [
        'services_page_parking_heading',
        'services_page_parking_descp',
        'services_page_parking_sec1_heading',
        'services_page_parking_sec1_descp',
        'services_page_parking_sec1_meetandgreet',
        'services_page_parking_sec1_parkandride',
        'services_page_parking_sec1_onairport',
        'services_page_parking_sec2_heading',
        'services_page_parking_sec2_descp',
        'services_page_parking_sec2_step1',
        'services_page_parking_sec2_step2',
        'services_page_parking_sec2_step3',
    ];
    $missing = collect($neededKeys)->filter(fn ($k) => empty(trim(strip_tags((string) ($site_settings_main[$k] ?? '')))));
    if ($missing->isNotEmpty()) {
        foreach ($settingsAll as $setting) {
            if ((string) $setting->agent_id === '1' && in_array($setting->field_name, $neededKeys, true)) {
                if (empty(trim(strip_tags((string) ($site_settings_main[$setting->field_name] ?? ''))))) {
                    $site_settings_main[$setting->field_name] = $setting->field_value;
                }
            }
        }
    }

    $site_settings_main = normalize_site_settings($site_settings_main);

    $cleanCmsHtml = function ($html): string {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        $html = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;
        $html = preg_replace('/\sstyle=("[^"]*"|\'[^\']*\')/i', '', $html) ?? $html;
        $html = preg_replace('/\s(face|size|color|bgcolor|align|dir)=("[^"]*"|\'[^\']*\')/i', '', $html) ?? $html;
        $html = preg_replace('/<\/?font\b[^>]*>/i', '', $html) ?? $html;
        $html = preg_replace('/<\/?span\b[^>]*>/i', '', $html) ?? $html;
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Word/Google Docs paste often uses Unicode spaces that break wrap + regex \s.
        $html = preg_replace('/[\x{00A0}\x{1680}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}\x{FEFF}]+/u', ' ', $html) ?? $html;
        $html = str_replace(['&nbsp;', "\xc2\xa0"], ' ', $html);
        $html = preg_replace('/\b(JETSEEKER|JET SEEKER|JetSeeker|Jetseeker|ParkingZone|Zairport Parking)\b/i', 'Total Travel Solutions', $html) ?? $html;
        $html = preg_replace('/Airports We Are Servings?/i', 'Airports We Serve', $html) ?? $html;
        $html = preg_replace('/\?(Get a Quote|Get a quote)/', '"$1"', $html) ?? $html;

        // Drop legacy numbered airport list paragraphs (chips cover this).
        // e.g. "1. Gatwick 2. Stansted 3.Heathrow" — on phones nbsp padding clipped names.
        $html = preg_replace_callback('/<p[^>]*>(.*?)<\/p>/is', function ($m) {
            $text = trim(preg_replace('/\s+/u', ' ', strip_tags($m[1])) ?? '');
            if ($text === '') {
                return '';
            }
            if (preg_match('/^(?:\d+\.\s*[A-Za-z][\w\'\-]*)(?:\s+\d+\.\s*[A-Za-z][\w\'\-]*)*$/u', $text)) {
                return '';
            }
            return '<p>' . trim($m[1]) . '</p>';
        }, $html) ?? $html;
        $html = preg_replace(
            '/\s*(?:The\s+)?(?:Total Travel Solutions|JetSeeker|JETSEEKER|ParkingZone)?\s*currently provides parking comparison services at:\s*/i',
            ' ',
            $html
        ) ?? $html;

        // Empty / whitespace-only paragraphs.
        $html = preg_replace('/<p[^>]*>\s*(?:<br\s*\/?>\s*)*<\/p>/i', '', $html) ?? $html;

        // Soften ALL-CAPS headings / short blurbs inside tags.
        $html = preg_replace_callback('/>([A-Z0-9][A-Z0-9\s&\-]{8,})</', function ($m) {
            $text = trim($m[1]);
            if ($text === strtoupper($text) && str_word_count($text) <= 10) {
                return '>' . ucwords(strtolower($text)) . '<';
            }
            return $m[0];
        }, $html) ?? $html;

        // Plain-text ALL CAPS (common for settings headings).
        $plain = trim(strip_tags($html));
        if ($plain !== '' && $plain === strtoupper($plain) && str_word_count($plain) <= 12 && !preg_match('/[<>]/', $html)) {
            $html = ucwords(strtolower($plain));
        }

        $html = preg_replace('/\s{2,}/', ' ', $html) ?? $html;
        $html = preg_replace('/(?:<br\s*\/?>\s*){2,}/i', '<br>', $html) ?? $html;

        return trim($html);
    };

    $settingOr = function (string $key, string $default = '') use ($site_settings_main, $cleanCmsHtml) {
        $value = $cleanCmsHtml($site_settings_main[$key] ?? '');
        return $value !== '' ? $value : $default;
    };

    $liveAirports = collect($airports ?? [])->where('status', 'Yes')->take(8)->values();
@endphp

<section class="js-page-hero js-page-hero--enhanced js-page-hero--services">
    <div class="js-container">
        <span class="js-page-hero__eyebrow">{{ crm('parking-services.hero_eyebrow', 'Airport parking') }}</span>
        <h1 class="js-page-hero__title">{{ crm('parking-services.hero_title', 'Parking Services') }}</h1>
        <p class="js-page-hero__subtitle">{{ crm('parking-services.hero_subtitle', 'Meet & Greet, Park & Ride, and On-Airport options') }}</p>
        <p class="js-page-hero__lead">{{ crm('parking-services.hero_lead', 'Compare trusted airport parking across the UK, pre-book online for guaranteed spaces, competitive rates, and a stress-free start to every journey.') }}</p>
    </div>
</section>

<main class="js-parking-services">

    {{-- Intro + booking --}}
    <section class="js-ps-intro">
        <div class="js-container">
            <div class="js-ps-intro__layout">
                <div class="js-ps-intro__main">
                    <span class="js-ps-intro__eyebrow">{{ crm('parking-services.intro_badge', 'How it works for you') }}</span>
                    <h2 class="js-ps-intro__title">{{ crm('parking-services.intro_title', 'Airport Parking Made Simple') }}</h2>
                    <article class="js-ps-intro__card">
                        <header class="js-ps-intro__card-head">
                            <h3>{!! $settingOr('services_page_parking_heading', 'Airports We Serve') !!}</h3>
                        </header>
                        <div class="js-ps-intro__card-body">
                            {!! $settingOr(
                                'services_page_parking_descp',
                                '<p>Total Travel Solutions works with trusted airport car parking suppliers around major UK airports to help you compare prices quickly and book with confidence.</p><p>Choose from Meet &amp; Greet, Park &amp; Ride, and On-Airport parking with secure, monitored operators.</p>'
                            ) !!}

                            @if($liveAirports->isNotEmpty())
                                <div class="js-ps-airports">
                                    @foreach($liveAirports as $airport)
                                        @php
                                            $slug = strtolower(trim(preg_replace('/\s+/', '-', $airport->name))) . '-airport-parking';
                                        @endphp
                                        <a class="js-ps-airports__chip" href="{{ route('page', ['slug' => $slug]) }}">{{ $airport->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>
                </div>

                <aside class="js-ps-sidebar">
                    <div class="js-ps-sidebar__card">
                        <header class="js-ps-sidebar__head">
                            <span class="js-ps-sidebar__badge">Quick search</span>
                            <h3>Find parking now</h3>
                            <p>Compare live rates in minutes.</p>
                        </header>
                        @include('partials.booking-widget', [
                            'selectedAirportId' => null,
                            'bookingCardId' => 'parking_services_search_form',
                            'bookingCardClass' => 'js-booking-card--sidebar',
                            'skipRefTracking' => true,
                        ])
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Parking types --}}
    <section class="js-ps-types" id="parking-types">
        <div class="js-container">
            <header class="js-ps-section-head">
                <span class="js-ps-section-head__badge">{{ crm('parking-services.types_badge', 'Service options') }}</span>
                <h2 class="js-ps-section-head__title">{!! $settingOr('services_page_parking_sec1_heading', crm('parking-services.services_title', 'Airport Parking Services')) !!}</h2>
                <p>{!! $settingOr(
                    'services_page_parking_sec1_descp',
                    'We offer on-site and off-site airport parking depending on what is available at each airport. Typically you can choose from three trusted service types.'
                ) !!}</p>
            </header>

            <div class="js-ps-types__grid">
                <article class="js-ps-type-card">
                    <div class="js-ps-type-card__codebar">
                        <span class="js-ps-type-card__code">MG</span>
                        <span class="js-ps-type-card__label">{{ crm('parking-services.park_2_title', 'Meet & Greet') }}</span>
                    </div>
                    <div class="js-ps-type-card__icon">
                        <img src="{{ asset('category-tile-meet-greet.svg') }}" alt="Meet & Greet" loading="lazy" width="88" height="88">
                    </div>
                    <h3 class="js-ps-type-card__title">{{ crm('parking-services.park_2_title', 'Meet & Greet') }}</h3>
                    <div class="js-ps-type-card__body">
                        {!! $settingOr(
                            'services_page_parking_sec1_meetandgreet',
                            crm('parking-services.park_2_text', 'Drive to the terminal, hand over your keys, and let a professional park your vehicle while you head straight to departures.')
                        ) !!}
                    </div>
                </article>

                <article class="js-ps-type-card">
                    <div class="js-ps-type-card__codebar">
                        <span class="js-ps-type-card__code">PR</span>
                        <span class="js-ps-type-card__label">{{ crm('parking-services.park_1_title', 'Park & Ride') }}</span>
                    </div>
                    <div class="js-ps-type-card__icon">
                        <img src="{{ asset('category-tile-park-ride.svg') }}" alt="Park & Ride" loading="lazy" width="88" height="88">
                    </div>
                    <h3 class="js-ps-type-card__title">{{ crm('parking-services.park_1_title', 'Park & Ride') }}</h3>
                    <div class="js-ps-type-card__body">
                        {!! $settingOr(
                            'services_page_parking_sec1_parkandride',
                            crm('parking-services.park_1_text', 'Park securely and take a complimentary shuttle straight to your terminal — great value for longer stays.')
                        ) !!}
                    </div>
                </article>

                <article class="js-ps-type-card">
                    <div class="js-ps-type-card__codebar">
                        <span class="js-ps-type-card__code">OA</span>
                        <span class="js-ps-type-card__label">{{ crm('parking-services.park_3_title', 'On Airport') }}</span>
                    </div>
                    <div class="js-ps-type-card__icon">
                        <img src="{{ asset('category-tile-park-stroll.svg') }}" alt="On Airport" loading="lazy" width="88" height="88">
                    </div>
                    <h3 class="js-ps-type-card__title">{{ crm('parking-services.park_3_title', 'On Airport') }}</h3>
                    <div class="js-ps-type-card__body">
                        {!! $settingOr(
                            'services_page_parking_sec1_onairport',
                            crm('parking-services.park_3_text', 'Park close to the terminal within walking distance for direct access, high security, and maximum convenience.')
                        ) !!}
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="js-ps-steps" id="how-it-works">
        <div class="js-container">
            <header class="js-ps-section-head">
                <span class="js-ps-section-head__badge">{{ crm('parking-services.steps_badge', 'Simple process') }}</span>
                <h2 class="js-ps-section-head__title">{!! $settingOr('services_page_parking_sec2_heading', 'Airport Parking in 3 Simple Steps') !!}</h2>
                <p>{!! $settingOr(
                    'services_page_parking_sec2_descp',
                    'Airport parking offers are just a few clicks away. Follow these simple steps to compare the latest prices and book.'
                ) !!}</p>
            </header>

            <ol class="js-ps-steps__grid">
                <li class="js-ps-step-card">
                    <div class="js-ps-step-card__image">
                        <img src="{{ asset('assets/images/1.png') }}" alt="" loading="lazy" width="140" height="140">
                    </div>
                    <h3 class="js-ps-step-card__title">Search</h3>
                    <div class="js-ps-step-card__body">
                        {!! $settingOr(
                            'services_page_parking_sec2_step1',
                            'Use the search form to enter your airport and travel dates, then click Get a Quote to see live parking options.'
                        ) !!}
                    </div>
                </li>
                <li class="js-ps-step-card">
                    <div class="js-ps-step-card__image">
                        <img src="{{ asset('assets/images/2.png') }}" alt="" loading="lazy" width="140" height="140">
                    </div>
                    <h3 class="js-ps-step-card__title">Compare</h3>
                    <div class="js-ps-step-card__body">
                        {!! $settingOr(
                            'services_page_parking_sec2_step2',
                            'Choose the offer that suits your trip. Selecting an option takes you to a secure booking and payment page.'
                        ) !!}
                    </div>
                </li>
                <li class="js-ps-step-card">
                    <div class="js-ps-step-card__image">
                        <img src="{{ asset('assets/images/3.png') }}" alt="" loading="lazy" width="140" height="140">
                    </div>
                    <h3 class="js-ps-step-card__title">Book</h3>
                    <div class="js-ps-step-card__body">
                        {!! $settingOr(
                            'services_page_parking_sec2_step3',
                            'Enter your payment details. Once verified, your booking confirmation is sent to you straight away.'
                        ) !!}
                    </div>
                </li>
            </ol>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="js-ps-banner">
        <div class="js-container">
            <div class="js-ps-banner__inner">
                <div class="js-ps-banner__copy">
                    <h2 class="js-ps-banner__title">{{ crm('parking-services.cta_title', 'Ready to compare airport parking?') }}</h2>
                    <p class="js-ps-banner__text">{{ crm('parking-services.cta_text', 'Search major UK airports and reserve your space in minutes with transparent pricing.') }}</p>
                </div>
                <a href="{{ url('/') }}" class="js-btn js-btn--accent">{{ crm('parking-services.cta_btn', 'Find Parking') }}</a>
            </div>
        </div>
    </section>

</main>

@include('layouts.footer')
