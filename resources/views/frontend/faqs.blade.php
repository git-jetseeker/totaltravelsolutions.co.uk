@php $meta = function_exists('crm_page_meta') ? crm_page_meta('faqs') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: ($page->meta_title ?? 'FAQs - Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: ($page->meta_keyword ?? 'faqs'))
@section('meta_description', ($meta->meta_description ?? null) ?: ($page->meta_description ?? 'Frequently asked questions about Total Travel Solutions airport parking.'))
@include('layouts.header')
@include('layouts.nav')

<section class="pz-page-hero pz-page-hero--tall">
    <div class="container">
        <h1 class="pz-page-hero__title">{{ crm('faqs.hero_title', 'Frequently Asked Questions') }}</h1>
        <p class="pz-page-hero__subtitle">{{ crm('faqs.hero_lead', 'Find answers to common questions about our airport parking, hotels, and lounge booking services.') }}</p>
    </div>
</section>

<section class="pz-page-content">
    <div class="pz-page-wrap pz-page-wrap--wide">
        <div class="pz-faq-card">
            @foreach ($faqs as $type => $faq)
                <div class="pz-faq-category">
                    <h2 class="pz-faq-category__title">{!! $type !!}</h2>

                    @foreach ($faq as $item)
                        <div class="pz-faq-item">
                            <a href="#collapse_{{ $item->id }}"
                               class="pz-faq-question"
                               data-toggle="collapse"
                               role="button"
                               aria-expanded="false"
                               onclick="toggleFaq(this, event)">
                                <span class="pz-faq-question__text">{!! $item->title !!}</span>
                                <span class="pz-faq-icon"><i class="fa fa-plus"></i></span>
                            </a>
                            <div id="collapse_{{ $item->id }}" class="pz-faq-answer collapse">
                                <div class="pz-faq-answer__content">
                                    {!! $item->content !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
function toggleFaq(element, event) {
    event.preventDefault();
    event.stopPropagation();

    var question = element;
    var targetId = question.getAttribute('href');
    var answer = document.querySelector(targetId);
    var icon = question.querySelector('.pz-faq-icon i');
    var isOpen = answer.classList.contains('show');

    if (isOpen) {
        question.classList.remove('active');
        answer.classList.remove('show');
        icon.classList.remove('fa-minus');
        icon.classList.add('fa-plus');
    } else {
        question.classList.add('active');
        answer.classList.add('show');
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-minus');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pz-faq-answer').forEach(function (el) {
        el.classList.remove('show');
    });
    document.querySelectorAll('.pz-faq-question').forEach(function (el) {
        el.classList.remove('active');
    });
    document.querySelectorAll('.pz-faq-icon i').forEach(function (el) {
        el.classList.remove('fa-minus');
        el.classList.add('fa-plus');
    });
});
</script>

@include('layouts.footer')
