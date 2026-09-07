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

@if(request()->get('source') == 'webgains')
{{ session()->put('bk_src', 'WG' )}}
@endif

@php
// $discount = request()->get('discount')
$discount = 0
@endphp

<?php
$ip = session()->get('user_ip');
$data['ref_url'] = session()->get('ref_url');
if(session()->get('bk_src') != ''){
$data["traffic_src"] = session()->get('bk_src');
}else{
$data["traffic_src"]='ORG';
}
$data["agentID"]='1';
$data["user_ip"]=$ip;
$data["current_url"]=url()->full();
if(session()->get('userEmail') != ''){
    $data['email'] = session()->get('userEmail');
}
$update = ref_tracking::Create($data);


?>

<style>
    .form-control,.form-control:focus,.form-control:hover{
        border-radius: 12px !important;
        background: rgb(255 255 255 / 12%) !important;
        color: white;
        height: 42px;
        border: 1px solid #ced4da;
        font-weight: 500;
    }
    .form-control2{
    box-shadow: none;
    border: 1px solid #DADADA;
    height: 42px;
    display: block;
    width: 100%;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    border-radius: 12px;
    background: rgb(255 255 255 / 12%);
    color: white;
    }
    .ui-datepicker .ui-datepicker-next span, .ui-datepicker .ui-datepicker-prev .ui-icon-circle-triangle-w span:before{
        transform: rotate(45deg);
            border-style: solid;
    border-width: 3px 3px 0 0;
    height: 7px;
    width: 7px;
    cursor: pointer;
    content: "";
    display: inline-block;
    top: 68% !important;
    left: 63% !important;
    }
    .ui-icon, .ui-widget-content .ui-icon, .ui-widget-header .ui-icon{background-image:none !important;}
    .ui-icon, .ui-widget-content .ui-icon, .ui-widget-header .ui-icon .ui-icon-circle-triangle-e span:before{
        transform: rotate(-135deg);
            border-style: solid;
    border-width: 3px 3px 0 0;
    height: 7px;
    width: 7px;
    cursor: pointer;
    content: "";
    display: inline-block;
    top: 68% !important;
    left: 63% !important;
    }
    label,input,option{font-family: "Poppins",Sans-serif !important;}
    
    @media screen and (min-width: 992px) {
        .md-2-width{
            width: 8%;
            padding: 0;
        }
        .form-control{padding-left: 42px !important;}
        .form-control2{padding-left: 42px !important;}
    }
    @media screen and (min-width: 992px) {
        .md-2-width{
            width: 8%;
            padding: 0;
        }
        .small-icon{
            position: absolute;left: 10px;top: 10px;color: #fff;font-size: 19px;
        }
    }
    @media screen and (max-width: 991px) {
        .small-icon{
            position: absolute;left: 23px;top: 41px;color: #fff;font-size: 19px;
        }
    }
    input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
  color: #fff !important;
}
.form-control option{
    color:#000;
}
input:-moz-placeholder,
textarea:-moz-placeholder {
  color: #fff !important;
}
.compare-parking-btn-inner,.compare-parking-btn-inner:focus,.compare-parking-btn-inner:hover{border-radius: 12px !important;background: #FB9F00;color: white;}
.search-landing {
    background: #4c256ad6;
    padding: 20px;
    border-radius: 22px;
}
.datepicker, .datepicker td, .datepicker th{
    display: none !important;
}
.lChev, .rChev{
    z-index: 4;
}
</style>
<form method="get" action="{{ route('searchresult') }}" id="search_form_1" class="search_panel_content" style="padding:10px">
	<div class="row">
		<div class="col-md-12">
			<label class="col-12 pd-lr0 ">Airport</label>
			<select id="airport" name="airport_id" class="form-control form-padding">
		        @foreach($airports as $airport)

			    <option @if(request()->airport_id==$airport->id) selected @endif  value="{{ $airport->id }}">{{ $airport->name }}</option>

				@endforeach
			</select>
			
			<i class="fa fa-plane hidden-sm hidden-xs" style="position: absolute;left: 21px;top: 41px;color: #fff;font-size: 19px;"></i>
		</div>
		<!--<div class="col-lg-12" style="margin-top:5px">-->
		<!--    <label class="col-12 pd-lr0">Departure </label>-->
		<!--</div>-->
		<div class="col-md-7 col-sm-7 col-7" style="margin-top: 10px;">
		    
			<label class="col-md-12 pd-lr0">Departure Date</label>
			<div class="form-group">
				<input type="text" class="form-control check_in search_input \ dpd1" id="startDate" name="dropoffdate" placeholder="DD/MM/YYYY" readonly required="required">
				<i class="far fa-calendar-alt hidden-sm hidden-xs" style="float: left;margin-top: -30px;color: #fff;font-size: 17px;left: 10px;position: relative;"></i>
			</div>
		</div>
		@php
            $dropdown_timer = [];
            for ($i = 0; $i <= 23; $i++) {
                for ($j = 0; $j <= 45; $j += 15) {
                    $dropdown_timer[str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT) . ':' . str_pad($j, 2, "0", STR_PAD_LEFT);
                }  
            }
        @endphp
		<div class="col-md-5 col-sm-5  col-5" style="margin-top: 10px;">
			<label class="col-md-12 pd-lr0">Time</label>
			<div class="form-group">
				<select class="form-control parking-select" name="dropoftime" id="departure_time" style="-webkit-appearance: none;">
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
				<i class="far fa-clock small-icon hidden-sm hidden-xs"></i>
			</div>
		</div>
		<!--<div class="col-12" style="margin-top:5px">-->
		<!--    <label class="col-md-12 pd-lr0">Arrival </label>-->
		<!--</div>-->
		<div class="col-md-7 col-sm-7  col-7">
			<label class="col-md-12 pd-lr0">Arrival Date</label>
			<div class="form-group">
				<input type="text" class="form-control check_in search_input dpd2" id="endDate" name="departure_date" placeholder="DD/MM/YYYY" readonly required="required">
				<i class="far fa-calendar-alt hidden-sm hidden-xs" style="float: left;margin-top: -30px;color: #fff;font-size: 17px;left: 10px;position: relative;"></i>
			</div>
		</div>
		<div class="col-md-5 col-sm-5  col-5">
			<label class="col-12 pd-lr0">Time</label>
			<div class="form-group">
				<select class="form-control parking-select" name="pickup_time" id="arrival_time" style="-webkit-appearance: none;">
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
				<i class="far fa-clock small-icon hidden-sm hidden-xs"></i>
			</div>
		</div>
		<!--<div class="col-12 col-sm-12">-->
  <!--          <label class="col-12 pd-lr0">Email</label>-->
  <!--          <div class="form-group">-->
  <!--              <input type="email" class="form-control" name="email" value="{{ request()->email }}" placeholder="Enter your email" required>-->
  <!--              <i class="far fa-envelope hidden-sm hidden-xs" style="position: absolute;left: 10px;top: 13px;color: #fff;font-size: 19px;"></i>-->
  <!--          </div>-->
  <!--      </div>-->
        <div class="col-md-7 col-sm-7  col-7">
			<label class="col-md-12 pd-lr0">Promo Code:</label>
			<div class="form-group">
				<input class="form-control" name="promo" id="promo"  placeholder="Optional" value="{{ request()->get('promo') }}">
				<input type="hidden" name="submitted" value="Yes">
				<input type="hidden" name="booking_for" value="airport_parking">
			</div>
		</div>
		<div class="col-md-5 col-sm-5  col-5">
		    <label class="col-12 pd-lr0">Cars</label>
		    <select class="form-control parking-select" name="vehicles" id="vehicles">
                @foreach (range(1, 5) as $value)
                    <option value="{{ $value }}" {{ request()->vehicles == $value ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
			<i class="fas fa-car-side  small-icon hidden-sm hidden-xs" style="position: absolute;left: 25px;top: 40px;color: #fff;font-size: 19px;"></i>
		</div>
		<div class="col-md-12 col-sm-12 text-center">
			<button  type="submit" id="" value="Get a quote" class="btn compare-parking-btn-inner hidden-sm hidden-xs">Reserve Your Spot & Save Up to 50%</button>
			<button  type="submit" id="" value="Get a quote" class="btn compare-parking-btn-inner hidden-md hidden-lg">Get A Quote</button>
		</div>
	</div>
</form>	
						