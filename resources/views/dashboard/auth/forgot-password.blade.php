@extends("dashboard.auth.layouts.main")

@section("title","Lupa Password")

@section("css")
@endsection

@section("content")
<section class="bg-home bg-circle-gradiant d-flex align-items-center">
    <div class="bg-overlay bg-overlay-white"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card form-signin p-4 rounded shadow">
                    <form method="POST" action="{{route('dashboard.auth.forgot-password.post')}}" autocomplete="off">
                        @csrf
                        @include("dashboard.auth.layouts.logo")
                        <h5 class="mb-3 text-center">Lupa Password</h5>
                        
                        <div class="row">
                            <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                <strong>Pemberitahuan</strong>, <br>Masukkan alamat email yang Anda gunakan saat Login dan kami akan mengirimkan petunjuk untuk mengatur ulang kata sandi.
                            </div>
                            </div>
                        </div>
                    
                        <div class="form-group mb-2">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email">
                        </div>
        
                        <button class="btn btn-primary w-100" type="submit">Submit</button>

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