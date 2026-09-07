

<link rel="stylesheet" href="{{asset('theme/styles/swiper-bundle.min.css')}}" />
<script src="{{asset('theme/js/swiper-bundle.min.js')}}"></script>


<!-- Swiper CSS -->
<!--<link rel="stylesheet" href="css/swiper-bundle.min.css">-->

<!-- Premium Top Tips CSS -->
<style type="text/css">
    /* Premium Container Styling */
    .slide-container {
        max-width: 1537px;
        width: 100%;
        position: relative;
        padding: 20px 0;
    }

    .slide-con {
        margin: 0 40px 35px !important;
        overflow: visible;
        padding: 20px 0;
    }

    /* Premium Card Design */
    .card-m {
        overflow: hidden;
        cursor: pointer;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px !important;
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
        border: 2px solid transparent;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        height: 100%;
    }

    /* Animated Border Gradient */
    .card-m::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 20px;
        padding: 2px;
        background: linear-gradient(135deg, #C2185B, #C2185B, #C2185B);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .card-m:hover::before {
        opacity: 1;
    }

    /* Shimmer Effect on Hover */
    .card-m::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(65, 105, 225, 0.062), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
        opacity: 0;
        pointer-events: none;
    }

    .card-m:hover::after {
        animation: shimmer 1.5s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
            opacity: 0;
        }
    }

    .card-m:hover {
        transform: translateY(-15px) scale(1.03);
        box-shadow: 0 20px 60px rgba(65, 105, 225, 0.377);
    }

    /* Premium Card Content */
    .card-content {
        display: flex;
        flex-direction: column;
        padding: 45px 30px;
        position: relative;
        z-index: 1;
        background: transparent;
    }

    /* Enhanced Title Styling */
    .card-m .name {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-top: 0;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        height: auto !important;
    }

    .card-m:hover .name {
        color: #C2185B;
        transform: scale(1.05);
    }

    /* Premium Icon Styling */
    .card-m .name img {
        width: 45px !important;
        height: 45px !important;
        filter: drop-shadow(0 4px 10px rgba(65, 105, 225, 0.377));
        transition: all 0.4s ease;
        background: linear-gradient(135deg, rgba(65, 105, 225, 0.377), rgba(65, 105, 225, 0.377));
        padding: 10px;
        border-radius: 12px;
        margin-right: 0 !important;
    }

    .card-m:hover .name img {
        transform: rotate(360deg) scale(1.15);
        filter: drop-shadow(0 6px 15px rgba(65, 105, 225, 0.377));
        background: linear-gradient(135deg, rgba(65, 105, 225, 0.377), rgba(65, 105, 225, 0.377));
    }

    /* Premium HR Separator */
    .card-m hr {
        border: none !important;
        height: 2px;
        background: linear-gradient(90deg, transparent, #C2185B, transparent) !important;
        margin: 0 0 30px 0 !important;
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .card-m:hover hr {
        opacity: 1;
        background: linear-gradient(90deg, transparent, #C2185B, #C2185B, #C2185B, transparent) !important;
    }

    /* Enhanced Text Content */
    .card-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555;
        text-align: center;
        margin: 0;
        font-weight: 400;
        transition: color 0.3s ease;
    }

    .card-m:hover .card-text {
        color: #333;
    }

    .card-slider1 {
        font-size: 1.05rem;
        padding-top: 0;
        text-align: center;
    }

    /* Premium Pagination Bullets */
    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
        transform: scale(0.8);
    }

    .swiper-pagination-bullet {
        background-color: #e0e0e0 !important;
        border: 2px solid #C2185B;
        opacity: 1;
        width: 14px;
        height: 14px;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet:hover {
        transform: scale(1.2);
    }

    .swiper-pagination-bullet-active {
        background-color: #C2185B !important;
        width: 30px;
        border-radius: 10px;
        box-shadow: 0 3px 15px rgba(65, 105, 225, 0.377);
    }

    /* Card Height */
    .csw {
        height: 320px;
        align-items: stretch;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .slide-con {
            margin: 0 10px !important;
        }
        .swiper-navBtn {
            display: none;
        }
        .card-content {
            padding: 35px 20px;
        }
        .csw {
            height: 280px;
        }
    }

    @media screen and (min-device-width: 992px) and (max-device-width: 1199px) {
        .scf {
            margin-left: 10px;
            margin-right: 10px;
        }
        .csw {
            height: 340px;
        }
    }

    @media screen and (min-device-width: 950px) and (max-device-width: 991px) {
        .scf {
            margin-left: -10px;
            margin-right: -10px;
        }
        .csw {
            height: 360px;
        }
    }

    @media screen and (min-device-width: 769px) and (max-device-width: 949px) {
        .scf {
            margin-left: 20px;
            margin-right: 20px;
        }
        .scs {
            height: 300px;
        }
        .csw {
            height: 300px;
        }
    }

    @media screen and (min-device-width: 700px) and (max-device-width: 768px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 320px;
        }
        .csw {
            height: 290px;
        }
    }

    @media screen and (min-device-width: 650px) and (max-device-width: 699px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 350px;
        }
        .csw {
            height: 315px;
        }
    }

    @media screen and (min-device-width: 600px) and (max-device-width: 649px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 350px;
        }
        .csw {
            height: 320px;
        }
    }

    @media screen and (min-device-width: 520px) and (max-device-width: 599px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 460px;
        }
        .csw {
            height: 430px;
        }
    }

    @media screen and (min-device-width: 327px) and (max-device-width: 519px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 330px;
        }
        .csw {
            height: 290px;
        }
    }

    @media screen and (min-device-width: 295px) and (max-device-width: 326px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 360px;
        }
        .csw {
            height: 320px;
        }
    }

    @media screen and (min-device-width: 262px) and (max-device-width: 294px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 420px;
        }
        .csw {
            height: 370px;
        }
    }

    @media screen and (max-width: 261px) {
        .scf {
            margin-left: 0;
            margin-right: 0;
        }
        .scs {
            height: 620px;
        }
        .csw {
            height: 585px;
        }
    }
</style>

<div class="slide-container swiper scf">
    <div class="slide-content slide-con scs">
        <div class="card-wrapper swiper-wrapper csw">

            <div class="card card-m swiper-slide">
                <div class="card-content">
                    <h3 class="name">
                        <img src="{{ asset('assets/images/Book in Advance.png') }}" alt="booking 1" loading="lazy">
                        Book in Advance
                    </h3>
                    <hr>
                    <p class="card-text card-slider1">Reserving the parking spot in advance often results in lower
                        rates. Take advantage of pre-booking discounts offered by parking facilities.</p>
                </div>
            </div>

            <div class="card card-m swiper-slide">
                <div class="card-content">
                    <h3 class="name">
                        <img src="{{ asset('assets/images/Compare Price.png') }}" alt="compare 1" loading="lazy">
                        Compare Prices
                    </h3>
                    <hr>
                    <p class="card-text card-slider1">Take your time with the parking options you come across. Compare
                        prices and find the best deal for your budget.</p>
                </div>
            </div>

            <div class="card card-m swiper-slide">
                <div class="card-content">
                    <h3 class="name">
                        <img src="{{ asset('assets/images/Of side Parking.png') }}" alt="car-parking-_1_ 2" loading="lazy">
                        Off-Site Parking
                    </h3>
                    <hr>
                    <p class="card-text card-slider1">Consider parking in off-site parking lots near the airport. These
                        facilities often offer lower rates compared to on-site airport parking.</p>
                </div>
            </div>

           
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>



<script>
    if (!document.querySelector('.js-tips-section__track')) {
        var swiper = new Swiper(".slide-content", {
            slidesPerView: 3,
            spaceBetween: 25,
            loop: false,
            centerSlide: 'true',
            fade: 'true',
            grabCursor: 'true',
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                520: {
                    slidesPerView: 2,
                },
                950: {
                    slidesPerView: 3,
                },
            },
        });
    }
</script>

</html>
