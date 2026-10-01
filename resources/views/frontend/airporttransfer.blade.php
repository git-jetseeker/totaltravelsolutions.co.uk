@php $meta = function_exists('crm_page_meta') ? crm_page_meta('airport-transfer') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: (optional($page ?? null)->meta_title ?: 'Airport Transfers | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: (optional($page ?? null)->meta_keyword ?: 'airport transfer, airport taxi'))
@section('meta_description', ($meta->meta_description ?? null) ?: (optional($page ?? null)->meta_description ?: 'Book reliable airport transfers with Total Travel Solutions.'))

@include('layouts.header')
@include('layouts.nav')
@include('layouts.search_form')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-product-landing.css?v=20261001product') }}">

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all();
    foreach ($settingsAll as $setting) {
        if (setting_agent_matches($setting)) {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }
    $site_settings_main = normalize_site_settings($site_settings_main);

    $heading = $site_settings_main['services_page_transfer_heading'] ?? 'Airport Transfers';
    $descp = $site_settings_main['services_page_transfer_descp'] ?? 'Book reliable transfers to and from major UK airports with Total Travel Solutions.';
@endphp

<main class="js-product-page">
    <div class="js-container">
        <section class="js-product-intro" aria-labelledby="js-transfer-intro-title">
            <span class="js-product-intro__badge">Airport transfers</span>
            <h2 id="js-transfer-intro-title">{!! strip_tags((string) $heading, '<span><strong><em><b><i>') !!}</h2>
            <div>{!! $descp !!}</div>
        </section>
    </div>

    <section class="js-product-section">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>Why Choose Our Transfers?</h2>
                <p>Competitive prices, dependable service, and safe journeys to help you travel with confidence.</p>
            </header>

            <div class="js-product-features">
                <article class="js-product-feature">
                    <div class="js-product-feature__body">
                        <h3>Best Prices</h3>
                        <p>{!! $site_settings_main['services_page_transfer_sec1_bestprice'] ?? 'Compare transfer options and book great-value airport transfers in advance.' !!}</p>
                    </div>
                </article>
                <article class="js-product-feature">
                    <div class="js-product-feature__body">
                        <h3>Best Service</h3>
                        <p>{!! $site_settings_main['services_page_transfer_sec1_bestsevices'] ?? 'Professional drivers and a smooth booking experience from search to arrival.' !!}</p>
                    </div>
                </article>
                <article class="js-product-feature">
                    <div class="js-product-feature__body">
                        <h3>Safe Transfers</h3>
                        <p>{!! $site_settings_main['services_page_transfer_sec1_safetransfer'] ?? 'Travel with trusted transfer partners focused on safety and reliability.' !!}</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="js-product-section js-product-section--alt">
        <div class="js-container">
            <header class="js-product-section__head">
                <h2>Advantages of Airport Transfer</h2>
                <p>Convenience, value, and peace of mind when travelling to or from the airport.</p>
            </header>

            <div class="js-product-tips js-product-tips--4">
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-map-marker"></i></span>
                    <h3>{!! strip_tags((string) ($site_settings_main['services_page_transfer_sec2_grid1_heading'] ?? 'Convenient'), '<span><strong><em>') !!}</h3>
                    <p>{!! $site_settings_main['services_page_transfer_sec2_grid1_descp'] ?? 'Door-to-door transfers that take the stress out of getting to the airport.' !!}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-tag"></i></span>
                    <h3>{!! strip_tags((string) ($site_settings_main['services_page_transfer_sec2_grid2_heading'] ?? 'Great value'), '<span><strong><em>') !!}</h3>
                    <p>{!! $site_settings_main['services_page_transfer_sec2_grid2_descp'] ?? 'Pre-book to lock in competitive transfer rates.' !!}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-shield"></i></span>
                    <h3>{!! strip_tags((string) ($site_settings_main['services_page_transfer_sec2_grid3_heading'] ?? 'Safe travel'), '<span><strong><em>') !!}</h3>
                    <p>{!! $site_settings_main['services_page_transfer_sec2_grid3_descp'] ?? 'Reliable partners and clear booking confirmation before you travel.' !!}</p>
                </article>
                <article class="js-product-tip">
                    <span class="js-product-tip__icon" aria-hidden="true"><i class="fa fa-list"></i></span>
                    <h3>{!! strip_tags((string) ($site_settings_main['services_page_transfer_sec2_grid4_heading'] ?? 'More choice'), '<span><strong><em>') !!}</h3>
                    <p>{!! $site_settings_main['services_page_transfer_sec2_grid4_descp'] ?? 'Choose the transfer option that best fits your schedule and group size.' !!}</p>
                </article>
            </div>
        </div>
    </section>

    <div class="js-container">
        <section class="js-product-cta">
            <h2>Ready to book your transfer?</h2>
            <p>Use the search form above to compare options and reserve your airport transfer.</p>
            <a href="#home_search_form" class="js-btn js-btn--accent">Search transfers</a>
        </section>
    </div>
</main>

@include('layouts.footer')
