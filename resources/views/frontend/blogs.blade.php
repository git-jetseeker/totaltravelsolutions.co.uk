@php $meta = function_exists('crm_page_meta') ? crm_page_meta('blogs') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: 'Travel Blogs & Airport Parking Tips | Total Travel Solutions')
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: 'airport parking blogs, travel tips')
@section('meta_description', ($meta->meta_description ?? null) ?: 'Stay updated with the latest travel tips, parking guides, and industry insights from Total Travel Solutions.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-blogs.css?v=20261001blogs') }}">

@include('partials.page-hero', [
    'eyebrow' => crm('blogs.hero_eyebrow', 'Insights & guides'),
    'title' => crm('blogs.hero_title', 'Our Latest Blogs'),
    'subtitle' => crm('blogs.hero_subtitle', 'Travel tips, parking guides, and industry insights'),
    'lead' => crm('blogs.hero_lead', 'Practical advice to help you book smarter and travel with confidence.'),
    'heroClass' => 'js-page-hero--enhanced',
])

<main class="js-blogs-page">
    <section class="js-blogs-body">
        <div class="js-container">
            <div class="js-blogs-grid">
                @foreach ($recent_posts as $index => $recent_post)
                    <div class="js-blog-item {{ $index < 8 ? 'show' : '' }}">
                        <a href="{{ url('blog/' . $recent_post->slug) }}" class="js-blog-card">
                            <div class="js-blog-card__media">
                                <img
                                    src="{{ 'https://dashboard.ttssgroup.com/storage/' . str_replace('public/', '', $recent_post->banner) }}"
                                    alt="{{ strip_tags((string) $recent_post->page_title) }}"
                                    loading="lazy"
                                    width="400"
                                    height="200"
                                >
                            </div>
                            <div class="js-blog-card__body">
                                <h3 class="js-blog-card__title">{!! $recent_post->page_title !!}</h3>
                                <p class="js-blog-card__excerpt">{!! $recent_post->meta_description !!}</p>
                                <span class="js-blog-card__cta">
                                    Learn more <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if (count($recent_posts) > 8)
                <div class="js-blogs-more">
                    <button type="button" id="showMoreBtn" class="js-btn js-btn--primary">Show More</button>
                </div>
            @endif
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('.js-blog-item');
    var btn = document.getElementById('showMoreBtn');
    var limit = 8;

    if (!btn || items.length <= limit) {
        if (btn) btn.style.display = 'none';
        return;
    }

    btn.addEventListener('click', function () {
        var hidden = Array.prototype.filter.call(items, function (el) {
            return !el.classList.contains('show');
        });

        if (hidden.length) {
            hidden.forEach(function (el) { el.classList.add('show'); });
            btn.textContent = 'Show Less';
        } else {
            Array.prototype.forEach.call(items, function (el, i) {
                if (i >= limit) el.classList.remove('show');
            });
            btn.textContent = 'Show More';
            var hero = document.querySelector('.js-page-hero');
            if (hero) hero.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>

@include('layouts.footer')
