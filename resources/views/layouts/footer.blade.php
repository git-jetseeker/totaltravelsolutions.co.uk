@php

    $site_settings_main = [];

    $settingsAll = App\Models\settings::all();

    foreach ($settingsAll as $setting) {
        if ($setting->agent_id == '9') {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }

@endphp



<style>
    #widget_socials_453929481_widget .fa:hover {
        opacity: 1;
        color: #fff;
        background-color: var(--js-primary);
    }
</style>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--js-accent); color: white;">
                <center><h4 class="modal-title" style="color: white">You Can Follow Us & Get Discount Code By E Mail</h4></center>
            </div>
            <div class="modal-body">
                <center><h3 id="modal-text"></h3></center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="color: #fff; background-color: var(--js-accent); border-color:#ffffff;" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if (Route::currentRouteName() != 'addBookingForm')
@endif

<footer class="js-footer" role="contentinfo">
    <div class="js-container">
        <div class="js-footer__grid">
            {{-- Column 1: Brand --}}
            <div>
                <div class="js-footer__logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('theme/images/logo-white.png') }}" loading="lazy" alt="Total Travel Solutions logo" width="300" height="88">
                    </a>
                </div>
                <p class="js-footer__about">{{ $site_settings_main['footer_catch_line'] ?? '' }}</p>
            </div>

            {{-- Column 2: Quick Links --}}
            <div>
                <h3 class="js-footer__title">Quick Links</h3>
                <ul class="js-footer__links">
                    <li><a href="{{ route('main') }}">Home</a></li>
                    <li><a href="{{ route('about-us') }}">About Us</a></li>
                    <li><a href="{{ url('parking-services') }}">Parking Services</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                    <li><a href="{{ route('support') }}">Customer Support</a></li>
                    <li><a href="{{ route('manage_booking') }}">Manage Booking</a></li>
                </ul>
            </div>

            {{-- Column 3: Airport Parking --}}
            <div>
                <h3 class="js-footer__title">Airport Parking</h3>
                <ul class="js-footer__links">
                    <li><a href="{{ route('page', ['slug' => 'heathrow-airport-parking']) }}">Heathrow Airport Parking</a></li>
                    <li><a href="{{ route('page', ['slug' => 'gatwick-airport-parking']) }}">Gatwick Airport Parking</a></li>
                    <li><a href="{{ route('page', ['slug' => 'manchester-airport-parking']) }}">Manchester Airport Parking</a></li>
                    <li><a href="{{ route('page', ['slug' => 'stansted-airport-parking']) }}">Stansted Airport Parking</a></li>
                    <li><a href="{{ route('page', ['slug' => 'luton-airport-parking']) }}">Luton Airport Parking</a></li>
                    <li><a href="{{ route('airports') }}">All Airports</a></li>
                </ul>
            </div>

            {{-- Column 4: Support & Legal --}}
            <div>
                <h3 class="js-footer__title">Support & Legal</h3>
                <ul class="js-footer__links">
                    <li><a href="{{ route('support') }}">Customer Support</a></li>
                    <li><a href="{{ route('static_page', ['page' => 'terms-and-conditions']) }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('static_page', ['page' => 'privacy-policy']) }}">Privacy Policy</a></li>
                    <li><a href="{{ route('cookies') }}">Cookie Policy</a></li>
                </ul>

                <div class="js-footer__contact js-mt-24">
                    @if (!empty($site_settings_main['footer_email']))
                        <p><i class="fa fa-envelope-o" aria-hidden="true"></i>
                            <a href="mailto:{{ $site_settings_main['footer_email'] }}">{{ $site_settings_main['footer_email'] }}</a>
                        </p>
                    @endif
                    @if (!empty($site_settings_main['footer_phone_no']))
                        <p><i class="fa fa-phone" aria-hidden="true"></i>
                            <a href="tel:{{ $site_settings_main['footer_phone_no'] }}">{{ $site_settings_main['footer_phone_no'] }}</a>
                        </p>
                    @endif
                </div>

                <div class="js-footer__social">
                    @if (array_key_exists('twitter', $site_settings_main) && $site_settings_main['twitter'] != '' && ($site_settings_main['twitter_status'] ?? '') == 'active')
                        <a target="_blank" rel="noopener" aria-label="Visit our Twitter page" href="{{ $site_settings_main['twitter'] }}"><i class="fa fa-twitter"></i></a>
                    @endif
                    @if (array_key_exists('facebook', $site_settings_main) && $site_settings_main['facebook'] != '' && ($site_settings_main['facebook_status'] ?? '') == 'active')
                        <a target="_blank" rel="noopener" aria-label="Visit our Facebook page" href="{{ $site_settings_main['facebook'] }}"><i class="fa fa-facebook"></i></a>
                    @endif
                    @if (array_key_exists('instagram', $site_settings_main) && $site_settings_main['instagram'] != '' && ($site_settings_main['instagram_status'] ?? '') == 'active')
                        <a target="_blank" rel="noopener" aria-label="Visit our Instagram page" href="{{ $site_settings_main['instagram'] }}"><i class="fa fa-instagram"></i></a>
                    @endif
                    @if (array_key_exists('linkedin', $site_settings_main) && $site_settings_main['linkedin'] != '' && ($site_settings_main['linkedin_status'] ?? '') == 'active')
                        <a target="_blank" rel="noopener" aria-label="Visit our LinkedIn page" href="{{ $site_settings_main['linkedin'] }}"><i class="fa fa-linkedin"></i></a>
                    @endif
                    @if (array_key_exists('youtube', $site_settings_main) && $site_settings_main['youtube'] != '' && ($site_settings_main['youtube_status'] ?? '') == 'active')
                        <a target="_blank" rel="noopener" aria-label="Visit our YouTube page" href="{{ $site_settings_main['youtube'] }}"><i class="fa fa-youtube"></i></a>
                    @endif
                </div>

                <p class="js-footer__title js-mt-24">We Accept</p>
                <div class="js-footer__payments">
                    <img src="{{ asset('theme/images/mgr-vise.png') }}" alt="Visa" height="28" width="48" loading="lazy">
                    <img src="{{ asset('theme/images/mgr-jcb.png') }}" alt="JCB" height="28" width="48" loading="lazy">
                    <img src="{{ asset('theme/images/mgr-master.png') }}" alt="Mastercard" height="28" width="48" loading="lazy">
                    <img src="{{ asset('theme/images/mgr-mastro.png') }}" alt="Maestro" height="28" width="48" loading="lazy">
                </div>

                <div class="js-mt-24" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                    <a href="//www.dmca.com/Protection/Status.aspx?ID=00d7fa62-cad9-46d5-a753-ca722bb5c731" title="DMCA.com Protection Status" class="dmca-badge">
                        <img src="https://images.dmca.com/Badges/_dmca_premi_badge_5.png?ID=00d7fa62-cad9-46d5-a753-ca722bb5c731" loading="lazy" alt="DMCA.com Protection Status" height="40">
                    </a>
                    <a href="https://www.dmca.com/compliance/www.totaltravelsolutions.co.uk" title="DMCA Compliance information">
                        <img width="80" height="40" alt="DMCA compliance" src="{{ asset('assets/dmca.png') }}" loading="lazy">
                    </a>
                </div>
            </div>
        </div>

        <div class="js-footer__bottom">
            <p class="js-mb-0">
                &copy; {{ date('Y') }} {{ $site_settings_main['footer_copyright'] ?? 'Total Travel Solutions' }}
                @if (!empty($site_settings_main['footer_company_reg_no']))
                    <span>{{ $site_settings_main['footer_company_reg_no'] }}</span>
                @endif
            </p>
        </div>
    </div>
</footer>

</div>

<style>
    .rChev,
    .day,
    .lChev {

        z-index: 10000 !important;

    }

    .ui-state-default:not(.ui-state-active) {
        text-align: center !important;
        /*background: #faebd7 !important;*/
        background: #fff;
        border: 0 !important;
    }

    .ui-datepicker-next,
    .ui-datepicker-prev {
        cursor: pointer;
        background: var(--js-primary, #C2185B);
    }

    .ui-datepicker-next-hover:hover,
    .ui-datepicker-prev:hover {
        background: var(--js-primary-dark, #1A1A1A);
        border: 0;
    }

    .ui-datepicker-calendar thead {
        background: var(--js-primary, #C2185B);
        color: #fff;
    }

    .ui-datepicker th {
        padding: 0.4em;
    }

    .ui-datepicker {
        background: #fff;
    }

    .ui-state-default.ui-state-active {
        background: var(--js-primary, #C2185B);
        color: #fff;
        text-align: center;
        border: 0;
        border-radius: 8px;
    }

    .ui-state-default.ui-state-active:hover {
        background: var(--js-primary, #C2185B) !important;
    }

    .ui-state-default:hover {
        background: var(--js-primary, #C2185B) !important;
        border-color: var(--js-primary, #C2185B) !important;
        color: #fff !important;
        border-radius: 8px;
    }

    .ui-datepicker td {
        padding: 0px;
        border: 0;
    }
</style>

{{-- <link href="{{ asset('theme/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet"

    type="text/css"> --}}

<!-- <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/responsive.css') }}"> -->





<script src="{{ asset('theme/js/jquery-3.2.1.min.js') }}"></script>

<script defer src="{{ asset('theme/styles/bootstrap4/popper.js') }}"></script>

<script defer src="{{ asset('theme/styles/bootstrap4/bootstrap.min.js') }}"></script>

@php $isBookingCheckout = in_array(Route::currentRouteName(), ['addBookingForm', 'addBookingForm2'], true); @endphp

@if (!$isBookingCheckout)
<script src="{{ asset('theme/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>

<script src="{{ asset('theme/plugins/easing/easing.js') }}"></script>

<script src="{{ asset('theme/js/custom.js') }}"></script>
<script src="{{ asset('theme/js/jetseeker-ui.js?v=20251023') }}"></script>

<script src="{{ asset('assets/front/js/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('assets/front/js/custom-date-picker.js') }}"></script>
@endif



<!--script type='text/javascript'>

window.__lo_site_id = 248579;



 (function() {

  var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;

  wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';

  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);

 })();

</script-->







{{-- <script async type="text/javascript" src="{{ asset('assets/front/parkingzone/js/trx_addons.js') }}"></script> --}}



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
@if (!$isBookingCheckout)
<!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->

<!-- <script src="{{ asset('theme/tinyCalender/index.js') }}"></script> -->
<script src="{{ asset('theme/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('theme/js/jetseeker-search.js?v=20251018') }}"></script>
@else
<script src="{{ asset('theme/js/jquery-ui.min.js') }}"></script>
@endif
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
@if (!$isBookingCheckout)
<script>
    $(document).ready(function () {
        // Hotel form (guest picker) — datepickers init via jetseeker-search.js when Hotels tab opens
        if (typeof window.initJsHotelSearchForm === 'function') {
            window.initJsHotelSearchForm(jQuery);
        }

        if (window.JetseekerSearch && typeof window.JetseekerSearch.initHotelDatepickers === 'function') {
            var $widget = $('[data-js-booking-widget]');
            var onHotelsTab = $widget.length && ($widget.attr('data-active') === 'hotels' || window.location.hash === '#hotels');
            // Also init on hotel results amend form (no booking widget tab).
            if (onHotelsTab || $('#hotel_checkin_date').length) {
                window.JetseekerSearch.initHotelDatepickers();
            }
        }

@if(session('open_booking_tab') === 'hotels' || $errors->has('hotel_checkin_date') || $errors->has('hotel_checkout_date') || $errors->has('hotel_room_type') || $errors->has('hotel_search'))
        if (window.JetseekerSearch && $('[data-js-booking-widget]').length) {
            $('[data-js-booking-widget]').find('.js-product-tab[data-product="hotels"]').trigger('click');
        }
@endif

        // Lounge flight time sync
        function syncLoungeFlightTime(force) {
            var checkIn = $('#lounge_checkIn_time').val() || '09:00';
            var parts = checkIn.split(':');
            var hours = (parseInt(parts[0], 10) || 0);
            var minutes = parseInt(parts[1], 10) || 0;
            hours = (hours + 4) % 24;
            var flight = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
            var $end = $('#lounge_flight_time');
            if (!$end.length) {
                return;
            }
            if (force || !$end.data('user-changed')) {
                $end.val(flight);
                if ($end.hasClass('select2-hidden-accessible')) {
                    $end.trigger('change.select2');
                }
            }
        }

        if ($('#lounge_checkIn_time').length) {
            syncLoungeFlightTime(true);

            $('#lounge_checkIn_time').on('change', function () {
                $('#lounge_flight_time').data('user-changed', false);
                syncLoungeFlightTime(true);
            });

            $('#lounge_flight_time').on('change', function () {
                $(this).data('user-changed', true);
            });

            $('#search_form_lounge').on('submit', function () {
                syncLoungeFlightTime(false);
                $('#lounge_submit').text('Please Wait..');
            });
        }
        });
    </script>
@if (substr(strrchr(url()->current(), '/'), 1) == 'result')
    @php
        $dropdate = str_replace('/', '-', request()->dropoffdate);
        $pickdate = str_replace('/', '-', request()->departure_date);
        $dropofdate = date('m/d/Y', strtotime($dropdate));
        $pickupdate = date('m/d/Y', strtotime($pickdate));
    @endphp

    <script>
        $(document).ready(function() {
            function initResultsAmendDatepickers() {
                if (window.JetseekerSearch && typeof window.JetseekerSearch.initResultPageDatepickers === 'function') {
                    window.JetseekerSearch.initResultPageDatepickers();
                }
            }

            var amendPanel = document.getElementById('js-results-amend-panel');
            if (amendPanel && amendPanel.classList.contains('is-open')) {
                initResultsAmendDatepickers();
            }

            $(document).on('click', '#js-results-amend-toggle, #js-results-meta-edit', function() {
                setTimeout(initResultsAmendDatepickers, 350);
            });
        });
    </script>
@endif
@endif
@if (!$isBookingCheckout)



<script async type="text/javascript">
    //     $('body').on('click', '.rChev, .lChev, .dpd1, .dpd2', function(){
    //         $('.day').each(function() {
    //             if (!$(this).hasClass('disb') && $(this).hasClass('active')) {
    //                 $(this).addClass('inBtw');
    //             }
    //         });
    //     });
    //
</script>


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


    });
</script>

@endif

@section('footer-script')



@show





<!-- paid on result -->

{{-- <script language="JavaScript" src="//porjs.com/1747.js"></script> --}}



<script>
    jQuery.event.special.touchstart = {

        setup: function(_, ns, handle) {

            this.addEventListener("touchstart", handle, {

                passive: !ns.includes("noPreventDefault")

            });

        }

    };

    jQuery.event.special.touchmove = {

        setup: function(_, ns, handle) {

            this.addEventListener("touchmove", handle, {

                passive: !ns.includes("noPreventDefault")

            });

        }

    };

    jQuery.event.special.wheel = {

        setup: function(_, ns, handle) {

            this.addEventListener("wheel", handle, {

                passive: true

            });

        }

    };

    jQuery.event.special.mousewheel = {

        setup: function(_, ns, handle) {

            this.addEventListener("mousewheel", handle, {

                passive: true

            });

        }

    };
</script>

<script>
    $(document).ready(function() {

        //  $('#search-box').on('keyup', function () {

        //      var given_name = $(this).val();

        //      $.ajax({

        //          url: '{{ url('get_location_suggestion') }}',

        //          type: 'POST',

        //          dataType: 'html',

        //          data:'keyword='+given_name

        //      }).success(function (data) {

        //         $("#suggesstion-box").show();

        //          $("#suggesstion-box").html(data);

        //          $("#search-box").css("background","transparent");

        //      });

        //  })

        $("#search-box").keyup(function(e) {

            setTimeout(getSuggestion($(this).val()), 500);

        });



        function getSuggestion(keyword) {

            $("#loc_type").val('');

            $("#loc_code").val('');

            $("#loc_name").val('');

            $("#loc_lat").val('');

            $("#loc_long").val('');

            $("#loc_country").val('');

            $("#loc_id").val('');



            var formData = {

                'keyword': keyword

            };

            $.ajax({

                type: "POST",

                url: '{{ url('get_location_suggestion') }}',

                data: formData,

                beforeSend: function() {

                    $("#search-box").css("background",

                        "#FFF url({{ asset('theme/images/placeholder.svg') }}) no-repeat 110px"

                    );

                },

                success: function(data) {

                    $("#suggesstion-box").show();

                    $("#suggesstion-box").html(data);

                    $("#search-box").css("background", "#FFF");

                }

            });

        }



        $("#search-box-dropoff").keyup(function(e) {

            setTimeout(getSuggestionDropoff($(this).val()), 500);

        });



        function getSuggestionDropoff(keyword) {

            $("#loc_type_drop").val('');

            $("#loc_code_drop").val('');

            $("#loc_name_drop").val('');

            $("#loc_lat_drop").val('');

            $("#loc_long_drop").val('');

            $("#loc_country_drop").val('');

            $("#loc_id_drop").val('');



            var formData = {

                'keyword': keyword,

                'loc_type': $("#loc_type").val()

            };

            $.ajax({

                type: "POST",

                url: '{{ url('get_location_suggestion_drop') }}',

                data: formData,

                beforeSend: function() {

                    $("#search-box-drop").css("background",

                        "#FFF url({{ asset('theme/images/placeholder.svg') }}) no-repeat 110px"

                    );

                },

                success: function(data) {

                    $("#suggesstion-box-drop").show();

                    $("#suggesstion-box-drop").html(data);

                    $("#search-box-drop").css("background", "#FFF");

                }

            });

        }

    });



    function selectRegion(loc_type, loc_name, loc_code, loc_lat, loc_long, loc_country, loc_id) {

        $("#search-box").val(loc_name);

        $("#loc_type").val(loc_type);

        $("#loc_code").val(loc_code);

        $("#loc_name").val(loc_name);

        $("#loc_lat").val(loc_lat);

        $("#loc_long").val(loc_long);

        $("#loc_country").val(loc_country);

        $("#loc_id").val(loc_id);



        $("#suggesstion-box").hide();

    }



    function selectHotel(loc_type, loc_name, loc_code, loc_lat, loc_long) {

        $("#search-box").val(loc_name);

        $("#loc_type").val(loc_type);

        $("#loc_code").val(loc_code);

        $("#loc_name").val(loc_name);

        $("#loc_lat").val(loc_lat);

        $("#loc_long").val(loc_long);

        $("#loc_country").val('');

        $("#loc_id").val('');



        $("#suggesstion-box").hide();

    }



    function selectRegionDrop(loc_type, loc_name, loc_code, loc_lat, loc_long, loc_country, loc_id) {

        $("#search-box-dropoff").val(loc_name);

        $("#loc_type_drop").val(loc_type);

        $("#loc_code_drop").val(loc_code);

        $("#loc_name_drop").val(loc_name);

        $("#loc_lat_drop").val(loc_lat);

        $("#loc_long_drop").val(loc_long);

        $("#loc_country_drop").val(loc_country);

        $("#loc_id_drop").val(loc_id);



        $("#suggesstion-box-drop").hide();

    }



    function selectHotelDrop(loc_type, loc_name, loc_code, loc_lat, loc_long) {

        $("#search-box-dropoff").val(loc_name);

        $("#loc_type_drop").val(loc_type);

        $("#loc_code_drop").val(loc_code);

        $("#loc_name_drop").val(loc_name);

        $("#loc_lat_drop").val(loc_lat);

        $("#loc_long_drop").val(loc_long);

        $("#loc_country_drop").val('');

        $("#loc_id_drop").val('');



        $("#suggesstion-box-drop").hide();

    }
</script>





</body>



</html>
