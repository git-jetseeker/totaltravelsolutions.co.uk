@section("title",$page->meta_title)
@section("meta_keyword",$page->meta_keyword )
@section("meta_description",$page->meta_description)
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-parking-services.css?v=20250918') }}">

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all();
    foreach ($settingsAll as $setting) {
        if ($setting->agent_id == '9') {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }
@endphp

@include('partials.page-hero', [
    'title' => 'Parking Services',
    'subtitle' => 'Meet & Greet, Park & Ride, and On-Airport parking options',
    'lead' => 'Compare trusted airport parking across the UK, pre-book online for guaranteed spaces, competitive rates, and a stress-free start to every journey.',
    'heroClass' => 'js-page-hero--enhanced',
])

<main class="js-parking-services">

    {{-- Intro + sidebar search --}}
    <section class="js-ps-intro">
        <div class="js-container">
            <div class="js-ps-intro__layout">
                <div class="js-ps-intro__main">
                    <span class="js-ps-intro__eyebrow">Airport parking</span>
                    <h1 class="js-ps-intro__title">Airport Parking</h1>
                    <article class="js-ps-intro__card">
                        <header class="js-ps-intro__card-head">
                            {!! $site_settings_main['services_page_parking_heading'] !!}
                        </header>
                        <div class="js-ps-intro__card-body">
                            {!! $site_settings_main['services_page_parking_descp'] !!}
                        </div>
                    </article>
                </div>
                <aside class="js-ps-sidebar">
                    @include('frontend.right_searchbar2')
                </aside>
            </div>
        </div>
    </section>

    {{-- Parking types --}}
    <section class="js-ps-types" id="parking-types">
        <div class="js-container">
            <header class="js-ps-section-head">
                <span class="js-ps-section-head__badge">Service options</span>
                <div class="js-ps-section-head__title">{!! $site_settings_main['services_page_parking_sec1_heading'] !!}</div>
                <p>{!! $site_settings_main['services_page_parking_sec1_descp'] !!}</p>
            </header>

            <div class="js-ps-types__grid">
                <article class="js-ps-type-card">
                    <div class="js-ps-type-card__codebar">
                        <span class="js-ps-type-card__code">MG</span>
                        <span class="js-ps-type-card__label">Meet &amp; Greet</span>
                    </div>
                    <div class="js-ps-type-card__icon">
                        <img src="{{ asset('category-tile-meet-greet.svg') }}" alt="Meet & Greet" loading="lazy" width="88" height="88">
                    </div>
                    <h3 class="js-ps-type-card__title">Meet &amp; Greet</h3>
                    <div class="js-ps-type-card__body">
                        {!! $site_settings_main['services_page_parking_sec1_meetandgreet'] !!}
                    </div>
                </article>

                <article class="js-ps-type-card">
                    <div class="js-ps-type-card__codebar">
                        <span class="js-ps-type-card__code">PR</span>
                        <span class="js-ps-type-card__label">Park &amp; Ride</span>
                    </div>
                    <div class="js-ps-type-card__icon">
                        <img src="{{ asset('category-tile-park-ride.svg') }}" alt="Park & Ride" loading="lazy" width="88" height="88">
                    </div>
                    <h3 class="js-ps-type-card__title">Park &amp; Ride</h3>
                    <div class="js-ps-type-card__body">
                        {!! $site_settings_main['services_page_parking_sec1_parkandride'] !!}
                    </div>
                </article>

                <article class="js-ps-type-card">
                    <div class="js-ps-type-card__codebar">
                        <span class="js-ps-type-card__code">OA</span>
                        <span class="js-ps-type-card__label">On Airport</span>
                    </div>
                    <div class="js-ps-type-card__icon">
                        <img src="{{ asset('category-tile-park-stroll.svg') }}" alt="On Airport" loading="lazy" width="88" height="88">
                    </div>
                    <h3 class="js-ps-type-card__title">On Airport</h3>
                    <div class="js-ps-type-card__body">
                        {!! $site_settings_main['services_page_parking_sec1_onairport'] !!}
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- How it works steps --}}
    <section class="js-ps-steps" id="how-it-works">
        <div class="js-container">
            <header class="js-ps-section-head">
                <span class="js-ps-section-head__badge">Simple process</span>
                <div class="js-ps-section-head__title">{!! $site_settings_main['services_page_parking_sec2_heading'] !!}</div>
                <p>{!! $site_settings_main['services_page_parking_sec2_descp'] !!}</p>
            </header>

            <div class="js-ps-steps__grid">
                <article class="js-ps-step-card">
                    <div class="js-ps-step-card__image">
                        <img src="{{ asset('assets/images/1.png') }}" alt="" loading="lazy" width="140" height="140">
                    </div>
                    <div class="js-ps-step-card__body">
                        {!! $site_settings_main['services_page_parking_sec2_step1'] !!}
                    </div>
                </article>

                <article class="js-ps-step-card">
                    <div class="js-ps-step-card__image">
                        <img src="{{ asset('assets/images/2.png') }}" alt="" loading="lazy" width="140" height="140">
                    </div>
                    <div class="js-ps-step-card__body">
                        {!! $site_settings_main['services_page_parking_sec2_step2'] !!}
                    </div>
                </article>

                <article class="js-ps-step-card">
                    <div class="js-ps-step-card__image">
                        <img src="{{ asset('assets/images/3.png') }}" alt="" loading="lazy" width="140" height="140">
                    </div>
                    <div class="js-ps-step-card__body">
                        {!! $site_settings_main['services_page_parking_sec2_step3'] !!}
                    </div>
                </article>
            </div>
        </div>
    </section>

</main>

@include('layouts.footer')
