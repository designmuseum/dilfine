@extends("front/layout.master")
@section('title','Register |')
@section("body")
@php
require_once(base_path().'/app/Http/Controllers/price.php');
@endphp
@section('stylesheet')
<style>
    .select2-selection__rendered {
        line-height: 38px !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        text-align: center;
    }
</style>
@endsection
<div class="body-content">
    <div class="container">
        <div class="sign-in-page sign-in-page-cn">
            <div class="sign-in-box-cn">
                <div class="sign-in cn-register">
                    <h4 class="checkout-subtitle text-center login-title">Register</h4>
        
                    <form class="form outer-top-xs" role="form" method="POST" action="{{ route('register') }}" novalidate>
                        @csrf
                        <!-- create a new account -->
        
                                <div class="form-group">
                                    <label class="info-title"
                                        for="exampleInputEmail1">{{ __('staticwords.Name') }}<span>*</span></label>
                                    <input required name="name" type="text" value="{{ old('name') }}"
                                        class="form-control unicase-form-control text-input{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        id="name" autofocus> @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span> @endif
        
                                   
                                </div>
        
                                <div class="form-group">
                                    <label class="info-title" for="exampleInputEmail2">{{ __('staticwords.eaddress') }}
                                        <span>*</span></label>
                                    <input required value="{{ old('email') }}" type="email"
                                        class="form-control unicase-form-control text-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        id="email" name="email" required >
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $errors->first('email') }}</strong></span>
                                    @endif
                                </div>
        
                            @if($genrals_settings->otp_enable == 0)
                                <div class="form-group">
                                    <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.MobileNo') }}
                                        <span>*</span></label>
                                    <input required pattern="[0-9]+" title="{{ __('Please enter valid mobile no') }}."
                                        value="{{ old('mobile') }}" type="text"
                                        class="form-control unicase-form-control text-input{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                        name="mobile" id="phone" required>
                                    @if ($errors->has('mobile'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            @endif
        
                            @if($genrals_settings->otp_enable == 1)
                                <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.MobileNo') }}
                                    <span>*</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select required class="form-control select2" name="phonecode" id="phonecode">
        
                                            @foreach(App\Allcountry::select('phonecode','nicename')->get() as $code)
                                            <optgroup label="{{ $code->nicename }}">
                                                <option {{ old('phonecode') == $code->phonecode ? "selected" : "" }}
                                                    value="{{ $code->phonecode }}">{{ $code->phonecode }}</option>
                                            </optgroup>
                                            @endforeach
        
                                        </select>
                                    </div>
                                    <input required pattern="[0-9]+" title="{{ __('Please enter valid mobile no') }}."
                                        value="{{ old('mobile') }}" type="text"
                                        class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile"
                                        id="phone" required>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <p class="text-danger error"></p>
                                <p class="text-success success"></p>
        
        
                            @endif
        
                                <div class="form-group">
                                    <label class="info-title" for="password">{{ __('staticwords.EnterPassword') }}
                                        <span>*</span></label>
                                    <input required type="password" id="password"
                                        class="form-control unicase-form-control text-input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="password" required> @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span> @endif
                                </div>
        
                                <div class="form-group">
                                    <label class="info-title" for="exampleInputEmail1">{{ __('staticwords.ConfirmPassword') }}
                                        <span>*</span></label>
                                    <input required type="password" name="password_confirmation" id="password-confirm"
                                        class="form-control unicase-form-control text-input" required />
                                </div>
        
        
                            @if($genrals_settings->captcha_enable == 1)
        
                                <div class="form-group">
                                    {!! no_captcha()->display() !!}
                                </div>
        
                                @error('g-recaptcha-response')
                                <p class="text-danger"><b>{{ $message }}</b></p>
                                @enderror
        
                            @endif
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="eula" id="eula" required>
                                        <label class="form-check-label" for="eula">
                                            <b>{{ __('I agree to ') }}<a href="#eulaModal" data-toggle="modal">{{ __('terms and conditions') }}</a></b>
                                        </label>
                                    </div>
                                </div>
        
                                <div class="d-flex">
                                    <button type="submit"
                                        class="register btn-upper btn btn-primary checkout-page-button">{{ __('staticwords.Register') }}</button>
                                    <a class="float-right cn-bold"
                                        href="{{ route('login') }}">{{ __('Already have account login here?') }}</a>
                                </div>
        
        
                        
        
                        @php
                          $userterm = App\TermsSettings::firstWhere('key','user-register-term');
                        @endphp
        
                        <div id="eulaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h5 class="modal-title" id="my-modal-title">{{ $userterm['title'] }}</h5>
                                        
                                    </div>
                                    <div class="modal-body">
                                        <div style="overflow: scroll;max-height:500px">
                                        
                                            {!! $userterm['description'] !!}
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /.body-content -->
@endsection
@section('script')
<script>
    "use strict";

    var baseurl = "<?= url('/') ?>";
    $(document).ready(function () {
        $('.select2').select2({
            height: '100px'
        });
    });
</script>
<script src="{{ url('js/ajaxlocationlist.js') }}"></script>
@if($genrals_settings->captcha_enable == 1)
{!! no_captcha()->script() !!}
@endif
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
                $('.register').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> {{ __('staticwords.Register') }}');
            }
            form.classList.add('was-validated');
            
        }, false);
        
        });
    }, false);
    })();
</script>
@endsection