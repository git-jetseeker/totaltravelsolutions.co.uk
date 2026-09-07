<?php  

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];  
  
    
    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];  
    $url = str_replace(rtrim(url('/'), '/') . '/', '', $url);
    $url = str_replace('https://www.totaltravelsolutions.co.uk/', '', $url);
    $url = str_replace('http://www.totaltravelsolutions.co.uk/', '', $url);
    $sagment = str_replace('/', '', $_SERVER['REQUEST_URI']);
    // echo $url;
    // exit;
   
    if($url == 'gatwick-airport-parking'|| $url == 'aberdeen-airport-parking' || $url == 'heathrow-airport-parking' || $url == 'stansted-airport-parking'
    || $url == 'birmingham-airport-parking' || $url == 'bournemouth-airport-parking' || $url == 'bristol-airport-parking' || $url == 'edinburgh-airport-parking' 
    || $url == 'southampton-airport-parking' || $url == 'liverpool-airport-parking' || $url == 'luton-airport-parking' || $url == 'manchester-airport-parking' 
    || $url == 'cardiff-airport-parking' || $url == 'doncaster-airport-parking' || $url == 'east-midlandsairport-parking' || $url == 'glasgow-airport-parking')
    {
          $redirecturl = url('/airport/'.$url);
    }
    elseif($sagment == 'cookies'|| $sagment == 'affiliates' || $sagment == 'site-security' || $sagment == 'about-us'
    || $sagment == 'privacy-policy' || $sagment == 'contact-us' || $sagment == 'terms-and-conditions')
    {
        $redirecturl = url('/'.$sagment);
    }
    else
    {
    $redirecturl = url('/blog/'.$url);
    }
    header("Location: $redirecturl");
die();
    echo $url; 
    exit;
  ?>   

@include('layouts.header')
@include('layouts.nav')
@section("title",$page->meta_title)
@section("meta_keyword",$page->meta_keyword )
@section("meta_description",$page->meta_description)

<style type="text/css">
.form-control {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
}
@media    screen and (max-width: 768px){
    .well{
        padding: 0px;
    }
    .top-offer img{
        display: none !important;
    }
}
.passenger-detail h1 {
    font-size: 21px;
    background: orange !important;
     padding: 0 20px;
    line-height: 1.6;
    background: linear-gradient(to right, #fa9e1b, orange);
    color: #fff;
    font-weight: 600;
}
.passenger-detail h3{
        font-size: 20px;
        border-radius: 0;
}
.block-card {
  display: none;
}

.block-card.active {
  display: block;
}
.accordion{
    padding: 20px;
}

</style>
    <section class="top-offer margintop80" style="top:40px">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="row">
                        <div class="panel passenger-detail bgbanner" style="padding: 20px">
                            @if (Request::is('about-us'))
                                 <h1 class="light-weight">{{ $page->page_title  }}</h1>
                                <div class="well-body myclass1">
                                    {!! $page->airport_parking !!}
                                </div>
                            @else
                            <section class="top-offer " style="top:0px; margin-bottom:10px ">
                                <div class="container padding0 hidden-xs hidden-sm">
                                    <div class="col-md-12 ">
                                        <div class="section-title text-left   passenger-detail">
                                            <h3 style="    width: 100%;
                            margin-left: 0px;" class="margin10left text-center">{{ $page->page_title  }}</h3>
                                            <div class="margin10left ap_page_content text-left ">
                                                    <div class="bhoechie-tab-menu">
                                                        <div class="list-group">
                                                            <ul class="nav nav-tabs row justify-content-center" id="menu-tabs">
                                                                <li class=" tabs-button" id="liofairports" ><a data-target="#block-1" data-toggle="tab" aria-expanded="false" href="#parking"
                                                                                class="list-group-item text-center filter-btn active" id="rounded" >
                                                                        <i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> <br>Airport Paking
                                                                    </a></li>
                                                                <li class=" tabs-button" id="liofairports" ><a data-target="#block-2" data-toggle="tab" aria-expanded="false" href="#overview"
                                                                                class="list-group-item text-center filter-btn" id="rounded">
                                                                        <i class="fa fa-road fa-lg" aria-hidden="true"></i> <br>Airport Overview
                                                                    </a></li>
                                                                <li class=" tabs-button" id="liofairports"><a data-target="#block-3" data-toggle="tab" aria-expanded="false" href="#fac"
                                                                                      class="list-group-item text-center filter-btn" id="rounded">
                                                                        <i class="fa fa-home fa-lg" aria-hidden="true"></i> <br>Airport facilities
                                                                    </a></li>
                        
                                                                <li class=" tabs-button" id="liofairports"><a data-target="#block-5" data-toggle="tab" aria-expanded="false" href="#top_things"
                                                                                class="list-group-item text-center" id="rounded">
                                                                        <i class="fa fa-child fa-lg" aria-hidden="true"></i> <br>Top things to do
                                                                        at </a></li>
                                                                {{--<li class=""><a data-toggle="tab" aria-expanded="false" href="#map"--}}
                                                                {{--class="list-group-item text-center">--}}
                                                                {{--<h4><i class="fa fa-map-marker" aria-hidden="true"></i></h4>Map--}}
                                                                {{--</a></li>--}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab-container " style="    border-top-right-radius: 5px;">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tab-content block-card active" id="block-1">
                                                        Airport Paking                         </div>
                                                        
                                                        <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tab-content  block-card" id="block-2">
                                                           Airport Overview
                                                        </div>
                                                        <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tab-content  block-card" id="block-3">
                                                            Airport facilities
                                                        </div>
                                                        <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tab-content  block-card" id="block-5">
                                                            Top things to do
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="clearfix"></div>
                               
                            </section>
                            
                            @endif
                           
                           
                        </div>
                    </div>
                </div>
                <div class="hidden-xs hidden-sm col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    @include("frontend.right_searchbar")
                </div>
            </div>
        </div>
    </section>
@include('layouts.footer')

<script>
    let $blocks = $('.block-card');

$('.filter-btn').on('click', e => {
  let $btn = $(e.target).addClass('active');
  $btn.siblings().removeClass('active');
  
  let selector = $btn.data('target');
  $blocks.removeClass('active').filter(selector).addClass('active');
});
</script>