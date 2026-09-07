<div id="header-bottom">

    <nav class="navbar navbar-default transparent-nav navbar-custom black-menu" id="mynavbar" style="    padding: 32px">

        <div class="container">

            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar1">

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                </button>

                <a href="{{ route('main') }}"> <img style="height: 50px;"
                        src="{{ asset('theme/images/logo-black.png') }}" alt="Total Travel Solutions" /></a>

                {{-- <a href="#" class="navbar-brand"><span>PARKING</span>ZONE</a> --}}

            </div><!-- end navbar-header -->



            <div class="collapse navbar-collapse" id="myNavbar1">

                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown active"><a href="{{ route('main') }}" class="dropdown-toggle">Home</a></li>







                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Airport
                            Parking<span><i class="fa fa-angle-down"></i></span></a>

                        <ul class="dropdown-menu">

                            <li><a href="{{ route('page', ['slug' => 'gatwick-airport-parking']) }}">Gatwick Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'heathrow-airport-parking']) }}">Heathrow Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'stansted-airport-parking']) }}">Stansted Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'birmingham-airport-parking']) }}">Birmingham Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'edinburgh-airport-parking']) }}">Edinburgh Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'southampton-airport-parking']) }}">Southampton
                                    Airport Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'liverpool-airport-parking']) }}">Liverpool Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'luton-airport-parking']) }}">Luton Airport
                                    Parking</a></li>

                            <li><a href="{{ route('page', ['slug' => 'manchester-airport-parking']) }}">Manchester Airport
                                    Parking</a></li>

                            @if (count($airports) > 9)
                                <li><a style="background: #1d9cbc; text-align:center" href="{{ route('airports') }}">
                                        {{ count($airports) - 9 }} More Choices </a></li>
                            @endif

                        </ul>



                    <li class="dropdown "><a href="{{ route('faqs') }}" class="dropdown-toggle">FAQ</a></li>

                    <li class="dropdown "><a href="{{ route('static_page', ['page' => 'terms-and-conditions']) }}"
                            class="dropdown-toggle">Terms & condition</a></li>

                    {{-- <li class="dropdown "><a href="{{ route("main") }}" class="dropdown-toggle" >Contact us</a></li> --}}

                    <li class="dropdown"><a href="{{ route('reviews') }}" class="dropdown-toggle">Reviews</a></li>



                    </li>





                    {{-- <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Rooms<span><i class="fa fa-angle-down"></i></span></a> --}}

                    {{-- <ul class="dropdown-menu"> --}}

                    {{-- <li><a href="room-listings.html">List View Full Width</a></li> --}}

                    {{-- <li><a href="room-listings-left-sidebar.html">List View Left Sidebar</a></li> --}}

                    {{-- <li><a href="room-listings-right-sidebar.html">List View Right Sidebar</a></li> --}}

                    {{-- <li><a href="room-grid.html">Grid View Full Width</a></li> --}}

                    {{-- <li><a href="room-grid-left-sidebar.html">Grid View Left Sidebar</a></li> --}}

                    {{-- <li><a href="room-grid-right-sidebar.html">Grid View Right Sidebar</a></li> --}}

                    {{-- <li><a href="room-details-left-sidebar.html">Room Details Left Sidebar</a></li> --}}

                    {{-- <li><a href="room-details-right-sidebar.html">Room Details Right Sidebar</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Dining<span><i class="fa fa-angle-down"></i></span></a> --}}

                    {{-- <ul class="dropdown-menu"> --}}

                    {{-- <li><a href="dining-1.html">Dining-1</a></li> --}}

                    {{-- <li><a href="dining-2.html">Dining-2</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Features<span><i class="fa fa-angle-down"></i></span></a> --}}

                    {{-- <ul class="dropdown-menu"> --}}

                    {{-- <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Header</a> --}}

                    {{-- <ul class="dropdown-menu dropdown-sbm right-sbm"> --}}

                    {{-- <li><a href="feature-header-1.html">Header Style 1</a></li> --}}

                    {{-- <li><a href="feature-header-2.html">Header Style 2</a></li> --}}

                    {{-- <li><a href="feature-header-3.html">Header Style 3</a></li> --}}

                    {{-- <li><a href="feature-header-4.html">Header Style 4</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Page Title</a> --}}

                    {{-- <ul class="dropdown-menu dropdown-sbm right-sbm"> --}}

                    {{-- <li><a href="feature-page-cover-1.html">Page Title Style 1</a></li> --}}

                    {{-- <li><a href="feature-page-cover-2.html">Page Title Style 2</a></li> --}}

                    {{-- <li><a href="feature-page-cover-3.html">Page Title Style 3</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Footer</a> --}}

                    {{-- <ul class="dropdown-menu dropdown-sbm right-sbm"> --}}

                    {{-- <li><a href="feature-footer-1.html">Footer Style 1</a></li> --}}

                    {{-- <li><a href="feature-footer-2.html">Footer Style 2</a></li> --}}

                    {{-- <li><a href="feature-footer-3.html">Footer Style 3</a></li> --}}

                    {{-- <li><a href="feature-footer-4.html">Footer Style 4</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Forms</a> --}}

                    {{-- <ul class="dropdown-menu dropdown-sbm right-sbm"> --}}

                    {{-- <li><a href="login-1.html">Login 1</a></li> --}}

                    {{-- <li><a href="login-2.html">Login 2</a></li> --}}

                    {{-- <li><a href="registration-1.html">Registration 1</a></li> --}}

                    {{-- <li><a href="registration-2.html">Registration 2</a></li> --}}

                    {{-- <li><a href="forgot-password-1.html">Forgot Password 1</a></li> --}}

                    {{-- <li><a href="forgot-password-2.html">Forgot Password 2</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages<span><i class="fa fa-angle-down"></i></span></a> --}}

                    {{-- <ul class="dropdown-menu mega-dropdown-menu row"> --}}

                    {{-- <li> --}}

                    {{-- <div class="row"> --}}

                    {{-- <div class="col-sm-4 col-md-3"> --}}

                    {{-- <ul class="list-unstyled"> --}}

                    {{-- <li class="dropdown-header">Standard Pages</li> --}}

                    {{-- <li><a href="about-1.html">About-1</a></li> --}}

                    {{-- <li><a href="about-2.html">About-2</a></li> --}}

                    {{-- <li><a href="services-1.html">Services-1</a></li> --}}

                    {{-- <li><a href="services-2.html">Services-2</a></li> --}}

                    {{-- <li><a href="event-listings.html">Event Listing</a></li> --}}

                    {{-- <li><a href="event-details.html">Event Details</a></li> --}}

                    {{-- <li><a href="blog-listings-left-sidebar.html">Blog Left Sidebar</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </div> --}}



                    {{-- <div class="col-sm-4 col-md-3"> --}}

                    {{-- <ul class="list-unstyled"> --}}

                    {{-- <li><a href="blog-listings-right-sidebar.html">Blog Right Sidebar</a></li> --}}

                    {{-- <li><a href="blog-details-left-sidebar.html">Blog Detail Left Sidebar</a></li> --}}

                    {{-- <li><a href="blog-details-right-sidebar.html">Blog Detail Right Sidebar</a></li> --}}

                    {{-- <li><a href="gallery-2-columns.html">2 Columns Gallery</a></li> --}}

                    {{-- <li><a href="gallery-3-columns.html">3 Columns Gallery</a></li> --}}

                    {{-- <li><a href="gallery-4-columns.html">4 Columns Gallery</a></li> --}}

                    {{-- <li><a href="gallery-masonry.html">Masonry Gallery</a></li> --}}

                    {{-- <li><a href="spa-page.html">Spa</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </div> --}}



                    {{-- <div class="col-sm-4 col-md-3"> --}}

                    {{-- <ul class="list-unstyled"> --}}

                    {{-- <li><a href="team-1.html">Team-1</a></li> --}}

                    {{-- <li><a href="team-2.html">Team-2</a></li> --}}

                    {{-- <li><a href="offers.html">Offers</a></li> --}}

                    {{-- <li><a href="testimonials-1.html">Testimonials-1</a></li> --}}

                    {{-- <li><a href="testimonials-2.html">Testimonials-2</a></li> --}}

                    {{-- <li><a href="testimonials-3.html">Testimonials-3</a></li> --}}

                    {{-- <li><a href="pricing-1.html">Pricing-1</a></li> --}}

                    {{-- <li><a href="pricing-2.html">Pricing-2</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </div> --}}



                    {{-- <div class="col-sm-4 col-md-3"> --}}

                    {{-- <ul class="list-unstyled"> --}}

                    {{-- <li><a href="reservation-left-sidebar.html">Reservation-Left-Sidebar</a></li> --}}

                    {{-- <li><a href="reservation-right-sidebar.html">Reservation-Right-Sidebar</a></li> --}}

                    {{-- <li class="dropdown-header header-2">Special Pages</li> --}}

                    {{-- <li><a href="coming-soon.html">Coming Soon</a></li> --}}

                    {{-- <li><a href="error-page.html">404 Page</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </div> --}}

                    {{-- </div> --}}

                    {{-- </li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Contact<span><i class="fa fa-angle-down"></i></span></a> --}}

                    {{-- <ul class="dropdown-menu"> --}}

                    {{-- <li><a href="contact-1.html">Contact-1</a></li> --}}

                    {{-- <li><a href="contact-2.html">Contact-2</a></li> --}}

                    {{-- </ul> --}}

                    {{-- </li> --}}

                    {{-- <li><a href="reservation-right-sidebar.html">Book Now</a></li> --}}

                </ul>

            </div><!-- end navbar collapse -->

        </div><!-- end container -->

    </nav><!-- end navbar -->

</div><!-- end header-bottom -->
