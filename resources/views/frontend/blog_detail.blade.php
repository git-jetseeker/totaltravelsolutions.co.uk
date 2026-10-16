@section('title', $post->meta_title)
@section('meta_keyword', $post->meta_keyword)
@section('meta_description', $post->meta_description)

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-blogs.css?v=20261001blogs') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261001mock') }}">

@include('partials.page-hero', [
    'eyebrow' => 'Blog',
    'title' => $post->page_title,
    'subtitle' => '',
    'heroClass' => 'js-page-hero--enhanced js-page-hero--compact',
])

<main class="js-blog-detail-page">
    <section class="js-blog-detail-body">
        <div class="js-container">
            <div class="js-blog-detail-layout">
                <article class="js-blog-article">
                    <a href="{{ url('/blogs') }}" class="js-blog-article__back">
                        <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back to blogs
                    </a>

                    <h1 class="js-blog-article__title">{{ $post->page_title }}</h1>

                    @if(!empty($post->banner))
                        <img
                            src="{{ 'https://dashboard.ttssgroup.com/storage/' . str_replace('public/', '', $post->banner) }}"
                            alt="{{ $post->page_title }}"
                            class="js-blog-article__banner"
                            loading="lazy"
                            width="900"
                            height="480"
                        >
                    @endif

                    @if(!empty($post->topthings))
                        <div class="js-blog-article__content">{!! $post->topthings !!}</div>
                    @endif
                    @if(!empty($post->overview))
                        <div class="js-blog-article__content">{!! $post->overview !!}</div>
                    @endif
                    @if(!empty($post->airport_parking))
                        <div class="js-blog-article__content">{!! $post->airport_parking !!}</div>
                    @endif
                    @if(!empty($post->parking_options))
                        <div class="js-blog-article__content">{!! $post->parking_options !!}</div>
                    @endif
                    @if(!empty($post->facilities))
                        <div class="js-blog-article__content">{!! $post->facilities !!}</div>
                    @endif
                </article>

                <aside class="js-blog-sidebar hidden-xs hidden-sm">
                    <div class="js-blog-sidebar-card">
                        <h3>Search parking</h3>
                        @include('partials.booking-widget', [
                            'bookingCardId' => 'blog_search_form',
                            'bookingCardClass' => '',
                            'skipRefTracking' => true,
                        ])
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

@include('layouts.footer')
