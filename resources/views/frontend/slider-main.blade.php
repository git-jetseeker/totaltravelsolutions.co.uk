@if (\Request::is('main'))
    <link rel="stylesheet" href='{{ asset('assets/css/menu.css') }}' media="all" type='text/css' />
@else
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/bootstrap4/bootstrap.min.css') }}">
    <link rel="stylesheet" href='{{ asset('assets/front/parkingzone/css/all.css') }}' media="all" type='text/css' />
@endif

<style>
    /* Premium Testimonial Styles */
    .premium-testimonial-slider {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 24px;
        padding: 60px 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .premium-testimonial-slider::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        /* background: linear-gradient(90deg, #C2185B, #C2185B); */
    }

    .testimonialTitle {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 800;
        color: black;
        margin-bottom: 50px;
        position: relative;
    }

    .testimonialTitle span {
        position: relative;
        z-index: 1;
    }

    .testimonialTitle::after {
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

    .premium-testimonial-card {
        background: white;
        padding: 50px 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.08);
        text-align: center;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        margin: 20px;
    }

    .premium-testimonial-card:hover {
        /* transform: translateY(-8px); */
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        border-color: #C2185B;
    }

    .premium-testimonial-card::before {
        content: '"';
        position: absolute;
        top: 30px;
        left: 40px;
        font-size: 5rem;
        color: #C2185B;
        opacity: 0.2;
        font-family: serif;
        line-height: 1;
    }

    .testimonial-content {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #555;
        font-style: italic;
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #C2185B, #C2185B);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    }

    .author-avatar i {
        font-size: 24px;
        color: white;
    }

    .author-info {
        text-align: left;
    }

    .author-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: black;
        margin: 0;
    }

    .author-role {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }

    .premium-rating {
        margin: 15px 0;
    }

    .star-checked {
        color: #C2185B;
        font-size: 1.1rem;
        margin: 0 2px;
        text-shadow: 0 2px 4px rgba(255, 140, 0, 0.3);
    }

    /* Enhanced Owl Carousel Styles */
    .owl-carousel.premium-owl-theme {
        position: relative;
    }

    .owl-nav {
        position: absolute;
        top: 36%;
        width: 100%;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
    }

    .owl-nav .owl-prev,
    .owl-nav .owl-next {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border: 2px solid #C2185B !important;
        color: #C2185B !important;
        font-size: 20px !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        pointer-events: all;
        margin: 0 -10px;
    }

    .owl-nav .owl-prev:hover,
    .owl-nav .owl-next:hover {
        background: #C2185B !important;
        color: white !important;
        transform: scale(1.1);
    }

    .owl-dots {
        margin-top: 40px !important;
        text-align: center;
    }

    .owl-dot span {
        width: 12px !important;
        height: 12px !important;
        margin: 5px !important;
        background: #ddd !important;
        transition: all 0.3s ease;
    }

    .owl-dot.active span {
        background: #C2185B !important;
        transform: scale(1.2);
    }

    /* Stats Styles */
    .testimonial-stats {
        display: flex;
        justify-content: center;
        gap: 60px;
        margin: 50px 0 80px 0;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
        padding: 20px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #C2185B;
        margin-bottom: 10px;
        line-height: 1;
    }

    .stat-label {
        font-size: 1rem;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .premium-testimonial-slider {
            padding: 40px 20px;
        }

        .premium-testimonial-card {
            padding: 40px 30px;
            margin: 15px;
        }

        .testimonialTitle {
            font-size: 2rem;
        }

        .testimonial-stats {
            gap: 40px;
        }

        .stat-number {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 767px) {
        .premium-testimonial-slider {
            padding: 30px 15px;
            border-radius: 16px;
        }

        .premium-testimonial-card {
            padding: 30px 20px;
            margin: 10px;
        }

        .testimonial-content {
            font-size: 1.1rem;
        }

        .testimonial-author {
            flex-direction: column;
            text-align: center;
        }

        .author-info {
            text-align: center;
        }

        .owl-nav .owl-prev,
        .owl-nav .owl-next {
            width: 50px;
            height: 50px;
                   margin: 0 -10px;

        }

        .testimonial-stats {
            gap: 30px;
        }

        .stat-item {
            padding: 15px;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>

<link rel="stylesheet" href="{{ url('public/theme/styles/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ url('public/theme/styles/owl.theme.default.css') }}">
<script src="{{ url('public/theme/js/owl.carousel.js') }}"></script>

<div class="premium-testimonial-slider js-reviews-slider">
    <div class="container">
       

        <div class="owl-carousel premium-owl-theme">
            @foreach ($reviews as $review)
                <div class="owl-item">
                    <div class="premium-testimonial-card">
                        <blockquote class="testimonial-content">
                            {!! $review['review'] !!}
                        </blockquote>

                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="author-info">
                                <div class="author-name">{{ $review['username'] }}</div>
                                <div class="author-role">Verified Customer</div>
                            </div>
                        </div>

                        <div class="premium-rating">
                            <i class="fa fa-star star-checked"></i>
                            <i class="fa fa-star star-checked"></i>
                            <i class="fa fa-star star-checked"></i>
                            <i class="fa fa-star star-checked"></i>
                            <i class="fa fa-star star-checked"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".owl-carousel.premium-owl-theme").owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 1,
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            },
            navText: [
                "<i class='fa fa-chevron-left'></i>",
                "<i class='fa fa-chevron-right'></i>"
            ]
        });
    });
</script>
