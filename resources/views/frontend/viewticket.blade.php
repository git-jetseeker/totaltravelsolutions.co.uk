@extends('layouts.main')


@section('content')

    <style type="text/css">
        /* Premium Theme Styling */
        body {
            font-family: 'Open Sans', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .col-md-12.padding0 {
            padding: 0px;
        }

        hr {
            border-top: 1px solid rgba(65, 105, 225, 0.2) !important;
            margin-top: 15px !important;
            margin-bottom: 15px !important;
        }

        .head-text-bookingdetail {
            font-size: 14px;
            padding-right: 0px;
            font-weight: 600;
            color: #555;
        }

        .my-class {
            width: 70% !important;
            margin-right: 27px;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
        }

        .margin15 {
            margin-top: 15px;
        }

        #room-listing-blocks #room-list > li:hover {
            transform: none;
            box-shadow: none;
        }

        .btn.btn-prm {
            color: #fff;
            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn.btn-prm:hover {
            background: black;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-prm.btn-icon i {
            padding: 3px 6px;
            font-size: 17px;
        }

        .btn-prm.btn-icon.icon-left i {
            float: left;
            right: auto;
            left: 0;
        }

        .btn-prm.btn-icon.icon-left {
            padding-right: 12px;
            padding-left: 3px;
        }

        .fpp-ticket {
            margin-top: 0px;
            padding: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .fpp-ticket:hover {
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }

        .nrm-cont p {
            line-height: 1.7;
            padding-bottom: 0px;
        }

        .maintab {
            padding-bottom: 12px;
            font-size: 22px;
            font-weight: 700;
        }

        .discount-fpp {
            margin: 0px;
            font-size: 27px;
            height: 67px;
            padding: 16px;
        }

        label {
            font-weight: 600 !important;
            color: #444;
            margin-bottom: 8px;
        }

        .margin-row {
            border-radius: 12px;
        }

        .fpp-ticket .user .name {
            display: block;
            font-size: 1em;
            color: #fff;
            font-weight: 600;
        }

        .fpp-ticket.staff .user {
            background: linear-gradient(135deg, #30a2c7 0%, #2891b8 100%);
        }

        .fpp-ticket .user {
            padding: 15px 20px;
            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);
            color: #fff;
            border-bottom: 3px solid rgba(255,255,255,0.2);
        }

        .fpp-ticket .date {
            float: right;
            padding: 8px 10px;
            font-size: 0.85em;
            background: rgba(255,255,255,0.15);
            border-radius: 6px;
            margin-top: 5px;
        }

        .fpp-ticket .user i {
            float: left;
            font-size: 2.5em;
            padding: 2px 15px;
            opacity: 0.9;
        }

        .fpp-ticket .user .type {
            display: block;
            font-weight: 600;
            font-size: 0.85em;
            opacity: 0.9;
        }

        .list-group {
            margin-bottom: 0px;
        }

        #room-list {
            color: #444;
            background: white;
            border: none;
            border-radius: 16px;
            margin-left: 15px;
            padding: 40px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        #room-list:hover {
            box-shadow: 0 8px 35px rgba(0,0,0,0.12);
        }

        .side-bar {
            padding: 0;
            background: white;
            border: none;
            border-radius: 16px;
            color: #000;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .side-bar-block {
            padding: 25px;
        }

        .side-bar-block.support-block {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .fa {
            font-size: 21px;
            margin-right: 10px;
            color: #C2185B;
            padding-bottom: 0;
        }

        .message p {
            padding: 20px;
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
        }

        .submit-btn {
            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);
            color: #fff;
            width: 50%;
            margin-top: 3%;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
            height: 30px !important;
        }

        .submit-btn:hover {
            background: black;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .ticket {
            color: #fff !important;
            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%) !important;
            padding: 15px 20px !important;
            margin: 0;
            border-radius: 0;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .ticket .fa {
            color: white;
            margin-right: 12px;
        }

        .btn-yellow {
            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%) !important;
            border: none !important;
            color: white !important;
            border-radius: 12px 12px 0 0 !important;
            transition: all 0.3s ease;
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .btn-yellow:hover {
            background: black !important;
            transform: translateY(-2px);
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.1);
            outline: none;
        }

        .room-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: black;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #C2185B;
        }

        .required-field {
            color: #dc3545;
            margin-left: 3px;
        }

        .alert-danger {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
        }

        @media only screen and (max-width: 500px){
            #room-list {
                margin-left: 0px;
                margin-top: 20px;
                padding: 25px;
            }

            .side-bar {
                margin-bottom: 20px;
            }

            .submit-btn {
                width: 100%;
            }

            .room-name {
                font-size: 1.4rem;
            }
        }

        @media only screen and (max-width: 991px) {
            #room-list {
                margin-left: 0;
                padding: 30px;
            }
        }

        .main_nav_book, .top-bar-contact{
            display: none !important;
        }

        /* Smooth animations */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .innerpage-wrapper {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .room-list-block {
            margin-bottom: 20px;
        }

        .room-text {
            padding: 40px !important;
        }

        /* Support links styling */
        .side-bar-block a {
            color: #444;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 12px 0;
        }

        .side-bar-block a:hover {
            color: #C2185B;
            transform: translateX(5px);
        }

        .side-bar-block a .fa {
            margin-right: 10px;
        }

        /* Companies class styling */
        .fpp-ticket.companies .user {
            background: linear-gradient(135deg, #fa6541 0%, #e85532 100%);
        }

        /* Attachment link styling */
        .message a {
            color: #C2185B;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 16px;
            background: rgba(65, 105, 225, 0.1);
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .message a:hover {
            background: #C2185B;
            color: white;
            transform: translateX(3px);
        }

    </style>

    <div class="home-container home-background">


        {{-- @include("frontend.header") --}}


    </div><!-- end home-container -->

    <section id="room-listings" style="margin-top: 110px;" class="innerpage-wrapper">


        <div id="room-listing-blocks" class="innerpage-section-padding">

            <div class="container">

                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 side-bar">


                        <div class="row">


                            <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12 " style="padding: 0px">

                                <h3 style="padding:13px 20px 0px !important" class="btn btn-lg btn-yellow col-md-12"><p class="ticket"><span><i

                                                    style="font-size: 26px;margin-right: 15px;"

                                                    class="fa fa-info"></i></span> Ticket Information</p></h3>




                                <div class="side-bar-block support-block"  style="margin-top:51px;    margin-bottom: 0px;">




                                    <div class="row">

                                        <div class="col-xs-6 col-md-4" class="head-text-bookingdetail">Ref#</div>

                                        <div class="col-xs-6 col-md-8" style="padding-left:0px;">

                                            <p class="text-right"

                                               style="font-size: 14px;">{{ $ticket->ticket_id }}</p>

                                        </div>


                                    </div>

                                    <hr/>


                                    <div class="row">

                                        <div class="col-xs-6 col-md-4" class="head-text-bookingdetail">Ticket Status

                                        </div>

                                        <div class="col-xs-6 col-md-8" style="padding-left:0px;">

                                            <p class="text-right"

                                               style="font-size: 14px;">{{ $ticket->status }}</p>

                                        </div>


                                    </div>

                                    <hr/>


                                    <div class="row">

                                        <div class="col-xs-6 col-md-4" class="head-text-bookingdetail">Department</div>

                                        <div class="col-xs-6 col-md-8" style="padding-left:0px;">

                                            <p class="text-right"

                                               style="font-size: 14px;">{{ $department->name }}</p>

                                        </div>


                                    </div>

                                    <hr/>

                                    <div class="row">

                                        <div class="col-xs-6 col-md-4" class="head-text-bookingdetail">Created#</div>

                                        <div class="col-xs-6 col-md-8" style="padding-left:0px;">

                                            <p class="text-right"

                                               style="font-size: 14px;">{{ $ticket->date }}</p>

                                        </div>


                                    </div>

                                    <hr/>



                                    <div class="row">

                                        <div class="col-xs-6 col-md-4" class="head-text-bookingdetail">Priority</div>

                                        <div class="col-xs-6 col-md-8" style="padding-left:0px;">

                                            <p class="text-right"

                                               style="font-size: 14px;">{{ $ticket->urgency }}</p>

                                        </div>


                                    </div>


                                    <hr/>

                                    <div class="row">

                                        <div class="col-xs-6 col-md-4" class="head-text-bookingdetail">Progress</div>

                                        <div class="col-xs-6 col-md-8" style="padding-left:0px;">

                                            <p class="text-right"

                                               style="font-size: 14px;">{{ $companyMsg }}</p>

                                        </div>


                                    </div>




                                </div><!-- end columns -->




                                <h3 style="margin-top:10px;padding:13px 20px 0px !important"

                                    class="btn btn-lg btn-yellow col-md-12"><p class="ticket"><span><i

                                                    style="font-size: 26px;margin-right: 15px;"

                                                    class="fa fa-support"></i></span> Support</p></h3>




                                <div class="side-bar-block support-block"

                                     style="margin-top:35px;    margin-bottom: 0px;">




                                    <div class="row">

                                        <div class="col-md-12">

                                            <a href="" style="font-size: 14px;"> <i class="fa fa-ticket"> My Support

                                                    Tickets</i></a>

                                        </div>

                                        <hr/>

                                        <div class="col-md-12">

                                            <a href="{{ route("support") }}" style="font-size: 14px;"> <i class="fa fa-comments"> Open

                                                    Ticket </i></a>

                                        </div>


                                    </div>




                                </div><!-- end columns -->




                            </div><!-- end row -->


                        </div><!-- end columns -->


                    </div><!-- end row -->


                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">


                        <ul id="room-list" class="list-unstyled">

                            <li id="room-list-1">

                                <div class="room-list-block">

                                    <div class="row">

                                        <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12 room-text"

                                             style="padding: 36px !important;">

                                            <div class="">



                                                    {{ Form::open(array('method'=>'post','route' => 'submit-reply', 'files' => true)) }}


                                                    @csrf

                                                    <input type="hidden" name="ticket_id"

                                                           value="{{  $ticket->id }}">

                                                    <input type="hidden" name="replyingadmin"

                                                           value="{{  $ticket->user_id }}">

                                                    <input type="hidden" name="ticket_ref"

                                                           value=" {{  $ticket->ticket_id }}">



                                                    <input type="hidden" name="reply_by"

                                                           value="Client">


                                                <h3 class="room-name">Reply {{ $ticket->title }} </h3>


                                                <div class="row margin15" id="vechile-detail">

                                                    <div class="col-lg-6 margin-vehicle">

                                                        <label class="normal-font">Name</label>

                                                        <span class="required-field">*</span>

                                                        <input class="form-control bf-inptfld" type="text"

                                                               name="name" id="name" disabled

                                                               placeholder="Name" value=" {{  $ticket->name }}">

                                                    </div>

                                                    <div class="col-lg-6">

                                                        <label class="normal-font">Email</label>

                                                        <span class="required-field">*</span>

                                                        <input class="form-control bf-inptfld" type="text" name="email"

                                                               id="email" disabled placeholder="email" value=" {{  $ticket->email }}">

                                                        @if ($errors->has('email'))


                                                            <div class="alert alert-danger alert alert-danger col-xs-10 col-sm-5" style="clear: both;">

                                                                <strong>{{ $errors->first('email') }}</strong>

                                                            </div>

                                                        @endif

                                                    </div>


                                                </div>


                                                <div class="row margin15">

                                                    <div class="col-lg-12">

                                                        <label class="normal-font">Message</label>

                                                        <textarea name="message" required style="height: 100px"

                                                                  class="col-md-12 form-control"> </textarea>

                                                        @if ($errors->has('message'))


                                                            <div class="alert alert-danger alert alert-danger col-xs-12 col-sm-12" style="clear: both;">

                                                                <strong>{{ $errors->first('message') }}</strong>

                                                            </div>

                                                        @endif

                                                    </div>



                                                </div>



                                                <div class="row margin15">

                                                    <div class="col-lg-12">

                                                        <label class="normal-font">Attachments</label>

                                                        <input type="file" name="attatchment" class="form-control">

                                                    </div>



                                                </div>

                                                <div class="col-md-12 col-lg-12 margin15 text-center">

                                                    <button class="btn btn-yellow submit-btn" type="submit" name="reply_ticket"

                                                           >Submit</button>

                                                </div>

                                                </form>


                                            </div><!-- end room-info -->

                                        </div><!-- end columns -->

                                    </div><!-- end row -->

                                </div><!-- end room-list-block -->

@php

    //$chat = $db->select("select * from " . $db->prefix . "tickets_chat where ticket_id = '" . $ticket['id'] . "' AND reply_to != 'Company' ORDER BY id desc");

                             use App\Models\User;

                             use Illuminate\Support\Facades\DB;


                             $chat = \App\Models\ticket_chat::where("ticket_id",$ticket->id)->orderBy("id","desc")->get();

                             //dd($chat);


                             foreach ($chat as $msg) {



                                //\App\ticket_chat::update();

                                  //   $db->update("UPDATE " . $db->prefix . "tickets_chat SET clientunread ='No' WHERE id='" . $msg['id'] . "'");

                                     if ($msg->reply_by == "Client") {

                                         $reply_by = $ticket->name;

                                         $reply_desg = "Client";

                                         $class = "";

                                         $bg = 'style="background-color: #ffba00;"';

                                     } elseif ($msg->reply_by == "Company") {

                                        // $admin = $db->get_row("select first_name,last_name from " . $db->prefix . "admin where id = '" . $ticket['company_admin_id'] . "'");

                                        // $reply_by = $admin['first_name'] . " " . $admin['last_name'];


                                         $user = DB::table("users")->where("id",$msg->company_admin_id)->first();


                                         $reply_by = $user->name;


                                         $reply_desg = "Company";

                                         $class = "companies";

                                         $bg = 'style="background-color: #fa6541;"';

                                     } else {

                                        // $admin = $db->get_row("select first_name,last_name from " . $db->prefix . "admin where id = '" . $msg['replyingadmin'] . "'");

                                          $admin = DB::table("users")->where("id",$msg->replyingadmin)->first();


                                        
                                         $reply_by = 'Customer Support Agent';

                                         //$reply_by = $admin['first_name'] . " " . $admin['last_name'];

                                         $reply_desg = "";

                                         $class = "staff";

                                         $bg = 'style="background-color: #30a2c7;"';


                                     }

@endphp


                                <div class="room-list-block {{ $class }}" style="margin-top:20px">

                                    <div class="row">

                                        <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12 room-text">

                                            <div class="margin-row">

                                                <div class="fpp-ticket " style="min-height: 170px;">

                                                    <div class="date">{{  $msg->replyingtime }}

                                                    </div>

                                                    <div class="user" style="{{ $bg }}">

                                                        <i class="fa fa-user"></i>

                                                        <span class="name">{{  $reply_by }}</span>

                                                        <span class="type">{{  $reply_desg }}</span>

                                                    </div>

                                                    <div class="message" style="padding-left: 10px;">


                                                        <p> {!!  $msg->message !!}   </p>

@if($msg->attachment!="")
                                                        
                                                        <a target="_blank" href="{{  $msg->reply_by == 'Client' ? url("storage/app/".$msg->attachment) : $msg->attachment  }}"> Attachment</a>

@endif

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>




@php


   }

@endphp


                    </li>




                    </ul>


                </div><!-- end columns -->



            </div>

        </div><!-- end container -->

        </div><!-- end room-listing-blocks -->


    </section>




@endsection

@section("footer-script")



@endsection
