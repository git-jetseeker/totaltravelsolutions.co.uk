<!DOCTYPE html>
<html lang="en-US" class="no-js scheme_default">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="Cache-Control: public, max-age=31536000"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta name="google-site-verification" content="kz0jW8P0ZXYec37awl79cMX367AGpQ_haFp6GB0l7Fc" />
    <meta name="msvalidate.01" content="058CE2BBE3EF9D932E6FA366CAC4120F" />
    <link rel="icon" type="image/png" href="{{ asset("assets/images/favicon-32x32.png") }}" sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{ asset("assets/images/favicon-16x16.png") }}" sizes="16x16"/>
    @php
        $site_settings_main=[];
            $settingsAll = App\Models\settings::all();
                    foreach ($settingsAll as $setting) {
        if ($setting->agent_id == '9') {
            // dd($setting);
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }
    @endphp
    @hasSection('title') <title>@yield('title')</title> @else
        <title>{{ $site_settings_main["site_title"] }}</title> @endif
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')"> @else
        <meta name="description" content="{{ $site_settings_main["meta_description"] }}"> @endif
    @hasSection('meta_keyword')
        <meta name="keywords" content="@yield('meta_keyword')"> @else
        <meta name="keywords" content="{{ $site_settings_main["meta_keyword"] }}">@endif


    <!--<script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>-->


    <link rel='dns-prefetch' href='https://ajax.googleapis.com'/>

    <link rel='dns-prefetch' href='https://fonts.googleapis.com'/>
    <link rel="canonical" href="{{ str_replace("//","/",Request::fullUrl()) }}" />

    <meta name="twitter:title" content="{!!   $site_settings_main["site_twitter_title"] !!}">
    <meta property="og:title" content="{!!   $site_settings_main["site_og_title"] !!}">
        <meta property="og:type" content="{!!   $site_settings_main["site_og_type"] !!}">
<meta property="og:image" content="{!!   $site_settings_main["site_og_image"] !!}">
<meta property="og:url" content="{!!   $site_settings_main["site_og_url"] !!}">
    <meta name="robots" content="">
    <meta name="author" content="{!!   $site_settings_main["site_author"] !!}">

    {!!   $site_settings_main["site_schema"] !!}


 <link   rel="stylesheet" href='{{ asset("assets/front/parkingzone/css/all.css") }}'  media="all" type='text/css'/>
 <!-- <link   rel="stylesheet" href='{{ asset("assets/css/menu.css") }}'  media="all" type='text/css'/> -->


<noscript id="deferred-styles">
     <link href="{{ asset("assets/front/parkingzone/css/font-awesome.min.css") }}" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<!--<link property="stylesheet" rel='stylesheet'-->
<!--          href='https://fonts.googleapis.com/css?family=Open+Sans%3A300%2C400%2C600%2C700%2C800&#038;ver=5.0.1'-->
<!--          type='text/css' media='all'/>-->
<!--    <link property="stylesheet" rel='stylesheet'-->
<!--          href='https://fonts.googleapis.com/css?family=Raleway%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900&#038;ver=5.0.1'-->
<!--          type='text/css' media='all'/>-->
<!--    <link property="stylesheet" rel='stylesheet'-->
<!--          href='https://fonts.googleapis.com/css?family=Droid+Serif%3A400%2C700&#038;ver=5.0.1' type='text/css'-->
<!--          media='all'/>-->


     <!--Custom Stylesheets -->
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/front/css/style.css") }}" media="all">

     <!--Font Awesome Stylesheet -->
  <!--  <link rel="stylesheet" type="text/css" href="{{ asset("assets/front/css/font-awesome.min.css") }}"
        media="all"> -->

    <link rel="stylesheet" type="text/css" href="{{ asset("assets/front/css/yellow.css") }}" media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/front/css/responsive.css") }}" media="all">

    <!--Date-Picker Stylesheet-->
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/front/css/datepicker.css") }}" media="all">

<link property="stylesheet" rel='stylesheet'
      href='{{ asset("assets/page.css") }}' type='text/css'
      media='all'/>
</noscript>



    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css"
          href="http://jetseeker.co.uk/wp-content/plugins/js_composer/assets/css/vc_lte_ie9.min.css"
          media="screen"><![endif]-->

<!--<link  rel='stylesheet' type='text/css' href='{{ asset("assets/page.css") }}' media='all'/>-->


    @yield('stylesheets')

    {!! $site_settings_main["site_header_analytics"] !!}

</head>


<body class="page-template-default page page-id-293 custom-background wp-custom-logo ua_chrome body_tag scheme_default blog_mode_page body_style_wide is_single sidebar_hide expand_content remove_margins trx_addons_present header_type_custom header_style_header-custom-9 header_position_default menu_style_top wpb-js-composer js-comp-ver-5.5.5 vc_responsive desktop_layout">


<div class="body_wrap">

    @yield('content')


</div><!-- </.page_content_wrap> -->

@if(Route::currentRouteName()!= "addBookingForm")
    <!--============== NEWSLETTER ===============-->
    <section id="newsletter" class="banner-padding row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <h2>{!! $site_settings_main["homepage_joinus_heading"]  !!}</h2>
                    <h3>{!! $site_settings_main["homepage_joinus_subheading"]  !!}</h3>
                    <p>{!! $site_settings_main["homepage_joinus_text"]  !!}</p>
                    <form id="subscribe_user" action="{{ route("subscribe_user") }}" method="post">
                        @csrf

                        <div class="form-group">

                            <div class="input-group">
                                <input id="subscribe_user_name" name="name" type="text"
                                       style="width: 40%;margin-right: 1%;"
                                       class="form-control input-lg" placeholder="Enter Your Name" required/>
                                <input id="subscribe_user_email" name="email" type="email" style="width: 59%;"
                                       class="form-control input-lg" placeholder="Your Email Address" required/>
                                <span class="input-group-btn">

                                    <button id="subscribe_user" class="btn btn-lg"><i
                                                class="fa fa-paper-plane"></i></button></span>

                            </div>
                        </div>
                        <div id="error_message"></div>
                    </form>
                </div><!-- end columns -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section><!-- end newsletter -->
@endif
<footer class=" row footer_wrap footer_custom footer_custom_341 footer_custom_footer-main scheme_dark">
    <div class="vc_row margin0  wpb_row vc_row-fluid vc_custom_1522740549230 vc_row-has-fill shape_divider_top-none shape_divider_bottom-none sc_layouts_row sc_layouts_row_type_normal">
        <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
            <div class="vc_column-inner">
                <div class="wpb_wrapper">

                    <div id="sc_content_1701363364"
                         class="sc_content color_style_default sc_content_default sc_content_width_1_1 sc_float_center">
                        <div class="sc_content_container">
                            <div class="vc_row wpb_row vc_inner vc_row-fluid shape_divider_top-none shape_divider_bottom-none">

                                <div class="footer-buttons-inline wpb_column vc_column_container vc_col-sm-2 sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="vc_empty_space" style="height: 0.45em"><span
                                                        class="vc_empty_space_inner"></span></div>
                                            <div class="sc_layouts_item"><a
                                                        href="http://jetseeker.co.uk/"
                                                        id="sc_layouts_logo_1442419164"
                                                        class="sc_layouts_logo sc_layouts_logo_default"
                                                        target="_blank"><img class="logo_image"
                                                                             src="{{ asset("assets/images/logo_new.png") }}"
                                                                             alt="JETSEEKER" title="JETSEEKER" width="160"
                                                                             height="41"></a>
                                                <!-- /.sc_layouts_logo --></div>
                                            <div class="wpb_text_column wpb_content_element  vc_custom_1537692692654">
                                                <div class="wpb_wrapper">
                                                    <p>JETSEEKER:</p> <span> was established in the advent of the 21st century and became a leading traveling
                                                        business.</span>


                                                    <div class="sc_layouts_item"><!-- /.sc_button --></div>
                                                    <div class="sc_layouts_item"><!-- /.sc_button --></div>

                                                </div>
                                                <div class="sc_layouts_item"><!-- /.sc_button --></div>
                                                <div class="sc_layouts_item"><!-- /.sc_button --></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wpb_column vc_column_container vc_col-sm-2 sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="vc_wp_custommenu wpb_content_element">
                                                <div class="widget widget_nav_menu"><h2 class="widgettitle">
                                                        Navigation</h2>
                                                    <div class="menu-navigation-container">
                                                        <ul id="menu-navigation" class="menu prepared">
                                                            <li id="menu-item-332"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-332">
                                                                <a href="{{ route("main") }}">Home</a></li>
                                                            <li id="menu-item-333"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-333">
                                                                <a href="{{ route("static_page",["slug"=>"about-us"]) }}">About</a>
                                                            </li>
                                                            <li id="menu-item-334"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-334">
                                                                <a href="{{ route("support") }}">Customer support</a>
                                                            </li>
                                                            <li id="menu-item-621"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-621">
                                                                <a href="{{ route("static_page",["page"=>"privacy-policy"]) }}">Privacy
                                                                    Policy</a></li>
                                                            <li id="menu-item-622"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-622">
                                                                <a href="{{ route("static_page",["page"=>"site-security"]) }}">Site
                                                                    Security</a></li>


                                                            <li id="menu-item-621"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-621">
                                                                <a href="{{ route("airports") }}">All Airports</a></li>
                                                            <li id="menu-item-622"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-622">
                                                                <a href="https://www.jetseeker.co.uk/blog">Blog</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="vc_empty_space  sc_height_tiny" style="height: 0px">
                                            <span class="vc_empty_space_inner"></span></div>
                                    </div>
                                </div>
                                <div class="wpb_column vc_column_container vc_col-sm-3 sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="vc_wp_custommenu wpb_content_element">
                                                <div class="widget widget_nav_menu"><h2 class="widgettitle">
                                                        Airports </h2>
                                                    <div class="menu-navigation-container">
                                                        <ul id="menu-navigation" class="menu prepared">
                                                            <li id="menu-item-333"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-333">
                                                                <a href="{{ route("page",["slug"=>"heathrow-airport-parking"]) }}">Heathrow
                                                                    Airport Parking</a>
                                                            </li>
                                                            <li id="menu-item-332"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-332">
                                                                <a href="{{ route("page",["slug"=>"gatwick-airport-parking"]) }}">Gatwick
                                                                    Airport Parking</a></li>
                                                            <li id="menu-item-622"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-622">
                                                                <a href="{{ route("page",["slug"=>"manchester-airport-parking"]) }}">Manchester
                                                                    Airport Parking</a></li>
                                                            <li id="menu-item-622"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-622">
                                                                <a href="{{ route("page",["slug"=>"luton-airport-parking"]) }}">Luton
                                                                    Airport Parking</a></li>

                                                            <li id="menu-item-334"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-334">
                                                                <a href="{{ route("page",["slug"=>"stansted-airport-parking"]) }}">Stansted
                                                                    Airport Parking</a>
                                                            </li>
                                                            <li id="menu-item-621"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-621">
                                                                <a href="{{ route("page",["slug"=>"birmingham-airport-parking"]) }}">Birmingham
                                                                    Airport Parking</a></li>
                                                            <li id="menu-item-622"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-622">
                                                                <a href="{{ route("page",["slug"=>"liverpool-airport-parking"]) }}">Liverpool
                                                                    Airport Parking</a></li>
                                                            <li id="menu-item-622"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-622">
                                                                <a href="{{ route("page",["slug"=>"edinburgh-airport-parking"]) }}">Edinburgh
                                                                    Airport Parking</a></li>


                                                            <li id="menu-item-621"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-621">
                                                                <a href="{{ route("page",["slug"=>"southampton-airport-parking"]) }}">Southampton
                                                                    Airport Parking</a></li>


                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="vc_empty_space  sc_height_tiny" style="height: 0px">
                                            <span class="vc_empty_space_inner"></span></div>
                                    </div>
                                </div>

                                <div class="wpb_column vc_column_container vc_col-sm-3 sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="sc_layouts_item">
                                                <div id="sc_title_925868519"
                                                     class="sc_title color_style_default sc_title_default"><h3
                                                            class="sc_item_title sc_title_title sc_align_left sc_item_title_style_default sc_item_title_tag">
                                                        Contact Info</h3></div><!-- /.sc_title --></div>
                                            <div class="wpb_text_column wpb_content_element ">
                                                <div class="wpb_wrapper">
                                                    <p>Office Address:</p>
                                                </div>

                                                <div class="sc_layouts_item">
                                                    <div id="sc_layouts_iconed_text_1713700494"
                                                         class="sc_layouts_iconed_text  vc_custom_1537692371431">
                                                        <span class="sc_layouts_item_icon sc_layouts_iconed_text_icon icon-location"></span><span
                                                                class="sc_layouts_item_details sc_layouts_iconed_text_details"><span
                                                                    class="sc_layouts_item_details_line2 sc_layouts_iconed_text_line2"> Unit 9105 141 Access House, Morden Road, Mitcham, Surrey, England, CR4 4DG</span></span>
                                                        <!-- /.sc_layouts_iconed_text_details --></div>
                                                    <!-- /.sc_layouts_iconed_text --></div>

                                                <div class="sc_layouts_item">
                                                    {{--<div id="sc_layouts_iconed_text_1796881343"--}}
                                                    {{--class="sc_layouts_iconed_text"><span--}}
                                                    {{--class="sc_layouts_item_icon sc_layouts_iconed_text_icon icon-icon_2"></span><span--}}
                                                    {{--class="sc_layouts_item_details sc_layouts_iconed_text_details"><span--}}
                                                    {{--class="sc_layouts_item_details_line2 sc_layouts_iconed_text_line2"> 02086609241</span></span>--}}
                                                    {{--<!-- /.sc_layouts_iconed_text_details --></div>--}}
                                                    {{--<br>--}}
                                                    {{--<div id="sc_layouts_iconed_text_1796881343"--}}
                                                    {{--class="sc_layouts_iconed_text"><span--}}
                                                    {{--class="sc_layouts_item_icon sc_layouts_iconed_text_icon fa fa-envelope"--}}
                                                    {{--style="color:white"></span><span--}}
                                                    {{--class="sc_layouts_item_details sc_layouts_iconed_text_details"><span--}}
                                                    {{--class="sc_layouts_item_details_line2 sc_layouts_iconed_text_line2"> info@parkingzone.co.uk</span></span>--}}
                                                    {{--<!-- /.sc_layouts_iconed_text_details --></div>--}}
                                                    <br>

                                                    <!-- /.sc_layouts_iconed_text --></div>
                                                <div class="vc_empty_space  sc_height_tiny" style="height: 0px">
                                                    <span class="vc_empty_space_inner"></span></div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="wpb_column vc_column_container vc_col-sm-2 sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="vc_wp_custommenu wpb_content_element">
                                                <div class="widget widget_nav_menu"><h2 class="widgettitle">
                                                        Other Pages</h2>
                                                    <div class="menu-discover-container">
                                                        <ul id="menu-discover" class="menu prepared">
                                                            <li id="menu-item-339"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-339">
                                                                <a href="{{ route("static_page",["page"=>"affiliates"]) }}">Affiliate </a>
                                                            </li>
                                                            <li id="menu-item-338"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-338">
                                                                <a href="{{ route("static_page",["page"=>"cookies"]) }}">Cookies</a>
                                                            </li>
                                                            <li id="menu-item-340"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-340">
                                                                <a href="{{ route("faqs") }}">FAQs</a></li>
                                                            <li id="menu-item-339"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-339">
                                                                <a href="{{ route("sitemap") }}">Site Map </a></li>

                                                            {{--<li id="menu-item-340"--}}
                                                            {{--class="menu-item menu-item-type-post_type menu-item-object-page menu-item-340">--}}
                                                            {{--<a href="{{ route("reviews") }}">Reviews</a></li>--}}
                                                            <li id="menu-item-340"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-340">
                                                                <a href="{{ route("airport_guide") }}">Airport Guide</a>
                                                            </li>
                                                            <li id="menu-item-340"
                                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-340">
                                                                <a href="{{ route("static_page",["page"=>"terms-and-conditions"]) }}">Terms
                                                                    & Conditions</a></li>

                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="vc_empty_space  sc_height_tiny" style="height: 0px">
                                                <span class="vc_empty_space_inner"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.sc_content -->
                    {{--<div class="vc_empty_space" style="height: 5.4rem"><span--}}
                    {{--class="vc_empty_space_inner"></span></div>--}}
                </div>
            </div>
        </div>

    </div>
    <div class="vc_row wpb_row vc_row-fluid vc_custom_1522740973564 vc_row-o-content-middle vc_row-flex shape_divider_top-none shape_divider_bottom-none sc_layouts_row sc_layouts_row_type_compact">
        <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
            <div class="vc_column-inner">
                <div class="wpb_wrapper">
                    <div id="sc_content_653695428"
                         class="sc_content color_style_default sc_content_default sc_content_width_1_1 sc_float_center">
                        <div class="sc_content_container">
                            <div class="vc_row wpb_row vc_inner vc_row-fluid vc_row-o-content-middle vc_row-flex shape_divider_top-none shape_divider_bottom-none">
                                <div class="wpb_column vc_column_container vc_col-sm-8 sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="vc_wp_text wpb_content_element">
                                                <div class="widget widget_text">
                                                    <div class="textwidget"><p><a
                                                                    href="{{ route("main") }}">JETSEEKER</a>
                                                            © {{ date("Y") }}.
                                                            All
                                                            rights reserved.</p>


                                                        Company Rigistration Number: <span
                                                                class="sc_layouts_item_details_line2 sc_layouts_iconed_text_line2"> 11502152</span></span>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wpb_column vc_column_container vc_col-sm-4 sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="sc_layouts_item">
                                                <div id="widget_socials_453929481"
                                                     class="widget_area sc_widget_socials vc_widget_socials wpb_content_element">
                                                    <aside id="widget_socials_453929481_widget"
                                                           class="widget widget_socials">

    <div class="socials_wrap sc_align_left">
                                                        @if(array_key_exists("twitter",$site_settings_main) && $site_settings_main["twitter"] !="" && $site_settings_main["twitter_status"] =="active")
                                                        <a
                                                                        target="_blank"
                                                                        href="{{ $site_settings_main["twitter"] }}"
                                                                        class="social_item social_item_style_icons social_item_type_icons"
                                                                        style="background: #33CCFF;"><span
                                                                            class="social_icon social_icon_twitter">

                                                                        <span
                                                                                class="icon-twitter" style="
    color: white;
"></span></span></a> @endif

                                                                @if(array_key_exists("google_plus",$site_settings_main) && $site_settings_main["google_plus"] !="" && $site_settings_main["google_plus_status"] =="active" )
                                                                    <a
                                                                            target="_blank"
                                                                            href="{{ $site_settings_main["google_plus"] }}"
                                                                            class="  social_item social_item_style_icons social_item_type_icons"
                                                                            style="background: #C63D2D;"><span
                                                                                class="social_icon social_icon_gplus ">

                                                                            <span
                                                                                    class="icon-gplus" style="
    color: white;
"></span></span></a>
                                                                @endif
                                                                @if(array_key_exists("instagram",$site_settings_main) && $site_settings_main["instagram"] !="" && $site_settings_main["instagram_status"] =="active")
                                                                    <a
                                                                            target="_blank"
                                                                            href="{{ $site_settings_main["instagram"] }}"
                                                                            class="  social_item social_item_style_icons social_item_type_icons"
                                                                            style="background: #4E433C;"><span
                                                                                class="social_icon social_icon_gplus">

                                                                            <span
                                                                                    class="fa fa-instagram" style="
    color: white;
"></span></span></a>
                                                                @endif


                                                                @if(array_key_exists("youtube",$site_settings_main) && $site_settings_main["youtube"] !="" && $site_settings_main["youtube_status"] =="active")

                                                                    <a
                                                                            target="_blank"
                                                                            href="{{ $site_settings_main["youtube"] }}"
                                                                            class="social_item social_item_style_icons social_item_type_icons"
                                                                            style="background: #33CCFF;"><span
                                                                                class="social_icon social_icon_facebook"><span
                                                                                    class="fa fa-youtube " style="
    color: white;
"></span></span></a>
                                                                @endif


                                                                @if(array_key_exists("pinterest",$site_settings_main) && $site_settings_main["pinterest"] !="" && $site_settings_main["pinterest_status"] =="active")

                                                                    <a
                                                                            target="_blank"
                                                                            href="{{ $site_settings_main["pinterest"] }}"
                                                                            class="social_item  social_item_style_icons social_item_type_icons"
                                                                            style="background:#c8232c;"><span
                                                                                class="social_icon social_icon_facebook"><span
                                                                                    class="fa fa-pinterest-p " style="
    color: white;
"></span></span></a>
                                                                @endif


                                                                @if(array_key_exists("linkedin",$site_settings_main) && $site_settings_main["linkedin"] !="" && $site_settings_main["linkedin_status"] =="active")

                                                                    <a
                                                                            target="_blank"
                                                                            href="{{ $site_settings_main["linkedin"] }}"
                                                                            class="social_item social_item_style_icons social_item_type_icons"
                                                                            style="background:#1d67d0;"><span
                                                                                class="social_icon social_icon_facebook"><span
                                                                                    class="fa fa-linkedin" style="
    color: white;
"></span></span></a>
                                                                @endif


                                                                @if(array_key_exists("facebook",$site_settings_main) && $site_settings_main["facebook"] !="" && $site_settings_main["facebook_status"] =="active")

                                                                    <a
                                                                            target="_blank"
                                                                            href="{{ $site_settings_main["facebook"] }}"
                                                                            class="social_item social_item_style_icons social_item_type_icons"
                                                                            style="background: #3b5998;"><span
                                                                                class="social_icon social_icon_facebook"><span
                                                                                    class="icon-facebook" style="
    color: white;
"></span></span></a>
                                                                @endif
                                                            </div>
                                                    </aside>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.sc_content --></div>
            </div>
        </div>
    </div>
</footer><!-- /.footer_wrap -->

</div><!-- /.page_wrap -->

</div><!-- /.body_wrap -->

<a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up sc_button_hover_slide_left inited"
   title="Scroll to top"></a>
   <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
 <script type='text/javascript'
            src='{{ asset("assets/front/parkingzone/js/jquery-migrate.min.js?ver=1.4.1") }}'></script>
<script async  type="text/javascript">
    var TRX_ADDONS_STORAGE = {
        ajax_nonce: "",
        ajax_url: "",
        ajax_views: "1",
        animate_inner_links: "0",
        email_mask: "^([a-zA-Z0-9_\-]+\.)*[a-zA-Z0-9_\-]+@[a-z0-9_\-]+(\.[a-z0-9_\-]+)*\.[a-z]{2,6}$",
        login_via_ajax: "1",
        menu_cache: (4) ["#sc_layouts_menu_994828205", "#menu-navigation", "#menu-discover", ".menu_mobile_inner > nav > ul"],
        menu_collapse: "1",
        menu_collapse_icon: "trx_addons_icon-ellipsis-vert",
        msg_ajax_error: "Invalid server answer!",
        msg_email_long: "E-mail address is too long",
        msg_email_not_valid: "E-mail address is invalid",
        msg_error_like: "Error saving your like! Please, try again later.",
        msg_field_email_empty: "Too short (or empty) email address",
        msg_field_email_not_valid: "Invalid email address",
        msg_field_name_empty: "The name can't be empty",
        msg_field_text_empty: "The message text can't be empty",
        msg_login_empty: "The Login field can't be empty",
        msg_login_error: "Login failed!",
        msg_login_long: "The Login field is too long",
        msg_login_success: "Login success! The page should be reloaded in 3 sec.",
        msg_magnific_error: "Error loading image",
        msg_magnific_loading: "Loading image",
        msg_not_agree: "Please, read and check 'Terms and Conditions'",
        msg_password_empty: "The password can't be empty and shorter then 4 characters",
        msg_password_long: "The password is too long",
        msg_password_not_equal: "The passwords in both fields are not equal",
        msg_registration_error: "Registration failed!",
        msg_registration_success: "Registration success! Please log in!",
        msg_sc_googlemap_geocoder_error: "Error while geocode address",
        msg_sc_googlemap_not_avail: "Googlemap service is not available",
        msg_search_error: "Search error! Try again later.",
        msg_send_complete: "Send message complete!",
        msg_send_error: "Transmit failed!",
        popup_engine: "magnific",
        post_id: "293",
        scroll_to_anchor: "1",
        shapes_url: "",
        site_url: "",
        update_location_from_anchor: "0",
        user_logged_in: "0",
        vc_edit_mode: "0"
    };

</script>

<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/functions.js?ver=2.2.1") }}"></script>
<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/scripts.js?ver=5.1") }}"></script>
<script async  type="text/javascript"
        src="{{ asset("assets/front/parkingzone/js/jquery-ui-timepicker-addon.min.js?ver=5.0.1") }}"></script>
<!--<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/trx_addons.js") }}"></script>-->
<script type="text/javascript" src="{{ asset("assets/front/parkingzone/js/superfish.min.js") }}"></script>
<script async type="text/javascript"
        src="{{ asset("assets/front/parkingzone/js/moment.min.js?ver=2.22.2") }}"></script>
<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/bootstrap.min.js") }}"></script>
<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/jstz.min.js?ver=5.0.1") }}"></script>
<script async type="text/javascript"
        src="{{ asset("assets/front/parkingzone/js/booki.1.0.min.js?booki=6.6&amp;ver=5.0.1") }}"></script>
<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/front.js?ver=1545085725") }}"></script>
<script async type="text/javascript" src="{{ asset("assets/front/parkingzone/js/__scripts.js") }}"></script>
<script async src="{{ asset("assets/front/js/bootstrap-datepicker.js") }}"></script>
<script async src="{{ asset("assets/front/js/custom-date-picker.js") }}"></script>
<script async type="text/javascript">

    $(document).ready(function () {
        $("#myNavbar1").css("display","none");
        parkingzone_init_sfmenu(".sf-menu");

        function  parkingzone_init_sfmenu(selector) {
            $(selector).show().each(function () {
                if ($(this).addClass('inited').parents('.menu_mobile').length > 0) return;
                var animation_in = $(this).parent().data('animation_in');
                if (animation_in == undefined) animation_in = "none";
                var animation_out = $(this).parent().data('animation_out');
                if (animation_out == undefined) animation_out = "none";
                $(this).superfish({
                    delay: 500,
                    animation: {opacity: 'show'},
                    animationOut: {opacity: 'hide'},
                    speed: animation_in != 'none' ? 500 : 200,
                    speedOut: animation_out != 'none' ? 500 : 200,
                    autoArrows: false,
                    dropShadows: false,
                    onBeforeShow: function (ul) {
                        if ($(this).parents("ul").length > 1) {
                            var w = $('.page_wrap').width();
                            var par_offset = $(this).parents("ul").offset().left;
                            var par_width = $(this).parents("ul").outerWidth();
                            var ul_width = $(this).outerWidth();
                            if (par_offset + par_width + ul_width > w - 20 && par_offset - ul_width > 0) $(this).addClass('submenu_left'); else $(this).removeClass('submenu_left');
                        }
                        if (animation_in != 'none') {
                            $(this).removeClass('animated fast ' + animation_out);
                            $(this).addClass('animated fast ' + animation_in);
                        }
                    },
                    onBeforeHide: function (ul) {
                        if (animation_out != 'none') {
                            $(this).removeClass('animated fast ' + animation_in);
                            $(this).addClass('animated fast ' + animation_out);
                        }
                    },
                    onShow: function (ul) {
                        if (!$(this).hasClass('layouts_inited')) {
                            $(this).addClass('layouts_inited');
                            $(document).trigger('action.init_hidden_elements', [$(this)]);
                        }
                    }
                });
            });
        }

        $(".icon-cancel").click(function(){

            $("#myNavbar1").css("display","none");
        });



        $(".trx_addons_icon-menu").click(function(){

            $("#myNavbar1").css("display","block");
        });

        // process the form
        $('#subscribe_user').submit(function (event) {

            var formData = {
                'name': $('#subscribe_user_name').val(),
                'email': $('#subscribe_user_email').val(),
                '_token': '{{ @csrf_token() }}'
            };

            // process the form
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '{{ route("subscribe_user") }}', // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true
            })
            // using the done promise callback
                .done(function (data) {

                    // log data to the console so we can see
                    console.log(data);
                    if (data.success == 0) {
                        $("#error_message").html(data.errors);
                        $("#error_message").css("color", "red");
                    } else {
                        $("#error_message").html(data.data);
                    }

                    // here we will handle errors and validation messages
                });

            // stop the form from submitting the normal way and refreshing the page
            event.preventDefault();
        });

    });

</script>
<script async  type="text/javascript">
    $(".accordion-toggle").on('click', function (e) {
        e.preventDefault();
        $($(this).attr("href")).toggleClass('collapse');
        var condition = false;
        if ($($(this).attr("href")).attr("aria-expanded") == false) {
            condition = true;
        }
        $($(this).attr("href")).attr("aria-expanded").toggle(condition);
    });
</script>
<script async  type="text/javascript">
		  var loadDeferredStyles = function() {
			var addStylesNode = document.getElementById("deferred-styles");
			var replacement = document.createElement("div");
			replacement.innerHTML = addStylesNode.textContent;
			document.body.appendChild(replacement)
			addStylesNode.parentElement.removeChild(addStylesNode);
		  };
		  var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
			  window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
		  if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
		  else window.addEventListener('load', loadDeferredStyles);
		</script>
@section("footer-script")

@show
</body>
