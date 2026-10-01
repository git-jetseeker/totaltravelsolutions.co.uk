@section('title', optional($page ?? null)->meta_title ?: 'Airport Guide - Total Travel Solutions')
@section('meta_keyword', optional($page ?? null)->meta_keyword ?: 'airport guide')
@section('meta_description', optional($page ?? null)->meta_description ?: 'Airport guide from Total Travel Solutions.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20260929legal') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261001mock') }}">

@include('partials.page-hero', [
    'eyebrow' => 'Travel guide',
    'title' => optional($page ?? null)->page_title ?: 'Airport Guide',
    'subtitle' => 'Helpful information for UK airports',
    'lead' => 'Plan your journey with practical airport parking and travel guidance from Total Travel Solutions.',
    'heroClass' => 'js-page-hero--enhanced',
    'withBookingWidget' => true,
    'bookingCardId' => 'airport_guide_search_form',
])

<main class="js-legal-page">
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
