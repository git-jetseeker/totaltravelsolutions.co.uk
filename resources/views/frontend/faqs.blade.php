@section('title', $page->meta_title)
@section('meta_keyword', $page->meta_keyword)
@section('meta_description', $page->meta_description)
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-faqs.css?v=20250924') }}">

<section class="js-page-hero js-page-hero--enhanced js-page-hero--faqs">
    <div class="js-container">
        <span class="js-page-hero__eyebrow">Total Travel Solutions Support Center</span>
        <h1 class="js-page-hero__title">Frequently Asked Questions</h1>
        <p class="js-page-hero__subtitle">Find answers about airport parking, bookings, transfers, vehicle security, payments, and more with Total Travel Solutions.</p>
        <p class="js-page-hero__lead">Browse our most common questions below for instant answers about airport parking and bookings.</p>
    </div>
</section>

<main class="js-faqs-page">
    <section class="js-faqs-body">
        <div class="js-container">
            <div class="js-faqs-main">
                    <header class="js-faqs-intro">
                        <span class="js-faqs-intro__badge">Help centre</span>
                        <h2>How Can We Help You?</h2>
                        <p>Browse our most common questions and get instant answers.</p>
                    </header>

                    <div class="js-faqs-panel">
                        @foreach ($faqs as $type => $faqGroup)
                            <div class="js-faqs-category">
                                @if (count($faqs) > 1)
                                    <span class="js-faqs-category__label">{!! $type !!}</span>
                                @endif
                                <div class="js-faqs-list">
                                    @foreach ($faqGroup as $item)
                                        @include('partials.faq-item', [
                                            'id' => 'faq-' . $item->id,
                                            'question' => $item->title,
                                            'answer' => $item->content,
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </section>
</main>

@include('layouts.footer')
