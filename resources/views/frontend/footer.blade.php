   @php
       $site_settings_main = [];
       $settingsAll = App\Models\settings::all();
       foreach ($settingsAll as $setting) {
           $site_settings_main[$setting->field_name] = $setting->field_value;
       }

   @endphp

   <style>
       #widget_socials_453929481_widget .fa:hover {
           opacity: 1;
           color: #fff;
           background-color: #C2185B;
       }

       .copyright .textwidget {
           margin-top: 15px;
           color: #fff;
       }

       .copyright p {
           color: #fff;
       }

       .contact_info_text:hover {
           color: black !important;
       }
   </style>

   <div class="modal fade" id="myModal" role="dialog">
       <div class="modal-dialog">

           <!-- Modal content-->
           <div class="modal-content">
               <div class="modal-header" style="background-color: #f7a311;color: white;">

                   <centers>
                       <h4 class="modal-title" style=" color: white">You Can Follow Us & Get Discount Code By E Mail</h4>
                       </center>
               </div>
               <div class="modal-body">
                   <center>
                       <h3 id="modal-text"></h3>
                   </center>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-default"
                       style="    color: #fff;background-color: #f7a311;border-color:#ffffff;"
                       data-dismiss="modal">Close</button>
               </div>
           </div>

       </div>
   </div>
   @if (Route::currentRouteName() != 'addBookingForm')
       <!--============== NEWSLETTER ===============-->
       <section id="newsletter" class="banner-padding row" style="
    background-color: #dcdcdc;
    padding: 6%;">
           <div class="container">
               <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="color: #350a4e">
                       <h2>{!! $site_settings_main['homepage_joinus_heading'] !!}</h2>
                       <h3>{!! $site_settings_main['homepage_joinus_subheading'] !!}</h3>
                       <p>{!! $site_settings_main['homepage_joinus_text'] !!}</p>
                       <form id="subscribe_user" action="{{ route('subscribe_user') }}" method="post">
                           @csrf

                           <div class="form-group">

                               <div class="input-group">
                                   <input id="subscribe_user_name" name="name" type="text" style=""
                                       class="form-control input-lg" placeholder="Enter Your Name" required />
                               </div><br>
                               <div class="input-group">
                                   <input id="subscribe_user_email" name="email" type="email"
                                       class="form-control input-lg" placeholder="Your Email Address" required />
                               </div><br>
                               <center> <span class="input-group-btn">

                                       <button id="subscribe_user" class="btn btn-lg" style="background: #C2185B;"><i
                                               class="fa fa-paper-plane"></i> Submit</button></span></center>

                           </div>
                   </div>
                   <div id="error_message"></div>
                   </form>
               </div><!-- end columns -->
           </div><!-- end row -->
           </div><!-- end container -->
       </section><!-- end newsletter -->
   @endif
   <footer class="footer">
       <div class="container">
           <div class="row">

               <!-- Footer Column -->
               <div class="col-lg-3 col-md-6 footer_column">
                   <div class="footer_col">
                       <div class="footer_content footer_about">
                           <div class="logo_container footer_logo">
                               <div class="logo"><a href="#"><img style="width: 175px;"
                                           src="{{ asset('theme/images/logo-white.png') }}" alt="Total Travel Solutions"></a></div>
                           </div>
                           <p class="footer_about_text">{{ $site_settings_main['footer_catch_line'] }}</p>
                           <div class="row copy-right-main">
                               <a href="//www.dmca.com/Protection/Status.aspx?ID=00d7fa62-cad9-46d5-a753-ca722bb5c731"
                                   title="DMCA.com Protection Status" class="dmca-badge"> <img
                                       src ="https://images.dmca.com/Badges/_dmca_premi_badge_5.png?ID=00d7fa62-cad9-46d5-a753-ca722bb5c731"
                                       alt="DMCA.com Protection Status" /></a>
                               <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"></script>

                               <a href="https://www.dmca.com/compliance/www.totaltravelsolutions.co.uk"
                                   title="DMCA Compliance information for www.totaltravelsolutions.co.uk"><img width="80"
                                       style="    margin-left: 8px;" src="{{ asset('public/assets/dmca.png') }}" /></a>

                           </div>
                       </div>
                   </div>
               </div>

               <!-- Footer Column -->
               <div class="col-lg-3 col-md-6 footer_column">
                   <div class="footer_col">
                       <div class="footer_title">AIRPORTS</div>
                       <br>
                       <div class="menu-navigation-container">
                           <ul>
                               <li id="" class=" contact_info_text  menu-item-332">
                                   <a href="{{ route('page', ['slug' => 'gatwick-airport-parking']) }}">Gatwick
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  ">
                                   <a href="{{ route('page', ['slug' => 'heathrow-airport-parking']) }}">Heathrow
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-334">
                                   <a href="{{ route('page', ['slug' => 'stansted-airport-parking']) }}">Stansted
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-334">
                                   <a href="{{ route('page', ['slug' => 'birmingham-airport-parking']) }}">Birmingham
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'edinburgh-airport-parking']) }}">Edinburgh
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-621">
                                   <a href="{{ route('page', ['slug' => 'southampton-airport-parking']) }}">Southampton
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'liverpool-airport-parking']) }}">Liverpool
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'luton-airport-parking']) }}">Luton
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'manchester-airport-parking']) }}">Manchester
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'glasgow-airport-parking']) }}">Glasgow
                                       Airport Parking</a>
                               </li>
                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'bristol-airport-parking']) }}">Bristol
                                       Airport Parking</a>
                               </li>

                               <li id="" class=" contact_info_text  menu-item-622">
                                   <a href="{{ route('page', ['slug' => 'east-midlandsairport-parking']) }}">East
                                       Midlands Airport Parking</a>
                               </li>
                           </ul>
                       </div>
                   </div>
               </div>

               <!-- Footer Column -->
               <div class="col-lg-3 col-md-6 footer_column">
                   <div class="footer_col">
                       <div class="footer_title"> Navigation</div>
                       <br>
                       <div class="menu-navigation-container">
                           <ul>
                               <li id="menu-item-332" class="contact_info_text menu-item-332">
                                   <a href="{{ route('main') }}">Home</a>
                               </li>
                               <li id="" class="contact_info_text menu-item-340">
                                   <a href="{{ url('about-us') }}">About Us</a>
                               </li>
                               <li id="" class="contact_info_text menu-item-340">
                                   <a href="{{ route('faqs') }}">FAQs</a>
                               </li>
                               <!--<li id="menu-item-333"-->
                               <!--    class="contact_info_text menu-item-333">-->
                               <!--    <a href="{{ route('static_page', ['slug' => 'about-us']) }}">About</a>-->
                               <!--</li>-->
                               <li id="menu-item-334" class="contact_info_text menu-item-334">
                                   <a href="{{ route('support') }}">Help Desk</a>
                               </li>
                               <li id="menu-item-621" class="contact_info_text menu-item-621">
                                   <a href="{{ route('static_page', ['page' => 'privacy-policy']) }}">Privacy
                                       Policy</a>
                               </li>
                               <li id="menu-item-622" class="contact_info_text menu-item-622">
                                   <a href="{{ route('static_page', ['page' => 'site-security']) }}">Site
                                       Security</a>
                               </li>


                               <li id="menu-item-621" class="contact_info_text menu-item-621">
                                   <a href="{{ route('airports') }}">All Airports</a>
                               </li>


                               {{-- <li id="menu-item-621"
                                                                class="contact_info_text menu-item-621">
                                                                <a>Rate Us</a></li> --}}
                               <!--<li id="menu-item-622"-->
                               <!--    class="contact_info_text menu-item-622">-->
                               <!--    <a href="{{ url('/') }}/blog">Blog</a>-->
                               <!--</li>-->
                           </ul>
                       </div>







                   </div>
               </div>

               <!-- Footer Column -->
               <div class="col-lg-3 col-md-6 footer_column">
                   <div class="footer_col">
                       <div class="footer_title">Other Pages</div>
                       <br>
                       <div class="menu-navigation-container">
                           <ul>
                               <li id="" class="contact_info_text menu-item-339">
                                   <a href="{{ route('static_page', ['page' => 'affiliates']) }}">Affiliate </a>
                               </li>
                               <li id="" class="contact_info_text menu-item-338">
                                   <a href="{{ route('static_page', ['page' => 'cookies']) }}">Cookies</a>
                               </li>

                               <li id="" class="contact_info_text menu-item-339">
                                   <a href="{{ route('sitemap') }}">Site Map </a>
                               </li>

                               {{-- <li id="" --}}
                               {{-- class="contact_info_text menu-item-340"> --}}
                               {{-- <a href="{{ route("reviews") }}">Reviews</a></li> --}}
                               <li id="" class="contact_info_text menu-item-340">
                                   <a href="{{ route('airport_guide') }}">Airport Guide</a>
                               </li>
                               <li id="" class="contact_info_text menu-item-340">
                                   <a href="{{ route('static_page', ['page' => 'terms-and-conditions']) }}">Terms
                                       & Conditions</a>
                               </li>

                           </ul>
                       </div>


                       <div class="social-icons">
                           <aside id="widget_socials_453929481_widget" class="widget widget_socials">
                               <div class="socials_wrap sc_align_left">
                                   @if (array_key_exists('twitter', $site_settings_main) &&
                                           $site_settings_main['twitter'] != '' &&
                                           $site_settings_main['twitter_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['twitter'] }}"
                                           class="fa fa-twitter"></a>
                                   @endif
                                   @if (array_key_exists('google_plus', $site_settings_main) &&
                                           $site_settings_main['google_plus'] != '' &&
                                           $site_settings_main['google_plus_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['google_plus'] }}"
                                           class="fa fa-google_plus"></a>
                                   @endif
                                   @if (array_key_exists('instagram', $site_settings_main) &&
                                           $site_settings_main['instagram'] != '' &&
                                           $site_settings_main['instagram_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['instagram'] }}"
                                           class="fa fa-instagram"></a>
                                   @endif
                                   @if (array_key_exists('youtube', $site_settings_main) &&
                                           $site_settings_main['youtube'] != '' &&
                                           $site_settings_main['youtube_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['youtube'] }}"
                                           class="fa fa-youtube"></a>
                                   @endif
                                   @if (array_key_exists('pinterest', $site_settings_main) &&
                                           $site_settings_main['pinterest'] != '' &&
                                           $site_settings_main['pinterest_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['pinterest'] }}"
                                           class="fa fa-pinterest"></a>
                                   @endif
                                   @if (array_key_exists('linkedin', $site_settings_main) &&
                                           $site_settings_main['linkedin'] != '' &&
                                           $site_settings_main['linkedin_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['linkedin'] }}"
                                           class="fa fa-linkedin"></a>
                                   @endif
                                   @if (array_key_exists('facebook', $site_settings_main) &&
                                           $site_settings_main['facebook'] != '' &&
                                           $site_settings_main['facebook_status'] == 'active')
                                       <a target="_blank" href="{{ $site_settings_main['facebook'] }}"
                                           class="fa fa-facebook"></a>
                                   @endif
                               </div>
                           </aside>
                       </div>
                       <br>
                       <p class="footer_title" style="font-weight: 500; text-transform: none;">Email: <a
                               href="mail:bookings@parkingzone.co.uk">bookings@parkingzone.co.uk</a></p>
                       <p class="footer_title" style="font-weight: 500; text-transform: none;">Phone: <a
                               href="tel:+442045114174"> 020 4511 4171</a></p>

                   </div>
               </div>

           </div>
       </div>
   </footer>

   <!-- Copyright -->

   <div class="copyright">
       <div class="container" style="text-align: center;">

           <div class="textwidget">
               <span>
                   @if ($site_settings_main['footer_address'] != '')
                       <i class="fa fa-map-marker"
                           aria-hidden="true"></i>&nbsp{{ $site_settings_main['footer_address'] }}
                   @endif
                   @if ($site_settings_main['footer_email'] != '')
                       <i class="fa fa-envelope"
                           aria-hidden="true"></i>&nbsp{{ $site_settings_main['footer_email'] }}
                   @endif
                   &nbsp
                   <!--   @if ($site_settings_main['footer_phone_no'] != '')
<i class="fa fa-phone" aria-hidden="true"></i>&nbsp{{ $site_settings_main['footer_phone_no'] }}
@endif -->
               </span>
               <p><a href="{{ route('main') }}">Total Travel Solutions</a>{{ $site_settings_main['footer_copyright'] }} <span
                       class=""> {{ $site_settings_main['footer_company_reg_no'] }}</span>
               </p>
           </div>

       </div>
   </div>

   </div>
   <style>
       .rChev,
       .lChev {
           z-index: 10000 !important;
       }

       .select2-container {
           z-index: 9999 !important;
           /* Ensure it appears above the modal */
       }
   </style>
   <script src="{{ asset('theme/js/jquery-3.2.1.min.js') }}"></script>
   <script src="{{ asset('theme/styles/bootstrap4/popper.js') }}" defer></script>
   <script src="{{ asset('theme/styles/bootstrap4/bootstrap.min.js') }}" defer></script>
   <script src="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}" defer></script>
   <script src="{{ asset('theme/plugins/easing/easing.js') }}" defer></script>
   <script src="{{ asset('theme/js/custom.js') }}" defer></script>

   <script type='text/javascript'>
       window.__lo_site_id = 248579;

       (function() {
           var wa = document.createElement('script');
           wa.type = 'text/javascript';
           wa.async = true;
           wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
           var s = document.getElementsByTagName('script')[0];
           s.parentNode.insertBefore(wa, s);
       })();
   </script>



   <!--<script async type="text/javascript" src="{{ asset('assets/front/parkingzone/js/trx_addons.js') }}"></script>-->

   <!--<script async src="{{ asset('assets/front/js/bootstrap-datepicker.js') }}"></script>-->
   <!--<script async src="{{ asset('assets/front/js/custom-date-picker.js') }}"></script>-->
   <script src="{{ asset('theme/tinyCalender/index.js') }}"></script>
   @if (substr(strrchr(url()->current(), '/'), 1) != 'result')
       <script>
           var enddate = new Date();
           enddate.setDate(enddate.getDate() + 8);
           new TinyPicker({
               format: 'dd-mm-yyyy',
               firstBox: document.getElementById('startDate'), // Required -- Overrides us finding the first input box
               lastBox: document.getElementById('endDate'), // Required -- Overrides us finding the last input box
               startDate: new Date(), // Needs to be a valid instance of Date
               endDate: enddate, // Needs to be a valid instance of Date
               allowPast: false, // If you want the user to be able to select past dates
               useCache: true,
               orientation: "top auto",
               horizontal: 'auto',
               vertical: 'auto'
           }).init();
       </script>
   @else
       @php

           $dropdate = str_replace('/', '-', request()->dropoffdate);
           $pickdate = str_replace('/', '-', request()->departure_date);

           $dropofdate = date('m/d/Y', strtotime($dropdate));
           $pickupdate = date('m/d/Y', strtotime($pickdate));

       @endphp
       <script>
           $(document).ajaxStop(function() {
               //var dropDate = '{{ request()->dropoffdate }}';
               //var departureDate = '{{ request()->departure_date }}';


               var dropDate = '{{ $dropofdate }}';
               var departureDate = '{{ $pickupdate }}';


               var enddate = new Date(departureDate);
               // enddate.setDate(enddate);
               new TinyPicker({
                   firstBox: document.getElementById(
                       'startDate'), // Required -- Overrides us finding the first input box
                   lastBox: document.getElementById(
                       'endDate'), // Required -- Overrides us finding the last input box
                   startDate: new Date(dropDate), // Needs to be a valid instance of Date
                   endDate: enddate, // Needs to be a valid instance of Date
                   allowPast: false, // If you want the user to be able to select past dates
                   useCache: true,
                   orientation: "top auto",
                   horizontal: 'auto',
                   success: function(startDate, endDate) {}, // callback function when user inputs dates,
                   vertical: 'auto'
               }).init();
           });
       </script>
   @endif

   <script async type="text/javascript">
       $(document).ready(function() {



           // process the form
           $('#subscribe_user').submit(function(event) {

               var formData = {
                   'name': $('#subscribe_user_name').val(),
                   'email': $('#subscribe_user_email').val(),
                   '_token': '{{ @csrf_token() }}'
               };

               // process the form
               $.ajax({
                       type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                       url: '{{ route('subscribe_user') }}', // the url where we want to POST
                       data: formData, // our data object
                       dataType: 'json', // what type of data do we expect back from the server
                       encode: true
                   })
                   // using the done promise callback
                   .done(function(data) {


                       // log data to the console so we can see
                       console.log(data);
                       if (data.success == 0) {
                           if (data.errors == 'validation.unique') {


                               $("#modal-text").html('This email already subscribed');
                               $("#modal-text").css("color", "red");
                               $('#myModal').modal('show');
                           } else {

                               $("#modal-text").html(data.errors);
                               $("#modal-text").css("color", "red");
                               $('#myModal').modal('show');
                           }
                           //  $("#error_message").html(data.errors);
                           // $("#error_message").css("color", "red");
                       } else {
                           $("#modal-text").html(data.data);
                           // alert(data.data);
                           $('#myModal').modal('show');
                       }

                       // here we will handle errors and validation messages
                   });

               // stop the form from submitting the normal way and refreshing the page
               event.preventDefault();
           });

       });
   </script>

   <script async type="text/javascript">
       $(".accordion-toggle").on('click', function(e) {
           e.preventDefault();
           $($(this).attr("href")).toggleClass('collapse');
           var condition = false;
           if ($($(this).attr("href")).attr("aria-expanded") == false) {
               condition = true;
           }
       });

       // $(function() {
       //     $("#startDate").datepicker({
       //         numberOfMonths: 1
       //     });
       // });
       $(document).mouseleave(function() {
           console.log('out');
       });
   </script>

   @section('footer-script')

   @show


   <!-- paid on result -->
   <script language="JavaScript" src="//porjs.com/1747.js"></script>


   </body>

   </html>
