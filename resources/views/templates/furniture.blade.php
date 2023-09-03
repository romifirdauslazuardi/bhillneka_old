<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    @include('templates.header')
    <!-- Bootstrap Css -->
    <link href="{{ asset('templates/landing-page/assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('templates/landing-page/assets/css/bootstrap-yellow.min.css') }}" id="bootstrap-style"
        class="theme-opt" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('templates/landing-page/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('templates/landing-page/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css"
        rel="stylesheet">
    <!-- Style Css-->
    <link href="{{ asset('templates/landing-page/assets/css/style-yellow.min.css') }}" id="color-opt" class="theme-opt"
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
    <header id="topnav" class="defaultscroll sticky">
        <div class="container">
            <!-- Logo container-->
            <a class="logo" href="{{ route(Route::currentRouteName()) }}">
                <span class="logo-light-mode">
                    <img src="{{ $data?->logo_dark != null || $data?->logo_dark != '' ? asset($data?->logo_dark) : asset('templates/landing-page/assets/images/logo-dark.png') }}"
                        class="l-dark" height="24" alt="{{ $data?->title }}">
                    <img src="{{ $data?->logo != null || $data?->logo != '' ? asset($data?->logo) : asset('templates/landing-page/assets/images/logo-light.png') }}"
                        class="l-light" height="24" alt="{{ $data?->title }}">
                </span>
                <img src="{{ $data?->logo != null || $data?->logo != '' ? asset($data?->logo) : asset('templates/landing-page/assets/images/logo-light.png') }}"
                    height="24" class="logo-dark-mode" alt="{{ $data?->title }}">
            </a>

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
                <ul class="navigation-menu nav-light">
                    <li onclick="setActiveNav('menu-home')" id="menu-home"><a href="#home"
                            class="sub-menu-item">Home</a></li>
                    <li onclick="setActiveNav('menu-categories')" id="menu-categories"><a href="#categories"
                            class="sub-menu-item">categories</a></li>
                    <li onclick="setActiveNav('menu-products')" id="menu-products"><a href="#products"
                            class="sub-menu-item">Products</a></li>
                </ul><!--end navigation menu-->
            </div><!--end navigation-->
        </div><!--end container-->
    </header><!--end header-->
    <!-- Navbar End -->

    <!-- Hero Start -->
    <section class="home-slider position-relative" id="home">
        <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @forelse ($headerSection as $key => $item)
                    <button type="button" data-bs-target="#carouselExampleInterval"
                        data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"
                        aria-current="true" aria-label="Slide {{ $key }}"></button>
                @empty
                    <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                @endforelse
            </div>
            @forelse ($headerSection as $key => $item)
                <div class="carousel-inner">
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-bs-interval="6000">
                        <div class="bg-home zoom-image d-flex align-items-center">
                            <div class="bg-overlay image-wrap"
                                style="background-image:url({{ asset($item->image) }});background-size: cover;background-position: center;">
                            </div>
                            <div class="bg-overlay opacity-5"></div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="title-heading position-relative mt-5" style="z-index: 1;">
                                            <h1 class="fw-bold display-4 mb-3 text-white title-dark">
                                                {{ $item->title }}.
                                            </h1>
                                            <p class="para-desc text-white-50">{{ $item->caption }}.</p>
                                            <div class="mt-4 pt-2">
                                                <a href="{{ url('') . '/shops/' . $business->slug }}"
                                                    class="btn btn-primary"><i class="uil uil-shopping-cart-alt"></i>
                                                    Shop Now</a>
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div><!--end container-->
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="6000">
                        <div class="bg-home zoom-image d-flex align-items-center">
                            <div class="bg-overlay image-wrap"
                                style="background-image:url({{ asset('templates/landing-page/assets/images/furniture/bg1.jpg') }})">
                            </div>
                            <div class="bg-overlay opacity-5"></div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="title-heading position-relative mt-5" style="z-index: 1;">
                                            <h1 class="fw-bold display-4 mb-3 text-white title-dark">Ini Hanya
                                                <br>Demo
                                            </h1>
                                            <p class="para-desc text-white-50">Untuk mengganti konten ini,silahkan
                                                masuk ke dashboard setting landing page.</p>
                                            <div class="mt-4 pt-2">
                                                <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                    class="btn btn-primary"><i class="uil uil-shopping-cart-alt"></i>
                                                    Shop Now</a>
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div><!--end container-->
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Start -->
    <section class="section pb-0" id="categories">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Top Categories</h4>
                        <p class="text-muted para-desc mb-0 mx-auto">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to
                            generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row mt-4">
                @if (count($categoryProduct) > 0)
                    @foreach ($categoryProduct as $key => $item)
                        @if ($key == 0)
                            <div class="col-md-6 p-2">
                                <div style="height: 100%;"
                                    class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                    <img src="{{ asset($item->image) }}" class="img-fluid" alt="">
                                    <div class="bg-overlay bg-linear-gradient-2"></div>
                                    <div class="position-absolute bottom-0 end-0 start-0 m-4 mt-0">
                                        <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                            class="d-flex justify-content-between align-items-center">
                                            <span>
                                                <span
                                                    class="d-block title text-white title-dark fs-5 fw-semibold">{{ $item->name }}</span>
                                                <span class="fs-6 text-white-50 d-block">{{ count($item->products) }}
                                                    Items</span>
                                            </span>
                                            <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div><!--end col-->
                        @endif
                    @endforeach
                    <div class="col-md-6">
                        <div class="row" style="height: 100%;">
                            <div class="col-6">
                                <div class="row" style="height: 100%;">
                                    @foreach ($categoryProduct as $key => $val)
                                        @if ($key > 0 && $key <= 2)
                                            <div class="col-12 p-2">
                                                <div style="height: 100%;"
                                                    class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                                    <img src="{{ asset($val->image) }}" class="img-fluid"
                                                        alt="">
                                                    <div class="bg-overlay bg-linear-gradient-2"></div>
                                                    <div
                                                        class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                        <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                            class="d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <span
                                                                    class="d-block title text-white title-dark fs-5 fw-semibold">{{ ucfirst($val->name) }}</span>
                                                                <span
                                                                    class="fs-6 text-white-50 d-block">{{ count($val->products) }}
                                                                    Items</span>
                                                            </span>
                                                            <i
                                                                class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div><!--end col-->
                                        @endif
                                    @endforeach
                                </div><!--end row-->
                            </div><!--end col-->
                            <div class="col-6">
                                <div class="row" style="height: 100%;">
                                    @foreach ($categoryProduct as $key => $val)
                                        @if ($key > 2 && $key <= 4)
                                            <div class="col-12 p-2">
                                                <div style="height: 100%;"
                                                    class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                                    <img src="{{ asset($val->image) }}" class="img-fluid"
                                                        alt="">
                                                    <div class="bg-overlay bg-linear-gradient-2"></div>
                                                    <div
                                                        class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                        <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                            class="d-flex justify-content-between align-items-center">
                                                            <span>
                                                                <span
                                                                    class="d-block title text-white title-dark fs-5 fw-semibold">{{ ucfirst($val->name) }}</span>
                                                                <span
                                                                    class="fs-6 text-white-50 d-block">{{ count($val->products) }}
                                                                    Items</span>
                                                            </span>
                                                            <i
                                                                class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div><!--end col-->
                                        @endif
                                    @endforeach
                                </div><!--end row-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end col-->
                @else
                    <h1 class="text-center">Ini hanya demo, silahkan add minimal 3 Kategori di bisnis anda untuk
                        memunculkan
                        Kategori disini.</h1>
                    <div class="col-md-6 p-2">
                        <div
                            class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                            <img src="{{ asset('templates/landing-page/assets/images/furniture/fea1.jpg') }}"
                                class="img-fluid" alt="">
                            <div class="bg-overlay bg-linear-gradient-2"></div>
                            <div class="position-absolute bottom-0 end-0 start-0 m-4 mt-0">
                                <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                    class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <span
                                            class="d-block title text-white title-dark fs-5 fw-semibold">Bedroom</span>
                                        <span class="fs-6 text-white-50 d-block">110 Items</span>
                                    </span>

                                    <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div><!--end col-->

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12 p-2">
                                        <div
                                            class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                            <img src="{{ asset('templates/landing-page/assets/images/furniture/fea2.jpg') }}"
                                                class="img-fluid" alt="">
                                            <div class="bg-overlay bg-linear-gradient-2"></div>
                                            <div class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                    class="d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <span
                                                            class="d-block title text-white title-dark fs-5 fw-semibold">Kitchen</span>
                                                        <span class="fs-6 text-white-50 d-block">110 Items</span>
                                                    </span>

                                                    <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-12 p-2">
                                        <div
                                            class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                            <img src="{{ asset('templates/landing-page/assets/images/furniture/fea3.jpg') }}"
                                                class="img-fluid" alt="">
                                            <div class="bg-overlay bg-linear-gradient-2"></div>
                                            <div class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                    class="d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <span
                                                            class="d-block title text-white title-dark fs-5 fw-semibold">Office</span>
                                                        <span class="fs-6 text-white-50 d-block">110 Items</span>
                                                    </span>

                                                    <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div><!--end col-->

                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12 p-2">
                                        <div
                                            class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                            <img src="{{ asset('templates/landing-page/assets/images/furniture/fea4.jpg') }}"
                                                class="img-fluid" alt="">
                                            <div class="bg-overlay bg-linear-gradient-2"></div>
                                            <div class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                    class="d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <span
                                                            class="d-block title text-white title-dark fs-5 fw-semibold">Living
                                                            Room</span>
                                                        <span class="fs-6 text-white-50 d-block">110 Items</span>
                                                    </span>

                                                    <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-12 p-2">
                                        <div
                                            class="features feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                            <img src="{{ asset('templates/landing-page/assets/images/furniture/fea5.jpg') }}"
                                                class="img-fluid" alt="">
                                            <div class="bg-overlay bg-linear-gradient-2"></div>
                                            <div class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                    class="d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <span
                                                            class="d-block title text-white title-dark fs-5 fw-semibold">Dining
                                                            Hall</span>
                                                        <span class="fs-6 text-white-50 d-block">110 Items</span>
                                                    </span>

                                                    <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end col-->
                @endif
                <div class="col-12 mt-4">
                    <div class="text-center">
                        <a href="{{ url('/') . '/shops/' . $business->slug }}"
                            class="btn btn-link primary fw-semibold mb-0">See More Categories <span
                                class="h5 mb-0 ms-1"><i class="uil uil-arrow-right align-middle"></i></span></a>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h4 class="title mb-4">Best Solutions for Your Home</h4>
                        <p class="para-desc text-muted mx-auto mb-0">Obviously I'm a Web Designer. Experienced with all
                            stages of the development cycle for dynamic web projects.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card features feature-primary explore-feature border-0 rounded text-center">
                        <div class="card-body">
                            <div class="icons rounded-circle shadow-lg d-inline-block">
                                <i data-feather="shopping-cart" class="fea"></i>
                            </div>
                            <div class="content mt-3">
                                <a href="javascript:void(0)" class="title text-dark fw-semibold">Delivery</a>
                                <p class="text-muted mt-2">It is a long established fact that a reader will be of a
                                    page reader will be of a page when looking at its layout.</p>
                                <a href="javascript:void(0)" class="btn btn-link primary fw-semibold mb-0">Read More
                                    <i class="uil uil-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card features feature-primary explore-feature border-0 rounded text-center">
                        <div class="card-body">
                            <div class="icons rounded-circle shadow-lg d-inline-block">
                                <i data-feather="codesandbox" class="fea"></i>
                            </div>
                            <div class="content mt-3">
                                <a href="javascript:void(0)" class="title text-dark fw-semibold">Design Interior</a>
                                <p class="text-muted mt-2">It is a long established fact that a reader will be of a
                                    page reader will be of a page when looking at its layout.</p>
                                <a href="javascript:void(0)" class="btn btn-link primary fw-semibold mb-0">Read More
                                    <i class="uil uil-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card features feature-primary explore-feature border-0 rounded text-center">
                        <div class="card-body">
                            <div class="icons rounded-circle shadow-lg d-inline-block">
                                <i data-feather="phone" class="fea"></i>
                            </div>
                            <div class="content mt-3">
                                <a href="javascript:void(0)" class="title text-dark fw-semibold">24/7 Support</a>
                                <p class="text-muted mt-2">It is a long established fact that a reader will be of a
                                    page reader will be of a page when looking at its layout.</p>
                                <a href="javascript:void(0)" class="btn btn-link primary fw-semibold mb-0">Read More
                                    <i class="uil uil-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <!-- Cta start -->
        <div class="container-fluid mt-100 mt-60 px-md-3 px-0">
            <div class="bg-half-170 rounded-md rounded-md-0 shadow-md position-relative overflow-hidden jarallax"
                data-jarallax data-speed="0.5"
                style="background: url({{ asset('templates/landing-page/assets/images/furniture/bg4.jpg') }}) center center;">
            </div><!--end slide-->
        </div><!--end container-->
        <!-- Cta End -->
    </section><!--end section-->
    <!-- End -->

    <!-- FEATURES START -->
    <section class="section" id="products">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="features-absolute">
                        <div class="row align-items-end">
                            <div class="col-md-3 col-6">
                                <img src="{{ asset('templates/landing-page/assets/images/furniture/cta1.jpg') }}"
                                    class="img-fluid rounded-md shadow" alt="">
                            </div><!--end col-->

                            <div class="col-md-3 col-6">
                                <img src="{{ asset('templates/landing-page/assets/images/furniture/cta2.jpg') }}"
                                    class="img-fluid rounded-md shadow" alt="">
                            </div><!--end col-->

                            <div class="col-md-6 col-12">
                                <div class="section-title bg-white-color p-4 rounded-md text-md-start text-center">
                                    <h2 class="fw-bold mb-3">We Provide You The <br> Best Experience</h2>
                                    <a href="{{ url('/') . "/shops/" . $business->name }}"
                                        class="btn btn-link primary text-muted title-dark fw-semibold"><i
                                            class="uil uil-shopping-cart-alt"></i> Shop Now</a>
                                </div>
                            </div><!--end col-->
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div id="grid" class="row mt-5 pt-3">
                @forelse ($product as $item)
                    <div class="col-lg-4 col-md-6 col-12 spacing picture-item" data-groups='["{{ $item->category->name }}"]'>
                        <div
                            class="card border-0 work-container work-primary work-modern position-relative d-block overflow-hidden rounded">
                            <div class="card-body p-0">
                                <img src="{{ asset($item->image) }}"
                                    class="img-fluid" alt="work-image">
                                <div class="overlay-work"></div>
                                <div class="content">
                                    <h5 class="mb-1"><a href="portfolio-detail-one.html"
                                            class="text-white title">{{ $item->name }}</a></h5>
                                    <h6 class="text-white-50 tag mt-1 mb-0">{{ $item->category->name }}</h6>
                                </div>
                                <div class="icons text-center">
                                    <a href="{{ asset($item->image) }}"
                                        class="work-icon bg-white d-inline-flex rounded-pill lightbox"><i
                                            data-feather="camera" class="fea icon-sm image-icon"></i></a>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                @empty
                    <div class="col-lg-4 col-md-6 col-12 spacing picture-item" data-groups='["branding"]'>
                        <div
                            class="card border-0 work-container work-primary work-modern position-relative d-block overflow-hidden rounded">
                            <div class="card-body p-0">
                                <img src="{{ asset('templates/landing-page/assets/images/furniture/i1.jpg') }}"
                                    class="img-fluid" alt="work-image">
                                <div class="overlay-work"></div>
                                <div class="content">
                                    <h5 class="mb-1"><a href="portfolio-detail-one.html"
                                            class="text-white title">Ini Contoh</a></h5>
                                    <h6 class="text-white-50 tag mt-1 mb-0">Tambahkan Produk anda untuk ditampilkan disini</h6>
                                </div>
                                <div class="icons text-center">
                                    <a href="{{ asset('templates/landing-page/assets/images/furniture/i1.jpg') }}"
                                        class="work-icon bg-white d-inline-flex rounded-pill lightbox"><i
                                            data-feather="camera" class="fea icon-sm image-icon"></i></a>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                @endforelse
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title mb-4 pb-2 text-center">
                        <span class="badge rounded-pill bg-soft-primary">Blogs & News</span>
                        <h4 class="title mt-3 mb-4">Latest News & Articals</h4>
                        <p class="text-muted mx-auto para-desc mb-0">Start working with <span
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
                            <img src="{{ asset('templates/landing-page/assets/images/furniture/i6.jpg') }}"
                                class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="javascript:void(0)" class="card-title title text-dark">High quality work for
                                    demand our customer.</a></h5>
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
                            <img src="{{ asset('templates/landing-page/assets/images/furniture/i4.jpg') }}"
                                class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="javascript:void(0)" class="card-title title text-dark">Building public
                                    support for a severige work bond</a></h5>
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
                            <img src="{{ asset('templates/landing-page/assets/images/furniture/i5.jpg') }}"
                                class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="javascript:void(0)" class="card-title title text-dark">Satisfection for the
                                    customer our first parity.</a></h5>
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
    <!-- End -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-py-60">
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="#" class="logo-footer">
                                    <img src="{{ asset('templates/landing-page/assets/images/logo-light.png') }}"
                                        height="24" alt="">
                                </a>
                                <p class="mt-4">Start working with {{ ucfirst($business->name) }} that can provide
                                    everything you need to
                                    generate awareness, drive traffic, connect.</p>
                                @include('templates.list-icon')
                            </div><!--end col-->

                            {{-- <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                <h5 class="footer-head">Company</h5>
                                <ul class="list-unstyled footer-list mt-4">
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> About us</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Services</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Team</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Pricing</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Project</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Careers</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Blog</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Login</a></li>
                                </ul>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                <h5 class="footer-head">Usefull Links</h5>
                                <ul class="list-unstyled footer-list mt-4">
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Terms of Services</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Privacy Policy</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Documentation</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Changelog</a></li>
                                    <li><a href="javascript:void(0)" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Components</a></li>
                                </ul>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                <h5 class="footer-head">Newsletter</h5>
                                <p class="mt-4">Sign up and receive the latest tips via email.</p>
                                <form>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="foot-subscribe mb-3">
                                                <label class="form-label">Write your email <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" name="email" id="emailsubscribe"
                                                        class="form-control ps-5 rounded"
                                                        placeholder="Your email : " required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-grid">
                                                <input type="submit" id="submitsubscribe" name="send"
                                                    class="btn btn-soft-primary" value="Subscribe">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><!--end col--> --}}
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
    <!-- Lightbox -->
    <script src="{{ asset('templates/landing-page/assets/libs/shufflejs/shuffle.min.js') }}"></script>
    <script src="{{ asset('templates/landing-page/assets/libs/tobii/js/tobii.min.js') }}"></script>
    <!-- Parallax -->
    <script src="{{ asset('templates/landing-page/assets/libs/jarallax/jarallax.min.js') }} "></script>
    <!-- Main Js -->
    <script src="{{ asset('templates/landing-page/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('templates/landing-page/assets/js/plugins.init.js') }}"></script>
    <!--Note: All init (custom) js like tiny slider, counter, countdown, lightbox, gallery, swiper slider etc.-->
    <script src="{{ asset('templates/landing-page/assets/js/app.js') }}"></script>
    <!--Note: All important javascript like page loader, menu, sticky menu, menu-toggler, one page menu etc. -->
    @include('templates.custom-script')
</body>

</html>
