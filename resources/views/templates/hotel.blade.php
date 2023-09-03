<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    @include('templates.header')
    <!-- Css -->
    <link href="{{ asset('templates/landing-page/assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('templates/landing-page/assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
    <link href="{{ asset('templates/landing-page/assets/libs/js-datepicker/datepicker.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('templates/landing-page/assets/css/bootstrap.min.css') }}" id="bootstrap-style"
        class="theme-opt" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('templates/landing-page/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('templates/landing-page/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css"
        rel="stylesheet">
    <!-- Style Css-->
    <link href="{{ asset('templates/landing-page/assets/css/style.min.css') }}" id="color-opt" class="theme-opt"
        rel="stylesheet" type="text/css">

</head>

<body>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>
    </div>
    <!-- Loader -->


    <!-- Navbar Start -->
    <header id="topnav" class="defaultscroll sticky navbar-white-bg">
        <div class="container">
            <!-- Logo container-->
            <a class="logo" href="index.html">
                <img src="{{ $data?->logo_dark != null || $data?->logo_dark != '' ? asset($data?->logo_dark) : asset('templates/landing-page/assets/images/logo-dark.png') }}"
                    height="24" class="logo-light-mode" alt="{{ $data?->title }}">
                <img src="{{ $data?->logo != null || $data?->logo != '' ? asset($data?->logo) : asset('templates/landing-page/assets/images/logo-light.png') }}"
                    height="24" class="logo-dark-mode" alt="{{ $data?->title }}">
            </a>
            <!-- Logo End -->

            <!-- End Logo container-->
            <div class="menu-extras">
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>

            @include('templates.login')

            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu nav-dark">
                    <li onclick="setActiveNav('menu-home')" id="menu-home"><a href="#home"
                            class="sub-menu-item">Home</a></li>
                    <li onclick="setActiveNav('menu-bookroom')" id="menu-bookroom"><a href="#bookroom"
                            class="sub-menu-item">Book Now</a></li>
                    <li onclick="setActiveNav('menu-service')" id="menu-service"><a href="#service"
                            class="sub-menu-item">Service</a></li>
                </ul><!--end navigation menu-->
            </div><!--end navigation-->
        </div><!--end container-->
    </header><!--end header-->
    <!-- Navbar End -->

    <!-- Hero Start -->
    <section class="home-slider position-relative" id="home">
        <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse ($headerSection as $key => $item)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-bs-interval="3000">
                        <div class="bg-home bg-animation-left d-flex align-items-center"
                            style="background-image:url({{ asset($item->image) }})">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-7 col-md-7">
                                        <div class="title-heading position-relative mt-4" style="z-index: 1;">
                                            <h1 class="heading mb-3">{{ $item->title }}</h1>
                                            <p class="para-desc">{{ $item->caption }}.</p>
                                            <div class="mt-4 pt-2">
                                                <a href="#rooms" class="btn btn-icon btn-pills btn-primary"><i
                                                        data-feather="home" class="icons"></i>
                                                </a>
                                                <span class="fw-bold text-uppercase small align-middle ms-2">Watch
                                                    Rooms</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active" data-bs-interval="3000">
                        <div class="bg-home bg-animation-left d-flex align-items-center"
                            style="background-image:url({{ asset('templates/landing-page/assets/images/hotel/bg01.jpg') }})">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-7 col-md-7">
                                        <div class="title-heading position-relative mt-4" style="z-index: 1;">
                                            <h1 class="heading mb-3">Ini Title bisa diganti di setting landing page
                                                Dashboard</h1>
                                            <p class="para-desc">Yang ditampilkan disini hanyalah demo,silahkan setting
                                                Terlebih dahulu untuk mengganti konten.</p>
                                            <div class="mt-4 pt-2">
                                                <a href="#rooms" class="btn btn-icon btn-pills btn-primary"><i
                                                        data-feather="home" class="icons"></i>
                                                </a>
                                                <span class="fw-bold text-uppercase small align-middle ms-2">Watch
                                                    Rooms</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Partners start -->
    <section class="section-two bg-light" id="bookroom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <form class="card p-4 shadow rounded">
                        <h4 class="mb-3">Book Now !</h4>
                        <div class="row text-start">
                            <div class="col-lg-3 col-md-6">
                                <div class="mb-3 mb-lg-0">
                                    <label class="form-label"> Check in : </label>
                                    <input name="date" type="text" class="form-control start"
                                        placeholder="Select date :">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6">
                                <div class="mb-3 mb-lg-0">
                                    <label class="form-label"> Check out : </label>
                                    <input name="date" type="text" class="form-control end"
                                        placeholder="Select date :">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-6">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="mb-3 mb-lg-0">
                                            <label class="form-label">Adults : </label>
                                            <input type="number" min="0" autocomplete="off" id="adult"
                                                required="" class="form-control" placeholder="Adults :">
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-md-4">
                                        <div class="mb-3 mb-lg-0">
                                            <label class="form-label">Children : </label>
                                            <input type="number" min="0" autocomplete="off" id="children"
                                                class="form-control" placeholder="Children :">
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-md-4 mt-lg-4 pt-2 pt-lg-1">
                                        <div class="d-grid">
                                            <input type="submit" id="search" name="search"
                                                class="searchbtn btn btn-primary" value="Search">
                                        </div>
                                    </div><!--end col-->
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Partners End -->

    <!-- Rooms Start -->
    <section class="section" id="rooms">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Rooms & Suits</h4>
                        <p class="text-muted para-desc mb-0 mx-auto">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to
                            generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                @forelse ($product as $item)
                    <div class="col-lg-4 col-md-6 mt-4 pt-2">
                        <div class="card work-container work-primary work-modern rounded border-0 overflow-hidden">
                            <div class="card-body p-0">
                                <img src="{{ asset($item->image) }}" class="img-fluid rounded" alt="work-image">
                                <div class="content">
                                    <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                        class="title text-white title-dark pb-2 border-bottom">{{ ucfirst($item->name) }}</a>
                                    <ul class="post-meta mb-0 mt-2 text-white title-dark list-unstyled">
                                        <li class="list-inline-item me-3"><i class="uil uil-bed-double me-2"></i>1 Bed
                                        </li>
                                        <li class="list-inline-item"><i class="uil uil-bath me-2"></i>1 Bathrooms</li>
                                    </ul>
                                    <p class="text-white title-dark d-block mb-0">Rent <span
                                            class="text-success">{{ moneyFormat($item->price) }}</span> /Day</p>
                                </div>

                                <div class="position-absolute top-0 end-0 m-3 z-index-1">
                                    <a href="" class="btn btn-primary btn-pills btn-sm btn-icon"><i
                                            data-feather="chevron-right" class="fea icon-sm title-dark"></i></a>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                @empty
                    <div class="col-lg-4 col-md-6 mt-4 pt-2">
                        <div class="card work-container work-primary work-modern rounded border-0 overflow-hidden">
                            <div class="card-body p-0">
                                <img src="{{ asset('templates/landing-page/assets/images/hotel/01.jpg') }}"
                                    class="img-fluid rounded" alt="work-image">
                                <div class="content">
                                    <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                        class="title text-white title-dark pb-2 border-bottom">Ini hanyalah Demo</a>
                                    <ul class="post-meta mb-0 mt-2 text-white title-dark list-unstyled">
                                        <li class="list-inline-item me-3"><i
                                                class="uil uil-bed-double me-2"></i>Tambahkan Produk
                                        </li>
                                        <li class="list-inline-item"><i class="uil uil-bath me-2"></i>Di Bisnis hotel
                                            Anda</li>
                                    </ul>
                                    <p class="text-white title-dark d-block mb-0">Rent <span
                                            class="text-success">$350</span> /Night</p>
                                </div>

                                <div class="position-absolute top-0 end-0 m-3 z-index-1">
                                    <a href="" class="btn btn-primary btn-pills btn-sm btn-icon"><i
                                            data-feather="chevron-right" class="fea icon-sm title-dark"></i></a>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                @endforelse
            </div><!--end row-->

            <div class="row justify-content-center">
                <div class="col-12 text-center mt-4 pt-2">
                    <a href="{{ url('/') . '/shops/' . $business->slug }}" class="btn btn-primary">See More <i
                            class="uil uil-angle-right-b"></i></a>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Rooms End -->

    <!-- CTA Start -->
    <section class="section bg-cta"
        style="background: url({{ asset('templates/landing-page/assets/images/hotel/bg04.jpg') }}) center center;"
        id="cta">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title">
                        <h4 class="title title-dark text-white mb-4">Resembling Tour of {{ ucfirst($business->name) }}
                            Resort</h4>
                        <p class="text-white-50 para-dark para-desc mx-auto">Start working with
                            {{ ucfirst($business->name) }} that can
                            provide everything you need to generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- CTA End -->

    <!-- Services Start -->
    <section class="section" id="service">
        <div class="container pb-lg-4 mb-md-5 mb-4">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Best Services for you</h4>
                        <p class="text-muted para-desc mb-0 mx-auto">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to
                            generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-md-4 col-12 mt-5 pt-4">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-wifi h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Free WIFI</h5>
                            <p class="text-muted mb-0">Nisi aenean vulputate eleifend tellus vitae eleifend enim a
                                Aliquam aenean elementum semper.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 pt-4">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-process h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Relaxation</h5>
                            <p class="text-muted mb-0">Allegedly, a Latin scholar established the origin of the text by
                                established compiling unusual word.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 pt-4">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-dumbbell h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Spa & Fitness</h5>
                            <p class="text-muted mb-0">It seems that only fragments of the original text remain in the
                                Lorem Ipsum fragments texts used today.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 pt-4">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-restaurant h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Restaurant</h5>
                            <p class="text-muted mb-0">It seems that only fragments of the original text remain in the
                                Lorem Ipsum fragments texts used today.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 pt-4">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-band-aid h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Smooth Parallax</h5>
                            <p class="text-muted mb-0">Nisi aenean vulputate eleifend tellus vitae eleifend enim a
                                Aliquam aenean elementum semper.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 pt-4">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-bed-double h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Bedrooms</h5>
                            <p class="text-muted mb-0">Allegedly, a Latin scholar established the origin of the text by
                                established compiling unusual word.</p>
                        </div>
                    </div>
                </div><!--end col-->
            </div>
        </div><!--end container-->
    </section><!--end section-->
    <div class="position-relative">
        <div class="shape overflow-hidden text-light">
            <svg viewBox="0 0 2880 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M720 125L2160 0H2880V250H0V125H720Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!-- End services -->

    <!-- News Start -->
    <section class="section pt-5 pt-sm-0 pt-md-4 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Latest News</h4>
                        <p class="text-muted para-desc mx-auto mb-0">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to
                            generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card blog blog-primary rounded border-0 shadow">
                        <div class="position-relative">
                            <img src="{{ asset('templates/landing-page/assets/images/hotel/bg01.jpg') }}"
                                class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="javascript:void(0)" class="card-title title text-dark">Design your apps in
                                    your own way</a></h5>
                            <div class="post-meta d-flex justify-content-between mt-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="list-inline-item me-2 mb-0"><a href="javascript:void(0)"
                                            class="text-muted like"><i class="uil uil-heart me-1"></i>33</a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)"
                                            class="text-muted comments"><i class="uil uil-comment me-1"></i>08</a>
                                    </li>
                                </ul>
                                <a href="blog-detail.html" class="text-muted readmore">Read More <i
                                        class="uil uil-angle-right-b align-middle"></i></a>
                            </div>
                        </div>
                        <div class="author">
                            <small class="user d-block"><i class="uil uil-user"></i> Calvin Carlo</small>
                            <small class="date"><i class="uil uil-calendar-alt"></i> 25th June 2021</small>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card blog blog-primary rounded border-0 shadow">
                        <div class="position-relative">
                            <img src="{{ asset('templates/landing-page/assets/images/hotel/bg02.jpg') }}"
                                class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="javascript:void(0)" class="card-title title text-dark">How apps is changing
                                    the IT world</a></h5>
                            <div class="post-meta d-flex justify-content-between mt-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="list-inline-item me-2 mb-0"><a href="javascript:void(0)"
                                            class="text-muted like"><i class="uil uil-heart me-1"></i>33</a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)"
                                            class="text-muted comments"><i class="uil uil-comment me-1"></i>08</a>
                                    </li>
                                </ul>
                                <a href="blog-detail.html" class="text-muted readmore">Read More <i
                                        class="uil uil-angle-right-b align-middle"></i></a>
                            </div>
                        </div>
                        <div class="author">
                            <small class="user d-block"><i class="uil uil-user"></i> Calvin Carlo</small>
                            <small class="date"><i class="uil uil-calendar-alt"></i> 25th June 2021</small>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card blog blog-primary rounded border-0 shadow">
                        <div class="position-relative">
                            <img src="{{ asset('templates/landing-page/assets/images/hotel/bg03.jpg') }}"
                                class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="javascript:void(0)" class="card-title title text-dark">Smartest
                                    Applications for Business</a></h5>
                            <div class="post-meta d-flex justify-content-between mt-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="list-inline-item me-2 mb-0"><a href="javascript:void(0)"
                                            class="text-muted like"><i class="uil uil-heart me-1"></i>33</a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)"
                                            class="text-muted comments"><i class="uil uil-comment me-1"></i>08</a>
                                    </li>
                                </ul>
                                <a href="blog-detail.html" class="text-muted readmore">Read More <i
                                        class="uil uil-angle-right-b align-middle"></i></a>
                            </div>
                        </div>
                        <div class="author">
                            <small class="user d-block"><i class="uil uil-user"></i> Calvin Carlo</small>
                            <small class="date"><i class="uil uil-calendar-alt"></i> 25th June 2021</small>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Blog End -->

    <!-- Client Start -->
    <section class="section"
        style="background: url({{ asset('templates/landing-page/assets/images/hotel/bg05.jpg') }}) center center;">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <div class="tiny-single-item">
                        @forelse ($testimoniSection as $item)
                            <div class="tiny-slider text-center client-testi">
                                <p class="text-white-50 para-dark h6 fst-italic">" {{ $item->content }}. "</p>
                                <ul class="list-unstyled mb-0 mt-3">
                                    @for ($i = 0; $i < $item->stars; $i++)
                                        <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                    @endfor
                                </ul>
                                <h6 class="text-white"> {{ $item->name }} </h6>
                                <img src="{{ asset('templates/landing-page/assets/images/client/01.jpg') }}"
                                    class="img-fluid avatar avatar-small rounded-circle mx-auto shadow"
                                    alt="">
                            </div>
                        @empty
                            <div class="tiny-slider text-center client-testi">
                                <p class="text-white-50 para-dark h6 fst-italic">" Silahkan Setting Testimoni di
                                    Dashboard untuk menampilkan disini. "</p>
                                <ul class="list-unstyled mb-0 mt-3">
                                    <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i></li>
                                </ul>
                                <h6 class="text-white"> Ini adalah Demo </h6>
                                <img src="{{ asset('templates/landing-page/assets/images/client/01.jpg') }}"
                                    class="img-fluid avatar avatar-small rounded-circle mx-auto shadow"
                                    alt="">
                            </div>
                        @endforelse
                    </div><!--end owl carousel-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- Client End -->
    </section>
    <!-- Client End -->

    <!-- Contact Start -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-6 p-0 ps-md-3 pe-md-3">
                    <div class="card map map-height-two rounded map-gray border-0">
                        <iframe
                            src="https://maps.google.com/maps?q={{ urlencode($business->name) }}&t=m&z=18&output=embed&iwloc=near"
                            style="border:0" class="rounded" allowfullscreen></iframe>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card rounded shadow border-0">
                        <div class="card-body py-5">
                            <h5 class="card-title">Get In Touch !</h5>

                            <div class="custom-form mt-4">
                                <div id="message"></div>
                                <form method="post" action="php/contact.php" name="contact-form" id="contact-form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Your Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="user" class="fea icon-sm icons"></i>
                                                    <input name="name" id="name" type="text"
                                                        class="form-control ps-5" placeholder="First Name :">
                                                </div>
                                            </div>
                                        </div><!--end col-->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Your Email <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input name="email" id="email" type="email"
                                                        class="form-control ps-5" placeholder="Your email :">
                                                </div>
                                            </div>
                                        </div><!--end col-->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Comments</label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="message-circle" class="fea icon-sm icons"></i>
                                                    <textarea name="comments" id="comments" rows="4" class="form-control ps-5" placeholder="Your Message :"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end row-->
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <div class="d-grid">
                                                <input type="submit" id="submit" name="send"
                                                    class="submitBnt btn btn-primary" value="Send Message">
                                                <div id="simple-msg"></div>
                                            </div>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </form><!--end form-->
                            </div><!--end custom-form-->
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- Contact End -->
    </section><!--end section-->
    <!-- News End -->


    <!-- Footer Start -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-py-60">
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="#" class="logo-footer">
                                    <img src="{{ $data?->logo != null || $data?->logo != '' ? asset($data?->logo) : asset('templates/landing-page/assets/images/logo-light.png') }}"
                                        class="l-light" height="24" alt="{{ $data?->title }}">
                                </a>
                                <p class="mt-4">Start working with {{ ucfirst($business->name) }} that can provide
                                    everything you need to
                                    generate awareness, drive traffic, connect.</p>
                                @include('templates.list-icon')
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="footer-py-30 footer-bar">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="text-sm-start">
                            <p class="mb-0">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ ucfirst($business->name) }}. Design with <i
                                    class="mdi mdi-heart text-danger"></i> by <a href="{{ url('/') }}"
                                    target="_blank" class="text-reset">{{ env('APP_NAME', 'Laravel') }}</a>.
                            </p>
                        </div>
                    </div><!--end col-->

                    <div class="col-sm-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                        <ul class="list-unstyled text-sm-end mb-0">
                            <li class="list-inline-item"><a href="javascript:void(0)"><img
                                        src="{{ asset('templates/landing-page/assets/images/payments/american-ex.png') }}"
                                        class="avatar avatar-ex-sm" title="American Express" alt=""></a>
                            </li>
                            <li class="list-inline-item"><a href="javascript:void(0)"><img
                                        src="{{ asset('templates/landing-page/assets/images/payments/discover.png') }}"
                                        class="avatar avatar-ex-sm" title="Discover" alt=""></a></li>
                            <li class="list-inline-item"><a href="javascript:void(0)"><img
                                        src="{{ asset('templates/landing-page/assets/images/payments/master-card.png') }}"
                                        class="avatar avatar-ex-sm" title="Master Card" alt=""></a></li>
                            <li class="list-inline-item"><a href="javascript:void(0)"><img
                                        src="{{ asset('templates/landing-page/assets/images/payments/paypal.png') }}"
                                        class="avatar avatar-ex-sm" title="Paypal" alt=""></a></li>
                            <li class="list-inline-item"><a href="javascript:void(0)"><img
                                        src="{{ asset('templates/landing-page/assets/images/payments/visa.png') }}"
                                        class="avatar avatar-ex-sm" title="Visa" alt=""></a></li>
                        </ul>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </div>
    </footer><!--end footer-->
    <!-- Footer End -->


    <!-- Cookies Start -->
    <div class="card cookie-popup shadow rounded py-3 px-4">
        <p class="text-muted mb-0">This website uses cookies to provide you with a great user experience. By using it,
            you accept our <a href="https://shreethemes.in" target="_blank" class="text-success h6">use of
                cookies</a></p>
        <div class="cookie-popup-actions text-end">
            <button><i class="uil uil-times text-dark fs-4"></i></button>
        </div>
    </div>
    <!--Note: Cookies Js including in plugins.init.js (path like; js/plugins.init.js) and Cookies css including in _helper.scss (path like; scss/_helper.scss)-->
    <!-- Cookies End -->


    @include('templates.off-canvas')
    <!-- Switcher Start -->
    <a href="javascript:void(0)" class="card switcher-btn shadow-md text-primary z-index-1 d-md-inline-flex d-none"
        data-bs-toggle="offcanvas" data-bs-target="#switcher-sidebar">
        <i class="mdi mdi-cog mdi-24px mdi-spin align-middle"></i>
    </a>

    <div class="offcanvas offcanvas-start shadow border-0" tabindex="-1" id="switcher-sidebar"
        aria-labelledby="offcanvasLeftLabel">
        <div class="offcanvas-header p-4 border-bottom">
            <h5 id="offcanvasLeftLabel" class="mb-0">
                <img src="{{ asset('templates/landing-page/assets/images/logo-dark.png') }}" height="24"
                    class="light-version" alt="">
                <img src="{{ asset('templates/landing-page/assets/images/logo-light.png') }}" height="24"
                    class="dark-version" alt="">
            </h5>
            <button type="button" class="btn-close d-flex align-items-center text-dark" data-bs-dismiss="offcanvas"
                aria-label="Close"><i class="uil uil-times fs-4"></i></button>
        </div>
        <div class="offcanvas-body p-4 pb-0">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h6 class="fw-bold">Select your color</h6>
                        <ul class="pattern mb-0 mt-3">
                            <li>
                                <a class="color-list rounded color1" href="javascript: void(0);"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Primary"
                                    onclick="setColorPrimary()"></a>
                            </li>
                            <li>
                                <a class="color-list rounded color2" href="javascript: void(0);"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Green"
                                    onclick="setColor('green')"></a>
                            </li>
                            <li>
                                <a class="color-list rounded color3" href="javascript: void(0);"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Yellow"
                                    onclick="setColor('yellow')"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="text-center mt-4 pt-4 border-top">
                        <h6 class="fw-bold">Theme Options</h6>

                        <ul class="text-center style-switcher list-unstyled mt-4">
                            <li class="d-grid"><a href="javascript:void(0)" class="rtl-version t-rtl-light"
                                    onclick="setTheme('style-rtl')"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/rtl.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">RTL
                                        Version</span></a></li>
                            <li class="d-grid"><a href="javascript:void(0)" class="ltr-version t-ltr-light"
                                    onclick="setTheme('style')"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/ltr.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">LTR
                                        Version</span></a></li>
                            <li class="d-grid"><a href="javascript:void(0)" class="dark-rtl-version t-rtl-dark"
                                    onclick="setTheme('style-dark-rtl')"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/dark-rtl.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">RTL
                                        Version</span></a></li>
                            <li class="d-grid"><a href="javascript:void(0)" class="dark-ltr-version t-ltr-dark"
                                    onclick="setTheme('style-dark')"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/dark.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">LTR
                                        Version</span></a></li>
                            <li class="d-grid"><a href="javascript:void(0)" class="dark-version t-dark mt-4"
                                    onclick="setTheme('style-dark')"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/dark.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">Dark
                                        Version</span></a></li>
                            <li class="d-grid"><a href="javascript:void(0)" class="light-version t-light mt-4"
                                    onclick="setTheme('style')"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/ltr.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">Light
                                        Version</span></a></li>
                            <li class="d-grid"><a href="../../dashboard/dist/index.html" target="_blank"
                                    class="mt-4"><img
                                        src="{{ asset('templates/landing-page/assets/images/demos/admin.png') }}"
                                        class="img-fluid rounded-md shadow-md d-block mx-auto" style="width: 240px;"
                                        alt=""><span class="text-dark fw-medium mt-3 d-block">Admin
                                        Dashboard</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas-footer p-4 border-top text-center">
            @include('templates.list-icon')
        </div>
    </div>
    <!-- Switcher End -->

    <!-- Back to top -->
    <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up"
            class="fea icon-sm icons align-middle"></i></a>
    <!-- Back to top -->

    <!-- javascript -->
    <!-- JAVASCRIPT -->
    <script src="{{ asset('templates/landing-page/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SLIDER -->
    <script src="{{ asset('templates/landing-page/assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
    <!-- Lightbox -->
    <script src="{{ asset('templates/landing-page/assets/libs/tobii/js/tobii.min.js') }}"></script>
    <!-- Datepicker -->
    <script src="{{ asset('templates/landing-page/assets/libs/js-datepicker/datepicker.min.js') }}"></script>
    <!-- Main Js -->
    <script src="{{ asset('templates/landing-page/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('templates/landing-page/assets/js/plugins.init.js') }}"></script>
    <!--Note: All init js like tiny slider, counter, countdown, maintenance, lightbox, gallery, swiper slider, aos animation etc.-->
    <script src="{{ asset('templates/landing-page/assets/js/app.js') }}"></script>
    <!--Note: All important javascript like page loader, menu, sticky menu, menu-toggler, one page menu etc. -->
    @include('templates.custom-script')
</body>

</html>
