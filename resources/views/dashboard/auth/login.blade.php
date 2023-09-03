@extends('dashboard.auth.layouts.new-template')
@section('title')
    Login
@endsection
@section('content')
    <div class="login-userheading">
        <h3>Sign In</h3>
        <h4>Please login to your account</h4>
    </div>

    <form action="{{ route('dashboard.auth.login.post') }}" method="POST" autocomplete="off">
        @csrf
        <div class="form-login">
            <label>Email @error('email')
                    <br><span class="text-danger">Periksa kembali alamat email anda !</span>
                @enderror
            </label>
            <div class="form-addons">
                <input type="email" placeholder="Enter your email address" name="email">
                <img src="{{ asset('assets/dreampos/assets/img/icons/mail.svg') }}" alt="img">
            </div>
        </div>
        <div class="form-login">
            <label>Password @error('password')
                    <br><span class="text-danger">Password yang anda masukkan Salah !</span>
                @enderror
            </label>
            <div class="pass-group">
                <input type="password" class="pass-input" name="password" placeholder="Enter your password">
                <span class="fas toggle-password fa-eye-slash"></span>
            </div>
        </div>
        <div class="form-login">
            <div class="alreadyuser">
                <h4><a href="{{ route('dashboard.auth.forgot-password.index') }}" class="hover-a">Forgot Password?</a></h4>
            </div>
        </div>
        <div class="form-login">
            <button class="btn btn-login" type="submit">Sign In</button>
        </div>
    </form>

    <div class="signinform text-center">
        <h4>Don’t have an account? <a href="{{ route('dashboard.auth.register.index') }}" class="hover-a">Sign Up</a></h4>
    </div>
    <div class="form-setlogin">
        <h4>Or Login with </h4>
    </div>
@endsection
