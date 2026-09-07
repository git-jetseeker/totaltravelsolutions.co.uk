@if (\Request::is('main'))
    <link rel="stylesheet" href='{{ asset('assets/css/menu.css') }}' media="all" type='text/css' />
@else
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/bootstrap4/bootstrap.min.css') }}">
    <link rel="stylesheet" href='{{ asset('assets/front/parkingzone/css/all.css') }}' media="all" type='text/css' />
@endif

<style>
    .th_class {
        background: #ffcb05;
    }

    .inner-section {
        background-color: #eff2f3;
        padding: 5px 0;
    }

    .passenger-details h3 {
        /* margin: 10px; */
        padding: 10px 20px;
        line-height: 1.6;
        /*background: linear-gradient(to right, rgba(30, 133, 95, 0.9) 0, rgba(13, 70, 141, 0.9));*/
        background-color: #31124b;
        color: #fff;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
    }

    .hxComment li {
        list-style-type: none;
    }

    .pl {
        padding-left: 10px;
    }

    .secti {
        width: 100%;
        height: 100%;
    }

    .secti {
        margin: 0;
        font-size: 100%;
        background: orange;
        font-family: Georgia, serif;
        /* lets the fonts look a bit better */
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .wrapper {
        position: relative;
        /* moves the wrapper up on y axis */
        /*transform: translateY(-50%);*/
        top: 10%;
        margin: 0 auto;
        width: 75%;
        overflow: hidden;
        height: 210px;
    }

    .wrapper p {

        left: 100%;
        width: 100%;
        font-size: 20px;
        color: #ffffff;
        top: 27px;
        opacity: 0;
        transition: left 1.8s, opacity 0.5s ease;
        mar
    }

    .wrapper p.activeText {
        position: absolute;
        left: 0;
        opacity: 1;
    }

    .wrapper p.slideLeft {
        left: -100%;
        opacity: 0.1;
    }

    .wrapper p:before {
        content: "\201C";
        font-size: 3em;
        line-height: 0.1em;
        margin-right: 0.1em;
        vertical-align: -0.4em;
    }

    .wrapper p:after {
        content: "\201D";
        font-size: 3em;
        line-height: 0.1em;
        margin-left: 0.1em;
        vertical-align: -0.45em;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 100px;
        background: #7f8c8d;
        display: inline-block;
        text-align: center;
        cursor: pointer;
    }

    .active {
        /*background: #ecf0f1;*/
    }

    /* Arrows */

    .arrows {
        color: rgba(127, 140, 141, 0.62);
        width: 1em;
        height: 1em;
        position: absolute;
        top: 50%;
        margin-top: -31px;
    }

    .prev {
        border-bottom: 6px solid;
        border-left: 6px solid;
        transform: rotate(45deg);
        left: 10px;
        color: white;
    }

    .next {
        border-bottom: 6px solid;
        border-left: 6px solid;
        transform: rotate(-135deg);
        right: 10px;
        color: white;
    }

    .prev:active,
    .next:active {
        color: white;
    }

    .padd-lr {
        padding-left: 1.2%;
        padding-right: 1%;
    }
</style>

<!--<section class="top-offer " style="margin-top: 94px;">-->


<!--        <div class="container">-->
<!--            <div class="row">-->
<!--                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">-->
<!--                    <div class="row">-->
<!--                        <div class="panel passenger-details">-->
<!--                            <h3 class="light-weight">JET SEEKER Reviews</h3>-->
<!--                            <div class="well-body">-->
<!--                                <p style="padding:15px">-->
<!--                                    Below are the reviews of our customers who have booked car parking with us. Our-->
<!--                                    Airport Parking feedback services, encourages customers to review, good or bad about-->
<!--                                    their experience with us. This allows us to know which car parks are better-->
<!--                                    performing and in which areas our customers are most satisfied with-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                    </div>-->
<!--                    <div class="row margin-row">-->
<!--                        <div class="panel">-->
<!--                            <ul class="hxComment" style="padding-left:0px;">-->

<!--                                @foreach ($reviews as $review)
-->

<!--                                    <li class="comment odd">-->
<!--                                        <span class="commentSect"></span>-->
<!--                                        <p class="item padding0px pl">-->
<!--                                            <strong class="fn">{{ $review['c_name'] }}</strong>-->
<!--                                        </p>-->
<!--                                        <div class="carousel-inner">-->
<!--                                            <div class="item active">-->
<!--                                                <blockquote style="border-left: 5px solid #2e7b7f">-->
<!--                                                    <p class="padding0px pl"-->
<!--                                                       style="text-align: left;">{{ $review['review'] }}</p>-->
<!--                                                    <small class="text-right pl">{{ $review['username'] }}<br>-->
<!--                                                        <strong class="pl">{{ $review['created_at'] }}<span class="value-title" title="{{ $review['created_at'] }}"></span></strong>-->
<!--                                                    </small>-->
<!--                                                </blockquote>-->
<!--                                                <hr>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </li>-->
<!--
@endforeach-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
<di class="container">
    <div class="row">
        <div class="col-lg-12 padd-lr">
            <div class="padd-lr">
                <h3 class="mt-3" dir="ltr"
                    style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; padding: 16pt 0pt 4pt;background: orange;    text-align: center;">
                    <span
                        style="color:white;font-size: 18pt !important; font-family: Verdana; background-color: transparent; font-weight: 700; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; vertical-align: baseline; white-space: pre-wrap;">
                        JET SEEKER Reviews
                    </span>
                </h3>
                <!--<h3 class="light-weight">JET SEEKER Reviews</h3>-->
                <section class="secti">

                    <div class="arrows prev" style="cursor: pointer;"></div>

                    <div class="wrapper" id="wrapper">
                        @foreach ($reviews as $review)
                            <p style="text-align:center">
                                <span>{{ $review['review'] }} </span>
                                <br>
                                <span style="text-align:center; "><b>{{ $review['username'] }}</b></span>
                            </p>
                        @endforeach
                        <!--<p>My life has completely changed! All of my friends and family are so impressed, and it has brought a certain kind of confidence in my abilities not only as a "techie" but as a mom, and person in general that I could switch careers and understand and-->
                        <!--  DO it. </p>-->

                        <!--<p>This quote is also inspirational!!!! Super inspirational! Because everything is awesome!</p>-->

                        <!--<ul class="dots-wrap">-->
                        <!--  <li class="dot"></li>-->
                        <!--  <li class="dot"></li>-->
                        <!--  <li class="dot"></li>-->
                        <!--</ul>-->

                    </div>

                    <div class="arrows next" style="cursor: pointer;"></div>

                </section>
            </div>
        </div>
    </div>
    </div>

    <script>
        // shoutout to cassiecodes for this JS! Go team Skillcrush!
        // SimpleSlider is an immediately invoked function expression (IIFE)
        // It returns an object with public methods and properties that can be used to configure and control the slider without modifying the source code
        // it obscures private functions and variables
        const SimpleSlider = (function($) {

            // initialize "global" variables
            let slider = {},
                $container,
                $slides,
                $prev,
                $next,
                $dots;

            // set slider config defaults
            slider.config = {
                slideDuration: 5000,
                auto: true,
                containerSelector: '#simpleSlider',
                slideSelector: 'p',
                prevArrowSelector: '.prev',
                nextArrowSelector: '.next',
                dotsSelector: '.dot'
            };

            // initialize slider with config
            slider.init = config => {
                // if config provided, merge it with default config
                if (config && typeof(config) == 'object') {
                    $.extend(slider.config, config);
                }
                // get slider element
                $container = $(slider.config.containerSelector);
                // get slides
                $slides = $container.find(slider.config.slideSelector);
                // get prev button element
                $prev = $(slider.config.prevArrowSelector);
                // get next button element
                $next = $(slider.config.nextArrowSelector);
                // get dots container element
                $dots = $(slider.config.dotsSelector);
                // hook up prev button
                $prev.click(slider.prev);
                // hook up next button
                $next.click(slider.next);
                // hook up dots nav
                $dots.each((i, dot) => {
                    $(dot).click(() => {
                        slider.setSlideByIndex($dots.index(dot));
                    });
                });
                // activate first slide
                $($slides[0]).addClass('activeText');
                // activate first dot
                $($dots[0]).addClass('active');
                // Slide Automatically or Nah...
                if (slider.config.auto) autoNext();
            };

            // Slide Automatically
            // private function
            function autoNext() {
                setInterval(slider.next, slider.config.slideDuration);
            }

            // Navigate to next slide
            // public method
            slider.next = () => {
                // get active slide
                const activeSlide = $slides.filter('.activeText');
                // get active dot
                const activeDot = $dots.filter('.active');
                // get current index
                const currentIndex = $slides.index(activeSlide);
                // remove active class from active slide
                activeSlide.removeClass('activeText');
                activeDot.removeClass('active');
                // apply activeText class to next slide
                // if on last slide
                if (currentIndex === $slides.length - 1) {
                    // make first slide active
                    $($slides[0]).addClass('activeText');
                    // make first dot active
                    $($dots[0]).addClass('active');
                } else {
                    // make next slide active
                    $($slides[currentIndex + 1]).addClass('activeText');
                    // make next slide dot
                    $($dots[currentIndex + 1]).addClass('active');
                }
            };

            // Navigate to previous slide
            slider.prev = () => {
                // get active slide
                const activeSlide = $slides.filter('.activeText');
                // get active dot
                const activeDot = $dots.filter('.active');
                // get current index
                const currentIndex = $slides.index(activeSlide);
                // remove active class from active slide
                activeSlide.removeClass('activeText');
                activeDot.removeClass('active');
                // apply activeText class to next slide
                // handle when next slide is first slide
                if (currentIndex === 0) {
                    // make last slide active
                    $slides[$slides.length - 1].classList.add('activeText');
                    // make last dot active
                    $dots[$dots.length - 1].classList.add('active');
                } else {
                    // make prev slide active
                    $($slides[currentIndex - 1]).addClass('activeText');
                    // make prev dot active
                    $($dots[currentIndex - 1]).addClass('active');
                }
            };

            // Navigate to slide by index
            slider.setSlideByIndex = index => {
                // get active slide
                const activeSlide = $slides.filter('.activeText');
                // get active dot
                const activeDot = $dots.filter('.active');
                // remove active class from active slide & dot
                activeSlide.removeClass('activeText');
                activeDot.removeClass('active');
                // make slide at given index active
                $($slides[index]).addClass('activeText');
                // make slide at given index active
                $($dots[index]).addClass('active');
            };

            // return the slider object with public methods
            return slider;
        }(jQuery)); //pass in any needed global variables


        // This can also be placed in the HTML file inside a script tag.
        // Doesn't seem to want to work that way in Codepen
        SimpleSlider.init({
            containerSelector: "#wrapper", //default: "#simpleSlider"
            auto: false //default: true
        });
    </script>
