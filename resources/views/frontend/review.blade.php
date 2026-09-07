@extends('layouts.main')
@section('content')
    <style>
        .th_class {
            background: #ffcb05;
        }

        .inner-section {
            background-color: #eff2f3;
            padding: 5px 0;
        }

        .passenger-detail h3 {
            /* margin: 10px; */
            padding: 10px 20px;
            line-height: 1.6;
            background: linear-gradient(to right, rgba(30, 133, 95, 0.9) 0, rgba(13, 70, 141, 0.9));
            color: #fff;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        #menu-tabs li {
            width: 100%;
            border: 1px solid #ccc;
        }

        .nav-tabs {
            border: 1px solid #dddddd03;
        }

        a:active {
            background: #1d9cbc;
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {

            border: none !important;
            border-bottom-color: inherit !important;
            background: #337ab7;
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

        }

        .sb-serc a {
            margin: 5px 0px;
            float: left;
            width: 100%;
            font-weight: bold;
            background: linear-gradient(to right, rgba(30, 126, 37, 0.9) 0, rgba(12, 66, 132, 0.9));
            /*color: #0e4060;*/
            color: #fff;
            margin-bottom: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
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
.pl{
    padding-left:10px;
}
    </style>
    <div class="home-container home-background">
        @include("frontend.header")
        


    </div><!-- end home-container -->






    <section class="top-offer " style="margin-top: 94px;">


        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="row">
                        <div class="panel passenger-detail">
                            <h3 class="light-weight">Total Travel Solutions Reviews</h3>
                            <div class="well-body">
                                <p>
                                    Below are the reviews of our customers who have booked car parking with us. Our
                                    Airport Parking feedback services, encourages customers to review, good or bad about
                                    their experience with us. This allows us to know which car parks are better
                                    performing and in which areas our customers are most satisfied with
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="row margin-row">
                        <div class="panel">
                            <ul class="hxComment" style="padding-left:0px;">

                                @foreach($reviews as $review)

                                    <li class="comment odd">
                                        <span class="commentSect"></span>
                                        <p class="item padding0px pl">
                                            <strong class="fn">{{ $review["c_name"] }}</strong>
                                        </p>

                                        <div class="carousel-inner">
                                            <div class="item active">
                                                <blockquote style="border-left: 5px solid #2e7b7f">
                                                    <p class="padding0px pl"
                                                       style="text-align: left;">{{ $review["review"] }}</p>
                                                    <small class="text-right pl">{{ $review["username"] }}<br>
                                                        <strong class="pl">{{ $review["created_at"] }}<span class="value-title"
                                                                                                 title="{{ $review["created_at"] }}"></span></strong>
                                                    </small>
                                                </blockquote>
                                                <hr>
                                            </div>
                                        </div>
                                    </li>

                                @endforeach


                            </ul>
                        </div>
                    </div>
                </div>


                <div class="hidden-xs hidden-sm col-xs-12 col-sm-12 col-md-4 col-lg-4">

                    @include("frontend.right_searchbar")

                </div>
            </div>
        </div>


    </section>
    <!-- end innerpage-wrapper -->


@endsection
@section("footer-script")
    <script>
        $(function () {
            $("#dropdatepicker12").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy',
                onSelect: function (dateText, inst) {

                    var date2 = $('#dropdatepicker12').datepicker('getDate', '+1d');
                    date2.setDate(date2.getDate() + 7);
                    $('#pickdatepicker12').datepicker('setDate', date2);
                }

            });
            $('#pickdatepicker12').datepicker(
                {
                    defaultDate: "+1w",
                    dateFormat: 'dd/mm/yy',
                    beforeShow: function () {
                        $(this).datepicker('option', 'minDate', $('#dropdatepicker12').val());
                        if ($('#dropdatepicker12').val() === '') $(this).datepicker('option', 'minDate', 0);
                    }
                });
        });
    </script>
@endsection
