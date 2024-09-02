@extends('layouts.auth.layer')

@section('title', 'Login | Drive Safe school')

@section('content') 

    <!-- signin-page-Start -->
    <div class="signin-page-area pd-top-120 pd-bottom-120">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-xl-12 col-lg-12">
                    <div class="LoginLogo text-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/frontend/img/logo.png') }}" alt="logo" />
                        </a>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-8 pt-4">
                    <form method="POST" action="{{ route('login') }}" class="signin-inner">
                        @csrf

                        <h3 class="LoginTitle">Login</h3>

                        <div class="row"> 
                            @error('callback')
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                </div>
                            @enderror
                            <div class="col-12">
                                <div class="single-input-inner style-bg-border">
                                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                                        class="@error('email') is-invalid @enderror" placeholder="LOGIN ID " autofocus>
                                    @error('email')
                                        <span class="invalid-feedback_login" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="single-input-inner style-bg-border">
                                    <input type="password" placeholder="PASSWORD" id="password" type="password"
                                        class="@error('password') is-invalid @enderror" name="password"
                                        autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback_login" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <button class="btn btn-base btn-11 w-100">LOGIN</button>
                            </div>
                            <div class="col-12">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- signin-page-end -->
    <Style>
        .invalid-feedback_login {
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
        }
        body .signin-inner {
            background: #fff;
            padding: 40px 40px 30px;
            border-radius: 7px;
            margin-top: 35px;
            box-shadow: 0 0.3rem 1rem rgba(0,0,0,.10);
        }
        body .single-input-inner input {
            border-radius: 8px;
            padding: 0 18px;
            border-right: 0;
            border-left: 0px;
            box-shadow: 0 0.3rem 1rem rgba(0,0,0,.10);
            border: none;
        }
        .btn:not(:disabled):not(.disabled){
            border-radius: 40px;
        }
        h3.LoginTitle {
            position: relative;
            margin-bottom: 25px;
            text-transform: uppercase;
            font-weight: normal;
        }
        h3.LoginTitle:after {
            content: "";
            position: absolute;
            bottom: -6px;
            width: 30px;
            height: 3px;
            background: var(--heading-color);
            left: 0;
        }

        body .signin-inner strong, body .signin-inner strong:hover{
            color: red;
            font-size: 14px;
            font-weight: normal;
        }
    </Style>
@endsection
