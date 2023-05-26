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
                    <form method="POST" action="{{route('dashboard.auth.verivication.send')}}">
                        @csrf
                        @include("dashboard.auth.layouts.logo")
                        <h5 class="mb-3 text-center">Verifikasi Email</h5>
                        
                        <div class="row">
                            <div class="col-12">
                                <p class="text-center">
                                    sdasd
                                </p>
                            </div>
                        </div>
        
                        <button class="btn btn-primary w-100" type="submit">{{ __('click here to request another') }}</button>

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