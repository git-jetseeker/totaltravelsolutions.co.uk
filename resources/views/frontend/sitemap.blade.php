@section('title', 'Sitemap | Total Travel Solutions')
@section('meta_keyword', 'sitemap, airport parking, Total Travel Solutions')
@section('meta_description', 'Browse all Total Travel Solutions pages including airport parking, lounges, hotels, and support.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-legal.css?v=20260929legal') }}">

<style>
.js-sitemap-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}
.js-sitemap-col h3 {
    margin: 0 0 14px;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--js-heading);
    padding-bottom: 10px;
    border-bottom: 2px solid var(--js-accent);
}
.js-sitemap-col ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
.js-sitemap-col li {
    margin: 0 0 8px;
}
.js-sitemap-col a {
    color: var(--js-text);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
}
.js-sitemap-col a:hover {
    color: var(--js-accent);
    text-decoration: underline;
}
@media (max-width: 767px) {
    .js-sitemap-grid { grid-template-columns: 1fr; gap: 28px; }
}
</style>

@include('partials.page-hero', [
    'eyebrow' => 'Navigation',
    'title' => 'Sitemap',
    'subtitle' => 'Find every page on Total Travel Solutions',
    'heroClass' => 'js-page-hero--enhanced js-page-hero--legal',
])

<main class="js-legal-page">
    <section class="js-legal-body">
        <div class="js-container">
            <article class="js-legal-card">
                <div class="js-sitemap-grid">
                    <div class="js-sitemap-col">
                        <h3>Airport Parking</h3>
                        <ul>
                            <li><a href="{{ route('page', ['slug' => 'gatwick-airport-parking']) }}">Gatwick Airport Parking</a></li>
                            <li><a href="{{ route('page', ['slug' => 'heathrow-airport-parking']) }}">Heathrow Airport Parking</a></li>
                            <li><a href="{{ route('page', ['slug' => 'stansted-airport-parking']) }}">Stansted Airport Parking</a></li>
                            <li><a href="{{ route('page', ['slug' => 'liverpool-airport-parking']) }}">Liverpool Airport Parking</a></li>
                            <li><a href="{{ route('page', ['slug' => 'luton-airport-parking']) }}">Luton Airport Parking</a></li>
                            <li><a href="{{ route('page', ['slug' => 'manchester-airport-parking']) }}">Manchester Airport Parking</a></li>
                        </ul>
                    </div>
                    <div class="js-sitemap-col">
                        <h3>Serving Airports</h3>
                        <ul>
                            <li><a href="{{ route('page', ['slug' => 'gatwick-airport-parking']) }}">Gatwick Airport</a></li>
                            <li><a href="{{ route('page', ['slug' => 'heathrow-airport-parking']) }}">Heathrow Airport</a></li>
                            <li><a href="{{ route('page', ['slug' => 'luton-airport-parking']) }}">Luton Airport</a></li>
                            <li><a href="{{ route('page', ['slug' => 'stansted-airport-parking']) }}">Stansted Airport</a></li>
                            <li><a href="{{ route('page', ['slug' => 'bristol-airport-parking']) }}">Bristol Airport</a></li>
                            <li><a href="{{ route('page', ['slug' => 'liverpool-airport-parking']) }}">Liverpool Airport</a></li>
                            <li><a href="{{ route('page', ['slug' => 'manchester-airport-parking']) }}">Manchester Airport</a></li>
                        </ul>
                    </div>
                    <div class="js-sitemap-col">
                        <h3>Other Pages</h3>
                        <ul>
                            <li><a href="{{ route('about-us') }}">About Us</a></li>
                            <li><a href="{{ route('terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                            <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('/site-security') }}">Site Security</a></li>
                            <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                            <li><a href="{{ route('affiliates') }}">Affiliates</a></li>
                            <li><a href="{{ route('cookies') }}">Cookies</a></li>
                            <li><a href="{{ route('airport_guide') }}">Airport Guide</a></li>
                            <li><a href="{{ route('faqs') }}">FAQs</a></li>
                            <li><a href="{{ route('airports') }}">All Airports</a></li>
                            <li><a href="{{ route('blogs') }}">Blogs</a></li>
                            <li><a href="{{ route('support') }}">Customer Support</a></li>
                        </ul>
                    </div>
                </div>
            </article>
        </div>
    </section>
</main>

@include('layouts.footer')
