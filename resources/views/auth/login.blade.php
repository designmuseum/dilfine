@extends("front/layout.master")
@section('title',__('staticwords.Login'))
@section('body')
@php
    require_once(base_path().'/app/Http/Controllers/price.php');
@endphp
    <div class="body-content">
    <div class="container">
        <div class="sign-in-page sign-in-page-cn">
            <div class="sign-in-box-cn">

               
<div id="aniBox" class="sign-in-page sign-in">
    <h4 class="login-title">Login</h4>
    <p class="login-title-sm">Sign in to your account</p>
    <div class="social-sign-in outer-top-xs">

        <div class="row">

            @if($config->fb_login_enable=='1')
                <div class="col-md-4">
                    <a href="{{url('login/facebook')}}" title="{{__('staticwords.SignInwithFacebook')}}" class="font-size-12 facebook-sign-in"><i class="fa fa-facebook"></i>Sign in with Facebook</a>    
                </div>
            @endif

            @if($config->google_login_enable=='1')
                <div class="col-md-4">
                    <a title="{{__('staticwords.SignInwithGoogle')}}" href="{{url('login/google')}}" class="google-sign-in"><i class="fa fa-google"></i> {{__('staticwords.SignInwithGoogle')}}</a>
                </div>
            @endif
            
            @if($config->twitter_enable == 1)
                <div class="col-md-4">
                    <a title="{{__('staticwords.SignInwithTwitter')}}" href="{{url('login/twitter')}}" class="twitter-sign-in"><i class="fa fa-twitter"></i>{{ __('staticwords.SignInWithTwitter') }}</a>
                </div>
            @endif

           
        </div>

        <div class="row margin-top-15">

            @if(env('ENABLE_GITLAB') == 1 )
                <div class="col-md-4">
                    <a title="{{__('staticwords.SignInwithGitLab')}}" href="{{url('login/gitlab')}}" class="gitlab"><i class="fa fa-gitlab"></i>{{__('staticwords.SignInwithGitLab')}}</a>
                </div>
            @endif

            
            @if($config->linkedin_enable=='1')
                <div class="col-md-4">
                    <a title="{{__('staticwords.SignInWithLinkedin')}}" href="{{url('login/linkedin')}}" class="linkedin-sign-in"><i class="fa fa-linkedin-square" aria-hidden="true"></i>
                        {{ __('staticwords.SignInWithLinkedin') }}</a>
                </div>
            @endif

            @if($config->amazon_enable=='1')
            <div class="col-md-4">
                <a title="{{__('staticwords.SignInWithAmazon')}}" href="{{url('login/amazon')}}" class="amazon-sign-in"><i class="fa fa-amazon" aria-hidden="true"></i> {{ __('staticwords.SignInWithAmazon') }}</a>
            </div>
            @endif
        </div>
    </div>
    <form novalidate id="loginform" method="POST" class="form register-form outer-top-xs" role="form" action="{{ route('normal.login') }}">
                        @csrf

        <div class="form-group">
            <label class="info-title" for="exampleInputEmail1">{{ __('E-Mail Address') }} <span>*</span></label>
            <input required type="email" name="email" class="form-control unicase-form-control text-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" value="{{ old('email') }}" required autofocus>

             @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label class="info-title" for="exampleInputPassword1">{{ __('Password') }} <span>*</span></label>
            <input required type="password" name="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }} form-control unicase-form-control text-input" id="password" >

             @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
             @endif
        </div>
        <div class="d-flex">
            <button type="submit" class="signin btn-upper btn btn-primary checkout-page-button">Login</button>
            <label>
                <input type="radio" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>{{__('staticwords.Rememberme')}}
            </label>
        </div>
        <div class="d-flex var">
            <a href="{{ route('password.request') }}" class="forgot-password pull-right">{{__('staticwords.ForgotYourPassword')}}</a>
            <a href="{{ route('register') }}" class="forgot-password pull-right">{{__('staticwords.NewUserRegisterNow')}}</a>
        </div>
    </form>                 
</div>
<!-- Sign-in -->

<div class="col-md-6 d-none">
    <canvas id="canvas" class="canvaslogin"></canvas>
</div>
</div><!-- /.row -->
        </div>    
    </div><!-- /.container -->
</div><!-- /.body-content -->
@endsection
@section('head-script')
<script src="{{ url('admin/plugins/flare/Flare.min.js') }}"></script>
<script src="{{ url('admin/plugins/flare/gl-matrix.js') }}"></script>
<script src="{{ url('admin/plugins/flare/canvaskit.js') }}"></script>
<script src="{{url('front/vendor/js/Event.js')}}"></script>
<script src="{{ url('front/vendor/js/loginanimation.js') }}"></script>
<script>var baseUrl = "<?= url('/') ?>";</script>
<script src="{{ url('js/login.js') }}"></script>
@endsection
@section('script')
<script>
     // Example starter JavaScript for disabling form submissions if there are invalid fields
     (function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('form');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
            }else{
                $('.signin').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> {{ __('staticwords.Login') }}');
            }
            form.classList.add('was-validated');
            
        }, false);
        
        });
    }, false);
    })();
</script>  
@endsection