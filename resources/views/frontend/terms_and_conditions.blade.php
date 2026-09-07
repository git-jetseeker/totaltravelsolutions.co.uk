@section('title', $page->meta_title ?: 'Terms & Conditions - Total Travel Solutions')
@section('meta_keyword', $page->meta_keyword ?: 'terms and conditions, Total Travel Solutions')
@section('meta_description', $page->meta_description ?: 'Read the Total Travel Solutions terms and conditions for booking airport parking services.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20251005') }}">

@include('partials.page-hero', [
    'title' => 'Terms & Conditions',
    'subtitle' => 'Please read these terms carefully before booking',
    'lead' => 'These terms govern your use of Total Travel Solutions and our airport parking booking services. By placing a booking, you agree to the conditions set out below.',
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
