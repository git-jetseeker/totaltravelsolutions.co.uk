@php $meta = function_exists('crm_page_meta') ? crm_page_meta('faqs') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: ($page->meta_title ?? 'FAQs - Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: ($page->meta_keyword ?? 'faqs, airport parking help'))
@section('meta_description', ($meta->meta_description ?? null) ?: ($page->meta_description ?? 'Frequently asked questions about Total Travel Solutions airport parking.'))
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261001faqs') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-faqs.css?v=20261001faqs2') }}">

@include('layouts.search_form', [
    'heroEyebrow' => crm('faqs.hero_eyebrow', crm('faqs.eyebrow', 'Help centre')),
    'heroTitle' => crm('faqs.hero_title', 'Frequently Asked Questions'),
    'heroSubtitle' => crm('faqs.hero_subtitle', 'Find answers about airport parking, bookings, payments, and more'),
    'hideBookingWidget' => true,
])

<main class="js-faqs-page">

    <section class="js-faqs-body section-spacing">
        <div class="container">
            <div class="js-faqs-main">

                <header class="js-section-head js-reveal">
                    <span class="js-section-head__badge">{{ crm('faqs.intro_badge', 'Help centre') }}</span>
                    <h2 class="js-section-title">{{ crm('faqs.intro_title', 'How Can We Help You?') }}</h2>
                    <p class="js-section-subtitle">{{ crm('faqs.intro_text', 'Browse our most common questions and get instant answers.') }}</p>
                </header>

                @if($faqs->isEmpty())
                    <div class="js-faqs-empty js-reveal">
                        <p>{{ crm('faqs.empty_text', 'No FAQs are available right now. Please check back soon or contact our support team.') }}</p>
                        <a href="{{ url('customer-support') }}" class="js-btn js-btn--primary">{{ crm('faqs.help_button', 'Contact support') }}</a>
                    </div>
                @else
                    @php
                        $categories = $faqs->keys()->values();
                    @endphp

                    @if($categories->count() > 1)
                        <div class="js-faqs-filters js-reveal" role="tablist" aria-label="FAQ categories">
                            <button type="button" class="js-faqs-filter is-active" data-faq-filter="all" role="tab" aria-selected="true">All</button>
                            @foreach($categories as $category)
                                <button type="button" class="js-faqs-filter" data-faq-filter="{{ \Illuminate\Support\Str::slug($category) }}" role="tab" aria-selected="false">{{ $category }}</button>
                            @endforeach
                        </div>
                    @endif

                    <div class="js-faqs-panel js-reveal">
                        <p class="js-faqs-hint">{{ crm('faqs.expand_hint', 'Click a question to expand the answer') }}</p>

                        @foreach($faqs as $type => $items)
                            <section class="js-faqs-category" data-faq-category="{{ \Illuminate\Support\Str::slug($type) }}">
                                <h2 class="js-faqs-category__label">{{ $type }}</h2>
                                <div class="js-faqs-list">
                                    @foreach($items as $item)
                                        @include('partials.faq-item', [
                                            'id' => 'faq-' . $item->id,
                                            'question' => $item->title,
                                            'answer' => $item->content,
                                        ])
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </section>

    <section class="js-faqs-banner">
        <div class="container">
            <div class="js-faqs-banner__inner js-reveal">
                <div class="js-faqs-banner__copy">
                    <h2 class="js-faqs-banner__title">{{ crm('faqs.help_title', 'Still need help?') }}</h2>
                    <p class="js-faqs-banner__text">{{ crm('faqs.help_text', 'Our support team is available Mon–Fri, 9AM–5PM. We will get you sorted quickly.') }}</p>
                </div>
                <a href="{{ url('customer-support') }}" class="js-btn js-btn--accent">{{ crm('faqs.help_button', 'Contact support') }}</a>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var filters = document.querySelectorAll('.js-faqs-filter');
    var categories = document.querySelectorAll('.js-faqs-category');
    if (!filters.length) return;

    filters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var filter = btn.getAttribute('data-faq-filter');

            filters.forEach(function (el) {
                el.classList.toggle('is-active', el === btn);
                el.setAttribute('aria-selected', el === btn ? 'true' : 'false');
            });

            categories.forEach(function (section) {
                var show = filter === 'all' || section.getAttribute('data-faq-category') === filter;
                section.hidden = !show;
            });
        });
    });
});
</script>

@include('layouts.footer')
