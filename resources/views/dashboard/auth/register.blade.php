@extends('dashboard.auth.layouts.new-template')
@section('title')
    Register
@endsection
@section('content')
    <div class="login-userheading">
        <h3>Create an Account</h3>
        <h4>Continue where you left off</h4>
    </div>

    <form action="{{ route('dashboard.auth.register.post') }}" method="POST" autocomplete="off">
        @csrf
        <div class="form-login">
            <label>Full Name @error('name')
                    <br><span class="text-danger">Periksa kembali nama anda !</span>
                @enderror
            </label>
            <div class="form-addons">
                <input type="text" placeholder="Enter your full name" name="name">
                <img src="{{ asset('assets/dreampos/assets/img/icons/product.svg') }}" alt="img">
            </div>
        </div>
        <div class="form-login">
            <label>Phone
            </label>
            <div class="form-addons">
                <input type="number" placeholder="Enter your phone number" name="phone">
                <img src="{{ asset('assets/dreampos/assets/img/icons/bell.svg') }}" width="15" alt="img">
            </div>
        </div>
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
            <label>Password Confirmation @error('password_confirmation')
                    <br><span class="text-danger">Konfirmasi ulang Password anda !</span>
                @enderror
            </label>
            <div class="pass-group">
                <input type="password" class="pass-input" name="password_confirmation" placeholder="Re enter your password">
                <span class="fas toggle-password fa-eye-slash"></span>
            </div>
        </div>
        <div class="form-login">
            <button class="btn btn-login" type="submit">Sign Up</button>
        </div>
    </form>

    <div class="signinform text-center">
        <h4>Do you have an account? <a href="{{ route('dashboard.auth.login.index') }}" class="hover-a">Sign In</a></h4>
    </div>
    <div class="form-setlogin">
        <h4>Or Signup with </h4>
    </div>
@endsection
