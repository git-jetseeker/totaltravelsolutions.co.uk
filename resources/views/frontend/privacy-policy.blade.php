@section('title', $page->meta_title ?: "Privacy Policy - Total Travel Solutions")
@section('meta_keyword', $page->meta_keyword ?: 'privacy policy, data protection, Total Travel Solutions')
@section('meta_description', $page->meta_description ?: 'Learn how Total Travel Solutions safeguards your personal data and privacy throughout your parking booking journey.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20251003') }}">

@include('partials.page-hero', [
    'title' => 'Privacy Policy',
    'subtitle' => 'How we protect and handle your personal data',
    'lead' => 'Your privacy matters to us. This policy explains what information we collect, how we use it, and the steps we take to keep your data secure.',
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
