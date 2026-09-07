@include('layouts.header')
@include('layouts.nav')



<style>
body{
    font-family: 'Poppins' !important;
}
    .customers{
        color: #fff;
        font-weight: 700;
        font-size: 35px;
        text-align: center;
        z-index: 10 !important;
        padding: 50px 0px;
    }
    .top-listitem li{
        color: #000;
        font-size: 15px;
        list-style: none;
    }
    .alert-info {
        color: #ffffff !important;
        background-color: #1773b9 !important;
        border-color: #1773b9 !important;
    }
    #js_contact-form{
        /* border: 2px solid #1773b9; */
        padding: 25px;
        border-radius: 15px;
        /* box-shadow: 1px 1px 20px 0px; */
    }
    label {
        display: inline-block;
        color: #000;
        padding-top: 15px;
    }
    .anchr-links a{
        text-decoration: none;
        color: #000;
        font-weight: 600;
    }
    .btn-warning {
        color: #fff !important;
        background-color: #1773b9 !important;
        border-color: #06a0ff !important;
    }
    .btn-warning:hover {
        color: #fff  !important;
        background-color: #06a0ff !important;
        border-color: #06a0ff !important;
    }
    .btn-yellow{
        color: black !important;
        background-color: #F79F02 !important;
        border-color: #F79F02 !important;  
        border-radius:6px;
        height:55px;
        width:314px;
    }
     .btn-yellow:hover{
        color: white  !important;
        background-color: #F79F02 !important;
        border-color: #F79F02  !important;
        border-radius:6px;
    }
    .sidebar-style{
        background-color: #1773b9;
        padding: 20px;
        border-radius: 15px;
        border: 2px solid #000;
    }
    .sidebar-style strong p{
        color: #fff;
        text-align: center;
        font-weight: 600;
    }
    .white-text{
        color: #fff;
        padding-top: 15px;
    }
    .btn-yellow2{
        color: #fff !important;
        background-color: #000 !important;
        border-color: #06a0ff !important;
        margin-top: 20px !important;
        display: block;  
        margin: auto;
    }
     .btn-yellow2:hover{
        color: #fff !important;
        background-color: #000 !important;
        border-color: #06a0ff !important; 
        transform: scale(1.1);
        transition: .8s;
    }

    .panel{
        box-shadow:none;
    }
    .h2tag {
    /*margin: 0 0 75px;*/
    padding-top: 60px;
    font-size: 40px;
    font-weight: bolder;
    text-align: left;
     font-family: 'Poppins' !important;
}

@media only screen and (min-width:992px){
    .top-offer {    padding-left: 80px;padding-right: 80px;}
}
#parent{
    padding:0px;
    border-radius: 24px;
}
.parent-h{
    background:#F79F02;
        border-top-right-radius: 24px;
    border-top-left-radius: 24px;
}
.parent-h2{
    font-size:30px;
    font-weight:bold;
    text-align:center;
    font-family: 'Poppins' !important;
}
.form-control{
    height: 50px;
    border-color: #F79F02;
}
select.form-control:not([size]):not([multiple]) {
    height: calc(50px + 3px);
}
@media only screen and (max-width:374px){
    .btn-yellow{
        height:49px;
        width:215px;
    }
}
@media only screen and (max-width:330px){
    #parent{
        margin-left:24px;
        margin-right:24px;
    }
}
</style>
@include("frontend.contact-us-searchbar")

    <section class="top-offer ">
        <div class="container">
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2 class="h2tag"><span style="color:#F79F02">We are here</span>  to help </h2>
                </div>
                
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    
                        <div class="panel passenger-detail">
                            <div class="well-body">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        
                                        <div id="parent">
                                            <div class="parent-h">
                                                <h2 class="parent-h2">Leave a message </h2>
                                            </div>
                                            @if(session()->has('success_message'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('success_message') }}
                                                </div>
                                            @endif
                                                <form id="js_contact-form" action="{{route('contact-us-submit')}}" class="contact-form" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                                        
                                                    <div class="row">

                                                    <!--<div class="col-xs-12 col-md-2">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label>Title<span class="required-field">*</span></label>-->
                                                    <!--        <select class="form-control" id="name-title" name="title">-->

                                                    <!--        <option value="Mr">Mr</option>-->

                                                    <!--        <option value="Ms">Ms</option>-->

                                                    <!--        <option value="Miss">Miss</option>-->

                                                    <!--        <option value="Mrs">Mrs</option>-->

                                                    <!--        </select>-->
                                                    <!--    </div>-->

                                                    <!--</div>-->
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label>First Name<span class="required-field">*</span></label>
                                                            <input type="text" class="form-control" name="firstname" placeholder="Name" required="" autofocus="">
                                                        </div>

                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label>Last Name <span class="required-field">*</span></label>
                                                            <input type="text" class="form-control" name="lastname" placeholder="Last Name" required="" autofocus="">
                                                        </div>

                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                            <div class="form-group">
                                                                <label>Email<span class="required-field">*</span></label>
                                                                <input type="email" class="form-control" name="email" placeholder="Email" required="">

                                                            </div>

                                                        </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label> Phone no. <span class="required-field">*</span></label>
                                                            <input type="text" class="form-control" name="phone" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" maxlength="10" placeholder="Mobile No." required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6">
                                                        <div class="form-group">
                                                            <label>Message subject*</label>
                                                            <select class="form-control valid" name="subject" required="">
                                                                <option value="">Please select your message main subject</option>
                                                                <option value="Amend or cancel a booking">Amend or cancel a booking</option>
                                                                <option value="Re-send booking confirmation">Re-send booking confirmation</option>
                                                                <option value="New booking enquiry">New booking enquiry</option>
                                                                <option value="General enquiry">General enquiry</option>
                                                                <option value="Change email or postal address details">Change email or postal address details</option>
                                                                <option value="Marketing/PR enquiry">Marketing/PR enquiry</option>
                                                                <option value="Make a complaint">Make a complaint</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label>Message <span class="required-field">*</span></label>
                                                            <textarea class="form-control textarea-contact ckeditor" required="" rows="10" id="comment" name="message" value="" style="height: 100px;" placeholder="Type Your Message/Feedback here..."></textarea>

                                                        
                                                        </div>
                                                        <!--<div class="g-recaptcha" data-sitekey="6LeFPwQnAAAAALP2mkqbiNP7fIjblhkqCTh0x5ly"></div> -->
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="clearfix"></div>

                                                    <div class="col-xs-12 text-center">
                                                        <br>

                                                        <button type="submit" class="btn btn-yellow btn-font-size">Submit </button>
                                                    </div>

                                                </form>
                                                
                                            </div>                                                   
                                    </div>
                                </div>

                                </div>
                            </div>
                        
                    </div>
                    </div>
                </div>
                    <br>
                    <br>
                  
                </div>
            </div>
        </div>

    </section>

@include('layouts.footer')


