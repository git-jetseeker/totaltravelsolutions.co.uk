<!--=============INNERPAGE-WRAPPER ===========-->

<link href="{{ asset('assets/js/jscriptreview.js')}}" type="text/javascript">

<link href="{{ asset('assets/js/start-revolution.extension.navigation.min.js')}}" type="text/javascript">

<link href="{{ asset('assets/css/stylereview.css') }}" rel="stylesheet">

<style type="text/css">

    /* small screen portrait */



    @media (max-width: 321px) {



        .pull-right {

            float: none!important;

        }



    }



    @media (max-width: 760px) {



        .pull-right {

            float: none!important;

        }



    }

    /* small screen lanscape */



    @media (max-width: 480px) {



        .pull-right {

            float: none!important;

        }



    }

</style>

<section id="room-listings" class="innerpage-wrapper bg" style="margin-top: 0px;">

    <div id="room-listing-blocks" class="innerpage-section-padding" style="padding-top: 10px;">

        <div class="row">





            <div class="">





                <div class="col-sm-12 col-md-8 col-md-12  pull-right  " style="margin-top: 125px;">



                    <div class="col-md-1  hidden-sm hidden-xs " style="background: #fff;"> <!-- required for floating -->

                        <!-- Nav tabs -->

                        <ul class="nav nav-tabs tabs-left sideways  ">

                            <li class="active"><i class="fa fa-car large" style="font-size: 35px;

    margin-top: 20px;"></i> Parking

                            </li>



                        </ul>

                    </div>



                    <div class="col-xs-12 col-md-11 col-sm-12" style="background: #fff">

                        <!-- Tab panes -->

                        <div class="tab-content" style="margin-top: 20px; background: white;">

                            <div class="tab-pane active" id="home-v">

                                <div class="col-md-8">

                                    <form action="{{ route("searchresult") }}" method="post" id="form2"

                                          class="col-lg-12">





                                        @csrf

                                        <div class="row">

                                            <div class="col-md-12">

                                                <label>Airport</label>

                                                <div class="form-group">

                                                    <div>

                                                        @php



                                                            $apid = old("airport_id");

                                                                if($airports_Detail){

                                                                    $apid = $airports_Detail->id;

                                                                }



                                                        @endphp

                                                        <select required="" style="padding-left:10px;" name="airport_id" class="form-control">

                                                            <option selected value="">Airport</option>



                                                            @foreach($airports as $airport)







                                                                <option @if($apid==$airport->id ) {{ "selected" }} @endif value="{{ $airport->id }}">{{ $airport->name }}</option>

                                                            @endforeach

                                                        </select>



                                                    </div>

                                                    <br>



                                                </div><!-- end columns -->

                                                @php $date = date("d-m-Y"); @endphp



                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <label>Drop of Date</label>

                                                        <div class="form-group">

                                                            <input type="text" name="dropoffdate" autocomplete="off"

                                                                   class="form-control dpd1"

                                                                   placeholder="Dropoff Date"

                                                                   value="{{ $date }}"

                                                                   readonly

                                                                   required=""/>



                                                        </div>

                                                    </div><!-- end columns -->





                                                    <div class="col-md-6">

                                                        <label>Drop of time</label>

                                                        <div class="form-group">





                                                            @php

                                                                $dropdown_timer = [];

                                                               for ($i = 0; $i <= 23; $i++) {

                                                                   for ($j = 0; $j <= 45; $j += 15) {

                                                                       $dropdown_timer[str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT);

                                                                   }

                                                               }

                                                            @endphp

                                                            {{ Form::select('dropoftime',$dropdown_timer,"",["class"=>"form-control","id"=>"dropoftime"]) }}



                                                        </div>

                                                    </div><!-- end columns -->

                                                </div>

                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <label>Pick Up Date</label>

                                                        <div class="form-group">

                                                            <input type="text" readonly autocomplete="off"

                                                                   name="departure_date"

                                                                   class="form-control dpd2"

                                                                   placeholder="Departure Date"



                                                                   <?php

                                                                   $mydate = $date;

                                                                   $daystosum = '2';

                                                                   $datesum = date('d-m-Y', strtotime($mydate . ' + ' . $daystosum . ' days'));

                                                                   ?>

                                                                   value="{{ $datesum}}"

                                                                   required/>



                                                        </div>

                                                    </div><!-- end columns -->





                                                    <div class="col-md-6">

                                                        <label>Pickup time</label>

                                                        <div class="form-group">



                                                            {{ Form::select('pickup_time',$dropdown_timer,"",["class"=>"form-control","id"=>"pickup_time"]) }}





                                                        </div>

                                                    </div><!-- end columns -->

                                                </div>





                                                <div class="row">

                                                    <div class="col-md-6">

                                                        <label>Promo Code</label>

                                                        <div class="form-group">

                                                            <input type="text" name="promo" class="form-control"

                                                                   value=" {{ old("promo") }}"

                                                                   placeholder="Promo Code"/>

                                                            @if ($errors->has('promo'))



                                                                <div class="error error-massage" style="color:red">

                                                                    {{ $errors->first('promo') }}

                                                                </div>

                                                            @endif



                                                        </div>

                                                    </div><!-- end columns -->



                                                    <div class="col-md-6">



                                                        <input type="submit" style="margin-top: 25px;"

                                                               class="form-control btn btn-success btn-lg btn-padding"

                                                               value="GET A QUOTE">



                                                    </div>

                                                </div><!-- end row -->



                                    </form>



                                </div>

                                </div>

                                </div>



                                <div class="col-md-4">

                                    <svg viewBox="0 0 251 46" xmlns="http://www.w3.org/2000/svg" style="

    margin-top: 103px;">

                                        <g class="tp-star">

                                            <path class="tp-star__canvas" fill="green"

                                                  d="M0 46.330002h46.375586V0H0z"></path>

                                            <path class="tp-star__shape"

                                                  d="M39.533936 19.711433L13.230239 38.80065l3.838216-11.797827L7.02115 19.711433h12.418975l3.837417-11.798624 3.837418 11.798624h12.418975zM23.2785 31.510075l7.183595-1.509576 2.862114 8.800152L23.2785 31.510075z"

                                                  fill="#FFF"></path>

                                        </g>

                                        <g class="tp-star">

                                            <path class="tp-star__canvas" fill="green"

                                                  d="M51.24816 46.330002h46.375587V0H51.248161z"></path>

                                            <path class="tp-star__shape"

                                                  d="M74.990978 31.32991L81.150908 30 84 39l-9.660206-7.202786L64.30279 39l3.895636-11.840666L58 19.841466h12.605577L74.499595 8l3.895637 11.841466H91L74.990978 31.329909z"

                                                  fill="#FFF"></path>

                                        </g>

                                        <g class="tp-star">

                                            <path class="tp-star__canvas" fill="green"

                                                  d="M102.532209 46.330002h46.375586V0h-46.375586z"></path>

                                            <path class="tp-star__shape"

                                                  d="M142.066994 19.711433L115.763298 38.80065l3.838215-11.797827-10.047304-7.291391h12.418975l3.837418-11.798624 3.837417 11.798624h12.418975zM125.81156 31.510075l7.183595-1.509576 2.862113 8.800152-10.045708-7.290576z"

                                                  fill="#FFF"></path>

                                        </g>

                                        <g class="tp-star">

                                            <path class="tp-star__canvas" fill="green"

                                                  d="M153.815458 46.330002h46.375586V0h-46.375586z"></path>

                                            <path class="tp-star__shape"

                                                  d="M193.348355 19.711433L167.045457 38.80065l3.837417-11.797827-10.047303-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418974zM177.09292 31.510075l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z"

                                                  fill="#FFF"></path>

                                        </g>

                                        <g class="tp-star">

                                            <path class="tp-star__canvas" fill="green"

                                                  d="M205.064416 46.330002h46.375587V0h-46.375587z"></path>

                                            <path class="tp-star__shape"

                                                  d="M244.597022 19.711433l-26.3029 19.089218 3.837419-11.797827-10.047304-7.291391h12.418974l3.837418-11.798624 3.837418 11.798624h12.418975zm-16.255436 11.798642l7.183595-1.509576 2.862114 8.800152-10.045709-7.290576z"

                                                  fill="#FFF"></path>

                                        </g>

                                    </svg>



                                    Rating <b>8.9</b>

                                    <p><b>1,628</b> reviews</p>



                                </div>

                                <div class="clearfix"></div>



                            </div>



                        </div>

                    </div>



                    <div class="clearfix"></div>



                </div>





            </div>

            <br/>



            <!-- end row -->

        </div>

        <!-- end container -->

    </div>

    <!-- end room-listing-blocks -->

</section>





