<?php ?>

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">

<div style="display:none;" id="notification"></div>

<div class="search1 js-hero">
    <div class="container fill_height">
        <div class="row fill_height fill_height1">
            <div class="col fill_height">
                <h1 class="main-heading js-hero__title displayNoneMobile">{{ crm('home.hero_title', 'Book Easy, Park Safe, Travel Happy!') }}</h1>
                <h1 class="main-heading js-hero__title displayBlockMobile">{{ crm('home.hero_title', 'Book Easy, Park Safe, Travel Happy!') }}</h1>
                <p class="main-paragraph js-hero__subtitle">{{ crm('home.hero_subtitle', 'Amazing Airport car park deals across all major UK airports') }}</p>

                @include('partials.booking-widget')
            </div>
        </div>
    </div>
</div>
