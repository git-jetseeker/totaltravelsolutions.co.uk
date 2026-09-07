@section('title', $page->meta_title ?: 'Cookie Policy - Total Travel Solutions')
@section('meta_keyword', $page->meta_keyword ?: 'cookie policy, cookies, Total Travel Solutions')
@section('meta_description', $page->meta_description ?: 'Learn how Total Travel Solutions uses cookies and how you can manage your preferences.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20251003') }}">

@include('partials.page-hero', [
    'title' => 'Cookie Policy',
    'subtitle' => 'How we use cookies on Total Travel Solutions',
    'lead' => 'This policy explains what cookies are, how we use them on our website, and the choices you have regarding their use.',
    'heroClass' => 'js-page-hero--enhanced',
])

<main class="js-legal-page">
    <section class="js-legal-body">
        <div class="js-container">
            <article class="js-legal-card js-legal-card--content">
                <div class="js-legal-content">
                    {!! $page->airport_parking ?? '' !!}
                </div>
            </article>
        </div>
    </section>
</main>

@include('layouts.footer')
