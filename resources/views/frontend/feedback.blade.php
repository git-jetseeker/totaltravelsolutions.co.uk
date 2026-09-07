@extends('layouts.main')

@section('content')

    <div class="home-container home-background paddinginherht">

<nav class="sticky">
        @include("layouts.menu")
</nav>
  
  <!-- end home-container -->
    <div id="room-listing-blocks" class="innerpage-section-padding innerpage_sec" >
        <section id="room-listings" class="innerpage-wrapper bg margintopnag10">

            <div class="container feedback_container">
                <div class="search-bar searchbar-background" id="searchbar-background">
                 
     <div class="home_form_sec_left col-xs-12 col-sm-12 col-md-12 col-lg-12 padding20">
            <div class="inner_pge_tit">
            <h1>Feedback</h1>
           
            </div>
   <div class="nrm-cont">
      <div id="reviewFrm">
    <div class="row">
        <div class="col-md-9">
            <div class="well well-sm well-sm-border">
                <h1></h1>
                  <form id="js_contact-form" action="{{ route("submit-feedback") }}" class="contact-form" method="post"
                          enctype="multipart/form-data">  
                          {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value=" " placeholder="Enter name" required="required" >
                        </div>
                        <div class="form-group hidden-xs hidden-sm hidden-md hidden-lg">
                            <label for="email">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" value="" placeholder="Enter email" required="required" readonly=""></div>
                        </div>
                        <div class="form-group">
                            <label for="input-2" class="control-label">Rate This</label>
                            <div class="rating-container rating-md rating-animate"><div class="clear-rating clear-rating-active" title="Clear"><i class="glyphicon glyphicon-minus-sign"></i></div><div class="rating-stars"><span class="empty-stars"><span class="star"><i class="glyphicon glyphicon-star-empty"></i></span><span class="star"><i class="glyphicon glyphicon-star-empty"></i></span><span class="star"><i class="glyphicon glyphicon-star-empty"></i></span><span class="star"><i class="glyphicon glyphicon-star-empty"></i></span><span class="star"><i class="glyphicon glyphicon-star-empty"></i></span></span><span class="filled-stars" style="width: 100%;"><span class="star"><i class="glyphicon glyphicon-star"></i></span><span class="star"><i class="glyphicon glyphicon-star"></i></span><span class="star"><i class="glyphicon glyphicon-star"></i></span><span class="star"><i class="glyphicon glyphicon-star"></i></span><span class="star"><i class="glyphicon glyphicon-star"></i></span></span><input id="rating" name="rating" name=""rating" value="5" class="rating-input"></div><div class="caption"><span class="text-success">Very Good</span></div></div>
                            <script>
                            $(document).on('ready', function(){
                                $('#rating').rating({
                                    step: 1,
                                    starCaptions: {1: 'Very Poor', 2: 'Poor', 3: 'Ok', 4: 'Good', 5: 'Very Good'},
                                    starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'}
                                });
                            });
                            </script>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">
                                Comment</label>
                            <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required" placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" name="submit" class="btn btn-primary pull-right" id="btnfeed">
                            Send Feedback</button>
                    </div>
                    <input type="hidden" id="type" name="type" value="">
                    <input type="hidden" id="company" name="company" value="">
                    <input type="hidden" id="reff" name="reff" value="">
                </div>
        
</form>
</div>

        <div class="col-md-3">
            <form>
            <legend><span class="fa fa-globe"></span>&nbsp;Our office</legend>
            <address>
                <strong>Working days/hours</strong><br>
                Monday-Friday 9:00-17:00<br>
                Phone: 02086609241<br>

                Email: info@parkingzone.co.uk<br>
                Address:  Unit 9105 141 Access House, Morden Road, Mitcham, Surrey, England, CR4 4DG
            </address>
            </form>
        </div>
    </div>
    </div>
    <div id="result" style="display:none;">
        <div class="alert alert-info text-center no-result">
           <h1 class="text-center">Thank you for choosing Total Travel Solutions Feedback Provide</h1>
        </div>
   </div>



            </div>

          
         
            <div class="container text-center">
                <div class="row">

                  
        <br>
        <br>
        <br>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="page-heading width96">
                        <h2>Our <span>Awesome Services</span></h2>
                        <p>Reliable, Efficient and customer-oriented services are our primary aim. If you want an
                            extraordinary service and well-skilled staff, then you are in the right place.</p>
                    </div><!-- end page-heading -->

                    <div class="row" style="">
                        <div id="service-blocks">

                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block bg-white">
                                    <span><i class="fa fa-plane"></i></span>
                                    <h2 class="service-name">Airport Parking</h2>
                                    <p>Secure, guaranteed and satisfactory. Our customers reap benefits from priority
                                        parking at 350+ car parks on 30+ major airports across UK.</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->

                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block bg-white" >
                                    <span><i class="fa fa-bitbucket "></i></span>
                                    <h2 class="service-name">Airport Lounges</h2>
                                    <p>Take a break from all the stressand Exhausted traveling. Why not Book a worldwide
                                        lounge through Total Travel Solutions LIMITED with in few clicks and user-friendly booking
                                        system</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->

                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block bg-white" >
                                    <span><i class="fa fa-hotel"></i></span>
                                    <h2 class="service-name">Airport Hotels</h2>
                                    <p>Why struggle through traffic to reach airport when you can say goodbye to stress
                                        with Flypark Plus and book an Airport hotel in two minutes.</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->

                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <div class="service-block bg-white">
                                    <span><i class="fa fa-cart-plus"></i></span>
                                    <h2 class="service-name">Other Traveling Services</h2>
                                    <p>Flying made easy. We offer best available rates for travel insurance, car
                                        rentals,taxi services, parking, airport lounges and international hotels.</p>
                                </div><!-- end service-block -->
                            </div><!-- end columns -->
                        </div><!-- end service-blocks -->
                    </div><!-- end row -->

</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>

                </div><!-- end columns -->
            </div><!-- end row -->
        </div><!-- end container -->

        <!--================ ROOMS ==============-->








@endsection
