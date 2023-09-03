@extends("dashboard.auth.layouts.main")

@section("title","Verifikasi Email")

@section("css")
@endsection

@section("content")
<section class="bg-home bg-circle-gradiant d-flex align-items-center">
    <div class="bg-overlay bg-overlay-white"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card form-signin p-4 rounded shadow">
                    @include("dashboard.auth.layouts.logo")
                    <h5 class="mb-3 text-center">Verifikasi Email</h5>

                    <div class="row">
                            <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <strong>Pemberitahuan</strong>, <br>Selamat,akun anda berhasil diaktivasi . Silahkan login untuk melanjutkan aplikasi
                            </div>
                            </div>
                        </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2 text-center">
                                @auth
                                <a href="{{ route('dashboard.index') }}" class="btn btn-primary w-100">
                                    {{ __('Kembali ke Beranda') }}
                                </a>
                                @else
                                <a href="{{ route('dashboard.auth.login.index') }}" class="btn btn-primary w-100">
                                    {{ __('Login') }}
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    @include("dashboard.auth.layouts.footer")
                </div>
            </div>
        </div>
    </div> <!--end container-->
</section><!--end section-->
@endsection

@section("js")
@endsection