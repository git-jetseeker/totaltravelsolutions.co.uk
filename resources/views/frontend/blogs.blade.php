@include('layouts.header')

@include('layouts.nav')



<style>

    /* ============================================

       PREMIUM BLOGS PAGE STYLES

       ============================================ */



    /* Premium Page Background */

    body {

        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);

        font-family: 'Open Sans', sans-serif;

        color: #333;

        overflow-x: hidden;

    }



    * {

        -webkit-font-smoothing: antialiased;

        -moz-osx-font-smoothing: grayscale;

    }



    html {

        scroll-behavior: smooth;

    }



    /* Premium Section Spacing */

    .blog-section {

        padding: 80px 0;

        position: relative;

    }



    .blog-hero-section {

        background: linear-gradient(135deg, #f5f7fa 0%, #E5E5E5 100%);

        padding: 60px 0 40px;

        position: relative;

        overflow: hidden;

        margin-bottom: 60px;

    }



    .blog-hero-section::before {

        content: '';

        position: absolute;

        top: 0;

        left: 0;

        right: 0;

        bottom: 0;

        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="rgba(49,18,75,0.05)"/></svg>');

        opacity: 0.3;

    }



    /* Premium Section Title */

    .section-title {

        text-align: center;

        font-size: 2.8rem;

        font-weight: 800;

        color: black;

        margin-bottom: 20px;

        position: relative;

        display: inline-block;

        width: 100%;

        z-index: 1;

    }



    .section-title::after {

        content: '';

        position: absolute;

        bottom: -10px;

        left: 50%;

        transform: translateX(-50%);

        width: 80px;

        height: 4px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        border-radius: 2px;

    }



    .section-title span {

        background: linear-gradient(135deg, #C2185B, #C2185B);

        -webkit-background-clip: text;

        -webkit-text-fill-color: transparent;

        background-clip: text;

    }



    .section-subtitle {

        text-align: center;

        color: #666;

        font-size: 1.2rem;

        max-width: 850px;

        margin: 0 auto 60px;

        line-height: 1.8;

        font-weight: 400;

        position: relative;

        z-index: 1;

    }



    /* Premium Blog Container */

    .blog-container {

        max-width: 1400px;

        margin: 0 auto;

        padding: 0 80px;

    }



    /* Premium Blog Grid */

    .blog-grid {

        display: grid;

        grid-template-columns: repeat(4, 1fr);

        gap: 40px;

        margin-top: 50px;

    }



    /* Blog Item - Hidden by default */

    .blog-item {

        display: none;

        animation: fadeInUp 0.6s ease forwards;

    }



    .blog-item.show {

        display: block;

    }



    @keyframes fadeInUp {

        from {

            opacity: 0;

            transform: translateY(30px);

        }



        to {

            opacity: 1;

            transform: translateY(0);

        }

    }



    /* Premium Blog Card */

    .blog-card {

        background: white;

        border-radius: 20px;

        overflow: hidden;

        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        border: 2px solid transparent;

        position: relative;

        height: 100%;

        display: flex;

        flex-direction: column;

        text-decoration: none;

    }



    .blog-card::before {

        content: '';

        position: absolute;

        top: 0;

        left: 0;

        width: 100%;

        height: 5px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        transform: scaleX(0);

        transition: transform 0.4s ease;

        z-index: 1;

    }



    .blog-card:hover::before {

        transform: scaleX(1);

    }



    .blog-card:hover {

        transform: translateY(-12px) scale(1.02);

        box-shadow: 0 12px 50px rgba(49, 18, 75, 0.2);

        border-color: rgba(65, 105, 225, 0.438);

        text-decoration: none;

    }



    /* Blog Image Container */

    .blog-image-container {

        position: relative;

        height: 252px;

        overflow: hidden;

        background: #f0f0f0;

    }



    .blog-image-container img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        transition: transform 0.5s ease;

    }



    .blog-card:hover .blog-image-container img {

        transform: scale(1.1);

    }



    /* Blog Content */

    .blog-content {

        padding: 30px 25px;

        flex: 1;

        display: flex;

        flex-direction: column;

    }



    .blog-title {

        font-size: 1.4rem;

        font-weight: 700;

        color: black;

        margin-bottom: 15px;

        line-height: 1.4;

        min-height: 60px;

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;

        transition: color 0.3s ease;

    }



    .blog-card:hover .blog-title {

        color: #C2185B;

    }



    .blog-description {

        color: #666;

        font-size: 1rem;

        line-height: 1.7;

        margin-bottom: 25px;

        flex: 1;

        display: -webkit-box;

        -webkit-line-clamp: 3;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }



    /* Blog Footer */

    .blog-footer {

        display: flex;

        justify-content: flex-end;

        align-items: center;

        margin-top: auto;

        padding-top: 20px;

        border-top: 1px solid #f0f0f0;

    }



    .blog-link {

        display: inline-flex;

        align-items: center;

        gap: 8px;

        background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);

        padding: 12px 28px;

        color: white;

        font-size: 14px;

        font-weight: 700;

        border: none;

        border-radius: 6px;

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        text-decoration: none;

        text-transform: uppercase;

        letter-spacing: 0.5px;

        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.438);

    }



    .blog-link:hover {

        background: black;

        transform: translateX(5px);

        box-shadow: 0 8px 25px rgba(49, 18, 75, 0.4);

        color: white;

        text-decoration: none;

    }



    .blog-link i {

        transition: transform 0.3s ease;

    }



    .blog-link:hover i {

        transform: translateX(5px);

    }



    /* Show More/Less Button */

    .btn-show-more {

        background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);

        padding: 15px 50px;

        color: white;

        font-size: 18px;

        font-weight: 700;

        border: none;

        border-radius: 6px;

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        text-decoration: none;

        display: inline-block;

        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.438);

        text-transform: uppercase;

        letter-spacing: 0.5px;

        cursor: pointer;

        margin-top: 50px;

    }



    .btn-show-more:hover {

        background: black;

        transform: translateY(-3px) scale(1.05);

        box-shadow: 0 8px 25px rgba(49, 18, 75, 0.4);

        color: white;

        text-decoration: none;

    }



    .btn-container {

        text-align: center;

        margin-top: 40px;

    }



    /* Responsive Design */

    @media only screen and (max-width: 1400px) {

        .blog-container {

            padding: 0 60px;

        }

    }



    @media only screen and (max-width: 1200px) {

        .blog-grid {

            grid-template-columns: repeat(3, 1fr);

            gap: 30px;

        }

    }



    @media only screen and (max-width: 991px) {

        .section-title {

            font-size: 2.2rem;

        }



        .section-subtitle {

            font-size: 1.1rem;

        }



        .blog-container {

            padding: 0 40px;

        }



        .blog-section {

            padding: 60px 0;

        }



        .blog-grid {

            grid-template-columns: repeat(2, 1fr);

            gap: 25px;

        }

    }



    @media only screen and (max-width: 767px) {

        .section-title {

            font-size: 1.8rem;

        }



        .section-subtitle {

            font-size: 1rem;

            margin-bottom: 40px;

        }



        .blog-container {

            padding: 0 20px;

        }



        .blog-section {

            padding: 50px 0;

        }



        .blog-hero-section {

            padding: 40px 0 30px;

        }



        .blog-grid {

            grid-template-columns: 1fr;

            gap: 30px;

        }



        .blog-card {

            border-radius: 16px;

        }



        .blog-content {

            padding: 25px 20px;

        }



        .blog-title {

            font-size: 1.2rem;

            min-height: auto;

        }



        .blog-description {

            font-size: 0.95rem;

            margin-bottom: 20px;

        }



        .blog-link {

            padding: 10px 20px;

            font-size: 13px;

            width: 100%;

            justify-content: center;

        }



        .btn-show-more {

            padding: 12px 40px;

            font-size: 16px;

            width: 100%;

        }

    }



    @media only screen and (max-width: 480px) {

        .blog-container {

            padding: 0 15px;

        }



        .section-title {

            font-size: 1.6rem;

        }



        .blog-image-container {

            height: 200px;

        }



        .blog-content {

            padding: 20px 15px;

        }

    }

</style>



@include('frontend.blog-searchbar')



<!-- Blog Hero Section -->

<section class="blog-hero-section">

    <div class="container">

        <h1 class="section-title">Our <span>Latest Blogs</span></h1>

        <p class="section-subtitle">Stay updated with the latest travel tips, parking guides, and industry insights</p>

    </div>

</section>



<!-- Main Blog Section -->

<section class="blog-section">

    <div class="blog-container">

        <div class="blog-grid">

            @foreach ($recent_posts as $index => $recent_post)

                <div class="blog-item {{ $index < 8 ? 'show' : '' }}">

                    <a href="{{ url('blog/' . $recent_post->slug) }}" class="blog-card">

                        <div class="blog-image-container">

                            <img src='{{ 'https://dashboard.jetseekergroup.com/storage/' . str_replace('public/','',$recent_post->banner) }}'

                                alt="{!! $recent_post->page_title !!}" loading="lazy">

                        </div>

                        <div class="blog-content">

                            <h4 class="blog-title">{!! $recent_post->page_title !!}</h4>

                            <p class="blog-description">{!! $recent_post->meta_description !!}</p>

                            <div class="blog-footer">

                                <span class="blog-link">

                                    Learn More <i class="fa fa-long-arrow-right" aria-hidden="true"></i>

                                </span>

                            </div>

                        </div>

                    </a>

                </div>

            @endforeach

        </div>



        @if (count($recent_posts) > 8)

            <div class="btn-container">

                <button id="showMoreBtn" class="btn-show-more">Show More</button>

            </div>

        @endif

    </div>

</section>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    $(document).ready(function() {

        const $blogItems = $('.blog-item');

        const $showMoreBtn = $('#showMoreBtn');

        const totalItems = $blogItems.length;

        const itemsToShow = 8;



        // Initially show first 8 items

        $blogItems.slice(0, itemsToShow).addClass('show');



        // Hide button if total items <= 8

        if (totalItems <= itemsToShow) {

            $showMoreBtn.hide();

        }



        // Show More / Show Less functionality

        $showMoreBtn.on('click', function() {

            const $hiddenItems = $blogItems.filter(':not(.show)');



            if ($hiddenItems.length > 0) {

                // Show all hidden items

                $hiddenItems.addClass('show');

                $(this).text('Show Less');

            } else {

                // Hide items beyond first 8

                $blogItems.slice(itemsToShow).removeClass('show');

                $(this).text('Show More');



                // Smooth scroll to blog section

                $('html, body').animate({

                    scrollTop: $('.blog-hero-section').offset().top - 100

                }, 800);

            }

        });

    });

</script>



@include('layouts.footer')

