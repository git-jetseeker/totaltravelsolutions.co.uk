<!DOCTYPE html>
<html lang="en-US" class="no-js scheme_default">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="Cache-Control: public, max-age=31536000" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="google-site-verification" content="0Hn1VMmAaGeUFSzG9jOKX8OkJim3YlwSviUgKGQgFu0" />
    <meta name="msvalidate.01" content="058CE2BBE3EF9D932E6FA366CAC4120F" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-32x32q.png') }}?v=20260915b" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-16x16q.png') }}?v=20260915b" sizes="16x16">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20260915b">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon-32x32q.png') }}?v=20260915b">
    @if (Route::currentRouteName() === 'main')
        <link rel="preload" as="image" type="image/webp" href="{{ url('/cached-media/hero-airport-parking.webp') }}" fetchpriority="high">
    @endif

    @php
        // Cached per-agent settings map (was settings::all() on every request).
        $site_settings_main = function_exists('site_settings')
            ? site_settings()
            : [];
    @endphp
    @hasSection('title')
        <title>@yield('title')</title>
    @else
        <title>{{ $site_settings_main['site_title'] ?? config('app.name', 'Total Travel Solutions') }}</title>
    @endif
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @else
        <meta name="description" content="{{ $site_settings_main['meta_description'] ?? 'Airport parking, lounges, hotels and transfers with Total Travel Solutions.' }}">
    @endif
    @hasSection('meta_keyword')
        <meta name="keywords" content="@yield('meta_keyword')">
    @else
        <meta name="keywords" content="{{ $site_settings_main['meta_keyword'] ?? 'airport parking, airport hotels, lounges, transfers' }}">
    @endif

    {{-- jQuery loads in footer; keep head free of render-blocking JS --}}
    {{-- parkingzone all.css dropped: ~17 KiB gzip unused BS3 rules (icons use FA 4.7) --}}

    <link rel="dns-prefetch" href="https://ajax.googleapis.com">
    <link rel="preconnect" href="{{ url('/') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('theme/plugins/font-awesome-4.7.0/fonts/fontawesome-webfont.woff2?v=4.7.0') }}" crossorigin>
    <link rel="canonical" href="{{ str_replace('', '/', Request::fullUrl()) }}" />

    <meta name="twitter:title" content="{!! $site_settings_main['site_twitter_title'] ?? config('app.name', 'Total Travel Solutions') !!}">
    <meta property="og:title" content="{!! $site_settings_main['site_og_title'] ?? config('app.name', 'Total Travel Solutions') !!}">
    <meta property="og:type" content="{!! $site_settings_main['site_og_type'] ?? 'website' !!}">
    <meta property="og:image" content="{!! $site_settings_main['site_og_image'] ?? '' !!}">
    <meta property="og:url" content="{!! $site_settings_main['site_og_url'] ?? url('/') !!}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="{!! $site_settings_main['site_author'] ?? config('app.name', 'Total Travel Solutions') !!}">

    {!! $site_settings_main['site_schema'] ?? '' !!}

    {{-- Critical CSS — homepage skips full Bootstrap (unused ~16 KiB); grid lives in jetseeker CSS --}}
    @if (Route::currentRouteName() !== 'main')
        <link rel="stylesheet" href="{{ asset('theme/styles/bootstrap4/bootstrap.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('theme/styles/jetseeker-design-system.css?v=20261014perf') }}">
    <link rel="stylesheet" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261005datetimerow') }}">
    @if (Route::currentRouteName() === 'main')
        <link rel="stylesheet" href="{{ asset('theme/styles/jetseeker-home.css?v=20261014perf') }}">
    @endif

    {{-- Non-critical CSS: media=print + onload is non-render-blocking --}}
    @php
        $isHome = Route::currentRouteName() === 'main';
        $deferredStyles = [];
        if ($isHome) {
            // Homepage: keep only styles needed below the fold / for booking widget.
            $deferredStyles = [
                asset('theme/styles/bootstrap4/bootstrap-modal.min.css?v=20261012'),
                asset('theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css'),
                asset('theme/styles/main_styles.css'),
                asset('theme/styles/index-main.css?v=4122025325'),
                asset('theme/styles/custom.css?v=3122025'),
                asset('theme/styles/responsive.css'),
                asset('theme/styles/jquery-ui.css'),
                asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.css'),
                asset('theme/plugins/OwlCarousel2-2.2.1/owl.theme.default.css'),
                asset('theme/styles/select2.min.css'),
            ];
        } else {
            $deferredStyles = [
                asset('theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css'),
                asset('theme/styles/main_styles.css'),
                asset('theme/styles/index-main.css?v=4122025325'),
                asset('theme/styles/custom.css?v=3122025'),
                asset('theme/styles/responsive.css'),
                asset('theme/styles/jquery-ui.css'),
                asset('theme/styles/flatpickr.min.css'),
                asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.css'),
                asset('theme/plugins/OwlCarousel2-2.2.1/owl.theme.default.css'),
                asset('theme/plugins/OwlCarousel2-2.2.1/animate.css'),
                asset('assets/front/css/datepicker.css'),
                asset('theme/styles/select2.min.css'),
                asset('theme/styles/jetseeker-product-results.css?v=20260907noblue2'),
            ];
        }
    @endphp
    @foreach ($deferredStyles as $href)
        <link rel="stylesheet" href="{{ $href }}" media="print" onload="this.media='all'">
    @endforeach
    <noscript>
        @foreach ($deferredStyles as $href)
            <link rel="stylesheet" href="{{ $href }}">
        @endforeach
    </noscript>

    @if (isset($site_settings_main['site_header_analytics']))
        {!! $site_settings_main['site_header_analytics'] !!}
    @endif
</head>





<body class="js-body{{ Route::currentRouteName() === 'main' ? ' home' : '' }}">
    @if (isset($site_settings_main['site_body_analytics']))
        {!! $site_settings_main['site_body_analytics'] !!}
    @endif



    <div class="super_container">
    <span class="display:none" id="reset_btn"></span>
    <span class="display:none" id="pain"></span>
    <span class="display:none" id="close"></span>
    <span class="display:none" id="close2"></span>
        <!-- Header -->

        <!-- Main Navigation -->
