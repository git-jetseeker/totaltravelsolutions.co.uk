<div class="sub-serc">

    <div class="row">

        <div class="col-sm-12">

            <div class="tabbable">

                <div class="tab-content">

                    <div class="tab-pane active" id="home4">

                        <div class="row">

                            <div class="col-sm-12">

                                <div id="accordion"

                                     class="accordion-style panel-group passenger-detail" style="background: #350a4e;">

                                    <h3 class="text-center"

                                    >JET SEEKER Get

                                        Discount</h3>

                                    <div class="">

                                        <div class="panel-heading">

                                            <h4 class="panel-title maintab">

                                                <a class="accordion-toggle" href="#collapseOne"

                                                   data-parent="#accordion" data-toggle="collapse">

                                                    <i class="icon-angle-down bigger-110"

                                                       data-icon-show="icon-angle-right"

                                                       data-icon-hide="icon-angle-down"></i>

                                                    Airport Parking

                                                </a>

                                            </h4>

                                        </div>

                                        <div id="home_search_form" class="panel-collapse collapse show">

                                            <form method="POST" class="quote-form"

                                                  action='{{ route("searchresult") }}' id="airportParkingForm12">

                                                @csrf

                                                <div class="panel-body">

                                                    <div class="row">

                                                        <div class="col-md-12 col-xs-12">

                                                            <label class="title">Airport

                                                                <small> ( Select Your Airport )

                                                                </small>

                                                            </label>

                                                            <select required name="airport_id" class="form-control">

                                                                <option value="" selected>Airport</option>

                                                                @foreach($airports as $airport)

                                                                    <option value="{{ $airport->id }}">{{ $airport->name }}</option>

                                                                @endforeach

                                                            </select>

                                                        </div>

                                                    </div>



                                                                                                @php $date = date("Y-m-d"); @endphp

                                                    <div class="row">

                                                        <div class="col-md-6 col-xs-6">

                                                            <label class="title">Departure

                                                                Date</label>

                                                            <input required class="form-control right_dpd1"

                                                                   id="startDate"

                                                                   autocomplete="off"

                                                                   readonly

                                                                   class="check_in search_input \ dpd1" 

                                                                   name="dropoffdate"

                                                                   value="{{ $date }}" placeholder="MM/DD/YY" value="" style="background:white;">

                                                        </div>



                                                        <div class=" col-md-6 col-xs-6">

                                                            <label class="title">Time</label>

                                                            @php

                                                                $dropdown_timer = [];

                                                               for ($i = 0; $i <= 23; $i++) {

                                                                   for ($j = 0; $j <= 45; $j += 15) {

                                                                       //$sel = str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT) == $opening_time ? 'selected' : '';

                                                                       //echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'"'.$sel.'>'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'</option>';

                                                                       $dropdown_timer[str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT);

                                                                   }

                                                               }

                                                            @endphp

                                                            {{ Form::select('dropoftime',$dropdown_timer,"",["class"=>"form-control","id"=>"dropoftime"]) }}





                                                        </div>



                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6 col-xs-6">

                                                            <label class="title">Arrival

                                                                Date</label>

                                                            <input required class="form-control right_dpd2"

                                                                   id="endDate"

                                                                   autocomplete="off"


                                                                   name="departure_date"

                                                                   <?php

                                                               $mydate = $date;

                                                               $daystosum = '2';

                                                               $datesum = date('Y-m-d', strtotime($mydate . ' + ' . $daystosum . ' days'));

                                                               ?>

                                                               value="{{ $datesum}}"

                                                               value="" class="check_in search_input dpd2" placeholder="MM/DD/YY" value="" style="background: white;">

                                                        </div>



                                                        <div class="col-md-6 col-xs-6">

                                                            <label class="title">Time</label>

                                                            {{ Form::select('pickup_time',$dropdown_timer,"",["class"=>"form-control ","id"=>"pickup_time"]) }}





                                                        </div>



                                                    </div>

                                                    <div class="row">

                                                        <div class=" col-md-12">

                                                            <label class="title"></label>

                                                              <br>

                                                            <button class="btn btn butn_1 center-block btn-quote-ap pull-right"

                                                                    style="color: #fff; font-size:14px;margin-bottom:10px; background: #C2185B;"

                                                                    type="submit" name="button"

                                                                    value="Get a quote">GET A QUOTE

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                    <!--<div class="panel panel-default">

                                        <div class="panel-heading">

                                            <h4 class="panel-title maintab">

                                                <a class="accordion-toggle collapsed" href="#collapseTwo" data-parent="#accordion" data-toggle="collapse">

                                                    <i class="icon-angle-right bigger-110" data-icon-show="icon-angle-right" data-icon-hide="icon-angle-down"></i>

                                                    Airport Hotel

                                                </a>

                                            </h4>

                                        </div>

                                        <div id="collapseTwo" class="panel-collapse collapse">

                                            <div class="panel-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. </div>

                                        </div>

                                    </div>

                                    <div class="panel panel-default">

                                        <div class="panel-heading">

                                            <h4 class="panel-title maintab">

                                                <a class="accordion-toggle collapsed" href="#collapseThree" data-parent="#accordion" data-toggle="collapse">

                                                <i class="icon-angle-right bigger-110" data-icon-show="icon-angle-right" data-icon-hide="icon-angle-down"></i>

                                                 Airport Hotel &  Parking

                                                </a>

                                            </h4>

                                        </div>

                                        <div id="collapseThree" class="panel-collapse collapse">

                                            <div class="panel-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. </div>

                                        </div>

                                    </div>-->

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



</div>


@section("footer-script")

    <script>

        $(function () {


        });

    </script>

@endsection