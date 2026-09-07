@include('layouts.header')
@include('layouts.nav')

<div class="js-product-results-page">
@include('partials.results-page-header', ['title' => 'Airport Transfer Results'])

<style type="text/css">
.modal-dialog p{
    color: #000 !important;
}
.modal-dialog span{
    color: #000 !important;
}
.checked {
    color: orange;
}
.home {
    width: 100%;
    height: 60vh;
}
.modal-header{
        background-color: #f8a031;  
}
.nav-tabs>li.active>a{
    border: 2px solid #000 !important;
}
.modal-content{
        border: 2px solid #673ab7;
    border-radius: 10px;
}
.home_slider_container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 73%;
    z-index: 10;
    background: #31124b;
}
.pricingdiv{
  margin-top: 2%;
  display: flex;
  flex-wrap: wrap;
  font-size: 16px;
  justify-content: center;
  font-family: 'Source Sans Pro', Arial, sans-serif;
  width:100%;
  background: #fffbf7;
  box-shadow: 2px 3px 15px 6px #00000040!important;
  margin: 2%;
  padding-top: 4%;
}

.pricingdiv ul.theplan{
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  color: black;
  width: 260px; /* width of each table */
  margin-right: 20px; /* spacing between tables */
  margin-bottom: 1em;
  border: 1px solid gray;
  transition: all .5s;
}

.pricingdiv ul.theplan:hover{ /* when mouse hover over pricing table */
  transform: scale(1.05);
  transition: all .5s;
  z-index: 10;
  box-shadow: 0 0 10px gray;
}

.pricingdiv ul.theplan .center{
  margin: 0 auto;
  text-align: center;
}

.pricingdiv ul.theplan img{
  max-width: 80%;
  height: auto;
}

.pricingdiv ul.theplan li{
  /*padding: 10px 10px;*/
  position: relative;
  border-bottom: 1px solid #eee;
}

.pricingdiv ul.theplan li.title{
  position: relative;
  font-weight: bold;
  text-align: center;
  padding: 30px 10px;
  background: rgb(40, 193, 203);
  color: white;
  box-shadow: 0 -10px 5px rgba(0,0,0,.1) inset;
  text-transform: uppercase;
}

.pricingdiv ul.theplan:nth-of-type(2) li.title{
  background: rgb(249, 111, 118);
  color: white;
}
    
.pricingdiv ul.theplan:nth-of-type(3) li.title{
  background: rgb(210, 117, 251);
  color: white;
}

.pricingdiv ul.theplan li b{
  text-transform: uppercase;
}
.pricingdiv ul.theplan li.title b{
  font-size: 250%;
}

.pricingdiv ul.theplan:last-of-type{ /* remove right margin in very last table */
  margin-right: 0;
}

/*very last LI within each pricing UL */
.pricingdiv ul.theplan li:last-of-type{
  /*text-align: center;*/
  margin-top: auto; /*align last LI (price botton li) to the very bottom of UL */
} 
.nav>li>a {
    padding: 10px 8px;
}
.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    color: #000;
    font-weight: 500;
} 

/*.pricingdiv a.pricebutton{
  background: #fa9e1b;
  text-decoration: none;
  display: inline-block;
  margin: 10px auto;
  border-radius: 5px;
  color: white;
  font-weight: bold;
  border-radius: 0px;
  text-transform: uppercase;*/

.modal-body ul li a{
  color: #33214a;
}

button:focus {
    outline: 1px dotted;
    outline: none;
}
@media only screen and (max-width: 600px) {
  .pricingdiv ul.theplan{
    border-radius: 0;
    width: 100%;
    margin-right: 0;
  }
  
  .pricingdiv ul.theplan:hover{
    transform: none;
    box-shadow: none;
  }
  
  .pricingdiv a.pricebutton{
    display: block;
  }
}
.pricingdiv ul.theplan img {
    max-width: 100%!important;
    height: 110px;
    width: 100%;
}
 span>.fa {
    padding:4px;
 font-size: 20px;
}

.pricingdiv ul.theplan {
    
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    color: #fa9e1b;
    width: 250px;
    /* margin-right: 52px; */
    
    border: 1px solid #dad9d9;
    transition: all .5s;
    background-color: #ffffff;
    margin: 1%;
}

.pricingdiv ul.theplan li {
    padding: 1px 15px;
    position: relative;
    border-bottom: 1px solid #eee;
}
@media (min-width: 1200px){
    .col-1 {
        width: 100%;
        top: -317px;
    }
}
.search {
    top: -200px;
}

.rating_4 i:first-child {
    color: rgba(1, 11, 31, 0.69);
}
}
.small, small {
    font-size: 57%;
     color: #f1cccc;
}

.offers_grid {
    width: 100%;
    margin-top: -206px;
}

@media only screen and (max-width: 991px)
{
    .search {
        height: auto;
        padding-top: 15px;
        padding-bottom: 100px;

    }
    .offers_grid {
        width: 100%;
        margin-top: -112px;
    }
    .search_tabs_container {
        margin-bottom: 10px; 
    }
    .offers_grid {
        margin-top: 0px !important;
    }
}
.order{
    margin: 5px;
    height: 28px;
    position: absolute;
    text-align: center;
    /* opacity: 1; */
    background: #C2185B!important;
    color: #fff;
    -webkit-animation: bounce .3s infinite alternate;
    -moz-animation: bounce .3s infinite alternate;
    animation: bounce .3s infinite alternate;
    -webkit-animation-iteration-count: 8;
    -moz-animation-iteration-count: 8;
    animation-iteration-count: 8;
}
.home_slider_content h1:nth-child(1) {
    font-size: 31px;
    color: #ffffff;
    margin-top: 10%;
   
    font-family: sans-serif;
}
.title p{
  color: white;
  font-size: 15px;
  font-weight: bold;
}

.pricebutton{
  padding: 2%; 
  background-color: #fa9e1b !important; 
  width: 100%; 
  border: 2px solid white;
  color: white;
  border-radius: 20px; 
  margin-bottom: 5%;
  cursor: pointer;
}
.home_slider_content_inner h1.result {
  text-align: center;
  margin-left: 0; 
}
#bookingFrm1 .pricebutton{
    width: 70%;
}
#bookingFrm1 .pricebutton:hover{
    width: 70%;
    background-color: #4c256e !important;
}
.moreinfo:hover{
	cursor: pointer;
}
.search_item:nth-child(4) {
    width: 12.947%;
    margin-right: 6px;
}
.modal-header .close {
    padding: 15px;
    margin: -15px -15px -15px auto;
    margin-left: -80px;
    font-size: 30px;
}
.scheme_default .close {
        background: #4c256e00;
}
@media only screen and (max-width: 767px)
{
    .search {
        padding-top: 15px;
        padding-bottom: 120px;

    }
    .scheme_default .close {
        background: #4c256e00;
    }
    .close{
        color: #31124b;
    font-size: 55px;
        margin-left: 80%;
    font-weight: bold;
    margin-top: -44px;
    }
    .modal-header .close{
        padding: 0px 10px;
    }
    .modal-header{
        display: block;
    }
}
    @media only screen and (max-width: 500px){
        .container.fill_height {
            margin-top: 0px;
        }  
        .search_item div{
            margin-bottom: 5px !important;
        }
       /* .search_input{
            height: 35px !important;
        }*/
        .search {
            padding-top: 0px;
            padding-bottom: 90px;
        }
    }
    
</style>

<div class="home-container home-background">


{{session()->get('bk_src')}}
</div>
  <div class="home">
        
        <!-- Home Slider -->

        <div class="home_slider_container">
            
            <div class="owl-carousel owl-theme home_slider">

  

       <div class="home_slider_background" style=""></div>

                    <div class="home_slider_content text-center">
                        <div class="home_slider_content_inner" data-animation-in="flipInX" data-animation-out="animate-out fadeOut">

                       
                            <h1 class="result">Results</h1>

                        </div>

                    </div>

                <!-- Slider Item -->
             

            </div>
            
            <!-- Home Slider Nav - Prev -->

            


            <!-- Home Slider Dots -->

         
            
        </div>

    </div>

      
      
<section id="section" class="js-product-results-section">
	<div class="container">
        <div id="ajax_search_results">
            <div class="spiner_container" style="margin-bottom:400px"><div class="lds-dual-ring"></div></div>
        </div>
    </div>
</section>

</div>

@include('layouts.footer')

<script type="text/javascript">

$('.spiner_container').show();
                
$( document ).ready(function() {



    var ajdata = {};

    ajdata['loc_type'] = '{{ request()->loc_type }}';
    ajdata['loc_code'] = '{{ request()->loc_code }}';
    ajdata['loc_name'] = '{{ request()->loc_name }}';
    ajdata['loc_lat'] = '{{ request()->loc_lat }}';
    ajdata['loc_long'] = '{{ request()->loc_long }}';
    ajdata['loc_id'] = '{{ request()->loc_id }}';
    ajdata['loc_country'] = '{{ request()->loc_country }}';
    
    
    ajdata['loc_type_drop'] = '{{ request()->loc_type_drop }}';
    ajdata['loc_code_drop'] = '{{ request()->loc_code_drop }}';
    ajdata['loc_name_drop'] = '{{ request()->loc_name_drop }}';
    ajdata['loc_lat_drop'] = '{{ request()->loc_lat_drop }}';
    ajdata['loc_long_drop'] = '{{ request()->loc_long_drop }}';
    ajdata['loc_id_drop'] = '{{ request()->loc_id_drop }}';
    ajdata['loc_country_drop'] = '{{ request()->loc_country_drop }}';
    
    ajdata['arrival_date'] = '{{ request()->arrival_date }}';
    ajdata['arrival_time'] = '{{ request()->arrival_time }}';
    ajdata['return_date'] = '{{ request()->return_date }}';
    ajdata['return_time'] = '{{ request()->return_time }}';
    ajdata['adults'] = '{{ request()->adults }}';
    ajdata['children'] = '{{ request()->children }}';
    ajdata['infants'] = '{{ request()->infants }}';
    

    $.ajax(
    {
        url: '{{ route("searchresult_transfer") }}',
        type: "POST",
        data: ajdata
        //datatype: "html"
    }).done(function(data){
        $("#ajax_search_results").empty().html(data);
        $('.spiner_container').hide();
        $('#to-top').click();
        //location.hash = page;
    }).fail(function(jqXHR, ajaxOptions, thrownError){
          $('.spiner_container').hide();
          //alert('No response from server');
          console.log('No response from server');
    });
    console.log( "ready!" );
})

</script>

<script type="text/javascript">
    $("a.apply-active").on('click', function () {
        $('li.active').removeClass('active');
       // $(this).closest("li.active").removeClass('active');
        $(this).closest("li").addClass('active');


        $(".tab-pane").hide();
        $($(this).attr("href")).show();
    });
</script>
