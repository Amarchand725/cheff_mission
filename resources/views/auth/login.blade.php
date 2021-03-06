@extends('layouts.website.master')
@section('content')
     <!-- BANNER SEC -->
        <section class="Login-sec">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="iner-baner-head">
                            <h3>WELCOME TO</h3>
                            <h1>chaff missions</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna alion.</p>
                        </div>    
                    </div>
                    <div class="col-md-6 form-bg">
                        <div class="log-forms">
                            <h1>LOGIN</h1>
                            <p>Customer Login Panel</p>
                            @if(Session::has('error'))
                                <p class="alert alert-danger">{{ Session::get('error') }}</p>
                            @endif
                            <form method="POST" action="{{ route('admin.authenticate') }}">
                                @csrf
                                <input type="hidden" name="user_type" value="Customer">
                                <div class="form-group">
                                    <input class="form-control" name="email" value="{{ old('email') }}" type="email" placeholder="Email Address">
                                    <span style="color: red">{{ $errors->first('email') }}</span>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="Password" name="password" required autocomplete="current-password">
                                    <span style="color: red">{{ $errors->first('password') }}</span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn-sub" name="form1">Login</button>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                    Keep me logged in
                                    </label>
                                </div>
                            </form>
                            <div class="form-under-btn">
                                <div class="forgot"><a href="">Forgot Password?</a></div>
                                <p>Don't have an account? <a href="">Register</a> </p>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- BANNER SEC -->
@endsection
