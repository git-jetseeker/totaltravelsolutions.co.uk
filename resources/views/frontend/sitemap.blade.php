@section("title","JET SEEKER | Sitemap")
@section("meta_keyword","JET SEEKER | Sitemap")
@section("meta_description","JET SEEKER | Sitemap")
@include('layouts.header')
@include('layouts.nav')

<style type="text/css">
    .passenger-detail
    {
        width: 100%;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 9px;
    }
    .tab-content>.active {
        margin-top: 0px;
    }
    .listlinkpz a{
        color: #000;
        font-weight: 500;
        font-size: 15px;
    }
    .listlinkpz a:hover{
        color: orange;
        font-weight: 500;
        font-size: 15px;
        text-decoration: underline;
    }
    .listlinkpz{
        padding-left: 10px;
    }
    .passenger-detail h3 {
        font-size: 21px;
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
</style>





    <section class="top-offer marginto73" style="top:40px" >


        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="row">
                        <div class="panel passenger-detail">
                            <h1 class="light-weight">Site Map</h1>
                            <div class="well-body">


                                <div class="inr-cnt  col-xs-12 col-sm-12 col-md-12 col-lg-12 mainsitemap" >

                                    <div class="">
                                        <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">

                                            <h3> Airport Parking</h3>
                                            <ul>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"gatwick-airport-parking"]) }}">Gatwick
                                                        Airport Parking</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"heathrow-airport-parking"]) }}">Heathrow
                                                        Airport Parking</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"stansted-airport-parking"]) }}">Stansted
                                                        Airport Parking</a></li>
                                                {{-- <li class="listlinkpz">
                                                    <a href="{{ route("page",["slug"=>"birmingham-airport-parking"]) }}">Birmingham
                                                        Airport Parking</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"edinburgh-airport-parking"]) }}">Edinburgh
                                                        Airport Parking</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("page",["slug"=>"southampton-airport-parking"]) }}">Southampton
                                                        Airport Parking</a></li> --}}
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"liverpool-airport-parking"]) }}">Liverpool
                                                        Airport Parking</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"luton-airport-parking"]) }}">Luton
                                                        Airport Parking</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("page",["slug"=>"manchester-airport-parking"]) }}">Manchester
                                                        Airport Parking</a></li>
                                                        <li  class="listlinkpz">
                                                {{-- <a href="{{ route("page",["slug"=>"east-midlandsairport-parking"]) }}">East Midlands
                                                        Airport Parking</a></li> --}}
                                            </ul>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                                            <h3> Serving Airports</h3>
                                            <ul>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"gatwick-airport-parking"]) }}">Gatwick
                                                        Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"heathrow-airport-parking"]) }}">Heathrow
                                                        Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"luton-airport-parking"]) }}">Luton
                                                        Airport</a></li>
                                                {{-- <li class="listlinkpz">
                                                    <a href="{{ route("page",["slug"=>"birmingham-airport-parking"]) }}">Birmingham
                                                        Airport</a></li> --}}
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"stansted-airport-parking"]) }}">Stansted
                                                        Airport</a></li>

                                                {{-- <li class="listlinkpz"><a href="{{ route("page",["slug"=>"edinburgh-airport-parking"]) }}">Edinburgh
                                                        Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"exeter-airport-parking"]) }}">Exeter
                                                        Airport</a></li> --}}
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"bristol-airport-parking"]) }}">Bristol
                                                        Airport</a></li>
                                                {{-- <li class="listlinkpz"><a href="{{ route("page",["slug"=>"glasgow-airport-parking"]) }}">Glassgow
                                                        Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"aberdeen-airport-parking"]) }}">Aberdeen
                                                        Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"newcastle-airport-parking"]) }}">Newcastle
                                                        Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"belfast-airport-parking"]) }}">Belfast
                                                        Int Airport</a></li>
                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"cardiff-airport-parking"]) }}">Cardiff
                                                        Airport</a></li> --}}


                                                <li class="listlinkpz"><a href="{{ route("page",["slug"=>"liverpool-airport-parking"]) }}">Liverpool
                                                        Airport</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("page",["slug"=>"manchester-airport-parking"]) }}">Manchester
                                                        Airport</a></li>

                                                {{-- <li class="listlinkpz">
                                                    <a href="{{ route("page",["slug"=>"east-midlandsairport-parking"]) }}">East Midlands
                                                        Airport</a></li> --}}

                                            </ul>
                                            <br>
                                            
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

                                            <h3>Other Pages</h3>
                                            <ul>


                                                <li class="listlinkpz">
                                                    <a href="{{ route("static_page",["page"=>"about-us"]) }}">About
                                                        Us</a></li>

                                                <li class="listlinkpz">
                                                    <a href="{{ route("static_page",["page"=>"terms-and-conditions"]) }}">Terms
                                                        & condition</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("static_page",["page"=>"privacy-policy"]) }}">Privacy
                                                        Policy</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("static_page",["page"=>"site-security"]) }}">Site
                                                        Security</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("sitemap") }}">Site Map</a></li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("static_page",["page"=>"affiliates"]) }}">Affiliates</a>
                                                </li>
                                                <li  class="listlinkpz"><a href="{{ route("static_page",["page"=>"cookies"]) }}">Cookies</a>
                                                </li>
                                                <li class="listlinkpz">
                                                    <a href="{{ route("airport_guide") }}">Airport Guide</a></li>
                                                <li class="listlinkpz"><a href="{{ route("faqs") }}" class="dropdown-toggle">FAQ</a></li>

                                            </ul>

                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="hidden-xs hidden-sm col-xs-12 col-sm-12 col-md-4 col-lg-4">


                    @include("frontend.right_searchbar")


                </div>
            </div>
        </div>


    </section>
    <br>
    <br>
    <!-- end innerpage-wrapper -->
@include('layouts.footer')


