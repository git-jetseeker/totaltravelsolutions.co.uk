@extends('layouts.main')
@section("title",$page->meta_title)
@section("meta_keyword",$page->meta_keyword )
@section("meta_description",$page->meta_description)
@section('content')

    @php
        $site_settings_main=[];
            $settingsAll = App\settings::all();
                    foreach ($settingsAll as $setting) {
                         if($setting->agent_id == '1')
                       {
                        $site_settings_main[$setting->field_name] = $setting->field_value;
                        }
                    }
    @endphp
    <style>
        .th_class {
            background: #ffcb05;
        }

        .inner-section {
            background-color: #eff2f3;
            padding: 5px 0;
        }

 .passenger-detail h3 {
          
            padding: 10px 20px;
            line-height: 1.6;
            background: linear-gradient(to right,rgba(14, 127, 28, 0.9) 0,rgba(39, 129, 238, 0.9));
            color: #fff;
             border-radius: 5px;
        }
     .panel{
          
           margin-bottom: 20px;
    /* background-color: #fff; */
   border: 0px solid transparent !important; 
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    box-shadow: 0 1px 1px rgba(0, 0, 0, .05);        }
       
.padding h2 {
    font-size: 22px;
    color: #1d9cbc;
    padding-left: 22px;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    margin-bottom: 0;
}

        #menu-tabs li {
            width: 100%;
            border: 1px solid #ccc;
        }

        .nav-tabs {
            border: 1px solid #ddd;
        }

        a:active {
            background: #ffcb05;
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {

            border: none !important;
            border-bottom-color: inherit !important;
            background: #16bbbb;
         
        }

        .bhoechie-tab-container {
            border: 1px solid #ccc;
            /* margin: -9px; */
            margin-top: 0px;
            margin-right: -9px;
            /* margin-bottom: -9px; */
            margin-left: -9px;
            padding: 0px;
        }

        .ap_page_content {
            padding-left: 19px;
        }

        .bhoechie-tab-menu {
            padding: 0px !important;
        }

        .inner-step i {
            border-radius: 50%;
            background: #ffcb05;
            color: #fff;
            padding: 23px;
            position: relative;
            display: inline-block;
            text-align: center;
        }
        .nrm-cont h3 {
    font-size: 22px;
    color: #1d9cbc;
    padding-left: 22px;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    margin-bottom: 0;
}

        .inner-step i path {
            fill: #fff;
        }

        .inner-step i svg {
            width: 60px;
            display: table-cell;
            vertical-align: middle;
            height: 60px;
        }

        .inner-step h5 {
            font-weight: 800;
            margin: 30px 0px 10px;
            text-transform: uppercase;
            color: #ffcb05;
            font-size: 34px;
        }

        .hxComment li {
            list-style-type: none;
        }

        .sb-serc {
            text-align: center;
            background: url(assets/images/banner16.jpg);
            border: 4px solid #fff;
            float: left;
            width: 100%;
            margin: 0px 0px 20px 0px;
            /*   min-height: 305px;
            max-height: 305px;*/
            overflow: hidden;
            padding-top: 15px;
            border-radius: 15px;

        }

        .sb-serc a {
            margin: 5px 0px;
            float: left;
            width: 100%;
            font-weight: bold;
            background: linear-gradient(to right,rgba(30, 133, 95, 0.9) 0,rgba(12, 113, 238, 0.9));
            /*color: #0e4060;*/
            color: #fff;
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            border-radius: 3px;
        }

        .sb-serc p {
            font-size: 15px;
            line-height: 21px;
            overflow: hidden;
            padding: 0 10px;
            text-align: center;
            min-height: 127px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
.light-weight h3{
    float: left;
    width: 100%;
    margin: 10px 0px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 5px;
}
        .accordion-style1 {

            background: url(assets/images/banner16.jpg);
        }

        .sub-serc .sb-serc p {
            min-height: 150px
        }

        .col-right-norm h2, h3, h4, h5 {
            font-size: 22px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 700;
        }
      .nrm-cont h3 {
    font-size: 22px;
    color: #1d9cbc;
    padding-left: 22px;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    margin-bottom: 0;
    background: none;
}
    </style>
    <div class="home-container home-background">

        @include("frontend.header")


    </div><!-- end home-container -->






    <section class="top-offer " style="margin-top: 110px;">


        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="row">
                        <div class="panel passenger-detail">
                            <h3 class="light-weight">{!! $site_settings_main["services_page_transfer_heading"];  !!}
</h3>
                            <div class="well-body">

                         
    

                           
                              
                               <div class="inr-cnt  col-xs-12 col-sm-12 col-md-12 col-lg-12" style="width: 100%; margin-top: -8px;border-color: #386171;box-shadow: 0px 4px 8px 5px #dfd7d7;"><p>
                                       {!! $site_settings_main["services_page_transfer_descp"];  !!}</p>
    
    <div class="nrm-cont  col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
  



</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >


</div>
</div>   
<section id="services" class="section-padding bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                   <!-- end page-heading -->

                    <div class="row">

                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="service-block"  style="">
                                   <h4 style="color:#350a4e">BEST PRICES</h4>
            <!--<img src="assets/images/meet.jpg" class="img-circle" style="width:80px;height:80px" alt="logo">-->
            <p>{!! $site_settings_main["services_page_transfer_sec1_bestprice"];  !!}</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->

                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="service-block"  style="">
                                     <h4 style="color:#350a4e">BEST SERVICE</h4>
            <!--<img src="assets/images/pandr.png" class="img-circle" style="width:80px;height:80px" alt="logo">-->
            <p>{!! $site_settings_main["services_page_transfer_sec1_bestsevices"];  !!}</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->
                    
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="service-block"  style="">
                                <h4 style="color:#350a4e">SAFE TRANSFERS</h4>
               <!--<img src="assets/images/onairport.jpg" class="img-circle" style="width:80px;height:80px" alt="logo">-->
                <p>{!! $site_settings_main["services_page_transfer_sec1_safetransfer"];  !!}</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->
                    </div><!-- end row -->


                </div><!-- end columns -->
            </div><!-- end row -->
        </div><!-- end container -->
</section>


</div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="hidden-xs hidden-sm col-xs-12 col-sm-12 col-md-4 col-lg-4">


               @include("frontend.right_searchbar2")




                </div>

            </div>

        </div>



    </section>
     
    
<section id="services" class="section-padding bg-grey">
        <div class="container my-4">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="page-heading">
                        <h2>Advantages of airport transfer</h2>
                     
                   
                    </div><!-- end page-heading -->

                   <div class="row">
  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block2"  style="">
                                   <h4>{!! $site_settings_main["services_page_transfer_sec2_grid1_heading"];  !!}</h4>
            <img src="assets/images/convinen.jpg" class="img-circle" style="width:80px;height:80px" alt="logo">
            <p>{!! $site_settings_main["services_page_transfer_sec2_grid1_descp"];  !!}</div><!-- end service-block -->
                            </div><!-- end columns -->
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block2"  style="">
                                   <h4>{!! $site_settings_main["services_page_transfer_sec2_grid2_heading"];  !!}</h4>
            <img src="assets/images/price.png" class="img-circle" style="width:80px;height:80px" alt="logo">
            <p>{!! $site_settings_main["services_page_transfer_sec2_grid2_descp"];  !!} </p><br><br><br><br>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block2"  style="">
                                   <h4>{!! $site_settings_main["services_page_transfer_sec2_grid3_heading"];  !!}</h4>
            <img src="assets/images/safety.png" class="img-circle" style="width:80px;height:80px" alt="logo">
            <p>{!! $site_settings_main["services_page_transfer_sec2_grid3_descp"];  !!} </p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block2"  style="">
                                   <h4>{!! $site_settings_main["services_page_transfer_sec2_grid4_heading"];  !!}</h4>
            <img src="assets/images/choice.jpg" class="img-circle" style="width:80px;height:80px" alt="logo">
            <p>{!! $site_settings_main["services_page_transfer_sec2_grid4_descp"];  !!}</p><br><br><br><br>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->
                    </div>

                </div><!-- end columns -->
            </div><!-- end row -->
        </div><!-- end container -->
</section>
  

    <!-- end innerpage-wrapper -->


@endsection

