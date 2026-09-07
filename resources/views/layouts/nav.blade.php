@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all();
    foreach ($settingsAll as $setting) {
        if ($setting->agent_id == '9') {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }

    $navAirports = [
        ['slug' => 'heathrow-airport-parking', 'name' => 'Heathrow', 'icon' => 'fa-plane'],
        ['slug' => 'gatwick-airport-parking', 'name' => 'Gatwick', 'icon' => 'fa-plane'],
        ['slug' => 'manchester-airport-parking', 'name' => 'Manchester', 'icon' => 'fa-plane'],
        ['slug' => 'stansted-airport-parking', 'name' => 'Stansted', 'icon' => 'fa-plane'],
        ['slug' => 'luton-airport-parking', 'name' => 'Luton', 'icon' => 'fa-plane'],
        ['slug' => 'birmingham-airport-parking', 'name' => 'Birmingham', 'icon' => 'fa-plane'],
        ['slug' => 'bristol-airport-parking', 'name' => 'Bristol', 'icon' => 'fa-plane'],
        ['slug' => 'liverpool-airport-parking', 'name' => 'Liverpool', 'icon' => 'fa-plane'],
        ['slug' => 'edinburgh-airport-parking', 'name' => 'Edinburgh', 'icon' => 'fa-plane'],
        ['slug' => 'glasgow-airport-parking', 'name' => 'Glasgow', 'icon' => 'fa-plane'],
        ['slug' => 'east-midlands-airport-parking', 'name' => 'East Midlands', 'icon' => 'fa-plane'],
        ['slug' => 'southampton-airport-parking', 'name' => 'Southampton', 'icon' => 'fa-plane'],
    ];

    $navAirportsCol1 = array_slice($navAirports, 0, 6);
    $navAirportsCol2 = array_slice($navAirports, 6);
@endphp

{{-- Top Bar --}}
<div class="js-topbar">
    <div class="js-topbar__inner js-container">
        <span class="js-topbar__tagline">Hassle Free Parking!</span>
        @if (!empty($site_settings_main['footer_phone_no']))
            @include('partials.helpline-widget', [
                'phone' => $site_settings_main['footer_phone_no'],
                'modifier' => 'topbar',
            ])
        @endif
    </div>
</div>

{{-- Main Header --}}
<header class="js-header" role="banner">
    <div class="js-navbar js-container">
        <div class="js-navbar__logo">
            <a href="{{ url('/') }}" aria-label="Total Travel Solutions Home">
                <img src="{{ asset('theme/images/logo-black.png') }}" alt="Total Travel Solutions logo" width="160" height="48">
            </a>
        </div>

        <nav class="js-nav__desktop" aria-label="Main navigation">
            <ul class="js-nav">
                <li class="js-nav__item">
                    <a href="{{ route('main') }}" class="js-nav__link">Home</a>
                </li>

                <li class="js-nav__item js-nav__item--dropdown">
                    <a href="#" class="js-nav__link" aria-haspopup="true" aria-expanded="false"
                        onclick="event.preventDefault();">Airport Parking</a>
                    <div class="js-megamenu" role="menu">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                            <div>
                                <div class="js-megamenu__title">Major Airports</div>
                                <div class="js-megamenu__grid">
                                    @foreach ($navAirportsCol1 as $airport)
                                        <a class="js-megamenu__link" role="menuitem"
                                            href="{{ route('page', ['slug' => $airport['slug']]) }}">
                                            <i class="fa {{ $airport['icon'] }}" aria-hidden="true"></i>
                                            {{ $airport['name'] }} Airport Parking
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <div class="js-megamenu__title">More Airports</div>
                                <div class="js-megamenu__grid">
                                    @foreach ($navAirportsCol2 as $airport)
                                        <a class="js-megamenu__link" role="menuitem"
                                            href="{{ route('page', ['slug' => $airport['slug']]) }}">
                                            <i class="fa {{ $airport['icon'] }}" aria-hidden="true"></i>
                                            {{ $airport['name'] }} Airport Parking
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="js-megamenu__footer">
                            <a href="{{ route('airports') }}" class="js-btn js-btn--outline js-btn--sm">View All Airports</a>
                        </div>
                    </div>
                </li>

                <li class="js-nav__item">
                    <a href="{{ url('parking-services') }}" class="js-nav__link">Parking Services</a>
                </li>
                <li class="js-nav__item">
                    <a href="{{ url('about-us') }}" class="js-nav__link">About Us</a>
                </li>
                <li class="js-nav__item">
                    <a href="{{ route('faqs') }}" class="js-nav__link">FAQs</a>
                </li>
                <li class="js-nav__item">
                    <a href="{{ route('support') }}" class="js-nav__link">Customer Support</a>
                </li>
                <li class="js-nav__item js-nav__cta">
                    <a href="{{ url('manage-booking') }}" class="js-btn js-btn--primary js-btn--sm">Manage Booking</a>
                </li>
            </ul>
        </nav>

        <div class="js-navbar__actions">
            @if (!empty($site_settings_main['footer_phone_no']))
                @include('partials.helpline-widget', [
                    'phone' => $site_settings_main['footer_phone_no'],
                    'modifier' => 'header',
                ])
            @endif

            <button type="button" class="js-nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="js-mobile-nav-panel">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    {{-- Mobile / tablet inline navigation (APB-style collapse below header) --}}
    <nav class="js-mobile-nav-inline" id="js-mobile-nav-panel" aria-hidden="true" aria-label="Mobile navigation">
        <ul class="js-mobile-nav__list">
            <li><a href="{{ route('main') }}" class="js-mobile-nav__link">Home</a></li>
            <li>
                <button type="button" class="js-mobile-nav__accordion-btn" aria-expanded="false"
                    aria-controls="mobile-airports-menu">
                    Airport Parking <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="js-mobile-nav__submenu" id="mobile-airports-menu">
                    @foreach ($navAirports as $airport)
                        <a href="{{ route('page', ['slug' => $airport['slug']]) }}">{{ $airport['name'] }} Airport Parking</a>
                    @endforeach
                    <a href="{{ route('airports') }}">View All Airports</a>
                </div>
            </li>
            <li><a href="{{ url('parking-services') }}" class="js-mobile-nav__link">Parking Services</a></li>
            <li><a href="{{ url('about-us') }}" class="js-mobile-nav__link">About Us</a></li>
            <li><a href="{{ route('faqs') }}" class="js-mobile-nav__link">FAQs</a></li>
            <li><a href="{{ route('support') }}" class="js-mobile-nav__link">Customer Support</a></li>
        </ul>
        <div class="js-mobile-nav__cta">
            <a href="{{ url('manage-booking') }}" class="js-btn js-btn--primary js-btn--block">Manage Booking</a>
        </div>
    </nav>
</header>

{{-- Legacy mobile menu hook for custom.js compatibility --}}
<div class="menu trans_500" style="display:none !important;" aria-hidden="true"></div>
<div class="hamburger" style="display:none !important;" aria-hidden="true"></div>
