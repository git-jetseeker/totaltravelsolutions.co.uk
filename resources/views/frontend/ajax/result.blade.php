@foreach($companies as $company)


    <div class="row flex-display" style="margin-bottom: 20px;">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 bg-grey room-text">
            <div class="row border-bottom">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 room-info">
                    <h1 class="room-name">{{ $company->name }}</h1>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <ul class="list_type">
                            <li><span></span><i class="fa fa-map-marker"
                                                aria-hidden="true"></i>{{ $company->parking_type }} </li>
                            <li><span></span><i class="fa fa-thumbs-up" aria-hidden="true"></i>Walking time 2 minutes
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <div class="customer-rating">
                        <div class="reevoo-badge">
                            <div class="score">
                                <div class="score-94">9.4</div>
                                <div class="score-text">out of 10</div>
                            </div>
                            <div class="extra-info">
                                <div class="score-logo">
                                    <div class="rating">
                                        <span><i class="fa fa-star"></i></span>
                                        <span><i class="fa fa-star"></i></span>
                                        <span><i class="fa fa-star"></i></span>
                                        <span><i class="fa fa-star"></i></span>
                                        <span><i class="fa fa-star-o"></i></span>
                                    </div>
                                    <!-- end rating -->
                                </div>
                                <a href="#" target="_blank">292 Reviews</a>
                            </div>
                        </div>
                    </div>
                    <!--<strong class="pull-right more-info">More Info <i class="fa fa-plus orange" aria-hidden="true"></i></strong>-->
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <figure class="logo-figure">

                        <img class="img-responsive center-block"
                             src="https://www.airportparkingessentials.co.uk/images/upload/company/1516804469-1516000819-Saver_1.png">
                    </figure>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="room-info">
                        <ul class="selected-room-features list-unstyled">
                            <li>
                                <span class="listspan"><i class="fa fa-check"></i></span>
                                <p>Lorem ipsum dolor sit amet</p>
                            </li>
                            <li>
                                <span class="listspan"><i class="fa fa-check"></i></span>
                                <p>consectetuer adipiscing elit</p>
                            </li>
                            <li>
                                <span class="listspan"><i class="fa fa-check"></i></span>
                                <p>sed diam nonummy nibh euismod</p>
                            </li>
                            <li>
                                <span class="listspan"><i class="fa fa-check"></i></span>
                                <p>adipiscing elit sed diam nonummy</p>
                            </li>
                        </ul>
                    </div>
                    <!-- end room-info -->
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 room-info">
                    <ul class="list-inline list-unstyled">
                        <li>
                            <img src="https://www.airportparkingessentials.co.uk/images/upload/awards/1513064227-roun-new.png"
                                 class="img-responsive awards-img"></li>
                        <li>
                            <img src="https://www.airportparkingessentials.co.uk/images/upload/awards/1513064270-parkmark-new.png"
                                 class="img-responsive awards-img"></li>
                        <li>
                            <img src="https://www.airportparkingessentials.co.uk/images/upload/awards/1513064296-bpa-new1.png"
                                 class="img-responsive awards-img"></li>
                    </ul>
                </div>
                <div class="center-block col-xs-12 col-sm-12 col-md-7 col-lg-7 room-info">
                    <ul class="list-inline list-unstyled room-features" style="margin-top: 4px;">
                        <li><span data-toggle="tooltip" title="" data-original-title="Wifi"><i
                                        class="fa fa-wifi"></i></span></li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Clean"><i class="fa fa-leaf"></i></span>
                        </li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Taxi"><i
                                        class="fa fa-taxi"></i></span></li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Big LCD"><i
                                        class="fa fa-television"></i></span></li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Breakfast"><i
                                        class="fa fa-cutlery"></i></span></li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Play Station"><i
                                        class="fa fa-futbol-o"></i></span></li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Wifi"><i
                                        class="fa fa-wifi"></i></span></li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Clean"><i class="fa fa-leaf"></i></span>
                        </li>
                        <li><span data-toggle="tooltip" title="" data-original-title="Taxi"><i
                                        class="fa fa-taxi"></i></span></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end columns -->
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 bg-white">

            <!-- Indicates a successful or positive action -->
            <div class="row destacados">

                <div class="box1">
                    <h3>Meet and Greet</h3>
                </div>


            </div>

            <br>
            <del class="price-offer">£ 79.00</del>
            <br>

            <center>
                <div class="price mar-bottom text-center"><span>£{{ $company->price }}</span></div>

                <a href="room-details.html" class="btn btn-lg btn-yellow">Book Now</a> <br><br>
                <strong class="more-info">More Info <i class="fa fa-plus orange" aria-hidden="true"></i></strong>
            </center>
        </div>
    </div>
@endforeach