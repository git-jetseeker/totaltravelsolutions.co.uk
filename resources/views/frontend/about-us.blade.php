@php $meta = function_exists('crm_page_meta') ? crm_page_meta('about-us') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: 'About Us - Total Travel Solutions')
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: 'Total Travel Solutions, about us, best parking deal')
@section('meta_description', ($meta->meta_description ?? null) ?: 'About Us | Total Travel Solutions provides hassle-free car parking booking options 24 hours a day, 7 days a week.')
@include('layouts.header')
@include('layouts.nav')

<section class="pz-page-hero pz-page-hero--tall">
    <div class="container">
        <h1 class="pz-page-hero__title">{{ crm('about-us.hero_title', 'About Total Travel Solutions') }}</h1>
        <p class="pz-page-hero__subtitle">{{ crm('about-us.hero_lead', 'Trusted UK airport parking — compare, book, and travel with confidence.') }}</p>
    </div>
</section>

<div class="pz-about-page-body">
    <section class="pz-about-mission">
        <div class="container">
            <div class="row align-items-center pz-about-split">
                <div class="col-lg-6">
                    <div class="pz-about-text">
                        <p class="pz-about-eyebrow">{{ crm('about-us.hero_eyebrow', 'Who we are') }}</p>
                        <h2 class="pz-about-heading">{{ crm('about-us.mission_title', 'Our Mission') }}</h2>
                        <p class="pz-about-copy">{{ crm('about-us.mission_text', 'Our mission at Total Travel Solutions is clear: we want to make airport parking reliable, more transparent, and accessible to all sorts of travellers. We work with a range of reputable Park Mark accredited airport parking service providers at over 8+ UK airports, providing customers an overall choice of services including UK Meet & Greet, On-Site and Park & Ride. We are committed to offer you the most competitive price in the market and achieve that by monitoring the current market prices and ensuring quality service, we can guarantee our customers exceptional value for money and a reliable service with no compromises.') }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pz-about-media pz-about-media--right">
                        <img src="{{ ttss_dashboard_asset_url('about-mission.jpg', asset('assets/images/about-mission.jpg')) }}" alt="{{ crm('about-us.mission_title', 'Our Mission') }} - Total Travel Solutions" class="img-fluid">
                    </div>
                </div>
            </div>

            <div class="row align-items-center pz-about-split pz-about-split--reverse">
                <div class="col-lg-6">
                    <div class="pz-about-media pz-about-media--left">
                        <img src="{{ ttss_dashboard_asset_url('about-promise.jpg', asset('assets/images/about-promise.jpg')) }}" alt="{{ crm('about-us.promise_title', 'Our Promise') }} - Total Travel Solutions" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pz-about-text">
                        <p class="pz-about-eyebrow">Peace of mind</p>
                        <h2 class="pz-about-heading">{{ crm('about-us.promise_title', 'Our Promise To You') }}</h2>
                        <p class="pz-about-copy">{{ crm('about-us.promise_text', 'We built Total Travel Solutions to remove the guesswork from airport parking. Our mission is simple: we deliver great value, without compromising on safety or service. Every provider we list is someone we\'d trust with our own key. So when you book through Total Travel Solutions, you\'re choosing peace of mind, every time you fly.') }}</p>
                        <a href="{{ url('/') }}" class="pz-about-cta">Search Parking <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pz-about-story">
        <div class="container">
            <div class="pz-about-story__inner">
                <h2 class="pz-about-heading pz-about-heading--center">{{ crm('about-us.company_title', 'Our Innovative Company') }}</h2>
                <p class="pz-about-copy pz-about-copy--center">{{ crm('about-us.company_text', 'We started in the early 2000s. After over 21 years, we have become a trusted parking provider in the UK. We adapt to changes in the travel industry. We offer the latest technology updates and improve our tools, all while focusing on customer satisfaction.') }}</p>
            </div>
        </div>
    </section>

    <section class="pz-about-features">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <article class="pz-about-feature-card">
                        <h3 class="pz-about-feature-card__title">{{ crm('about-us.team_title', 'Our Team is Dedicated to Providing') }}</h3>
                        <ul class="pz-about-list">
                            <li>{{ crm('about-us.team_1', 'A smooth user experience.') }}</li>
                            <li>{{ crm('about-us.team_2', 'Secure and trusted payment systems.') }}</li>
                            <li>{{ crm('about-us.team_3', 'Personalized booking support before, during, and after.') }}</li>
                        </ul>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="pz-about-feature-card">
                        <h3 class="pz-about-feature-card__title">{{ crm('about-us.specialise_title', 'What we specialise in') }}</h3>
                        <ul class="pz-about-list">
                            <li>{{ crm('about-us.specialise_1', 'Competitive pricing by checking verified provider prices.') }}</li>
                            <li>{{ crm('about-us.specialise_2', 'Ongoing high-level service from our online partners.') }}</li>
                            <li>{{ crm('about-us.specialise_3', 'Full customer engagement in the booking process for flexibility and clarity.') }}</li>
                            <li>{{ crm('about-us.specialise_4', 'Continuous improvement through feedback and innovation.') }}</li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="pz-about-info">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <article class="pz-about-info-card">
                        <h3 class="pz-about-info-card__title">{{ crm('about-us.pricing_title', 'Pricing updates & VAT') }}</h3>
                        <p class="pz-about-copy">{{ crm('about-us.pricing_text', 'Total Travel Solutions will make sure you get the lowest most affordable price, however the prices may vary if the VAT increases on parking services.') }}</p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="pz-about-info-card">
                        <h3 class="pz-about-info-card__title">{{ crm('about-us.trademarks_title', 'Total Travel Solutions Trademarks') }}</h3>
                        <p class="pz-about-copy">{{ crm('about-us.trademarks_text', 'This website is managed by Total Travel Solutions, registered in England and Registration No 16770283, Address: Suite 8f, Kelvin House, Kelvin Way, Crawley, United Kingdom, RH10 9WE') }}</p>
                    </article>
                </div>
            </div>
        </div>
    </section>
</div>

@include('layouts.footer')
