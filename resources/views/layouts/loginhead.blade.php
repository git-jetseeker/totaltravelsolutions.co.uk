<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>JET SEEKER</title>

    <meta name="description" content="User login page" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href=" {{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset("assets/css/fonts.googleapis.com.css") }}" />

    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset("assets/css/ace.min.css") }}" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset("assets/css/ace-part2.min.css") }} " />
    <![endif]-->
    <link rel="stylesheet" href="{{ asset("assets/css/ace-rtl.min.css") }}" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="{{ asset("assets/css/ace-ie.min.css") }}" />
    <![endif]-->

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src=" {{ asset('assets/js/html5shiv.revolution.extension.navigation.min.js') }} "></script>
    <script src=" {{ asset('assets/js/respond.revolution.extension.navigation.min.js') }} "></script>
    <![endif]-->
</head>
<main class="py-4" style="    margin-top: 171px;">
    @yield('content')
</main>