@extends("landing-page.layouts.main")

@section("title","Home")

@section("css")
@endsection

@section("content")
<!-- Start -->
<section class="bg-home pb-5 pb-sm-0 d-flex align-items-center bg-linear-gradient-primary">
    <div class="container">
        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <div class="title-heading me-lg-4 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h1 class="heading fw-bold mb-3">{{ \SettingHelper::settings('landing_page', 'title')}}</h1>
                    <p class="para-desc text-muted">{{ \SettingHelper::settings('landing_page', 'description')}}</p>
                    <a href="{{route('landing-page.pages.index','about-us')}}" class="btn btn-primary text-uppercase">Tentang Kami</a>
                </div>
            </div><!--end col-->

            <div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0">
                <div class="position-relative ms-lg-5">
                    <div class="bg-half-260 overflow-hidden rounded-md shadow-md jarallax" data-jarallax data-speed="0.5" style="background: url('/templates/landing-page/assets/images/saas/modern-hero.jpg');">
                        <div class="py-lg-5 py-md-0 py-5"></div>
                    </div>

                    <div class="modern-saas-absolute-left wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                        <div class="card">
                            <div class="features feature-primary d-flex justify-content-between align-items-center rounded shadow p-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon bg-soft-primary text-center rounded-pill">
                                        <i class="uil uil-usd-circle fs-4 mb-0"></i>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <h6 class="mb-0 text-muted">Revenue</h6>
                                        <p class="fs-5 text-dark fw-bold mb-0">$<span class="counter-value" data-target="48575">45968</span></p>
                                    </div>
                                </div>

                                <span class="text-success ms-4"><i class="uil uil-arrow-growth"></i> 3.84%</span>
                            </div>
                        </div>
                    </div>

                    <div class="modern-saas-absolute-right wow animate__animated animate__fadeInUp" data-wow-delay=".5s">
                        <div class="card rounded shadow">
                            <div class="p-3">
                                <h5>Manage Your Software</h5>

                                <div class="progress-box mt-2">
                                    <h6 class="title fw-normal text-muted">Work in dashboard</h6>
                                    <div class="progress">
                                        <div class="progress-bar position-relative bg-primary" style="width:84%;">
                                            <div class="progress-value d-block text-muted h6 mt-1">84%</div>
                                        </div>
                                    </div>
                                </div><!--end process box-->
                            </div>
                        </div>
                    </div>

                    <div class="position-absolute top-0 start-0 translate-middle z-index-m-1">
                        <img src="{{URL::to('/')}}/templates/landing-page/assets/images/shapes/dots.svg" class="avatar avatar-xl-large" alt="">
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End -->

<section class="section overflow-hidden">
    <div class="container">
        <div class="row d-flex justify-content-center">
            @foreach($our_services as $index => $row)
            <div class="col-md-4 col-12 wow animate__animated animate__fadeInUp">
                <div class="features feature-primary text-center">
                    <div class="image position-relative d-inline-block">
                        <i class="{{$row->icon}} h2 text-primary" style="font-size:40px;"></i>
                    </div>

                    <div class="content mt-4">
                        <h5>{{$row->name}}</h5>
                        <p class="text-muted mb-0">{{$row->description}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div><!--end row-->
    </div><!--end container-->
</section>

<!-- How It Work Start -->
<section class="section">
    <div class="container">
        @foreach($whyUs as $index => $row)
        <div class="row mb-3">
            <div class="col-12">
                <div class="section-title mb-4 pb-2 wow animate__animated animate__fadeInUp">
                    <h5 class="fw-bold text-uppercase text-success">{{$row->title}}</h5>
                    <h2 class="fw-semibold">{{$row->sub_title}}</h2>
                    <p class="text-muted para-desc mb-0 mx-auto">{!! $row->trixRender('content') !!}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div><!--end container-->

    @if(count($testimonials) >= 1)
    <div class="container mt-100 mt-60">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h4 class="title mb-4">Bagaimana testimoni dari klien kami?</h4>
                    <p class="text-muted para-desc mb-0 mx-auto">Start working with <span class="text-primary fw-bold">Landrick</span> that can provide everything you need to generate awareness, drive traffic, connect.</p>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row justify-content-center">
            <div class="col-lg-12 mt-4">
                <div class="tiny-three-item">
                    @foreach($testimonials as $index => $row)
                    <div class="tiny-slide wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                        <div class="d-flex client-testi m-1">
                            <img src="{{asset($row->avatar)}}" class="avatar avatar-small client-image rounded shadow" alt="">
                            <div class="card flex-1 content p-3 shadow rounded position-relative">
                                <ul class="list-unstyled mb-0">
                                    @for($star=1;$star<=$row->star;$star++)
                                    <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                    @endfor
                                </ul>
                                <p class="text-muted mt-2">" {{$row->message}} "</p>
                                <h6 class="text-primary">- {{$row->name}} <small class="text-muted">{{$row->position}}</small></h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
    @endif

    @if(count($faqs) >= 1)
    <div class="container mt-100 mt-60">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <h4 class="title mb-4">Frequently Asked Questions</h4>
                    <p class="text-muted para-desc mb-0 mx-auto">Start working with <span class="text-primary fw-bold">Landrick</span> that can provide everything you need to generate awareness, drive traffic, connect.</p>
                </div>
            </div><!--end col-->
        </div><!--end row-->
        
        <div class="row align-items-center">
            <div class="col-md-6 mt-4 pt-2">
                <div class="bg-half-260 overflow-hidden rounded-md shadow-md jarallax" data-jarallax data-speed="0.5" style="background: url('/templates/landing-page/assets/images/saas/cta.jpg');">
                </div>
            </div><!--end col-->

            <div class="col-md-6 mt-4 pt-2">
                <div class="accordion" id="accordionExample">
                    @foreach($faqs as $index => $row)
                    <div class="accordion-item rounded shadow mt-3 wow animate__animated animate__fadeInUp" data-wow-delay=".5s">
                        <h2 class="accordion-header" id="heading-{{$index}}">
                            <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$index}}"
                                aria-expanded="false" aria-controls="collapse-{{$index}}">
                                {{$row->question}}
                            </button>
                        </h2>
                        <div id="collapse-{{$index}}" class="accordion-collapse border-0 collapse" aria-labelledby="heading-{{$index}}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                {{$row->answer}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
    @endif
</section><!--end section-->
<!-- End -->
@endsection

@section("script")
<script>
    $(function(){

    })
</script>
@endsection