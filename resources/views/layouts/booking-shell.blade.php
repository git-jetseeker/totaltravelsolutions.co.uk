<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    @include('partials.site-favicon')
    <title>@yield('title', 'Confirm Booking') | Total Travel Solutions</title>
    <meta name="description" content="Complete your airport parking booking with Total Travel Solutions.">

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/bootstrap4/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/page.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-design-system.css?v=20260907logo') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking.css?v=20260907logo') }}">
    @yield('stylesheets')

    @if (isset($settings['site_header_analytics']))
        {!! $settings['site_header_analytics'] !!}
    @endif
</head>
<body class="js-body js-booking-flow apb-booking-checkout">
    @if (isset($settings['site_body_analytics']))
        {!! $settings['site_body_analytics'] !!}
    @endif

    @include('partials.booking-logo-bar')

    <div class="super_container">
        @yield('content')
        @include('layouts.footer')
    </div>
