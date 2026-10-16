@section('title', optional($page ?? null)->meta_title ?: 'Site Security - Total Travel Solutions')
@section('meta_keyword', optional($page ?? null)->meta_keyword ?: 'site security')
@section('meta_description', optional($page ?? null)->meta_description ?: 'How Total Travel Solutions protects your bookings and data.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20260929legal') }}">

@include('partials.page-hero', [
    'eyebrow' => 'Trust & safety',
    'title' => optional($page ?? null)->page_title ?: 'Site Security',
    'subtitle' => 'How we protect your bookings and data',
    'lead' => 'Total Travel Solutions uses secure payment processing and industry-standard protections for your personal information.',
    'heroClass' => 'js-page-hero--enhanced js-page-hero--legal',
])

<main class="js-legal-page">
    <section class="js-legal-body">
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
