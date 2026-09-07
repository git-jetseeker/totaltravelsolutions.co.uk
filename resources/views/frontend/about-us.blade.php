@section('title', 'About Us - Total Travel Solutions')
@section('meta_keyword', 'Total Travel Solutions, about us, best parking deal')
@section('meta_description', 'About Us | JET SEEKER provides a hassle-free car parking booking options for your car 24 hours a day, 7 days a week. Call us now to book your space.')

    @include('layouts.header')
    @include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-about.css?v=20250922') }}">

@include('partials.page-hero', [
    'title' => 'About Total Travel Solutions',
    'subtitle' => 'Your trusted partner for UK airport parking',
    'lead' => 'For over two decades we have helped travellers find reliable, Park Mark accredited parking at major UK airports — transparent pricing, quality service, and peace of mind every time you fly.',
    'heroClass' => 'js-page-hero--enhanced',
])

<main class="js-about-page">

    {{-- Mission --}}
    <section class="js-about-story">
        <div class="js-container">
            <div class="js-about-story__grid">
                <div class="js-about-story__copy">
                    <span class="js-about-story__eyebrow">Our mission</span>
                    <h2 class="js-about-story__title">Our <span>Mission</span></h2>
                    <p class="js-about-story__text">Our mission at Total Travel Solutions is clear: we want to make <a class="linked" href="{{ url('/') }}">airport parking</a> reliable, more transparent, and accessible to all sorts of travellers. We work with a range of reputable Park Mark accredited airport parking service providers at over 8+ UK airports, providing customers an overall choice of services including UK <strong>Meet &amp; Greet, On-Site and Park &amp; Ride</strong>. We are committed to offer you the most competitive price in the market and achieve that by monitoring the current market prices and ensuring quality service, we can guarantee our customers exceptional value for money and a reliable service with no compromises.</p>
                </div>
                <div class="js-about-story__media js-about-story__media--rounded-tr">
                    <img src="{{ asset('assets/images/about1.png') }}" alt="Total Travel Solutions mission" class="img-fluid" loading="lazy" width="450" height="340">
                </div>
            </div>
        </div>
    </section>

    {{-- Promise --}}
    <section class="js-about-story js-about-story--alt">
        <div class="js-container">
            <div class="js-about-story__grid js-about-story__grid--reverse">
                <div class="js-about-story__copy">
                    <span class="js-about-story__eyebrow">Our promise</span>
                    <h2 class="js-about-story__title">Our Promise <span>To You</span></h2>
                    <p class="js-about-story__text">We built Total Travel Solutions to remove the guesswork from <a class="linked" href="{{ url('/') }}">airport parking</a>. Our mission is simple: we deliver great value, without compromising on safety or service. Every provider we list is someone we'd trust with our own key. So when you book through Total Travel Solutions, you're choosing peace of mind, every time you fly.</p>
                </div>
                <div class="js-about-story__media js-about-story__media--rounded-tl">
                    <img src="{{ asset('assets/images/about2.png') }}" alt="Total Travel Solutions promise" class="img-fluid" loading="lazy" width="450" height="340">
                </div>
            </div>
        </div>
    </section>

    {{-- Company + team + specialise --}}
    <section class="js-about-content js-about-content--alt">
        <div class="js-container">

            <article class="js-about-card">
                <header class="js-about-card__head">
                    <span class="js-about-card__badge">Since the 2000s</span>
                    <h2 class="js-about-card__title">Our <span>Innovative</span> Company</h2>
                </header>
                <p class="js-about-card__intro">We started in the early 2000s. After over 21 years, we have become a trusted parking provider in the UK. We adapt to changes in the travel industry. We offer the latest technology updates and improve our tools, all while focusing on customer satisfaction.</p>
            </article>

            <article class="js-about-card">
                <header class="js-about-card__head">
                    <span class="js-about-card__badge">Customer first</span>
                    <h2 class="js-about-card__title cap-text">Our Team is <span>Dedicated</span> to Providing</h2>
                </header>
                <ul class="js-about-list">
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                        A smooth user experience.
                        </li>
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                            Secure and trusted payment systems.
                        </li>
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                            Personalized booking support before, during, and after.
                        </li>
                    </ul>
            </article>

            <article class="js-about-card">
                <header class="js-about-card__head">
                    <span class="js-about-card__badge">What we do best</span>
                    <h2 class="js-about-card__title cap-text">What we <span>specialise</span> in</h2>
                </header>
                <ul class="js-about-list">
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                        Competitive pricing by checking verified provider prices.
                        </li>
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                            Ongoing high-level service from our online partners.
                        </li>
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                            Full customer engagement in the booking process for flexibility and clarity.
                        </li>
                    <li>
                        <span class="js-about-list__icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                            Continuous improvement through feedback and innovation.
                        </li>
                    </ul>
            </article>

        </div>
    </section>

    {{-- Pricing & legal --}}
    <section class="js-about-info">
        <div class="js-container">
            <div class="js-about-info__grid">
                <article class="js-about-info__item">
                    <h2>Pricing updates &amp; VAT</h2>
                    <p>JET SEEKER will make sure you get the lowest most affordable price, however the prices may vary if the VAT increases on parking services.</p>
                </article>
                <article class="js-about-info__item">
                    <h2>Jet<span>Seeker</span> Trademarks</h2>
                    <p>This website is managed by Total Travel Solutions, registered in England and Registration No 16770283, Address: Suite 8f, Kelvin House, Kelvin Way, Crawley, United Kingdom, RH10 9WE</p>
                </article>
            </div>
        </div>
    </section>

</main>

    @include('layouts.footer')
