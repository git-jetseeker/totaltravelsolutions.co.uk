@section('title', optional($page ?? null)->meta_title ?: 'Affiliates - Total Travel Solutions')
@section('meta_keyword', optional($page ?? null)->meta_keyword ?: 'affiliates')
@section('meta_description', optional($page ?? null)->meta_description ?: 'Partner with Total Travel Solutions.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20260929legal') }}">

@include('partials.page-hero', [
    'eyebrow' => 'Partners',
    'title' => optional($page ?? null)->page_title ?: 'Affiliates',
    'subtitle' => 'Partner with Total Travel Solutions',
    'lead' => 'Learn how to join our affiliate programme and earn commission by promoting airport parking deals.',
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
