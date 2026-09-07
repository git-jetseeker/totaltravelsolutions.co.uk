@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-manage-booking.css?v=20250931') }}">
@section("title",$page->meta_title)
@section("meta_keyword",$page->meta_keyword )
@section("meta_description",$page->meta_description)
<style type="text/css">
    /* Premium Page Background */
    body {
        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    }

    /* Premium Container */
    .bg-grey-margin {
        margin-top: 57px;
        margin-right: 63px;
        margin-bottom: 63px;
        padding: 50px;
        margin-left: 63px;
        background-color: white;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .bg-grey-margin:hover {
        box-shadow: 0 15px 50px rgba(255, 165, 0, 0.15);
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        .bg-grey-margin {
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            padding: 8%;
            margin-left: 0px;
            border-radius: 0;
        }
    }

    /* Premium Intro Section */
    .intro {
        margin-top: 110px;
        margin-bottom: 60px;
    }

    @media only screen and (max-width: 575px) {
        .intro {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 20px;
            margin-top: 90px;
        }
    }

    /* Premium Page Title */
    .manage_boking {
        margin-top: 0;
        margin-bottom: 35px;
        color: #1a1a1a;
        font-weight: 800;
        font-size: 40px;
        padding-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    .manage_boking::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #C2185B, #C2185B);
        border-radius: 2px;
    }

    /* Premium Form Styles */
    .form-group label {
        color: #1a1a1a;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .required-field {
        color: #dc3545;
        font-weight: bold;
    }

    .form-control {
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .form-control:focus {
        border-color: #C2185B;
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
        outline: none;
        background: #fffbf5;
    }

    .form-control:hover {
        border-color: rgba(36, 58, 124, 0.377);
    }

    /* Premium Button */
    .btn-yellow {
        background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 14px 45px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 15px rgba(36, 58, 124, 0.377);
        font-size: 15px;
        margin-top: 15px;
    }

    .btn-yellow:hover {
        background: linear-gradient(135deg, black 0%, #1a1a1a 100%);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        color: white;
    }

    /* Premium Alert */
    .alert-danger {
        background: linear-gradient(135deg, #fff5f5, #ffe5e5);
        border: 2px solid #dc3545;
        border-radius: 10px;
        color: #721c24;
        font-weight: 600;
        padding: 18px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.15);
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-danger li {
        margin-bottom: 5px;
    }

    /* Form Animation */
    #parent {
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .manage_boking {
            font-size: 34px;
        }
    }

    @media (max-width: 768px) {
        .manage_boking {
            font-size: 30px;
        }

        .btn-yellow {
            width: 100%;
        }
    }

    @media (max-width: 575px) {
        .manage_boking {
            font-size: 26px;
        }

        .form-control {
            padding: 10px 14px;
        }
    }
</style>
<div class="js-manage-booking-page">
    <div class="intro">
    <div class="container ">
        <div class="bg-grey-margin row" >
            <div class="inr-cnt  col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!------starting contact us ------->
                <!--form start---->
                <div class="" >
                    <h1 class="text-left manage_boking">Booking Summary</h1>
                    <div id="parent">
                        <form id="js_manage-booking-form" action="{{ route('booking_search') }}" class="js-manage-form contact-form" method="post" enctype="multipart/form-data" novalidate>
                            @csrf

                            @if ($errors->isNotEmpty())
                                <div class="js-manage-form__alert js-manage-form__alert--error" role="alert">
                                    <strong>Unable to find booking.</strong>
                                    <span>Please review the highlighted fields below and try again.</span>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="ref_no">Booking Reference No.<span class="required-field">*</span></label>
                                        <input type="text" class="form-control{{ $errors->has('ref_no') ? ' is-invalid' : '' }}" id="ref_no" name="ref_no" placeholder="JSXXXXXXXXX" required value="{{ Request::old('ref_no') }}" autofocus @if($errors->has('ref_no')) aria-invalid="true" @endif>
                                        @error('ref_no')
                                            <span class="js-manage-field-error" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="required-field">*</span></label>
                                        <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name" placeholder="Last Name" required value="{{ Request::old('last_name') }}" @if($errors->has('last_name')) aria-invalid="true" @endif>
                                        @error('last_name')
                                            <span class="js-manage-field-error" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="required-field">*</span></label>
                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Email" required value="{{ Request::old('email') }}" @if($errors->has('email')) aria-invalid="true" @endif>
                                        @error('email')
                                            <span class="js-manage-field-error" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 js-manage-form__actions">
                                <button type="submit" name="submit" class="btn btn-yellow">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
             
            </div>
       
        </div>
        <div class="clearfix"></div>
    </div>
</div>
</div>
<script type="text/javascript">
window.scroll({
 top: 0, 
 left: 0
});
	

</script>
@include('layouts.footer')
