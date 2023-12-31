@extends("dashboard.auth.layouts.main")

@section("title","Login")

@section("css")
@endsection

@section("content")
<section class="bg-home bg-circle-gradiant d-flex align-items-center">
    <div class="bg-overlay bg-overlay-white"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card form-signin p-4 rounded shadow">
                    <form method="POST" action="{{route('dashboard.auth.login.post')}}" autocomplete="off">
                        @csrf
                        @include("dashboard.auth.layouts.logo")
                        <h5 class="mb-3 text-center">Silahkan login terlebih dahulu</h5>
                    
                        <div class="form-group mb-2">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email">
                        </div>

                        <div class="form-group mb-2">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    
                        <div class="d-flex justify-content-between">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="rememberme">
                                    <label class="form-check-label" for="flexCheckDefault">Ingat Saya</label>
                                </div>
                            </div>
                            <p class="forgot-pass mb-0"><a href="{{route('dashboard.auth.forgot-password.index')}}" class="text-dark small fw-bold">Lupa Password ?</a></p>
                        </div>
        
                        <button class="btn btn-primary w-100" type="submit">Login</button>

                        <div class="row">
                            <div class="col-lg-12 mt-2 text-center">
                                <h6>Atau login dengan</h6>
                                <div class="row">
                                    <div class="col-12 mt-1">
                                        <div class="d-grid">
                                            <a href="{{route('dashboard.auth.google.index')}}" class="btn btn-light"><i class="mdi mdi-google text-danger"></i> Google</a>
                                        </div>
                                    </div><!--end col-->
                                </div>
                            </div><!--end col-->
                        </div>

                        <div class="col-12 text-center mt-3">
                            <p class="mb-0 mt-3"><small class="text-dark me-2">Belum punya akun ?</small> <a href="{{route('dashboard.auth.register.index')}}" class="text-dark fw-bold">Daftar Sekarang</a></p>
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