@include('layouts.header')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-product-results.css?v=20250902a') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-search-results.css?v=20260907logo') }}">

@include('partials.results-page-logo-bar')

@php
    $req = $request ?? request();
@endphp

<div class="js-results-page"
    data-product-type="hotel"
    data-search-url="{{ route('searchresult_hotel.ajax') }}"
    data-csrf="{{ csrf_token() }}"
    data-airport-id="{{ $req->input('airport_id') }}"
    data-hotel-checkin-date="{{ $req->input('hotel_checkin_date') }}"
    data-hotel-checkout-date="{{ $req->input('hotel_checkout_date') }}"
    data-hotel-checkin-time="{{ $req->input('hotel_checkin_time', '14:00') }}"
    data-hotel-checkout-time="{{ $req->input('hotel_checkout_time', '11:00') }}"
    data-hotel-adults="{{ $req->input('hotel_adults', 1) }}"
    data-hotel-children="{{ $req->input('hotel_children', 0) }}"
    data-hotel-infants="{{ $req->input('hotel_infants', 0) }}"
    data-hotel-rooms="{{ $req->input('hotel_rooms', 1) }}"
    data-hotel-room-type="{{ $req->input('hotel_room_type', 'Double') }}"
    data-hotel-radius="{{ $req->input('hotel_radius', 16000) }}"
    data-hotel-promo="{{ $req->input('hotel_promo') }}"
    data-children-ages='@json(array_values((array) $req->input('children_ages', [])))'
>

<style type="text/css">
    .js-results-page .select2-container .select2-selection__arrow { display: block !important; }
    .js-results-page .select2-container .select2-results__option { color: #1A1A1A !important; }
    .js-results-page .select2-container .select2-selection__rendered { color: #1A1A1A !important; }
</style>

@include('partials.results-hotel-shell', [
    'airports' => $airports,
    'request' => $req,
    'preloadedResults' => $preloadedResults ?? null,
])

</div>

@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('theme/js/select2.min.js') }}"></script>
<script src="{{ asset('theme/js/jetseeker-product-results.js?v=20250902d') }}"></script>
