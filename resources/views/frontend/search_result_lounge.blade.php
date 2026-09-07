@include('layouts.header')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-product-results.css?v=20250902c') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-search-results.css?v=20260907logo2') }}">

@include('partials.results-page-logo-bar')

<div class="js-results-page"
    data-product-type="lounge"
    data-search-url="{{ route('searchresult_lounge.ajax') }}"
    data-csrf="{{ csrf_token() }}"
    data-airport-id="{{ request()->airport_id }}"
    data-checkin-date="{{ request()->input('checkIn_date', request()->input('checkin_date')) }}"
    data-checkin-time="{{ request()->input('checkIn_time', request()->input('checkin_time', '09:00')) }}"
    data-flight-time="{{ request()->input('flight_time', '13:00') }}"
    data-adults="{{ request()->input('aladults', request()->input('adults', 1)) }}"
    data-children="{{ request()->input('alchildren', request()->input('children', 0)) }}"
    data-infants="{{ request()->input('alinfants', request()->input('infants', 0)) }}"
    data-promo="{{ request()->input('promo') }}"
>

<style type="text/css">
    .js-results-page .select2-container .select2-selection__arrow { display: block !important; }
    .js-results-page .select2-container .select2-results__option { color: #1A1A1A !important; }
    .js-results-page .select2-container .select2-selection__rendered { color: #1A1A1A !important; }
</style>

@include('partials.results-lounge-shell', ['airports' => $airports])

</div>

@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('theme/js/select2.min.js') }}"></script>
<script src="{{ asset('theme/js/jetseeker-product-results.js?v=20250902d') }}"></script>
