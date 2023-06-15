@extends("landing-page.layouts.main")

@section("title","Hubungi Kami")

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">Hubungi Kami</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hubungi Kami</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section pb-0">
    <div class="container">
        <div class="row d-flex justify-content-center">
            @if(!empty(\SettingHelper::settings('landing_page', 'phone')))
            <div class="col-md-4">
                <div class="card border-0 text-center features feature-primary feature-clean">
                    <div class="icons text-center mx-auto">
                        <i class="uil uil-phone rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-4">
                        <h5 class="fw-bold">Phone</h5>
                        <a href="tel:{{ \SettingHelper::settings('landing_page', 'phone')}}" class="text-muted">{{ \SettingHelper::settings('landing_page', 'phone')}}</a>
                    </div>
                </div>
            </div>
            @endif

            @if(!empty(\SettingHelper::settings('landing_page', 'email')))
            <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 text-center features feature-primary feature-clean">
                    <div class="icons text-center mx-auto">
                        <i class="uil uil-envelope rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-4">
                        <h5 class="fw-bold">Email</h5>
                        <a class="text-muted" href="mailto:{{ \SettingHelper::settings('landing_page', 'email')}}">{{ \SettingHelper::settings('landing_page', 'email')}}</a>
                    </div>
                </div>
            </div><!--end col-->
            @endif
            
            @if(!empty(\SettingHelper::settings('landing_page', 'location')))
            <div class="col-md-4 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card border-0 text-center features feature-primary feature-clean">
                    <div class="icons text-center mx-auto">
                        <i class="uil uil-map-marker rounded h3 mb-0"></i>
                    </div>
                    <div class="content mt-4">
                        <h5 class="fw-bold">Location</h5>
                        <a class="text-muted" href="#">{{ \SettingHelper::settings('landing_page', 'location')}}</a>
                    </div>
                </div>
            </div><!--end col-->
            @endif
        </div><!--end row-->
    </div><!--end container-->

    <div class="container mt-100 mt-60">
        <div class="row align-items-center">
            <div class="col-12 pt-2 pt-sm-0 order-2 order-md-1">
                <div class="card shadow rounded border-0">
                    <div class="card-body py-5">
                        <h4 class="card-title">Dapatkan Informasi</h4>
                        <div class="custom-form mt-3">
                            <form method="post" id="frmStore" onsubmit="return confirm('Apakah anda yakin ingin mengirim data ini?')" action="{{route('landing-page.contact-us.store')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="user" class="fea icon-sm icons"></i>
                                                <input name="name" id="name" type="text" class="form-control ps-5" placeholder="Nama Lengkap" value="{{old('name')}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="mail" class="fea icon-sm icons"></i>
                                                <input name="email" id="email" type="email" class="form-control ps-5" placeholder="Email" value="{{old('email')}}">
                                            </div>
                                        </div> 
                                    </div><!--end col-->

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Subjek Pesan</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="book" class="fea icon-sm icons"></i>
                                                <input name="subject" id="subject" class="form-control ps-5" placeholder="Subjek Pesan" value="{{old('subject')}}">
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Pesan <span class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="message-circle" class="fea icon-sm icons clearfix"></i>
                                                <textarea name="message" id="message" rows="4" class="form-control ps-5" placeholder="Pesan">{{old('message')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" id="submit" name="send" class="btn btn-primary">Kirim Pesan</button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </form>
                        </div><!--end custom-form-->
                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div><!--end container-->
</section>
@endsection

@section("script")
<script>
    $(function(){

    })
</script>
@endsection