<!DOCTYPE html>
<html lang="en-US" class="no-js scheme_default">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="Cache-Control: public, max-age=31536000" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="google-site-verification" content="kz0jW8P0ZXYec37awl79cMX367AGpQ_haFp6GB0l7Fc" />
    <meta name="msvalidate.01" content="058CE2BBE3EF9D932E6FA366CAC4120F" />
    <!-- <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'> -->
    <!--<meta name="facebook-domain-verification" content="ijicroog46un4zpog2phqh297tt92n" />-->
    <link rel="icon" type="image/png" defer href="{{ asset('assets/images/favicon-32x32q.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" defer href="{{ asset('assets/images/favicon-16x16q.png') }}" sizes="16x16" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="{{asset('theme/styles/font-awesome.min.css')}}"> -->
    <!-- <link rel="stylesheet" href="{{asset('theme/styles/all.css')}}"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <link rel="stylesheet" href="{{asset('theme/styles/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('theme/styles/flatpickr.min.css')}}">

    @php
        $site_settings_main = [];
        $settingsAll = App\Models\settings::all()->where('agent_id', '9');
        foreach ($settingsAll as $setting) {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
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


    <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
    @if (Route::currentRouteName() == 'main')
        <!--<link rel="stylesheet"  href='{{ asset('assets/css/menu.css') }}'  media="all" type='text/css'/>-->
        <!--<link rel="stylesheet"  href='{{ asset('assets/front/parkingzone/css/all_home.css') }}'  media="all" type='text/css'/>-->
        
    @else
        <link rel="stylesheet" href="{{ asset('assets/front/parkingzone/css/all.css') }}" media="all"
            type='text/css' />
    @endif

    <link rel='dns-prefetch' href='https://ajax.googleapis.com' />

    <link rel='dns-prefetch' href='https://fonts.googleapis.com' />
    <link rel="canonical" href="{{ str_replace('', '/', Request::fullUrl()) }}" />
    <!-- <link rel="canonical" href="{{ str_replace('//', '/', Request::fullUrl()) }}" /> -->

    <meta name="twitter:title" content="{!! $site_settings_main['site_twitter_title'] ?? config('app.name', 'Total Travel Solutions') !!}">
    <meta property="og:title" content="{!! $site_settings_main['site_og_title'] ?? config('app.name', 'Total Travel Solutions') !!}">
    <meta property="og:type" content="{!! $site_settings_main['site_og_type'] ?? 'website' !!}">
    <meta property="og:image" content="{!! $site_settings_main['site_og_image'] ?? '' !!}">
    <meta property="og:url" content="{!! $site_settings_main['site_og_url'] ?? url('/') !!}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="{!! $site_settings_main['site_author'] ?? config('app.name', 'Total Travel Solutions') !!}">

    {!! $site_settings_main['site_schema'] ?? '' !!}

    @if (\Request::is('main'))
    @else
    @endif
    {!! $site_settings_main['site_schema'] ?? '' !!}









    <noscript id="deferred-styles">

        {{-- <!--<link rel="stylesheet"  type="text/css" href="{{ url('theme/styles/bootstrap4/bootstrap.min.css') }}">--> --}}

        {{-- <!--<link href="{{ url('theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}"  rel="stylesheet" type="text/css">--> --}}
    </noscript>

    <script>
        var loadDeferredStyles = function() {
            var addStylesNode = document.getElementById("deferred-styles");
            var replacement = document.createElement("div");
            replacement.innerHTML = addStylesNode.textContent;
            document.body.appendChild(replacement)
            addStylesNode.parentElement.removeChild(addStylesNode);
        };
        var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
            window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        if (raf) raf(function() {
            window.setTimeout(loadDeferredStyles, 0);
        });
        else window.addEventListener('load', loadDeferredStyles);
    </script>


    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/bootstrap4/bootstrap.min.css') }}">

    <!--<link href="{{ asset('theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}"  rel="stylesheet" type="text/css">-->
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/OwlCarousel2-2.2.1/animate.css') }}">
    

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/datepicker.css') }}" media="all">

    <link rel="stylesheet" href="{{ asset('theme/styles/select2.min.css') }}">
    
    <!--<link rel="stylesheet"   type="text/css" href="{{ asset('theme/styles/responsive.css') }}">-->
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/main_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/index-main.css?v=4122025325') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/custom.css?v=3122025') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-design-system.css?v=20260907logo2') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-product-results.css?v=20260907noblue2') }}">

    @if (isset($site_settings_main['site_header_analytics']))
        {!! $site_settings_main['site_header_analytics'] !!}
    @endif
</head>






<body class="js-body">
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
