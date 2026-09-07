<style>
    .search_panel.active {
        display: flex !important;
        background-color: orange;
        padding: 15px;
    }

    .home_slider_container {
        height: 385px !important;
    }

    @media screen and (max-width:991px) {
        .home_slider_container {
            height: auto !important;
        }

        .search_panel.active {
            display: flex !important;
            background-color: orange;
            padding: 20px;
            padding-bottom: 40px;
        }

        .search {
            top: -328px;
        }
    }

    @media screen and (max-width:575px) {
        .search {
            padding-top: 90px;
            padding-bottom: 90px;
            top: 0px;
        }

        .search_tabs_container {
            margin-bottom: 10px;
            display: none;
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
                            </i><img src="{{ asset('theme/images/departure.png') }}" alt=""><span>Lounge</span>
                        </div>
                        <!--      <div class="search_tab d-flex flex-row align-items-center justify-content-lg-center justify-content-start" style="pointer-events: none;">
                            <span style="visibility: hidden;">Airport : </span>
                            <span class="pull-right"  style="visibility: hidden;"> 9</span></div> -->


                    </div>
                </div>

                <!-- Search Panel -->

                <div class="search_panel active">

                    <form method="get" action="{{ route('searchresult_lounge') }}" id="search_form_1"
                        class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start">
                        <div class="search_item">
                            <div>Airport</div>
                            <div class="form-group">
                                <select required name="airport_id" class="dropdown_item_select search_input"
                                    required="required">
                                    <option value="" disabled selected>Select</option>

                                    @foreach ($airports as $airport)
                                        <option @if (request()->airport_id == $airport->id) {{ 'selected' }} @endif
                                            value="{{ $airport->id }}">{{ $airport->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="search_item">
                            <div>Checkin Date</div>

                            <div class="form-group">
                                <input autocomplete="off" name="checkin_date" type="text" id="checkin_date"
                                    class="checkin_date search_input" placeholder="DD/MM/YYYY"
                                    value="{{ request()->checkin_date }}" readonly required />

                            </div>
                        </div>


                        <div class="search_item">

                            <div>Checkin time</div>

                            <div class="form-group">


                                @php
                                    $dropdown_timer = [];
                                    for ($i = 0; $i <= 23; $i++) {
                                        for ($j = 0; $j <= 45; $j += 15) {
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
                                    //dd($request->input('dropoftime'));
                                @endphp
                                {{ Form::select('checkin_time', $dropdown_timer, request()->checkin_time, ['class' => 'check_in search_input', 'id' => 'checkin_time']) }}
                            </div>
                        </div>

                        <div class="search_item">

                            <div>Adults</div>

                            <div class="form-group">
                                <select required="" class="search_input" id="adults" name="adults">
                                    <option value='1' @if (request()->adults == 1)  @endif>1</option>
                                    <option value='2' @if (request()->adults == 2)  @endif>2</option>
                                    <option value='3' @if (request()->adults == 3)  @endif>3</option>
                                    <option value='4' @if (request()->adults == 4)  @endif>4</option>
                                    <option value='5' @if (request()->adults == 5)  @endif>5</option>
                                    <option value='6' @if (request()->adults == 6)  @endif>6</option>
                                </select>
                            </div>
                        </div>
                        <div class="search_item">

                            <div>Children</div>

                            <div class="form-group">
                                <select required="" class="search_input" id="children" name="children">
                                    <option value='0' @if (request()->children == 0)  @endif>0</option>
                                    <option value='1' @if (request()->children == 1)  @endif>1</option>
                                    <option value='2' @if (request()->children == 2)  @endif>2</option>
                                    <option value='3' @if (request()->children == 3)  @endif>3</option>
                                    <option value='4' @if (request()->children == 4)  @endif>4</option>
                                </select>
                            </div>
                        </div>

                        <div class="search_item">
                            <div>Promo Code</div>

                            <div class="form-group">

                                <input type="text" name="promo" class="check_in search_input"
                                    value='{{ request()->promo }}' placeholder="Promo Code" />

                            </div>
                        </div>
                        <button
                            class="button form-button search_button">Search<span></span><span></span><span></span></button>
                    </form>
                </div>

                <!-- Search Panel -->

            </div>
        </div>
    </div>
</div>
