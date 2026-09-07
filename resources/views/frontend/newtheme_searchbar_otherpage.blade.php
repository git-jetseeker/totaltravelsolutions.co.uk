@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all();
    foreach ($settingsAll as $setting) {
        $site_settings_main[$setting->field_name] = $setting->field_value;
    }
@endphp
<div class="post_content entry-content">
    <div data-vc-full-width="true" data-vc-full-width-init="true" data-vc-stretch-content="true"
        class="vc_row wpb_row vc_row-fluid vc_custom_1523525429124 vc_row-has-fill vc_row-no-padding shape_divider_top-none shape_divider_bottom-none"
        style="position: relative; left: -109.5px; box-sizing: border-box;">
        <div class="wpb_column vc_column_container vc_col-sm-12 sc_layouts_column_icons_position_left">
            <div class="vc_column-inner">
                <div class="wpb_wrapper">
                    <div class="sc_content_wrap sc_pull_medium">
                        <div id="sc_content_945759841"
                            class="sc_content color_style_default sc_content_default sc_content_width_1_1 sc_float_center">
                            <div class="sc_content_container">

                            </div>
                        </div><!-- /.sc_content -->
                    </div><!-- /.sc_content_wrap -->
                    <div class="wpb_revslider_element wpb_content_element">
                        <link href="https://fonts.googleapis.com/css?family=Open+Sans:700%2C400" rel="stylesheet"
                            property="stylesheet" type="text/css" media="all">
                        <div id="rev_slider_2_1_wrapper"
                            class="rev_slider_wrapper height_bg_banner fullwidthbanner-container" data-source="gallery"
                            style="margin: 0px auto; background: transparent; padding: 0px;  overflow: visible;">
                            <!-- START REVOLUTION SLIDER 5.4.8 auto mode -->
                            <div id="rev_slider_2_1"
                                class="rev_slider height_bg_banner fullwidthabanner revslider-initialised tp-simpleresponsive rev_redraw_on_blurfocus"
                                style="margin-top: 0px; margin-bottom: 0px;">
                                <ul class="tp-revslider-mainul "
                                    style="visibility: visible; display: block; overflow: hidden; height: 100%; max-height: none; left: 0px;">

                                    <!-- SLIDE  -->
                                    <li data-index="rs-6" data-transition="fade" data-slotamount="default"
                                        data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default"
                                        data-easeout="default" data-masterspeed="300" data-rotate="0"
                                        data-saveperformance="off" data-title="Slide" data-param1="" data-param2=""
                                        data-param3="" data-param4="" data-param5="" data-param6="" data-param7=""
                                        data-param8="" data-param9="" data-param10="" data-description=""
                                        class="tp-revslider-slidesli active-revslide"
                                        style="color:#ffffff; width: 100%; height: 100%; overflow: hidden; z-index: 20; visibility: inherit; opacity: 1; background-color: rgba(255, 255, 255, 0);">
                                        <!-- MAIN IMAGE -->
                                        <div class="slotholder"
                                            style="position: absolute; top: 0px; left: 0px; z-index: 0; width: 100%; height: 100%; visibility: inherit; opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);">
                                            <!--Runtime Modification - Img tag is Still Available for SEO Goals in Source - <img src="http://parkingzone.co.uk/wp-content/uploads/2018/04/s3.jpg" alt="" title="s3" width="1920" height="612" data-bgposition="center center" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-scalestart="100" data-scaleend="110" data-rotatestart="0" data-rotateend="0" data-blurstart="0" data-blurend="0" data-offsetstart="0 0" data-offsetend="0 0" class="rev-slidebg defaultimg" data-no-retina="">-->
                                            {{-- <div class="tp-bgimg defaultimg " --}}
                                            {{-- data-bgcolor="undefined" --}}
                                            {{-- style="background-repeat: no-repeat; background-image: url('{{ asset("assets/front/parkingzone/images/s3.jpg") }}'); background-size: cover; background-position: center center; width: 100%; height: 100%; opacity: 1; visibility: inherit; z-index: 20;" --}}
                                            {{-- src="{{ asset("assets/front/parkingzone/images/s2.jpg") }}"></div> --}}
                                            <div class="tp-kbimg-wrap "
                                                style="z-index: 2; width: 100%; height: 100%; top: 0px; left: 0px; position: absolute; ">
                                                <img class="tp-kbimg"
                                                    src="{{ asset('assets/front/parkingzone/images/s1.jpg') }}"
                                                    style="position: absolute; height: 602px; width: 100%; ">
                                            </div>
                                        </div>
                                        <!-- LAYERS -->

                                        <!-- LAYER NR. 5 -->
                                        <div class="tp-parallax-wrap hidden-sm hidden-xs"
                                            style="position: absolute; display: block; visibility: visible; left: 98px; top: 138px; z-index: 5;">
                                            <div class="tp-loop-wrap" style="position:absolute;display:block;;">
                                                <div class="tp-mask-wrap"
                                                    style="position: absolute; display: block; overflow: visible;">
                                                    <div class="tp-caption BigWhiteText2   tp-resizeme"
                                                        id="slide-6-layer-1" data-x="100" data-y="center"
                                                        data-voffset="-58" data-width="['auto']" data-height="['auto']"
                                                        data-type="text" data-responsive_offset="on"
                                                        data-frames="[{&quot;delay&quot;:590,&quot;speed&quot;:800,&quot;frame&quot;:&quot;0&quot;,&quot;from&quot;:&quot;x:50px;opacity:0;&quot;,&quot;to&quot;:&quot;o:1;&quot;,&quot;ease&quot;:&quot;Power3.easeInOut&quot;},{&quot;delay&quot;:&quot;wait&quot;,&quot;speed&quot;:300,&quot;frame&quot;:&quot;999&quot;,&quot;to&quot;:&quot;opacity:0;&quot;,&quot;ease&quot;:&quot;Power3.easeInOut&quot;}]"
                                                        data-textalign="['left','left','left','left']"
                                                        data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]"
                                                        data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                                        style="z-index: 5; white-space: nowrap; letter-spacing: -1px; visibility: inherit; transition: none 0s ease 0s; text-align: left; line-height: 80px; border-width: 0px; margin: 0px; padding: 0px; font-weight: 700; font-size: 46px; min-height: 0px; min-width: 0px; max-height: none; max-width: none; opacity: 1; transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1); transform-origin: 50% 50% 0px;">
                                                        {!! $site_settings_main['homepage_tagline_heading'] !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- LAYER NR. 6 -->
                                        <div class="tp-parallax-wrap hidden-sm hidden-xs"
                                            style="position: absolute; display: block; visibility: visible; left: 98px; top: 383px; z-index: 6;">
                                            <div class="tp-loop-wrap" style="position:absolute;display:block;;">
                                                <div class="tp-mask-wrap"
                                                    style="position: absolute; display: block; overflow: visible;">
                                                    <div class="tp-caption SmallWhiteText   tp-resizeme  hidden-sm hidden-xs"
                                                        id="slide-6-layer-2" data-x="100" data-y="center"
                                                        data-voffset="112" data-width="['auto']"
                                                        data-height="['auto']" data-type="text"
                                                        data-responsive_offset="on"
                                                        data-frames="[{&quot;delay&quot;:1460,&quot;speed&quot;:800,&quot;frame&quot;:&quot;0&quot;,&quot;from&quot;:&quot;y:50px;opacity:0;&quot;,&quot;to&quot;:&quot;o:1;&quot;,&quot;ease&quot;:&quot;Power3.easeInOut&quot;},{&quot;delay&quot;:&quot;wait&quot;,&quot;speed&quot;:300,&quot;frame&quot;:&quot;999&quot;,&quot;to&quot;:&quot;opacity:0;&quot;,&quot;ease&quot;:&quot;Power3.easeInOut&quot;}]"
                                                        data-textalign="['inherit','inherit','inherit','inherit']"
                                                        data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]"
                                                        data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                                        style="color:#ffffff; z-index: 6; white-space: nowrap; font-size: 20px; line-height: 28px; letter-spacing: 0px; visibility: inherit; transition: none 0s ease 0s; text-align: inherit; border-width: 0px; margin: 0px; padding: 0px; font-weight: 400; min-height: 0px; min-width: 0px; max-height: none; max-width: none; opacity: 1; transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1); transform-origin: 50% 50% 0px;">

                                                        {!! $site_settings_main['homepage_tagline'] !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-5 col-sm-12 col-xs-12 "
                                            style="position: absolute;display: block;visibility: visible;right: 0;top: 10px;color: white;font-weight: bold;z-index: 5;">

                                            <div id="main" class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="search-box-wrapper style1">
                                                    <div class="search-box">


                                                        <ul class="nav nav-pills nav-ul col-md-3">
                                                            <li class="active">
                                                                <a data-toggle="pill" href="#flights-tab">
                                                                    <center>
                                                                        <i class="nav-icon fa fa-car"></i>
                                                                    </center>
                                                                    <span>Parking</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a data-toggle="pill" href="#lounges">
                                                                    <center>
                                                                        <i class="nav-icon fa fa-coffee"></i>
                                                                    </center>
                                                                    <span>Lounges</span>
                                                                </a>
                                                            </li>
                                                            <li class="">
                                                                <a data-toggle="pill" href="#transfert">
                                                                    <center>
                                                                        <i class="nav-icon fa fa-exchange"
                                                                            aria-hidden="true"></i>
                                                                    </center>
                                                                    <span>Transfer</span>
                                                                </a>
                                                            </li>
                                                        </ul>

                                                        <div class="search-tab-content col-md-9">
                                                            <div class="tab-pane fade active in" id="flights-tab">
                                                                <form method="POST"
                                                                    action="{{ route('searchresult') }}">
                                                                    <div class="title-container">
                                                                        <h1 style="font-size: 20px"
                                                                            class="search-title" align="center">
                                                                            Get a Quote!</h1>
                                                                    </div>
                                                                    <div class="search-content">
                                                                        <p class="title">
                                                                            Select an
                                                                            Airport</p>
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-md-12 col-sm-12">
                                                                                <div class="selector">
                                                                                    <select name="airport_id"
                                                                                        style="height:43px; padding-left:10px;"
                                                                                        name="selected_airport"
                                                                                        required="required">
                                                                                        @foreach ($airports as $airport)
                                                                                            <option
                                                                                                @if (old('airport_id') == $airport->id) {{ 'selected' }} @endif
                                                                                                value="{{ $airport->id }}">
                                                                                                {{ ucwords($airport->name) }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        @php $date = date("Y-m-d"); @endphp
                                                                        <p class="title">
                                                                            Dropoff date and
                                                                            time</p>
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-md-6 col-sm-12">
                                                                                <input type="text"
                                                                                    name="dropoffdate"
                                                                                    autocomplete="off"
                                                                                    class="form-control dpd1"
                                                                                    placeholder="Dropoff Date"
                                                                                    value="{{ $date }}"
                                                                                    readonly />
                                                                            </div>
                                                                            <div class="col-xs-12 col-md-6 col-sm-12">
                                                                                @php
                                                                                    $dropdown_timer = [];
                                                                                    for ($i = 0; $i <= 23; $i++) {
                                                                                        for (
                                                                                            $j = 0;
                                                                                            $j <= 45;
                                                                                            $j += 15
                                                                                        ) {
                                                                                            $dropdown_timer[
                                                                                                str_pad(
                                                                                                    $i,
                                                                                                    2,
                                                                                                    '0',
                                                                                                    STR_PAD_LEFT,
                                                                                                ) .
                                                                                                    ':' .
                                                                                                    str_pad(
                                                                                                        $j,
                                                                                                        2,
                                                                                                        '0',
                                                                                                        STR_PAD_LEFT,
                                                                                                    )
                                                                                            ] =
                                                                                                str_pad(
                                                                                                    $i,
                                                                                                    2,
                                                                                                    '0',
                                                                                                    STR_PAD_LEFT,
                                                                                                ) .
                                                                                                ':' .
                                                                                                str_pad(
                                                                                                    $j,
                                                                                                    2,
                                                                                                    '0',
                                                                                                    STR_PAD_LEFT,
                                                                                                );
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                                {{ Form::select('dropoftime', $dropdown_timer, '', ['class' => 'form-control', 'id' => 'dropoftime']) }}


                                                                            </div>
                                                                        </div>


                                                                        <p class="title">
                                                                            Pickup date and
                                                                            time</p>
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-md-6 col-sm-12">
                                                                                <input type="text" readonly
                                                                                    autocomplete="off"
                                                                                    name="departure_date"
                                                                                    class="form-control dpd2"
                                                                                    placeholder="Departure Date"
                                                                                    <?php
                                                                                    $mydate = $date;
                                                                                    $daystosum = '2';
                                                                                    $datesum = date('Y-m-d', strtotime($mydate . ' + ' . $daystosum . ' days'));
                                                                                    ?>
                                                                                    value="{{ $datesum }}"
                                                                                    value="" />
                                                                            </div>
                                                                            <div class="col-xs-12 col-md-6 col-sm-12">

                                                                                {{ Form::select('pickup_time', $dropdown_timer, '', ['class' => 'form-control', 'id' => 'pickup_time']) }}

                                                                            </div>
                                                                        </div>


                                                                        <p class="title">
                                                                            Promo code </p>
                                                                        <div class="row">
                                                                            <div class="col-xs-12 col-md-6 col-sm-12">

                                                                                @php $prmocode =old("promo");  @endphp
                                                                                <input type="text" name="promo"
                                                                                    value=" {{ $prmocode }}"
                                                                                    class="form-control"
                                                                                    placeholder="enter promo code " />
                                                                                @if ($errors->has('promo'))
                                                                                    <div class="error error-massage"
                                                                                        style="color:red">
                                                                                        {{ $errors->first('promo') }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-xs-12 col-md-6 col-sm-12">
                                                                                <button type="submit"
                                                                                    class="form-control btn btn-medium">
                                                                                    Search
                                                                                </button>
                                                                            </div>
                                                                        </div>


                                                                    </div>


                                                            </div>
                                                            <div class="tab-pane fade" id="lounges">
                                                                <div class="title-container">
                                                                    <h1 style="font-size: 20px" class="search-title"
                                                                        align="center">
                                                                        Get a Quote!</h1>
                                                                </div>

                                                                <h3 class="comming_soon">Comming
                                                                    Soon Lounges</h3>
                                                            </div>
                                                            <div class="tab-pane fade" id="transfert">
                                                                <div class="title-container">
                                                                    <h1 style="font-size: 20px" class="search-title"
                                                                        align="center">
                                                                        Get a Quote!</h1>
                                                                </div>

                                                                <h3 class="comming_soon">Comming
                                                                    Soon Lounges</h3>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                        </div>
                            </div>
                            </li>
                            </ul>
                            <div class="tp-bannertimer tp-bottom"
                                style="visibility: hidden; width: 10.9222%; transform: translate3d(0px, 0px, 0px);">
                            </div>
                            <div class="tp-loader spinner0" style="display: none;">
                                <div class="dot1"></div>
                                <div class="dot2"></div>
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                        </div>

                    </div><!-- END REVOLUTION SLIDER -->
                </div>
            </div>
        </div>
    </div>
</div>
