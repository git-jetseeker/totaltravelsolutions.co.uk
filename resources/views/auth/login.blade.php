@extends('layouts.loginhead')
@section('content')


<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-plane green"></i>
                            <span class="red">Parking</span>
                            <span class="white" id="id-text2">Zone</span>
                        </h1>

                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="ace-icon fa fa-coffee green"></i>
                                        Please Enter Your Information
                                    </h4>

                                    <div class="space-6"></div>

                                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                                        @csrf
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"/>
															<i class="ace-icon fa fa-user"></i>
                                                            @if ($errors->has('email'))
                                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                                            @endif
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                                            @if ($errors->has('password'))
                                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                                            @endif
														</span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <label class="inline">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <span class="lbl"> Remember Me</span>
                                                </label>

                                                <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                    <i class="ace-icon fa fa-key"></i>
                                                    <span class="bigger-110">Login</span>
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>



                                </div><!-- /.widget-main -->


                            </div><!-- /.widget-body -->
                        </div><!-- /.login-box -->


                    </div><!-- /.position-relative -->

                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="assets/js/jquery-2.1.4.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="assets/js/jquery-1.11.3.revolution.extension.navigation.min.js"></script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.revolution.extension.navigation.min.js'>"+"<"+"/script>");
</script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function($) {
        $(document).on('click', '.toolbar a[data-target]', function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('.widget-box.visible').removeClass('visible');//hide others
            $(target).addClass('visible');//show target
        });
    });



    //you don't need this, just used for changing background
    jQuery(function($) {
        $('#btn-login-dark').on('click', function(e) {
            $('body').attr('class', 'login-layout');
            $('#id-text2').attr('class', 'white');
            $('#id-company-text').attr('class', 'blue');

            e.preventDefault();
        });
        $('#btn-login-light').on('click', function(e) {
            $('body').attr('class', 'login-layout light-login');
            $('#id-text2').attr('class', 'grey');
            $('#id-company-text').attr('class', 'blue');

            e.preventDefault();
        });
        $('#btn-login-blur').on('click', function(e) {
            $('body').attr('class', 'login-layout blur-login');
            $('#id-text2').attr('class', 'white');
            $('#id-company-text').attr('class', 'light-blue');

            e.preventDefault();
        });

    });
</script>
</body>
</html>





@endsection
