@php $meta = function_exists('crm_page_meta') ? crm_page_meta('about-us') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: 'About Us - Total Travel Solutions')
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: 'Total Travel Solutions, about us, airport parking')
@section('meta_description', ($meta->meta_description ?? null) ?: 'About Us | Total Travel Solutions provides hassle-free car parking booking options 24 hours a day, 7 days a week.')
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-about.css?v=20260926about') }}">

@php
    $allowTags = '<span><strong><em><b><i><br><a><p>';
    $crmSafe = function (string $key, string $default = '') use ($allowTags) {
        return strip_tags((string) crm_html($key, $default), $allowTags);
    };
@endphp

<section class="js-page-hero js-page-hero--enhanced">
    <div class="js-container">
        <span class="js-page-hero__eyebrow">{{ crm('about-us.hero_eyebrow', 'About us') }}</span>
        <h1 class="js-page-hero__title">{{ crm('about-us.hero_title', 'About Total Travel Solutions') }}</h1>
        <p class="js-page-hero__subtitle">{{ crm('about-us.hero_subtitle', 'Your trusted partner for UK airport parking') }}</p>
        <p class="js-page-hero__lead">{{ crm('about-us.hero_lead', 'For over two decades we have helped travellers find reliable, Park Mark accredited parking at major UK airports — transparent pricing, quality service, and peace of mind every time you fly.') }}</p>
    </div>
</section>

<main class="js-about-page">

    {{-- Stats --}}
    <section class="js-about-stats" aria-label="Company highlights">
        <div class="js-container">
            <div class="js-about-stats__grid">
                <article class="js-about-stat">
                    <span class="js-about-stat__value">{{ crm('about-us.stat_1_value', '40+') }}</span>
                    <span class="js-about-stat__label">{{ crm('about-us.stat_1_label', 'UK Airports') }}</span>
                </article>
                <article class="js-about-stat">
                    <span class="js-about-stat__value">{{ crm('about-us.stat_2_value', '100+') }}</span>
                    <span class="js-about-stat__label">{{ crm('about-us.stat_2_label', 'Car Parks') }}</span>
                </article>
                <article class="js-about-stat">
                    <span class="js-about-stat__value">{{ crm('about-us.stat_3_value', '100k+') }}</span>
                    <span class="js-about-stat__label">{{ crm('about-us.stat_3_label', 'Happy Clients') }}</span>
                </article>
            </div>
        </div>
    </section>

    {{-- Mission --}}
    <section class="js-about-story">
        <div class="js-container">
            <div class="js-about-story__grid">
                <div class="js-about-story__copy">
                    <span class="js-about-story__eyebrow">{{ crm('about-us.intro_eyebrow', 'Who we are') }}</span>
                    <h2 class="js-about-story__title">{!! $crmSafe('about-us.mission_title', 'Our <span>Mission</span>') !!}</h2>
                    <p class="js-about-story__text">{!! $crmSafe('about-us.mission_text', 'Our mission at Total Travel Solutions is clear: we want to make airport parking reliable, more transparent, and accessible to all sorts of travellers. We work with a range of reputable Park Mark accredited airport parking service providers at over 8+ UK airports, providing customers an overall choice of services including UK <strong>Meet &amp; Greet, On-Site and Park &amp; Ride</strong>. We are committed to offer you the most competitive price in the market and achieve that by monitoring the current market prices and ensuring quality service, we can guarantee our customers exceptional value for money and a reliable service with no compromises.') !!}</p>
                </div>
                <div class="js-about-story__media js-about-story__media--rounded-tr">
                    <img
                        src="{{ ttss_dashboard_asset_url('about-mission.jpg', asset('assets/images/about-mission.jpg')) }}"
                        alt="{{ strip_tags(crm('about-us.mission_title', 'Our Mission')) }} - Total Travel Solutions"
                        width="640"
                        height="480"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- Promise --}}
    <section class="js-about-story js-about-story--alt">
        <div class="js-container">
            <div class="js-about-story__grid js-about-story__grid--reverse">
                <div class="js-about-story__copy">
                    <span class="js-about-story__eyebrow">{{ crm('about-us.promise_eyebrow', 'Peace of mind') }}</span>
                    <h2 class="js-about-story__title">{!! $crmSafe('about-us.promise_title', 'Our Promise <span>To You</span>') !!}</h2>
                    <p class="js-about-story__text">{!! $crmSafe('about-us.promise_text', 'We built Total Travel Solutions to remove the guesswork from airport parking. Our mission is simple: we deliver great value, without compromising on safety or service. Every provider we list is someone we\'d trust with our own key. So when you book through Total Travel Solutions, you\'re choosing peace of mind, every time you fly.') !!}</p>
                    <a href="{{ url('/') }}" class="js-btn js-btn--primary js-about-cta">
                        {{ crm('about-us.intro_cta', 'Search Parking') }}
                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="js-about-story__media js-about-story__media--rounded-tl">
                    <img
                        src="{{ ttss_dashboard_asset_url('about-promise.jpg', asset('assets/images/about-promise.jpg')) }}"
                        alt="{{ strip_tags(crm('about-us.promise_title', 'Our Promise')) }} - Total Travel Solutions"
                        width="640"
                        height="480"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- Why choose us --}}
    <section class="js-about-why">
        <div class="js-container">
            <header class="js-about-section-head">
                <span class="js-about-section-head__badge">{{ crm('about-us.why_eyebrow', 'Why book with us') }}</span>
                <h2 class="js-about-section-head__title">{{ crm('about-us.why_title', 'Why Customers Choose Us') }}</h2>
            </header>
            <div class="js-about-why__grid">
                <article class="js-about-why-card">
                    <span class="js-about-why-card__icon" aria-hidden="true"><i class="fa fa-tags"></i></span>
                    <h3 class="js-about-why-card__title">{{ crm('about-us.why_1_title', 'Best prices') }}</h3>
                    <p class="js-about-why-card__text">{{ crm('about-us.why_1_text', 'We compare parking lots near airports and show you a clear comparison list so you can book with confidence.') }}</p>
                </article>
                <article class="js-about-why-card">
                    <span class="js-about-why-card__icon" aria-hidden="true"><i class="fa fa-shield"></i></span>
                    <h3 class="js-about-why-card__title">{{ crm('about-us.why_2_title', 'Trusted partners') }}</h3>
                    <p class="js-about-why-card__text">{{ crm('about-us.why_2_text', 'The car parking providers we work with are secure, established operators you can rely on before you travel.') }}</p>
                </article>
                <article class="js-about-why-card">
                    <span class="js-about-why-card__icon" aria-hidden="true"><i class="fa fa-gbp"></i></span>
                    <h3 class="js-about-why-card__title">{{ crm('about-us.why_3_title', 'Low price promise') }}</h3>
                    <p class="js-about-why-card__text">{{ crm('about-us.why_3_text', 'We help you find competitive airport parking rates and great value across Meet & Greet, Park & Ride, and onsite options.') }}</p>
                </article>
                <article class="js-about-why-card">
                    <span class="js-about-why-card__icon" aria-hidden="true"><i class="fa fa-headphones"></i></span>
                    <h3 class="js-about-why-card__title">{{ crm('about-us.why_4_title', 'Support') }}</h3>
                    <p class="js-about-why-card__text">{{ crm('about-us.why_4_text', 'Our team is only a phone call or email away whenever you need help with a booking or a change to your trip.') }}</p>
                </article>
                <article class="js-about-why-card">
                    <span class="js-about-why-card__icon" aria-hidden="true"><i class="fa fa-car"></i></span>
                    <h3 class="js-about-why-card__title">{{ crm('about-us.why_5_title', 'Every parking type') }}</h3>
                    <p class="js-about-why-card__text">{{ crm('about-us.why_5_text', 'Book Meet & Greet, On Airport, and Park & Ride services at major UK airports through one simple comparison site.') }}</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Company story --}}
    <section class="js-about-content js-about-content--alt">
        <div class="js-container">
            <article class="js-about-card">
                <div class="js-about-card__head">
                    <span class="js-about-card__badge">{{ crm('about-us.company_badge', 'Since the 2000s') }}</span>
                    <h2 class="js-about-card__title">{!! $crmSafe('about-us.company_title', 'Our Innovative <span>Company</span>') !!}</h2>
                    <p class="js-about-card__intro">{{ crm('about-us.company_text', 'We started in the early 2000s. After over 21 years, we have become a trusted parking provider in the UK. We adapt to changes in the travel industry. We offer the latest technology updates and improve our tools, all while focusing on customer satisfaction.') }}</p>
                </div>
            </article>
        </div>
    </section>

    {{-- Team + specialise --}}
    <section class="js-about-content">
        <div class="js-container">
            <div class="js-about-features__grid">
                <article class="js-about-card js-about-card--feature">
                    <span class="js-about-card__badge">{{ crm('about-us.team_badge', 'Customer first') }}</span>
                    <h3 class="js-about-card__title js-about-card__title--left">{{ crm('about-us.team_title', 'Our Team is Dedicated to Providing') }}</h3>
                    <ul class="js-about-list">
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.team_li_1', crm('about-us.team_1', 'A smooth user experience.')) }}</span>
                        </li>
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.team_li_2', crm('about-us.team_2', 'Secure and trusted payment systems.')) }}</span>
                        </li>
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.team_li_3', crm('about-us.team_3', 'Personalized booking support before, during, and after.')) }}</span>
                        </li>
                    </ul>
                </article>
                <article class="js-about-card js-about-card--feature">
                    <span class="js-about-card__badge">{{ crm('about-us.specialise_badge', 'What we do best') }}</span>
                    <h3 class="js-about-card__title js-about-card__title--left">{{ crm('about-us.specialise_title', 'What we specialise in') }}</h3>
                    <ul class="js-about-list">
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.specialise_li_1', crm('about-us.specialise_1', 'Competitive pricing by checking verified provider prices.')) }}</span>
                        </li>
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.specialise_li_2', crm('about-us.specialise_2', 'Ongoing high-level service from our online partners.')) }}</span>
                        </li>
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.specialise_li_3', crm('about-us.specialise_3', 'Full customer engagement in the booking process for flexibility and clarity.')) }}</span>
                        </li>
                        <li>
                            <span class="js-about-list__icon" aria-hidden="true"><i class="fa fa-check"></i></span>
                            <span>{{ crm('about-us.specialise_li_4', crm('about-us.specialise_4', 'Continuous improvement through feedback and innovation.')) }}</span>
                        </li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="js-about-how">
        <div class="js-container">
            <header class="js-about-section-head">
                <span class="js-about-section-head__badge">{{ crm('about-us.how_eyebrow', 'How it works') }}</span>
                <h2 class="js-about-section-head__title">{{ crm('about-us.how_title', 'Search, Book, Travel') }}</h2>
            </header>
            <ol class="js-about-how__grid">
                <li class="js-about-how-step">
                    <span class="js-about-how-step__num">01</span>
                    <h3 class="js-about-how-step__title">{{ crm('about-us.how_1_title', 'Search') }}</h3>
                    <p class="js-about-how-step__text">{{ crm('about-us.how_1_text', 'Enter your airport and travel dates to see live parking quotes from our comparison list.') }}</p>
                </li>
                <li class="js-about-how-step">
                    <span class="js-about-how-step__num">02</span>
                    <h3 class="js-about-how-step__title">{{ crm('about-us.how_2_title', 'Book') }}</h3>
                    <p class="js-about-how-step__text">{{ crm('about-us.how_2_text', 'Choose the option that suits your trip, compare rates, and confirm your booking in minutes.') }}</p>
                </li>
                <li class="js-about-how-step">
                    <span class="js-about-how-step__num">03</span>
                    <h3 class="js-about-how-step__title">{{ crm('about-us.how_3_title', 'Travel') }}</h3>
                    <p class="js-about-how-step__text">{{ crm('about-us.how_3_text', 'Arrive at the airport knowing your parking is reserved — start your journey stress-free.') }}</p>
                </li>
            </ol>
        </div>
    </section>

    {{-- Legal / pricing info --}}
    <section class="js-about-info">
        <div class="js-container">
            <div class="js-about-info__grid">
                <article class="js-about-info__item">
                    <h3>{!! $crmSafe('about-us.pricing_title', 'Pricing updates &amp; VAT') !!}</h3>
                    <p>{{ crm('about-us.pricing_text', 'Total Travel Solutions will make sure you get the lowest most affordable price, however the prices may vary if the VAT increases on parking services.') }}</p>
                </article>
                <article class="js-about-info__item">
                    <h3>{!! $crmSafe('about-us.trademark_title', crm('about-us.trademarks_title', 'Total Travel Solutions Trademarks')) !!}</h3>
                    <p>{{ crm('about-us.trademark_text', crm('about-us.trademarks_text', 'This website is managed by Total Travel Solutions, registered in England and Registration No 16770283, Address: Suite 8f, Kelvin House, Kelvin Way, Crawley, United Kingdom, RH10 9WE')) }}</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="js-about-banner">
        <div class="js-container">
            <div class="js-about-banner__inner">
                <div class="js-about-banner__copy">
                    <h2 class="js-about-banner__title">Ready to book airport parking?</h2>
                    <p class="js-about-banner__text">Compare trusted providers across major UK airports and reserve your space in minutes.</p>
                </div>
                <a href="{{ url('/') }}" class="js-btn js-btn--accent">Find Parking</a>
            </div>
        </div>
    </section>

</main>


    @php
        $cmsAboutBody = trim((string) (($page->airport_parking ?? '') ?: ($page->content ?? '')));
    @endphp
    @if ($cmsAboutBody !== '')
    <section class="pz-page-content" style="padding: 2rem 0;">
        <div class="container">
            <div class="pz-page-wrap">
                {!! $cmsAboutBody !!}
            </div>
        </div>
    </section>
    @endif

@include('layouts.footer')
