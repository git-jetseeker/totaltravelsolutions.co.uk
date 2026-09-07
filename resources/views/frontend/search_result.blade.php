@include('layouts.header')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-search-results.css?v=20260907logo2') }}">

@include('partials.results-page-logo-bar')

<div class="js-results-page"
    data-search-url="{{ route('searchresult.ajax') }}"
    data-csrf="{{ csrf_token() }}"
    data-airport-id="{{ request()->airport_id }}"
    data-dropoffdate="{{ request()->dropoffdate }}"
    data-departure-date="{{ request()->departure_date }}"
    data-dropoftime="{{ request()->dropoftime ?? request()->dropofftime ?? '09:00' }}"
    data-pickup-time="{{ request()->pickup_time ?? request()->pickuptime ?? '09:00' }}"
    data-email="{{ request()->email }}"
    data-promo="{{ request()->promo }}"
    data-promo2="{{ request()->promo2 }}"
    data-src="{{ request()->src ?? 'ORG' }}"
>

<style type="text/css">
    /* Layout + cards: jetseeker-search-results.css */
    .js-results-page .select2-container .select2-selection__arrow { display: block !important; }
    .js-results-page .select2-container .select2-results__option { color: #1A1A1A !important; }
    .js-results-page .select2-container .select2-selection__rendered { color: #1A1A1A !important; }
</style>

@include('partials.results-parking-shell')

</div>{{-- /.js-results-page --}}

@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('theme/js/select2.min.js') }}"></script>
<script src="{{ asset('theme/js/jetseeker-results.js?v=20250901w') }}"></script>
