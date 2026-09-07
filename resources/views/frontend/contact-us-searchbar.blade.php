@if(request()->get('src') != '')
{{ session()->put('bk_src', request()->get('src') )}}
@endif

@if(request()->get('utm_source') == 'ppc')
{{ session()->put('bk_src', 'PPC' )}}
@endif

@if(request()->get('utm_source') == 'bing')
{{ session()->put('bk_src', 'BING' )}}
@endif

@if(request()->get('utm_source') == 'EMAIL')
{{ session()->put('bk_src', 'EM' )}}
@endif

@php
$discount = request()->get('discount')
@endphp
<style>
    @media only screen and (max-width: 600px) {
  .search {
    height: 1024px;
  }
}
@media only screen and (max-width: 991px) {
   .sty{
            border-radius:0px !important;
        }
}
.search1{

    background-image: url(public/banner-contact.webp);

    width: 100%;

    height: 486px;

    background-size: cover;

}
@media screen and (min-device-width: 484px) and (max-device-width: 991px) {
        .search1{height: 1043px;}
        }
@media screen and (min-device-width: 341px) and (max-device-width: 483px) {
        .search1{height: 1030px;}
        }
@media only screen and (max-width: 340px) {
    .search1{ height: 1041px;}
}
.main-heading{
    font-size:50px;
    color:white;
    text-align:center;
    margin-top:201px;
}
.main-paragraph{
    color:white;
    font-size:20px;
    text-align:center;
}
.search_panel1{
    margin-top: 55px;
    padding-top: 30px;
    padding-bottom: 34px;
    padding-right: 20px;
    padding-left: 20px;
    border-radius: 20px;
    background-color:rgb(255, 255, 255,0.2);
}
.main-heading-h2{
    font-size:30px;
    color:white;
    text-align:center;
}

select{
    border-radius: 5px;
}
input{
    border-radius: 5px
}

.form-button {
    border-radius: 5px;
    margin-top: -10px;
    background-color: #F79F02;
}
@media only screen and (min-width: 992px) {

.bttn{
    padding:0px !important;
    margin: 0px !important;
   margin-top: -8px !important;
}
}
@media only screen and (max-width: 400px) {
.search_panel1 {
    padding-right: 35px;
    padding-left: 26px;
}
}
@media only screen and (min-width: 1200px) {
.bttn{width: 155px;}
}
@media screen and (min-device-width: 992px) and (max-device-width: 1199px) {
        .bttn{width: 155px;}
        }
@media only screen and (min-width: 1414px) {
.search_panel1{margin-left: -129px;margin-right: -129px;}
}
@media screen and (min-device-width: 992px) and (max-device-width: 1413px) {
.search_panel1{margin-left: -40px;
    margin-right: -40px;}
}
.button{background-color: #F79F02 !important;}
.fff{
        position: absolute;
    color: gray;
    margin-top: 13px;
    /* float: left; */
    margin-left: -26px;
    z-index: 99;
    font-size: 20px !important;
}
</style>
<div style="display:none;" id="notification"></div>
    <div class="search1">


        <!-- Search Contents -->

        <div class="container fill_height">
            <div class="row fill_height">
                <div class="col fill_height">
                    <h1 class="main-heading"><b><span style="color:#F79F02">Contact</span> Us </b></h1>
                    {{-- <!--<p class="main-paragraph">Over 100's of Airport Car Parks Across the UK.</p>-->


                    <!--<div class="search_tabs_container">-->
                    <!--    <div class="search_tabs d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">-->
                    <!--        <div class="search_tab active d-flex flex-row align-items-center justify-content-lg-center justify-content-start"></i><img src="{{url('theme/images/suitcase.webp')}}" alt=""><span> Airport Parking</span></div>-->
                    <!--        <div class="search_tab d-flex flex-row align-items-center justify-content-lg-center justify-content-start"><img src="{{url('theme/images/bus.webp')}}" alt="">Lounges</div>-->
                    <!--        <div class="search_tab d-flex flex-row align-items-center justify-content-lg-center justify-content-start"><img src="{{url('theme/images/departure.webp')}}" alt="">Transfer</div>-->

                    <!--    </div>      -->
                    <!--</div>--> --}}

                    <!-- Search Panel -->
                    <!-- Search Tabs -->


                    <div id="home_search_form" class="search_panel1">
                        <!--<h2 class="main-heading-h2"><b><span style="color:#F79F02">BOOK</span> NOW</b></h2>-->
                        <form method="get" action="{{ route("searchresult") }}" id="search_form_1" class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">
                               <div class="search_item airport-w">
                                <div>Airport</div>
                                <div class="form-group">
                               <select required name="airport_id"   class="dropdown_item_select search_input sty" required="required">

                                                <option value="" disabled selected>Select</option>
                                                @foreach($airports as $airport)

                                                    <option value="{{ $airport->id }}">{{ $airport->name }}</option>

                                                @endforeach

                                 </select>
                             </div>
                                </div>
                            <div class="search_item dep-w" >
                                <!--<div>Drop off Date</div>-->
                                <div>Check In</div>

                                     <div class="form-group">

                                            <input autocomplete="off" name="dropoffdate" type="text"
                                            id="startDate"

                                                   class="check_in search_input \ dpd1" style="" placeholder="Dropoff Date"

                                                   readonly

                                                   required/>
                                                   <i class="fa fa-calendar fff" ></i>


                                        </div>

                            </div>


                            <div class="search_item drop-w">

                                <!--<div>Drop off time</div>-->
                               <div>&nbsp;</div>
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

                                            <select class="check_in search_input" id="dropoftime" name="dropoftime">
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
                            <div class="search_item arival-w" style="width: 20%">
                             <!--<div>Pick Up Date</div>-->
                             <div>Check Out</div>


                                        <div class="form-group">

                                            <input type="text" readonly autocomplete="off" name="departure_date"
                                            id="endDate"

                                                   class="check_in search_input dpd2" placeholder="PickUp Date"

                                                   required/>
                                                   <i class="fa fa-calendar fff" ></i>



                                        </div>

                            </div>
                            <div class="search_item pickup-w">
                                <!--<div>Pickup time</div>-->
                                <div>&nbsp;</div>



                                        <div class="form-group">


                                           <select class="dropdown_item_select search_input" id="pickup_time" name="pickup_time">
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
                             <!--<div class="search_item pickup-w col-lg-3 col-md-3">-->
                             <!--       <div>Email</div>-->
                             <!--       <div class="form-group">-->
                             <!--           <input type="email" class="form-control search_input" name="email" value="{{ request()->email }}" placeholder="Enter your email" required>-->
                             <!--       </div>-->
                             <!--   </div>-->
                            @if($discount != null)
                            <div class="alert alert-success" style = 'margin-right:10px ; margin-top:33px ; width:250px'>
                        <strong>Success!</strong> Discount Applied.
                            </div>
                            <div class="search_item promo-w" style = 'display:none'>
                                <div>Promo Code</div>


                                        <div class="form-group">

                                            <input type="hidden" name="promo" class="check_in search_input"

                                                   placeholder="Optional" value="{{ request()->get('discount') }}" />


                                     </div>
                            </div>
                            @else
                            <div class="search_item promo-w">
                                <div>Promo Code</div>


                                        <div class="form-group">

                                            <input type="text" name="promo" class="check_in search_input"

                                                   placeholder="Optional" value="{{ request()->get('promo') }}" />


                                     </div>
                            </div>
                            @endif
                            <input type="hidden" value="{{ (request()->get('promo') != null) ? 'EM' : 'ORG' }}" name='src'>
                            <button class="button form-button search_button bttn">Get A Quote</button>
                        </form>
                    </div>

                    <!-- Search Panel -->

                    <div class="search_panel">
                        <form action="#" id="search_form_2" class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">



                        <center>    <button type="button" class="button search_button">Coming Soon<span></span><span></span><span></span></button> </center>
                        </form>
                    </div>

                    <!-- Search Panel -->

                    <div class="search_panel">
                    <form action="#" id="search_form_2" class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">



                           <center> <button type="button" class="button search_button">Coming Soon<span></span><span></span><span></span></button></center>
                        </form>
                    </div>

                    <!-- Search Panel -->


                    <!-- Search Panel -->

                    <!-- Search Panel -->


                </div>
            </div>
        </div>
    </div>
