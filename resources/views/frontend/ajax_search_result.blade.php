<?php

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;



?>

{{--

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/datepicker.css') }}" media="all">

--}}

<section id="lounge-sec" class="js-results-cards-section">
    <div class="js-apb-results-list" id="skip-b">

            @php

                $booking_fee = DB::table('settings')->where('field_name', 'booking_fee')->first();

                $booking_fee = $booking_fee->field_value;

            @endphp

            @if ($companies)

            @else

                <h3 class="h3-css" style="color: #C2185B;"><b style="padding-bottom: 20%">No Result Found</b></h3>

            @endif

            @php

                $no_of_days = $no_of_days + 1;

            @endphp

            @php
                $fixModalEncoding = function ($html) {
                    if (!is_string($html) || $html === '') {
                        return '';
                    }

                    return str_replace(
                        ['â€¢', 'â€"', 'â€"', 'â€™', 'â€œ', 'â€', 'Â·'],
                        ['•', '–', '—', "'", '"', '"', '•'],
                        $html
                    );
                };
            @endphp

            @foreach ($companies as $index => $company)

                @php

                    $facCheck = 0;

                    $logo = 0;

                    $discount_amount = 0;

                    if ($company->companyID != null) {

                        $facilities = \App\Models\Company::find($company->companyID)->facilities->take(4);

                    }

                    // dd($facilities); for 30 days .. we add 3000 for own aph

                    if ($no_of_days > 30000) {

                        $after30Days = $company->after_30_days;

                        // Ensure that $company->price is numeric



                        $company->price = str_replace(',', '', $company->price); // "1105"

                        $company_price = (float) $company->price ?? 0; // 1105.0



                        $base_price = $company_price ?? 0;

                        $after30Days = is_numeric($after30Days) ? (float) $after30Days : 0;

                        // Format base price

                        $booking_price = number_format($base_price, 2, '.', '');

                        // Calculate the booking price for days over 30

                        $booking_price = $booking_price + $after30Days * ($no_of_days - 30);

                        // Format the final booking price

                        $booking_price = number_format($booking_price, 2, '.', '');

                    } else {

                        // Ensure that $company->price is numeric

                        $company->price = str_replace(',', '', $company->price); // "1105"

                        $company_price = (float) $company->price ?? 0; // 1105.0



                        $base_price = $company_price ?? 0;

                        // Format the booking price

                        $booking_price = number_format($base_price, 2, '.', '');

                    }



                    $booking_price = $booking_price;

                    $parking_total = $booking_price;

                    $traffic_src = session()->get('bk_src', 'ORG');

                    if ($traffic_src == 'ORG') {

                        // Increase the total price by 6%

                        $booking_price = $booking_price * 1;

                    }

                    $adjusted_price = $booking_price;

                    //dd($promo);

                    if ($promo != '') {

                        $dis = new \App\Models\discounts();

                        $promo_verify = $dis->varifyPromoCode($promo);

                        if ($promo_verify == 'Verify') {

                            if (isset($company->park_api) && isset($company->EA)) {

                                if (

                                    $company->park_api == 'holiday' ||

                                    $company->park_api == 'aph' ||

                                    $company->park_api == 'a2z'

                                ) {

                                    $bookingPrice = $company->price - $company->EA;

                                    $booking_fee = $site_settings_main['booking_fee'];

                                    $extra = $company->EA;

                                    $discount_amount = $dis->getPromoDiscountHoliday(

                                        $promo,

                                        $bookingPrice,

                                        $booking_fee,

                                        $extra,

                                        $bookingfor,

                                        $company->companyID,

                                    );

                                    if ($adjusted_price > $discount_amount) {

                                        $booking_price = $adjusted_price - $discount_amount;

                                    }

                                }

                            } else {

                                $discount_amount = $dis->getPromoDiscount(

                                    $promo,

                                    $adjusted_price,

                                    $bookingfor,

                                    $company->companyID,

                                );

                                //dd($booking_price." DIs ".$discount_amount);

                                //$discount_amount=1;

                                //echo $discount_amount."==".$booking_price;



                                if ($adjusted_price >= $discount_amount) {

                                    if (!(isset($company->park_api) && $company->park_api == 'bookfhr')) {
                                        $booking_price = $adjusted_price - $discount_amount;
                                    }

                                }

                            }

                            //echo $booking_price;

                        }

                    }

                @endphp

                @include('partials.results-deal-card', ['index' => $index])

                <div class="modal fade detailEditModal js-deal-info-modal" id="exampleModalCenter{{ $company->companyID }}"

                    tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered js-deal-info-modal__dialog" role="document">

                        <div class="modal-content js-deal-info-modal__content">

                            <div class="modal-header js-deal-info-modal__header">

                                <h5 class="modal-title js-deal-info-modal__title" id="exampleModalLongTitle">{{ $company->name }}</h5>

                                <button type="button" class="close js-deal-info-modal__close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                            </div>

                            <div class="modal-body js-deal-info-modal__body" id="listing-tabs">

                                <nav class="js-deal-info-modal__nav">

                                    <div class="nav nav-tabs js-deal-info-modal__tabs js-tabs-controls mob-nav-tab"

                                        id="nav-tab" role="tablist">

                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"

                                            href="#overview{{ $company->companyID }}" role="tab"

                                            aria-controls="overview" aria-selected="true">Overview</a>

                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"

                                            href="#arrival{{ $company->companyID }}" role="tab"

                                            aria-controls="arrival" aria-selected="false">Arrival</a>

                                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab"

                                            href="#return{{ $company->companyID }}" role="tab"

                                            aria-controls="return" aria-selected="false">Return</a>

                                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab"

                                            href="#map{{ $company->companyID }}" role="tab" aria-controls="map"

                                            aria-selected="false">Map</a>

                                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab"

                                            href="#note{{ $company->companyID }}" role="tab"

                                            aria-controls="note" aria-selected="false">Note</a>

                                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab"

                                            href="#terms{{ $company->companyID }}" role="tab"

                                            aria-controls="terms" aria-selected="false">Terms &amp; Conditions</a>

                                    </div>

                                </nav>

                                <div class="tab-content js-deal-info-modal__tab-content" id="nav-tabContent">

                                    <?php

                                    // Load the original HTML content

                                    

                                    $arivalContent = $company->overview;

                                    

                                    $overviewWithoutNumericValues = '';

                                    

                                    if (!empty($arivalContent)) {

                                        // Create a new DOMDocument

                                    

                                        $dom = new DOMDocument();

                                    

                                        // Suppress errors due to malformed HTML

                                    

                                        libxml_use_internal_errors(true);

                                    

                                        // Load the HTML content into the DOMDocument

                                    

                                        $dom->loadHTML($arivalContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                                    

                                        // Create an XPath instance for the document

                                    

                                        $xpath = new DOMXPath($dom);

                                    

                                        // Get all <p> tags in the document

                                    

                                        $pTags = $xpath->query('//p');

                                    

                                        // Iterate over <p> tags

                                    

                                        foreach ($pTags as $pTag) {

                                            // Get all text nodes within the <p> tag

                                    

                                            $textNodes = $xpath->query('.//text()', $pTag);

                                    

                                            foreach ($textNodes as $textNode) {

                                                // Remove numeric values of 3 digits or more from text nodes

                                    

                                                $textNode->nodeValue = preg_replace('/\b\d{3,}\b/', '', $textNode->nodeValue);

                                            }

                                        }

                                    

                                        // Save the modified HTML content

                                    

                                        $overviewWithoutNumericValues = $dom->saveHTML();

                                    

                                        // Restore error handling

                                    

                                        libxml_clear_errors();

                                    }

                                    

                                    ?>

                                    <div class="tab-pane fade show active note-editable note-editor mt-0 py-2"

                                        id="overview{{ $company->companyID }}" role="tabpanel"

                                        aria-labelledby="nav-home-tab">

                                        <div class="note-editor note-fram pane mb-0">

                                            <div class="note-editing-area">

                                                <div class="note-editable">

                                                    {!! $fixModalEncoding($overviewWithoutNumericValues) !!}

                                                </div>

                                            </div>

                                        </div>

                                        <p class="mb-0 mt-3"><span

                                                style="font-size: 20px;font-weight:700">Note:</span> We can not accept

                                            non-standard sized vehicles such as SWB, LWB Vans, Camper Vans, Pickup

                                            cars/trucks, and commercial vehicles.</p>

                                    </div>

                                    <?php

                                    // Load the original HTML content

                                    

                                    $arivalContent = $company->arival;

                                    

                                    $arivalWithoutNumericValues = '';

                                    

                                    if (!empty($arivalContent)) {

                                        // Create a new DOMDocument

                                    

                                        $dom = new DOMDocument();

                                    

                                        // Suppress errors due to malformed HTML

                                    

                                        libxml_use_internal_errors(true);

                                    

                                        // Load the HTML content into the DOMDocument

                                    

                                        $dom->loadHTML($arivalContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                                    

                                        // Create an XPath instance for the document

                                    

                                        $xpath = new DOMXPath($dom);

                                    

                                        // Get all <p> tags in the document

                                    

                                        $pTags = $xpath->query('//p');

                                    

                                        // Iterate over <p> tags

                                    

                                        foreach ($pTags as $pTag) {

                                            // Get all text nodes within the <p> tag

                                    

                                            $textNodes = $xpath->query('.//text()', $pTag);

                                    

                                            foreach ($textNodes as $textNode) {

                                                // Remove numeric values of 3 digits or more from text nodes

                                    

                                                $textNode->nodeValue = preg_replace('/\b\d{3,}\b/', '', $textNode->nodeValue);

                                            }

                                        }

                                    

                                        // Save the modified HTML content

                                    

                                        $arivalWithoutNumericValues = $dom->saveHTML();

                                    

                                        // Restore error handling

                                    

                                        libxml_clear_errors();

                                    }

                                    

                                    ?>

                                    <div class="tab-pane fade py-0" id="arrival{{ $company->companyID }}"

                                        role="tabpanel" aria-labelledby="nav-contact-tab">

                                        <div class="note-editor note-fram pane mb-0">

                                            <div class="note-editing-area">

                                                <div class="note-editable">

                                                    {!! $fixModalEncoding($arivalWithoutNumericValues) !!}

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <?php

                                    // Load the original HTML content

                                    

                                    $arivalContent = $company->return_proc;

                                    

                                    $returnWithoutNumericValues = '';

                                    

                                    if (!empty($arivalContent)) {

                                        // Create a new DOMDocument

                                    

                                        $dom = new DOMDocument();

                                    

                                        // Suppress errors due to malformed HTML

                                    

                                        libxml_use_internal_errors(true);

                                    

                                        // Load the HTML content into the DOMDocument

                                    

                                        $dom->loadHTML($arivalContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                                    

                                        // Create an XPath instance for the document

                                    

                                        $xpath = new DOMXPath($dom);

                                    

                                        // Get all <p> tags in the document

                                    

                                        $pTags = $xpath->query('//p');

                                    

                                        // Iterate over <p> tags

                                    

                                        foreach ($pTags as $pTag) {

                                            // Get all text nodes within the <p> tag

                                    

                                            $textNodes = $xpath->query('.//text()', $pTag);

                                    

                                            foreach ($textNodes as $textNode) {

                                                // Remove numeric values of 3 digits or more from text nodes

                                    

                                                $textNode->nodeValue = preg_replace('/\b\d{3,}\b/', '', $textNode->nodeValue);

                                            }

                                        }

                                    

                                        // Save the modified HTML content

                                    

                                        $returnWithoutNumericValues = $dom->saveHTML();

                                    

                                        // Restore error handling

                                    

                                        libxml_clear_errors();

                                    }

                                    

                                    ?>

                                    <div class="tab-pane fade py-0" id="return{{ $company->companyID }}"

                                        role="tabpanel" aria-labelledby="nav-contact-tab">

                                        <div class="note-editor note-fram pane mb-0">

                                            <div class="note-editing-area">

                                                <div class="note-editable">

                                                    {!! $fixModalEncoding($returnWithoutNumericValues) !!}

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="map{{ $company->companyID }}" role="tabpanel"

                                        aria-labelledby="nav-contact-tab">

                                        @if ($company->parking_type == 'Meet and Greet')

                                            <div class="tab-pane" id="tab_map{{ $company->companyID }}">

                                                <iframe width="100%" height="400" frameborder="0"

                                                    style="border:0"

                                                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAbqO8h1hqd8sQ5YR7zC10C4TQ0kbW1j_g&q={{ $company->address }}+{{ $company->town }}+{{ $company->post_code }}"

                                                    allowfullscreen></iframe>

                                            </div>

                                        @else

                                            <div class="tab-pane" id="tab_map{{ $company->companyID }}">

                                                <iframe width="100%" height="400" frameborder="0"

                                                    style="border:0"

                                                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAbqO8h1hqd8sQ5YR7zC10C4TQ0kbW1j_g&q={{ $company->address }}+{{ $company->town }}+{{ $company->post_code }}"

                                                    allowfullscreen></iframe>

                                            </div>

                                        @endif

                                    </div>

                                    <div class="tab-pane fade" id="note{{ $company->companyID }}" role="tabpanel"

                                        aria-labelledby="nav-contact-tab">

                                        <h4><strong>Important Information</strong></h4>

                                        <ul class="points"

                                            style="text-align: left;list-style: none;line-height: 25px;font-size: 15px;padding-left:0px;     color: #000;">

                                            @php

                                                $features = explode(',', $company->special_features);

                                            @endphp

                                            @foreach ($companies_special_features as $companies_special_feature)

                                                @if (in_array($companies_special_feature->name, $features))

                                                    <li>

                                                        <i

                                                            class="fa fa-chevron-right"></i>{{ $companies_special_feature->name }}</span>

                                                    </li>

                                                @endif

                                            @endforeach

                                        </ul>

                                    </div>

                                    <div class="tab-pane fade" id="terms{{ $company->companyID }}" role="tabpanel"

                                        aria-labelledby="nav-contact-tab">

                                        <div class="alert alert-info text-left" style="margin-top: 10px;">

                                            <strong>Will Be provided on request</strong>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

    </div>

</section>

<script type="text/javascript">

    (function($) {







        "use strict";



        $(function() {



            $('body').on('click', '.modal-body ul li', function() {



                $('.modal-body ul li').removeClass('active');



                $(this).closest('.modal-body ul li').addClass('active');



            });



        });





    })(jQuery);









    $('.moreinfo').click(function() {



        $('#infoModal').modal('toggle');

        $.ajax({



            type: 'POST',



            data: {



                id: $(this).data('id'),

                _token: "{{ csrf_token() }}"





            },



            dataType: 'json',



            url: '{{ route('loadinfo') }}',



            success: function(res) {



                //var parsed_data = JSON.stringify(res);



                //console.log(res[0].overview);



                var len = res.length;



                var revhtml = '';



                for (var i = 0; i < len; i++) {



                    if (res[i].username != null) {



                        var rat = res[i].rating;



                        revhtml += ` <div class="card" >

   

                       <div class="card-body">

   

                         <h4 class="card-title" style="margin-top:0">` + res[i].username + ` | ` + res[i].title +



                            ` </h4>`;







                        for ($x = 1; $x <= res[i].rating; $x++) {



                            revhtml += `<span class="fa fa-star checked"></span>`;



                        }



                        // if (rat.indexOf(".") > -1) {



                        //     revhtml +=`<span class="fa fa-star"></span>`;



                        //     $x++;



                        // }



                        while ($x <= 5) {



                            revhtml += ` <span class="fa fa-star"></span>`;



                            $x++;



                        }







                        revhtml += `<p class="card-text">` + res[i].review + `</p>  </div>

   

                     </div>`;



                    }



                }



                $(".reviews-result").html(revhtml);



                $(".info-overview").html(res[0].overview);



                $(".info-arrivals").html(res[0].arival);



                $(".info-return").html(res[0].return_proc);



            }



        })



    });

</script>



{{--

<script async src="{{ asset('assets/front/js/bootstrap-datepicker.js') }}"></script>

<script async src="{{ asset('assets/front/js/custom-date-picker.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

--}}
