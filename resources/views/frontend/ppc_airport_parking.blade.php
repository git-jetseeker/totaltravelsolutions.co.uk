



















@include('layouts.header')
@include('layouts.nav')
<style>
    .copyright{
        /*height: 80px;*/
    padding-top: 10px;
    }
    @media only screen and (max-width: 991px) {
   .home_slider{
            display:none !important;
        }
}
@media only screen and (max-width: 767px) {
   .for-offres-intro .intro_center{
        min-height: 160px!important;
        }
}
.tipsh2{
    padding-top: 60px;
    font-size: 40px;
    font-weight: bolder;
    text-align: center;
    padding-bottom: 60px;
}
.ppc-h2-tag{
    text-align: left;
    padding: 0;
    font-size: 34px;
    font-weight: 600;
    color: #4D2375;
}
.ppc-h3-tag{
    font-size: 30px;
    font-weight: 100;
    color: black;
}
.ppc-li-tag{
    color: black;
    font-size: 20px;
}
.col-bag{
    background: rgb(75 37 109/20%);
    padding: 26px;
    border-radius: 15px;
    margin: 30px 0;
}
</style>
@php
$site_settings_main=[];
$settingsAll = App\Models\settings::all();
foreach ($settingsAll as $setting) {
if($setting->agent_id == '1')
$site_settings_main[$setting->field_name] = $setting->field_value;
}

$sliders = unserialize($site_settings_main['sliders']);

@endphp

@include('layouts.search_form')
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-2"></div>
            <div class="col-lg-8 col-md-8 col-bag">
                <h2 class="ppc-h2-tag text-center">Save up to 60% Airport Parking</h2>
                <!--<h3 class="ppc-h3-tag">on your airport parking</h3>-->
                <br>
                <ul>
                    <li class="ppc-li-tag">
                        <i class="fa fa-check" aria-hidden="true" style="color: #30922F;"></i> &nbsp; Park Mark Awarded Car Parks Only
                    </li>
                    <li class="ppc-li-tag">
                        <i class="fa fa-check" aria-hidden="true" style="color: #30922F;"></i> &nbsp; 24 hour security at all car parks
                    </li>
                    <li class="ppc-li-tag">
                        <i class="fa fa-check" aria-hidden="true" style="color: #30922F;"></i> &nbsp; Price Check Gurantee*
                    </li>
                    <li class="ppc-li-tag">
                        <i class="fa fa-check" aria-hidden="true" style="color: #30922F;"></i> &nbsp; Years of professional experience
                    </li>
                    <li class="ppc-li-tag">
                        <i class="fa fa-check" aria-hidden="true" style="color: #30922F;"></i> &nbsp; Easy Cancellation and Amendments
                    </li>
                    <li class="ppc-li-tag">
                        <i class="fa fa-check" aria-hidden="true" style="color: #30922F;"></i> &nbsp; 94% customer satisfaction
                    </li>
                </ul>
                <br>
                <p style="font-size:14px">*We compare  our prices daily with other service providers, therefore we offer price check gurantee. </p>
            </div>
            <div class="col-lg-2 col-md-2"></div>
        </div>
    </div>
</section>
<section style="background: #EEEEEE;">
    @include('frontend.slider-main')
</section>

@include('layouts.footer')
<script>
    $(function () {
    $(".more1").slice(0, 6).addClass('display');
    $("#loadMore").on('click', function (e) {
        e.preventDefault();
        $(".more1:hidden").slice(0, 14).addClass('display');
        if ($(".more1:hidden").length == 0) {
           $("#loadMore").remove();
        } else {
            $('html,body').animate({
                scrollTop: $(this).offset().top
            }, 1500);
        }
    });
});
</script>
<script>
//display datepicker on top side if page is not scrolled down
	function getVisible() {    
    var $el = $('#home_search_form'),
    scrollTop = $(this).scrollTop();
	var top_height =  $(window).height() - ( $('#home_search_form').offset().top + $('#home_search_form').height() );
    $('#notification').text(top_height-scrollTop);
	if((top_height-scrollTop)>(-300))
	{
		$('div.cal').addClass('calendor_top');
	}
	else
	{
		$('div.cal').removeClass('calendor_top');
	}

}
</script>

<script>
    $(function(){
    $(".div").slice(0, 6).show(); // select the first ten
    $("#load").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div:hidden").slice(0, 15).show(); // select next 10 hidden divs and show them
       
    });
});
</script>

