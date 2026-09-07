<?php
use App\Models\ref_tracking;
?>
{{ session()->put('user_ip', request()->ip() )}}
@if(isset($_SERVER['HTTP_REFERER']))
{{ session()->put('ref_url', $_SERVER['HTTP_REFERER'] )}}
@endif

@if(request()->get('src') != '')
{{ session()->put('bk_src', request()->get('src') )}}
@endif

@if(request()->get('utm_source') == 'PPC')
{{ session()->put('bk_src', 'PPC' )}}
@endif

@if(request()->get('utm_source') == 'Bing')
{{ session()->put('bk_src', 'BING' )}}
@endif

@if(request()->get('utm_source') == 'EMAIL')
{{ session()->put('bk_src', 'EM' )}}
@endif

@php
$discount = request()->get('discount')
@endphp

<?php
$ip = session()->get('user_ip');
$data['ref_url'] = session()->get('ref_url');
if(session()->get('bk_src') != ''){
$data["m_source"] = session()->get('bk_src');
}else{
$data["m_source"]='ORG';
}
$data["agentID"]='1';
$data["user_ip"]=$ip;
$data["current_url"]=url()->full();
$update = ref_tracking::Create($data);


?>

<style>
   @media only screen and (min-width: 1043px) {
  div.cal {
    width: 255px !important;
  }
}

.sb-serc1{
    background: #C2185B;
    padding: 10px;
    margin-top: 10px;
    text-align: center;
}
.sb-serc1 h5{
    font-size: 22px;
        font-weight: 700;
        color:white;
}
.accordion-toggle:after{
    display:none;
}
.btn-right-searchfm{
    background: white;
    color: black;
    border-radius: 50px;
    font-size: 20px;
    font-weight: 600;
    padding: 4px 35px;
}
.search-card{
    background: #C2185B;
    padding: 15px;
}
.search-card h3{
    color: white;
    font-weight: 600;
    padding: 10px 5px;
    font-size: 18px;
}
.search-card h3 span{
    font-size: 29px;
}
.label{
    color: white;
    padding: 5px 0;
    font-weight: 600;
    font-size: 14px;
}
.form-control{
    font-weight:500;
    background: white !important;
}
.btn-submit{
    background: white !important;
    border-radius: 20px;
    color: black;
}
</style>
<div class="sub-serc">

    <div class="row">

        <div class="col-sm-12">

            <div class="tabbable">

                <div class="tab-content">

                    <div class="tab-pane active" id="home4">

                        <div class="row">

                            <div class="col-sm-12">

                                <div id="accordion"

                                     class="accordion-style panel-group search-card">

                                    <h3 class="text-center" ><span>Compare & Save</span><br> on Airport Parking Deals</h3>

                                    <div class="">

                                        <!--<div class="panel-heading">-->

                                        <!--    <h4 class="panel-title maintab">-->

                                        <!--        <a class="accordion-toggle" href="#collapseOne"-->

                                        <!--           data-parent="#accordion" data-toggle="collapse">-->

                                        <!--            <i class="icon-angle-down bigger-110"-->

                                        <!--               data-icon-show="icon-angle-right"-->

                                        <!--               data-icon-hide="icon-angle-down"></i>-->

                                        <!--            Airport Parking-->

                                        <!--        </a>-->

                                        <!--    </h4>-->

                                        <!--</div>-->

                                        <div id="home_search_form" class="panel-collapse collapse show">

                                            <form method="get" action="{{ route("searchresult") }}" id="search_form_1" class="search_panel_content">

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="label">Airport</div>
                                                        <div class="form-group">
                                                           <select required name="airport_id"   class="form-control p-0" required="required">
                                                                <option value="" disabled selected>Select</option>
                                                                @foreach($airports as $airport)
                                                                    <option value="{{ $airport->id }}">{{ $airport->name }}</option>
                                                                @endforeach
                                                             </select>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <!--<div>Drop off Date</div>-->
                                                        <div class="label">Drop off Date</div>
                                                        <div class="form-group">
                                                            <input autocomplete="off" name="dropoffdate" type="text" id="startDate" class=" form-control" style="" placeholder="Dropoff Date" readonly required="required"/>
                                                            <i class="fa fa-calendar fff" style="float: right;margin-top: -31px;padding-right:3px;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="label">Drop off Time</div>
                                                        <div class="form-group">
                                                          @php
                                                            $dropdown_timer = [];
                                                               for ($i = 0; $i <= 23; $i++) {
                                                                   for ($j = 0; $j <= 45; $j += 15) {
                                                                      //$sel = str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT) == $opening_time ? 'selected' : '';
                                                                          //echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'"'.$sel.'>'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'</option>';
                                                                            $dropdown_timer[str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT);
                                                                            //print_r($dropdown_timer);
                                                                         }
                                                                        }
                                                            @endphp
                                                            {{-- {{ Form::select('dropoftime',$dropdown_timer,"",["class"=>"check_in search_input","id"=>"dropoftime"]) }} --}}
                                                            <select class="form-control check_in search_input dropdown_item_select p-0" id="dropoftime" name="dropoftime">
                                                              @php
                                                                foreach ($dropdown_timer as $key => $value) {
                                                                  $selected ='';
                                                                  if($value == '09:00'){
                                                                    $selected ='selected';
                                                                  }
                                                              @endphp
                                                                 <option {{$selected}} value="{{ $value }}">{{ $value }}</option>
                                                              @php
                                                              }
                                                              @endphp
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="label">Pick Up Date</div>
                                                        <div class="form-group">
                                                            <input type="text" readonly autocomplete="off" name="departure_date" id="endDate" class="form-control" placeholder="Departure Date" required="required"/>
                                                            <i class="fa fa-calendar fff" style="float: right;margin-top: -31px;padding-right:3px;"></i>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="label">Pick Up Time</div>
                                                        <div class="form-group">
                                                           <select class="form-control dropdown_item_select search_input p-0" id="pickup_time" name="pickup_time">
                                                              @php
                                                                foreach ($dropdown_timer as $key => $value) {
                                                                  $selected ='';
                                                                  if($value == '09:00'){
                                                                    $selected ='selected';
                                                                  }
                                                              @endphp
                                                                 <option {{$selected}} value="{{ $value }}">{{ $value }}</option>
                                                              @php
                                                              }
                                                              @endphp
                                                            </select>
                                                            {{-- {{ Form::select('pickup_time',$dropdown_timer,"",["class"=>"dropdown_item_select search_input","id"=>"pickup_time"]) }} --}}
                                                        </div>
                                                    </div>
                                                  <div class="col-lg-6">
                                 <div>Email</div>
                                 <div class="form-group">
                                     <input type="email" class="form-control " name="email" value="{{ request()->email }}" placeholder="Enter your email" required>
                                 </div>
                                </div>
                                                    <div class="col-md-6">
                                                        @if($discount != null)
                                                        <div class="alert alert-success" style = 'margin-right:10px ; margin-top:33px ; width:250px'>
                                                        <strong>Success!</strong> Discount Applied.
                                                            </div>
                                                                <div class="label">Promo Code </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" name="promo" class="form-control" placeholder="Optional" value="{{ request()->get('discount') }}" />
                                                                </div>
                                                            @else
                                                                <div class="label">Promo Code</div>
                                                                <div class="form-group">
                                                                    <input type="text" name="promo" class="form-control" placeholder="Optional" value="{{ request()->get('promo') }}" />
                                                                </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12 text-center">
                                                        <div>&nbsp;</div>
                                                        <input type="hidden" value="{{ (request()->get('promo') != null) ? 'EM' : 'ORG' }}" name='src'>
                                                        <button class="btn btn-right-searchfm">Get A Quote</button>
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

<div class="sb-serc1">

    <h5>Airport Parking</h5>

    <img src="{{ asset('assets/images/black.png') }}" alt="logo" class="guide-width">

    <p style="color:white">Secure, guaranteed and satisfactory. Our customers reap benefits from priority parking at

        350+ car parks on 30+ major airports across UK, while saving up to 30%.</p>

    <a href="{{ route("airports") }}" class="btn btn-submit">Learn More</a>

</div>

<!--<div class="sb-serc1">-->

<!--    <h5>Airport Hotels</h5>-->

<!--    <img src="{{ asset('assets/images/hotels.png') }}" alt="logo" class="guide-width">-->

<!--    <p style="color:white">Why struggle through traffic to reach airport when you can say goodbye to stress with JET SEEKER-->

<!--        and book an Airport hotel in two minutes. </p>-->

<!--    <a  class="btn btn-submit">Coming Soon</a>-->

<!--</div>-->

<!--<div class="sub-serc">-->

<!--    <div class="sb-serc1">-->

<!--        <h5>Airport Lounges</h5>-->

<!--        <img src="{{ asset('assets/images/ds.png') }}" alt="logo" class="guide-width">-->

<!--        <p style="color:white">Take a break from all the stress and extensive traveling. Book a premium lounge with-->

<!--            JET SEEKER  in the price of an economy airport lounge. </p>-->

<!--        <a  class="btn btn-submit">Coming Soon</a>-->

<!--    </div>-->

<!--</div>-->

<!--<div class="sub-serc">-->

<!--    <div class="sb-serc1">-->

<!--        <h5>Other Traveling Services</h5>-->

<!--        <img src="{{ asset('assets/images/car.png') }}" alt="logo" class="guide-width">-->

<!--        <p style="color:white">Flying made easy. We offer unbeatable rates for car rentals, travel insurance, taxi-->

<!--            services, parking, airport lounges and international hotels. Try it to believe it!</p>-->

<!--        <a  class="btn btn-submit">Coming Soon</a>-->

<!--    </div>-->

<!--</div>-->

@section("footer-script")

    <script>

        $(function () {

//            var dateToday = new Date();

//

//            $("#dropdatepicker12").datepicker({

//                minDate: 0,

//

//                dateFormat: 'dd/mm/yy'

////                onSelect: function (dateText, inst) {

////

////                    var date2 = $('#dropdatepicker12').datepicker('getDate', '+1d');

////                    //date2.setDate(date2.getDate() + 7);

////                    //$('#pickdatepicker12').datepicker('setDate', date2);

////                }

//

//            });

//            $('#pickdatepicker12').datepicker({

//                    minDate: 0,

//                    dateFormat: 'dd/mm/yy'

////                    beforeShow: function () {

////                        $(this).datepicker('option', 'minDate', $('#dropdatepicker12').val());

////                        if ($('#dropdatepicker12').val() === '') $(this).datepicker('option', 'minDate', 0);

////                    }

//                });



//            $('#dropdatepicker12').datepicker(

//                { startDate: new Date(),

//                    minDate: 0,

//                    dateFormat: 'dd/mm/yy',

//                    beforeShow: function() {

//                        $(this).datepicker('option', 'maxDate', $('#pickdatepicker12').val());

//                    }

//                });

//            $('#pickdatepicker12').datepicker(

//                {

//                    startDate: new Date(),

//                    dateFormat: 'dd/mm/yy',

//                    beforeShow: function() {

//                        $(this).datepicker('option', 'minDate', $('#from').val());

//                        if ($('#from').val() === '') $(this).datepicker('option', 'minDate', 0);

//                    }

//                });





        });

    </script>

@endsection
