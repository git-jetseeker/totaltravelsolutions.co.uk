<style>
    @media only screen and (max-width: 500px) {
        .container.fill_height {
            margin-top: 0px;
        }

        .search_item div {
            margin-bottom: 5px;
        }

        .search_input {
            height: 35px !important;
        }

        .search_panel_content {
            justify-content: top !important;
            padding-top: 14px;
        }
    }
</style>

<div class="search">


    <!-- Search Contents -->

    <div class="container fill_height">
        <div class="row fill_height">
            <div class="col fill_height">

                <!-- Search Tabs -->

                <div class="search_tabs_container">
                    <div
                        class="search_tabs d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">
                        <div
                            class="search_tab active d-flex flex-row align-items-center justify-content-lg-center justify-content-start">
                            <!-- <i class="nav-icon fa fa-car"></i> --><img src="{{ asset('theme/images/suitcase.png') }}"
                                alt=""><span>Airport Parking</span></div>
                        <div
                            class="search_tab d-flex flex-row align-items-center justify-content-lg-center justify-content-start">
                            <img src="{{ asset('theme/images/bus.png') }}" alt="">Lounges</div>
                        <div
                            class="search_tab d-flex flex-row align-items-center justify-content-lg-center justify-content-start">
                            <img src="{{ asset('theme/images/departure.png') }}" alt="">Transfer</div>

                    </div>
                </div>

                <!-- Search Panel -->


                <div class="search_panel active">
                    <form method="get" action="{{ route('searchresult') }}" id="search_form_1"
                        class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">
                        <div class="search_item">
                            <div>Airport</div>
                            <div class="form-group">
                                <select required name="airport_id" class="dropdown_item_select search_input"
                                    required="required">
                                    <option value="" selected disabled>Select</option>

                                    @foreach ($airports as $airport)
                                        <option @if ($airport->id == $id) selected @endif
                                            value="{{ $airport->id }}">{{ $airport->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="search_item">
                            <div>Drop of Date</div>

                            <div class="form-group">

                                <input autocomplete="off" name="dropoffdate" type="text" id="startDate"
                                    class="check_in search_input \ dpd1" style="" placeholder="Dropoff Date"
                                    readonly required />


                            </div>

                        </div>


                        <div class="search_item">

                            <div>Drop of time</div>

                            <div class="form-group">

                                @php
                                    $dropdown_timer = [];
                                    for ($i = 0; $i <= 23; $i++) {
                                        for ($j = 0; $j <= 45; $j += 15) {
                                            //$sel = str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT) == $opening_time ? 'selected' : '';
                                            //echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'"'.$sel.'>'.str_pad($i, 2, "0", STR_PAD_LEFT).':'.str_pad($j, 2, "0", STR_PAD_LEFT).'</option>';
                                            $dropdown_timer[
                                                str_pad($i, 2, '0', STR_PAD_LEFT) .
                                                    ':' .
                                                    str_pad($j, 2, '0', STR_PAD_LEFT)
                                            ] =
                                                str_pad($i, 2, '0', STR_PAD_LEFT) .
                                                ':' .
                                                str_pad($j, 2, '0', STR_PAD_LEFT);
                                        }
                                    }

                                @endphp

                                {{ Form::select('dropoftime', $dropdown_timer, '', ['class' => 'check_in search_input', 'id' => 'dropoftime']) }}
                            </div>
                        </div>
                        <div class="search_item" style="width: 20%">
                            <div>Pick Up Date</div>


                            <div class="form-group">

                                <input type="text" readonly autocomplete="off" name="departure_date" id="endDate"
                                    class="check_in search_input dpd2" placeholder="Departure Date" required />



                            </div>

                        </div>
                        <div class="search_item">
                            <div>Pickup time</div>



                            <div class="form-group">



                                {{ Form::select('pickup_time', $dropdown_timer, '', ['class' => 'dropdown_item_select search_input', 'id' => 'pickup_time']) }}



                            </div>



                        </div>
                        <div class="search_item">
                            <div>Promo Code</div>


                            <div class="form-group">

                                <input type="text" name="promo" class="check_in search_input"
                                    value="{{ request()->get('promo') }}" placeholder="Promo Code" />


                            </div>
                        </div>
                        <button class="button form-button search_button">Get A
                            Quote<span></span><span></span><span></span></button>
                    </form>
                </div>

                <!-- Search Panel -->

                <div class="search_panel">
                    <form action="#" id="search_form_2"
                        class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">



                        <center> <button type="button" class="button search_button">Coming
                                Soon<span></span><span></span><span></span></button> </center>
                    </form>
                </div>

                <!-- Search Panel -->

                <div class="search_panel">
                    <form action="#" id="search_form_2"
                        class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">



                        <center> <button type="button" class="button search_button">Coming
                                Soon<span></span><span></span><span></span></button></center>
                    </form>
                </div>

                <!-- Search Panel -->


                <!-- Search Panel -->

                <!-- Search Panel -->


            </div>
        </div>
    </div>
</div>
