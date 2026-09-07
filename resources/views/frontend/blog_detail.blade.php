@section("title",$post->meta_title)

@section("meta_keyword",$post->meta_keyword )

@section("meta_description",$post->meta_description)

@include('layouts.header')

@include('layouts.nav')



<style type="text/css">

    /* ============================================

       PREMIUM BLOG DETAIL PAGE STYLES

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



    /* Premium Hero Section */

    .blog-detail-hero {

        /* background: linear-gradient(135deg, #f5f7fa 0%, #E5E5E5 100%); */

        padding: 60px 0 40px;

        position: relative;

        overflow: hidden;

        /* margin-bottom: 60px; */

    }



    .blog-detail-hero::before {

        content: '';

        position: absolute;

        top: 0;

        left: 0;

        right: 0;

        bottom: 0;

        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="rgba(49,18,75,0.05)"/></svg>');

        opacity: 0.3;

    }



    .blog-detail-section {

        padding: 0 0 80px 0;

        position: relative;

    }



    /* Premium Container */

    .blog-detail-container {

        max-width: 1400px;

        margin: 0 auto;

        padding: 0 80px;

    }



    /* Premium Blog Content Card */

    .blog-content-card {

        background: white;

        border-radius: 20px;

        padding: 50px;

        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);

        transition: all 0.3s ease;

        border: 2px solid transparent;

        position: relative;

        overflow: hidden;

    }



    .blog-content-card::before {

        content: '';

        position: absolute;

        top: 0;

        left: 0;

        width: 100%;

        height: 5px;

        /* background: linear-gradient(90deg, #C2185B, #C2185B); */

    }



    .blog-content-card:hover {

        box-shadow: 0 8px 40px rgba(49, 18, 75, 0.15);

        border-color: rgba(65, 105, 225, 0.438);

    }



    /* Premium Page Title */

    .blog-detail-title {

        font-size: 2.5rem;

        font-weight: 800;

        color: black;

        margin-bottom: 30px;

        line-height: 1.3;

        position: relative;

        padding-bottom: 20px;

    }



    .blog-detail-title::after {

        content: '';

        position: absolute;

        bottom: 0;

        left: 0;

        width: 80px;

        height: 4px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        border-radius: 2px;

    }



    /* Premium Banner Image */

    .blog-banner-image {

        width: 100%;

        border-radius: 16px;

        margin-bottom: 40px;

        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);

        transition: all 0.5s ease;

    }



    .blog-banner-image:hover {

        transform: scale(1.02);

        box-shadow: 0 15px 50px rgba(65, 105, 225, 0.438);

    }



    /* Premium Content Styling */

    .blog-detail-content {

        font-size: 1.1rem;

        line-height: 1.9;

        color: #444;

    }



    .blog-detail-content h1,

    .blog-detail-content h2,

    .blog-detail-content h3,

    .blog-detail-content h4,

    .blog-detail-content h5,

    .blog-detail-content h6 {

        color: black;

        font-weight: 700;

        margin-top: 35px;

        margin-bottom: 20px;

        line-height: 1.4;

    }



    .blog-detail-content h1 {

        font-size: 2.2rem;

        position: relative;

        padding-bottom: 15px;

    }



    .blog-detail-content h1::after {

        content: '';

        position: absolute;

        bottom: 0;

        left: 0;

        width: 60px;

        height: 3px;

        background: linear-gradient(90deg, #C2185B, #C2185B);

        border-radius: 2px;

    }



    .blog-detail-content h2 {

        font-size: 1.9rem;

    }



    .blog-detail-content h3 {

        font-size: 1.6rem;

        color: #1a1a1a;

        padding-left: 0 !important;

    }



    .blog-detail-content h4 {

        font-size: 1.4rem;

    }



    .blog-detail-content p {

        margin-bottom: 20px;

        color: #555;

    }



    .blog-detail-content strong,

    .blog-detail-content b {

        color: black;

        font-weight: 700;

    }



    .blog-detail-content a {

        color: #C2185B;

        text-decoration: none;

        border-bottom: 2px solid transparent;

        transition: all 0.3s ease;

    }



    .blog-detail-content a:hover {

        color: #C2185B;

        border-bottom-color: #C2185B;

    }



    /* Premium List Styling */

    .blog-detail-content ul,

    .blog-detail-content ol {

        margin: 25px 0;

        padding-left: 0;

        list-style: none;

    }



    .blog-detail-content ul li,

    .blog-detail-content ol li {

        list-style: none !important;

        color: #555;

        font-size: 1.1rem;

        line-height: 1.8;

        margin-bottom: 15px;

        padding-left: 40px;

        position: relative;

        transition: all 0.3s ease;

    }



    .blog-detail-content ul li:hover,

    .blog-detail-content ol li:hover {

        padding-left: 45px;

        color: #333;

    }



    .blog-detail-content ul li::before {

        content: '';

        position: absolute;

        left: 0;

        top: 8px;

        width: 10px;

        height: 10px;

        background: linear-gradient(135deg, #C2185B, #C2185B);

        border-radius: 50%;

        box-shadow: 0 2px 8px rgba(65, 105, 225, 0.438);

    }



    .blog-detail-content ol {

        counter-reset: item;

    }



    .blog-detail-content ol li::before {

        content: counter(item);

        counter-increment: item;

        position: absolute;

        left: 0;

        top: 0;

        width: 25px;

        height: 25px;

        background: linear-gradient(135deg, #C2185B, #C2185B);

        color: white;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        font-weight: 700;

        font-size: 0.9rem;

        box-shadow: 0 2px 10px rgba(65, 105, 225, 0.438);

    }



    /* Premium Blockquote */

    .blog-detail-content blockquote {

        background: linear-gradient(135deg, rgba(255, 165, 0, 0.05), rgba(255, 140, 0, 0.02));

        border-left: 5px solid #C2185B;

        padding: 25px 30px;

        margin: 30px 0;

        border-radius: 8px;

        font-style: italic;

        color: #444;

        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);

    }



    /* Premium Images in Content */

    .blog-detail-content img {

        max-width: 100%;

        height: auto;

        border-radius: 12px;

        margin: 30px 0;

        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);

        transition: all 0.3s ease;

    }



    .blog-detail-content img:hover {

        transform: scale(1.02);

        box-shadow: 0 12px 40px rgba(65, 105, 225, 0.438);

    }



    /* Premium Tables */

    .blog-detail-content table {

        width: 100%;

        border-collapse: collapse;

        margin: 30px 0;

        border-radius: 12px;

        overflow: hidden;

        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);

    }



    .blog-detail-content table th {

        background: linear-gradient(135deg, #C2185B, #C2185B);

        color: white;

        padding: 15px;

        text-align: left;

        font-weight: 700;

    }



    .blog-detail-content table td {

        padding: 15px;

        border-bottom: 1px solid #f0f0f0;

        color: #555;

    }



    .blog-detail-content table tr:hover {

        background: rgba(255, 165, 0, 0.05);

    }



    /* Premium Sidebar */

    .blog-sidebar {

        position: sticky;

        top: 100px;

    }



    /* Premium Form Controls */

    .form-control {

        display: block;

        width: 100%;

        padding: 12px 18px;

        font-size: 1rem;

        line-height: 1.5;

        color: #495057;

        background-color: #fff;

        border: 2px solid #e0e0e0;

        border-radius: 8px;

        transition: all 0.3s ease;

    }



    .form-control:focus {

        border-color: #C2185B;

        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);

        outline: none;

    }



    /* Accordion Styling */

    #accordion {

        padding: 20px !important;

    }



    /* Back to Blogs Button */

    .back-to-blogs {

        display: inline-flex;

        align-items: center;

        gap: 10px;

        background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);

        padding: 12px 30px;

        color: white;

        font-size: 15px;

        font-weight: 700;

        border: none;

        border-radius: 6px;

        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);

        text-decoration: none;

        text-transform: uppercase;

        letter-spacing: 0.5px;

        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.438);

        margin-bottom: 30px;

    }



    .back-to-blogs:hover {

        background: black;

        transform: translateX(-5px);

        box-shadow: 0 8px 25px rgba(49, 18, 75, 0.4);

        color: white;

        text-decoration: none;

    }



    .back-to-blogs i {

        transition: transform 0.3s ease;

    }



    .back-to-blogs:hover i {

        transform: translateX(-5px);

    }



    /* Responsive Design */

    @media only screen and (max-width: 1400px) {

        .blog-detail-container {

            padding: 0 60px;

        }

    }



    @media only screen and (max-width: 991px) {

        .blog-detail-container {

            padding: 0 40px;

        }



        .blog-content-card {

            padding: 40px;

        }



        .blog-detail-title {

            font-size: 2rem;

        }



        .blog-detail-content {

            font-size: 1.05rem;

        }



        .blog-detail-content h1 {

            font-size: 1.9rem;

        }



        .blog-detail-content h2 {

            font-size: 1.6rem;

        }



        .blog-detail-content h3 {

            font-size: 1.4rem;

        }



        .blog-sidebar {

            position: static;

            margin-top: 40px;

        }

    }



    @media only screen and (max-width: 767px) {

        .blog-detail-container {

            padding: 0 20px;

        }



        .blog-detail-hero {

            padding: 40px 0 30px;

            margin-bottom: 40px;

        }



        .blog-detail-section {

            padding: 0 0 50px 0;

        }



        .blog-content-card {

            padding: 30px 20px;

            border-radius: 16px;

        }



        .blog-detail-title {

            font-size: 1.6rem;

            margin-bottom: 25px;

        }



        .blog-detail-content {

            font-size: 1rem;

        }



        .blog-detail-content h1 {

            font-size: 1.6rem;

        }



        .blog-detail-content h2 {

            font-size: 1.4rem;

        }



        .blog-detail-content h3 {

            font-size: 1.2rem;

        }



        .blog-detail-content h4 {

            font-size: 1.1rem;

        }



        .blog-detail-content ul li,

        .blog-detail-content ol li {

            font-size: 1rem;

            padding-left: 35px;

        }



        .back-to-blogs {

            padding: 10px 20px;

            font-size: 13px;

            width: 100%;

            justify-content: center;

        }



        .blog-banner-image {

            margin-bottom: 30px;

            border-radius: 12px;

        }

    }



    @media only screen and (max-width: 480px) {

        .blog-detail-container {

            padding: 0 15px;

        }



        .blog-content-card {

            padding: 25px 15px;

        }



        .blog-detail-title {

            font-size: 1.4rem;

        }

    }

</style>



<section class="blog-detail-hero ">

    <div class="container">

        {{-- <div class="blog-detail-container">

            <a href="{{ url('/blogs') }}" class="back-to-blogs">

                <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back to Blogs

            </a>

        </div> --}}

    </div>

</section>



<section class="blog-detail-section">

    <div class="blog-detail-container">

        <div class="container-fluid">

            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">

                    <div class="blog-content-card">

                        <h1 class="blog-detail-title">{{ $post->page_title }}</h1>



                        @if(isset($post->banner))

                            <img src='{{ 'https://dashboard.jetseekergroup.com/storage/' . str_replace('public/','',$post->banner) }}'

                                 alt="{{ $post->page_title }}"

                                 class="blog-banner-image img-fluid"

                                 loading="lazy">

                        @endif 



                        <div class="blog-detail-content">

                            {!! $post->topthings !!}

                        </div>
                        <div class="blog-detail-content">

                            {!! $post->overview !!}

                        </div>
                        <div class="blog-detail-content">

                            {!! $post->airport_parking !!}

                        </div>
                        <div class="blog-detail-content">

                            {!! $post->parking_options !!}

                        </div>
                        <div class="blog-detail-content">

                            {!! $post->facilities !!}

                        </div>

                    </div>

                </div>



                <div class="hidden-xs hidden-sm col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    <div class="blog-sidebar">

                        @include("frontend.right_searchbar")

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



@include('layouts.footer')

