@php $meta = function_exists('crm_page_meta') ? crm_page_meta('airport-parking-types') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: (optional($page ?? null)->meta_title ?: 'Airport Parking Types | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: (optional($page ?? null)->meta_keyword ?: 'parking types, meet and greet, park and ride'))
@section('meta_description', ($meta->meta_description ?? null) ?: (optional($page ?? null)->meta_description ?: 'Compare Meet & Greet, Park & Ride, and on-site airport parking types.'))

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261001mobileform') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20260929legal') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261001mock') }}">

@include('partials.page-hero', [
    'eyebrow' => 'Parking options',
    'title' => optional($page ?? null)->page_title ?: 'Airport Parking Types',
    'subtitle' => 'Meet & Greet, Park & Ride, and on-site parking explained',
    'lead' => 'Choose the parking style that best fits your trip, then compare live deals at major UK airports.',
    'heroClass' => 'js-page-hero--enhanced',
    'withBookingWidget' => true,
    'bookingCardId' => 'parking_types_search_form',
])

<main class="js-legal-page">
    @include('partials.parking-gates', [
        'badge' => 'Service options',
        'title' => 'Parking Options to Suit Every Traveller',
        'subtitle' => 'Compare Meet & Greet, on-site, and Park & Ride across major UK airports.',
    ])

    @include('partials.why-choose')

    <section class="js-legal-body js-legal-body--top">
        <div class="js-container">
            <article class="js-legal-card js-legal-card--content">
                <div class="js-legal-content">
                    {!! !empty(optional($page ?? null)->airport_parking) ? $page->airport_parking : (optional($page ?? null)->content ?? '') !!}
                </div>
            </article>
        </div>
    </section>
</main>

@include('layouts.footer')
