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
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-32x32q.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon-16x16q.png') }}" sizes="16x16" />
    @php
        $site_settings_main = [];
        $settingsAll = App\Models\settings::all()->where('agent_id', '1');
        foreach ($settingsAll as $setting) {
            if ($setting->agent_id == '1') {
                $site_settings_main[$setting->field_name] = $setting->field_value;
            }
        }
    @endphp
    <title>Total Travel Solutions</title>
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @else
        <meta name="description" content="{{ $site_settings_main['meta_description'] }}">
    @endif
    @hasSection('meta_keyword')
        <meta name="keywords" content="@yield('meta_keyword')">
    @else
        <meta name="keywords" content="{{ $site_settings_main['meta_keyword'] }}">
    @endif


    {{-- <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script> --}}
    @if (\Request::is('main'))
        <link rel="stylesheet" href='{{ asset('assets/css/menu.css') }}' media="all" type='text/css' />
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/bootstrap4/bootstrap.min.css') }}">
        <link rel="stylesheet" href='{{ asset('assets/front/parkingzone/css/all.css') }}' media="all"
            type='text/css' />
    @endif

    <link rel='dns-prefetch' href='https://ajax.googleapis.com' />

    <link rel='dns-prefetch' href='https://fonts.googleapis.com' />
    <link rel="canonical" href="{{ str_replace('', '/', Request::fullUrl()) }}" />

    <meta name="twitter:title" content="{!! $site_settings_main['site_twitter_title'] !!}">
    <meta property="og:title" content="{!! $site_settings_main['site_og_title'] !!}">
    <meta property="og:type" content="{!! $site_settings_main['site_og_type'] !!}">
    <meta property="og:image" content="{!! $site_settings_main['site_og_image'] !!}">
    <meta property="og:url" content="{!! $site_settings_main['site_og_url'] !!}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="{!! $site_settings_main['site_author'] !!}">

    {!! $site_settings_main['site_schema'] !!}

    @if (\Request::is('main'))
    @else
    @endif
    {!! $site_settings_main['site_schema'] !!}



    <noscript id="deferred-styles">
        {{--
        <link property="stylesheet" rel='stylesheet'
                  href='https://fonts.googleapis.com/css?family=Open+Sans%3A300%2C400%2C600%2C700%2C800&#038;ver=5.0.1'
                  type='text/css' media='all'/>
            <link property="stylesheet" rel='stylesheet'
                  href='https://fonts.googleapis.com/css?family=Raleway%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900&#038;ver=5.0.1'
                  type='text/css' media='all'/>
            <link property="stylesheet" rel='stylesheet'
                  href='https://fonts.googleapis.com/css?family=Droid+Serif%3A400%2C700&#038;ver=5.0.1' type='text/css'
                  media='all'/>
        --}}
        <link href="{{ asset('assets/front/parkingzone/css/font-awesome.min.css') }}" rel="stylesheet"
            integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <!--Custom Stylesheets -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/style.css') }}" media="all">

        <!--Font Awesome Stylesheet -->
        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/font-awesome.min.css') }}" media="all"> --}}

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/yellow.css') }}" media="all">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/responsive.css') }}" media="all">

        <!--Date-Picker Stylesheet-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/datepicker.css') }}" media="all">

    </noscript>


    <link property="stylesheet" rel='stylesheet' href='{{ asset('assets/page.css') }}' type='text/css'
        media='all' />
    <link href="{{ asset('theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/plugins/OwlCarousel2-2.2.1/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/main_styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/responsive.css') }}">


    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/datepicker.css') }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/index-main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-design-system.css?v=20260907logo') }}">
    <script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "e29av9oghi");
    </script>

    <!--  Clickcease.com tracking-->
    <script type='text/javascript'>
        var script = document.createElement('script');
        script.async = true;
        script.type = 'text/javascript';
        var target = 'https://www.clickcease.com/monitor/stat.js';
        script.src = target;
        var elem = document.head;
        elem.appendChild(script);
    </script>
    <noscript>
        <a href='https://www.clickcease.com' rel='nofollow'><img
                src='https://monitor.clickcease.com/stats/stats.aspx' alt='ClickCease' /></a>
    </noscript>
    <!--  Clickcease.com tracking-->

    <!-- Bing tracking-->
    <script>
        (function(w, d, t, r, u) {
            var f, n, i;
            w[u] = w[u] || [], f = function() {
                var o = {
                    ti: "343006614"
                };
                o.q = w[u], w[u] = new UET(o), w[u].push("pageLoad")
            }, n = d.createElement(t), n.src = r, n.async = 1, n.onload = n.onreadystatechange = function() {
                var s = this.readyState;
                s && s !== "loaded" && s !== "complete" || (f(), n.onload = n.onreadystatechange = null)
            }, i = d.getElementsByTagName(t)[0], i.parentNode.insertBefore(n, i)
        })(window, document, "script", "//bat.bing.com/bat.js", "uetq");
    </script>

    <!-- Google tracking-->


    @if (isset($site_settings_main['site_header_analytics']))
        {!! $site_settings_main['site_header_analytics'] !!}
    @endif

</head>
@include('frontend.header')

<body class="js-body">
    @if (isset($site_settings_main['site_body_analytics']))
        {!! $site_settings_main['site_body_analytics'] !!}
    @endif


    <div class="super_container">

        <!-- Header -->

        <!-- Main Navigation -->

        @yield('content')

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #f7a311;color: white;">

                        <center>
                            <h4 class="modal-title" style=" color: white">You Can Follow Us & Get Discount Code By E
                                Mail</h4>
                        </center>
                    </div>
                    <div class="modal-body">
                        <center>
                            <h3 id="modal-text"></h3>
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                            style="    color: #fff;background-color: #f7a311;border-color:#ffffff;"
                            data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div><!-- </.page_content_wrap> -->


        @php
            $site_settings_main = [];
            $settingsAll = App\Models\settings::all();
            foreach ($settingsAll as $setting) {
                $site_settings_main[$setting->field_name] = $setting->field_value;
            }
        @endphp


        @include('layouts.footer')
