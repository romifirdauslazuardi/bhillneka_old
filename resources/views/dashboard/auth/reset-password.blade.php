@extends("dashboard.auth.layouts.main")

@section("title","Reset Password")

@section("css")
@endsection

@section("content")
<section class="bg-home bg-circle-gradiant d-flex align-items-center">
    <div class="bg-overlay bg-overlay-white"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card form-signin p-4 rounded shadow" style="height: 100vh;overflow-y:scroll;">
                    <form method="POST" action="{{route('dashboard.auth.reset-password.post')}}" autocomplete="off">
                        @csrf
                        @include("dashboard.auth.layouts.logo")
                        <h5 class="mb-3 text-center">Reset Password</h5>
                        <input type="hidden" name="token" value="{{request()->get('token')}}">
                        <div class="form-group mb-2">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{request()->get('email')}}" readonly>
                        </div>

                        <div class="form-group mb-2">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>

                        <div class="form-group mb-2">
                            <label>Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password">
                        </div>
        
                        <button class="btn btn-primary w-100" type="submit">Register</button>

                        <div class="col-12 text-center mt-3">
                            <p class="mb-0 mt-3"><small class="text-dark me-2">Sudah punya akun ?</small> <a href="{{route('dashboard.auth.login.index')}}" class="text-dark fw-bold">Login Sekarang</a></p>
                        </div><!--end col-->

                        @include("dashboard.auth.layouts.footer")
                    </form>
                </div>
            </div>
        </div>
    </div> <!--end container-->
</section><!--end section-->
@endsection

@section("js")
@endsection