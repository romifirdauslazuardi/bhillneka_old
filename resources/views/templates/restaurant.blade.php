<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    @include('templates.header')
    <!-- Style Css-->
    <link href="{{ asset('templates/landing-page/assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('templates/landing-page/assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
    <link href="{{ asset('templates/landing-page/assets/libs/js-datepicker/datepicker.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('templates/landing-page/assets/css/bootstrap-dark-yellow.min.css') }}" id="bootstrap-style"
        class="theme-opt" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('templates/landing-page/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('templates/landing-page/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css"
        rel="stylesheet">
    <!-- Style Css-->
    <link href="{{ asset('templates/landing-page/assets/css/style-dark-yellow.min.css') }}" id="color-opt"
        class="theme-opt" rel="stylesheet" type="text/css">

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
                    <li onclick="setActiveNav('menu-about')" id="menu-about"><a href="#about"
                            class="sub-menu-item">About</a></li>
                    <li onclick="setActiveNav('menu-menu')" id="menu-menu"><a href="#menu"
                            class="sub-menu-item">Menu</a></li>
                    <li onclick="setActiveNav('menu-book')" id="menu-book"><a href="#table" class="sub-menu-item">Book
                            a Table</a></li>
                </ul><!--end navigation menu-->
            </div><!--end navigation-->
        </div><!--end container-->
    </header><!--end header-->
    <!-- Navbar End -->

    <!-- Hero Start -->
    <section class="bg-home d-flex align-items-center" id="home">
        <div class="bg-overlay bg-linear-gradient-4"></div>
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <h5 class="text-primary fw-semibold mb-3">Make A Order</h5>
                        <h4 class="display-4 mb-4 fw-bold text-white title-dark" id="titleHeader">The Best Food For The
                            <br> Best
                            Moments</h4>
                        <p class="para-desc text-white-50 mx-auto" id="captionHeader">Launch your campaign and benefit
                            from our expertise
                            on designing and managing conversion centered bootstrap v5 html page.</p>
                        <div class="mt-4 pt-2">
                            <a href="#table" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Start -->
    <section class="section" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-6">
                    <img src="{{ asset($aboutSection?->image) }}" class="img-fluid shadow rounded" alt="">
                </div><!--end col-->

                <div class="col-lg-7 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="section-title ms-lg-5">
                        <p class="text-muted fs-5 mb-0">{{ $aboutSection?->caption }}.</p>

                        <h4 class="title fw-bold my-3">{{ $aboutSection?->title }}.</h4>

                        <p class="text-muted">{{ $aboutSection?->description }}.</p>
                    </div>
                </div>
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->

    <!-- Start Happy Hour -->
    <section class="jarallax" data-jarallax data-speed="0.5"
        style="background: url({{ asset('templates/landing-page/assets/images/restaurant/bg4.jpg') }}) top; background-size: cover;">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-7 col-6 px-0">
                    <div class="card rounded-0">
                        <div class="row align-items-center g-0">
                            <div class="col-md-6">
                                <img src="{{ asset('templates/landing-page/assets/images/restaurant/f17.jpg') }}"
                                    class="img-fluid" alt="">
                            </div>

                            <div class="col-md-6">
                                <div class="section-title text-center px-3 py-4 p-md-4">
                                    <h4 class="title">Our Chef's <br> Secrets</h4>
                                    <a href="" class="btn btn-link primary fw-semibold text-muted mb-0">Learn
                                        More <span class="h5 mb-0 ms-1"><i
                                                class="uil uil-arrow-right align-middle"></i></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center g-0">
                            <div class="col-md-6 order-md-1 order-2">
                                <div class="section-title text-center px-3 py-4 p-md-4">
                                    <h4 class="title">View Full <br> Menus</h4>
                                    <a href="" class="btn btn-link primary fw-semibold text-muted mb-0">Menu
                                        <span class="h5 mb-0 ms-1"><i
                                                class="uil uil-arrow-right align-middle"></i></span></a>
                                </div>
                            </div>

                            <div class="col-md-6 order-md-2 order-1">
                                <img src="{{ asset('templates/landing-page/assets/images/restaurant/f18.jpg') }}"
                                    class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End Happy Hour -->

    <!-- Start -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Best Solutions</h4>
                        <p class="text-muted para-desc mb-0 mx-auto">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card border-0 text-center features feature-primary feature-clean">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-pizza-slice rounded h3 mb-0"></i>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-3">Food Meets Style</h5>
                            <p class="text-muted">Composed in a pseudo-Latin language which more or less pseudo-Latin
                                language corresponds.</p>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-link primary fw-semibold">Read More <i
                                        class="uil uil-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card border-0 text-center features feature-primary feature-clean">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-restaurant rounded h3 mb-0"></i>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-3">Quality Check</h5>
                            <p class="text-muted">Composed in a pseudo-Latin language which more or less pseudo-Latin
                                language corresponds.</p>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-link primary fw-semibold">Read More <i
                                        class="uil uil-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card border-0 text-center features feature-primary feature-clean">
                        <div class="icons text-center mx-auto">
                            <i class="uil uil-swiggy rounded h3 mb-0"></i>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-3">Home Delivery</h5>
                            <p class="text-muted">Composed in a pseudo-Latin language which more or less pseudo-Latin
                                language corresponds.</p>
                            <div>
                                <a href="javascript:void(0)" class="btn btn-link primary fw-semibold">Read More <i
                                        class="uil uil-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->

    <!-- Start -->
    <section class="bg-half-170 jarallax" data-jarallax data-speed="0.5"
        style="background: url({{ asset('templates/landing-page/assets/images/restaurant/bg5.jpg') }}) center center;"
        id="menu">
        <div class="bg-overlay bg-linear-gradient-2"></div>
        <div class="section-title position-absolute bottom-0 text-center end-0 start-0">
            <h5 class="text-muted title-dark-50">Our Menu</h5>
            <h4 class="title text-white title-dark mb-4">Choose your mixture & order now!</h4>
        </div>
    </section><!--end section-->

    <section class="section bg-light pt-0"
        style="background: url({{ asset('templates/landing-page/assets/images/restaurant/food-menu.png') }}) bottom no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 filters-group-wrap text-center mt-4 pt-1">
                    <div class="card filters-group p-4 shadow sticky-bar">
                        <ul class="container-filter filter-border mb-0 list-unstyled filter-options">
                            <li class="text-uppercase position-relative border-0 active" data-group="all">All</li>
                            @foreach ($categoryProduct as $item)
                                <li class="text-uppercase position-relative border-0"
                                    data-group="{{ Str::slug($item->name) }}">{{ ucfirst($item->name) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div><!--end col-->

                <div class="col-lg-9 col-md-8 col-12">
                    <div class="row justify-content-center row-cols-xl-4 row-cols-lg-3 row-cols-2" id="grid">
                        @foreach ($product as $item)
                            <div class="col picture-item mt-4 pt-1"
                                data-groups='["{{ Str::slug($item->category->name) }}"]'>
                                <div
                                    class="card border-0 work-container work-primary work-creative position-relative d-block overflow-hidden rounded">
                                    <div class="card-body p-0">
                                        <div class="position-relative overflow-hidden">
                                            <img src="{{ asset($item->image) }}" class="img-fluid" alt="work-image">
                                            <div class="overlay-grid"></div>
                                        </div>
                                        <div class="content p-3 text-center">
                                            <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                                class="text-white title h5 d-block mb-2">{{ ucfirst($item->name) }}</a>
                                            <p class="mb-0 text-primary h5 fw-semibold">
                                                {{ moneyFormat($item->price) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end col-->
                        @endforeach
                    </div><!--end row-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->

    <!-- Start Booking Table -->
    <section class="section jarallax" data-jarallax data-speed="0.5" id="table"
        style="background: url({{ asset('templates/landing-page/assets/images/restaurant/bg6.jpg') }}) center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow rounded p-lg-5 p-4 me-lg-5">
                        <div class="section-title mb-4">
                            <span class="badge rounded-pill bg-soft-primary py-2 px-3 fw-semibold">Reservation</span>
                            <h4 class="title fw-bold text-uppercase my-3">Book A Table</h4>
                            <p class="text-muted mx-auto para-desc mb-0">We make it a priority to offer flexible
                                services to accomodate your needs</p>
                        </div>

                        <form>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-uppercase">Your Name</label>
                                        <input name="name" id="name" type="text" class="form-control"
                                            placeholder="First Name :">
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-uppercase">Your Email</label>
                                        <input name="email" id="email" type="email" class="form-control"
                                            placeholder="Your email :">
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-uppercase">Phone no.</label>
                                        <input name="number" type="number" id="phone-number" class="form-control"
                                            placeholder="Phone no. :">
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-uppercase">Person</label>
                                        <input type="number" min="0" autocomplete="off" id="adult"
                                            class="form-control" required="" placeholder="Person :">
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-uppercase">Date</label>
                                        <input name="date" type="text" class="form-control start"
                                            placeholder="(ex: mm/ dd/ yy)">
                                    </div>
                                </div><!--end col-->

                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-uppercase">Time</label>
                                        <input name="time" type="number" id="input-time"
                                            class="form-control timepicker" placeholder="(ex: 8:00 p.m)">
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->

                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <input type="submit" id="submit" name="send"
                                        class="btn btn-primary text-uppercase w-100" value="Book a table">
                                </div><!--end col-->
                            </div><!--end row-->
                        </form><!--end form-->
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End Booking Table -->

    <!-- Insta Post Start -->
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-12 px-0">
                <div class="tiny-twelve-item">
                    @forelse ($product as $item)
                        <div class="tiny-slide">
                            <div
                                class="work-container work-primary work-modern position-relative d-block client-testi rounded-0 overflow-hidden">
                                <div class="card-img position-relative">
                                    <img src="{{ asset($item->image) }}" class="img-fluid"
                                        alt="{{ $item->name }}">
                                    <div class="card-overlay"></div>

                                    <div class="icons text-center">
                                        <a href="{{ asset($item->image) }}"
                                            class="work-icon bg-white d-inline-flex rounded-pill lightbox"><i
                                                class="uil uil-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="tiny-slide">
                            <div
                                class="work-container work-primary work-modern position-relative d-block client-testi rounded-0 overflow-hidden">
                                <div class="card-img position-relative">
                                    <img src="{{ asset('templates/landing-page/assets/images/restaurant/f1.jpg') }}"
                                        class="img-fluid" alt="">
                                    <div class="card-overlay"></div>

                                    <div class="icons text-center">
                                        <a href="{{ asset('templates/landing-page/assets/images/restaurant/f1.jpg') }}"
                                            class="work-icon bg-white d-inline-flex rounded-pill lightbox"><i
                                                class="uil uil-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- Insta Post End -->

        @if (count($testimoniSection) > 0)
            <!-- Start -->
            <section class="section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            <div class="section-title mb-4 pb-2">
                                <h4 class="title mb-4">Client's Review</h4>
                                <p class="text-muted para-desc mx-auto mb-0">Start working with <span
                                        class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can
                                    provide everything you need
                                    to generate awareness, drive traffic, connect.</p>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->

                    <div class="row justify-content-center">
                        <div class="col-lg-12 mt-4">
                            <div class="tiny-three-item">
                                @foreach ($testimoniSection as $item)
                                    <div class="tiny-slide">
                                        <div class="d-flex client-testi m-2">
                                            <img src="{{ asset($item->image) }}"
                                                class="avatar avatar-small client-image rounded shadow"
                                                alt="">
                                            <div class="card flex-1 content p-3 shadow rounded position-relative">
                                                <ul class="list-unstyled mb-0">
                                                    @for ($i = 0; $i < $item->stars; $i++)
                                                        <li class="list-inline-item"><i
                                                                class="mdi mdi-star text-warning"></i>
                                                        </li>
                                                    @endfor
                                                </ul>
                                                <p class="text-muted mt-2">" {{ $item->content }}"</p>
                                                <h6 class="text-primary">- {{ $item->name }} <small
                                                        class="text-muted">{{ $item->jabatan }}</small></h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end container-->
            </section><!--end section-->
            <!-- End -->
        @endif

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="footer-py-60">
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-md-6">
                                    <div class="text-center">
                                        <h5 class="footer-head fw-semibold mb-4">Open Hours</h5>
                                        <p class="mb-2">Monday - Friday: 10 AM - 11 PM</p>
                                        <p class="mb-0">Saturday - Sunday: 9 AM - 1 PM</p>
                                    </div>
                                </div><!--end col-->

                                <div class="col-lg-4 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                    <div class="text-center">
                                        <h5 class="footer-head fw-semibold mb-4">Reservation</h5>
                                        <p class="mb-2"><a href="tel:{{ $data?->phone }}"
                                                class="text-foot">{{ $data?->phone }}</a></p>
                                        <p class="mb-0"><a href="mailto:{{ $data?->email }}"
                                                class="text-foot">{{ $data?->email }}</a></p>
                                    </div>
                                </div><!--end col-->

                                <div class="col-lg-4 col-md-6 mt-4 mt-lg-0 pt-2 pt-lg-0">
                                    <div class="text-center">
                                        <h5 class="footer-head fw-semibold mb-4">Address</h5>
                                        <p class="mb-2">{{ ucfirst($business->name) }}</p>
                                        <p class="mb-0">{{ $data?->location }}</p>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->

                            <div class="row justify-content-center mt-5">
                                <div class="col-12">
                                    <div class="text-center">
                                        <a href="#" class="logo-footer">
                                            <img src="{{ asset('templates/landing-page/assets/images/logo-icon.png') }}"
                                                height="110" alt="">
                                        </a>
                                        <p class="mt-4 para-desc mx-auto">Start working with
                                            {{ ucfirst($business->name) }} that can provide everything you need to
                                            generate awareness, drive traffic, connect.</p>
                                        <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">
                                            <li class="list-inline-item mb-0"><a
                                                    href="{{ url('/' . 'shops/' . $business->slug) }}"
                                                    target="_blank" class="rounded"><i
                                                        class="uil uil-shopping-cart align-middle"
                                                        title="Buy Now"></i></a></li>
                                            <li class="list-inline-item mb-0"><a href="{{ $data?->facebook }}"
                                                    target="_blank" class="rounded"><i
                                                        class="uil uil-facebook-f align-middle"
                                                        title="facebook"></i></a>
                                            </li>
                                            <li class="list-inline-item mb-0"><a href="{{ $data?->instagram }}"
                                                    target="_blank" class="rounded"><i
                                                        class="uil uil-instagram align-middle"
                                                        title="instagram"></i></a>
                                            </li>
                                            <li class="list-inline-item mb-0"><a href="{{ $data?->twitter }}"
                                                    target="_blank" class="rounded"><i
                                                        class="uil uil-twitter align-middle" title="twitter"></i></a>
                                            </li>
                                            <li class="list-inline-item mb-0"><a href="mailto:{{ $data?->email }}"
                                                    class="rounded"><i class="uil uil-envelope align-middle"
                                                        title="email"></i></a></li>
                                        </ul><!--end icon-->
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->

            <div class="footer-py-30 footer-bar bg-footer">
                <div class="container text-center">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="text-center">
                                <p class="mb-0">©
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script>
                                    {{ ucfirst($business->name) }}. Design with <i
                                        class="mdi mdi-heart text-danger"></i>
                                    by <a href="{{ url('/') }}" target="_blank"
                                        class="text-reset">{{ env('APP_NAME', 'Laravel') }}</a>.
                                </p>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end container-->
            </div>
        </footer><!--end footer-->
        <!-- Footer End -->

        <!-- Cookies Start -->
        <div class="card cookie-popup shadow rounded py-3 px-4">
            <p class="text-muted mb-0">This website uses cookies to provide you with a great user experience. By using
                it,
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
        <a href="javascript:void(0)"
            class="card switcher-btn shadow-md text-primary z-index-1 d-md-inline-flex d-none"
            data-bs-toggle="offcanvas" data-bs-target="#switcher-sidebar">
            <i class="mdi mdi-cog mdi-24px mdi-spin align-middle"></i>
        </a>

        <div class="offcanvas offcanvas-start shadow border-0" tabindex="-1" id="switcher-sidebar"
            aria-labelledby="offcanvasLeftLabel">
            <div class="offcanvas-header p-4 border-bottom">
                <h5 id="offcanvasLeftLabel" class="mb-0">
                    <img src="{{ $data?->logo_dark != null || $data?->logo_dark != '' ? asset($data?->logo_dark) : asset('templates/landing-page/assets/images/logo-dark.png') }}"
                        class="l-dark" height="24" alt="{{ $data?->title }}">
                    <img src="{{ $data?->logo != null || $data?->logo != '' ? asset($data?->logo) : asset('templates/landing-page/assets/images/logo-light.png') }}"
                        class="l-light" height="24" alt="{{ $data?->title }}">
                </h5>
                <button type="button" class="btn-close d-flex align-items-center text-dark"
                    data-bs-dismiss="offcanvas" aria-label="Close"><i class="uil uil-times fs-4"></i></button>
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
                                <li class="d-grid"><a href="javascript:void(0)" class="dark-version t-dark mt-4"
                                        onclick="setTheme('style-dark')"><img
                                            src="{{ asset('templates/landing-page/assets/images/demos/dark.png') }}"
                                            class="img-fluid rounded-md shadow-md d-block mx-auto"
                                            style="width: 240px;" alt=""><span
                                            class="text-dark fw-medium mt-3 d-block">Dark
                                            Version</span></a></li>
                                <li class="d-grid"><a href="javascript:void(0)" class="light-version t-light mt-4"
                                        onclick="setTheme('style')"><img
                                            src="{{ asset('templates/landing-page/assets/images/demos/ltr.png') }}"
                                            class="img-fluid rounded-md shadow-md d-block mx-auto"
                                            style="width: 240px;" alt=""><span
                                            class="text-dark fw-medium mt-3 d-block">Light
                                            Version</span></a></li>
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
        <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i
                data-feather="arrow-up" class="fea icon-sm icons align-middle"></i></a>
        <!-- Back to top -->

        <!-- javascript -->
        <!-- JAVASCRIPT -->
        <script src="{{ asset('templates/landing-page/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- SLIDER -->
        <script src="{{ asset('templates/landing-page/assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
        <script src="{{ asset('templates/landing-page/assets/js/easy_background.js') }}"></script>
        <!-- Parallax -->
        <script src="{{ asset('templates/landing-page/assets/libs/jarallax/jarallax.min.js') }} "></script>
        <!-- Datepicker -->
        <script src="{{ asset('templates/landing-page/assets/libs/js-datepicker/datepicker.min.js') }}"></script>
        <!-- Lightbox -->
        <script src="{{ asset('templates/landing-page/assets/libs/shufflejs/shuffle.min.js') }}"></script>
        <script src="{{ asset('templates/landing-page/assets/libs/tobii/js/tobii.min.js') }}"></script>
        <!-- Main Js -->
        <script src="{{ asset('templates/landing-page/assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('templates/landing-page/assets/js/plugins.init.js') }}"></script>
        <!--Note: All init (custom) js like tiny slider, counter, countdown, lightbox, gallery, swiper slider etc.-->
        <script src="{{ asset('templates/landing-page/assets/js/app.js') }}"></script>
        <!--Note: All important javascript like page loader, menu, sticky menu, menu-toggler, one page menu etc. -->

        <script>
            @php
                $backgroundArray = [];
                $titleArray = [];
                $captionArray = [];
                foreach ($headerSection as $item) {
                    $backgroundArray[] = $item->image;
                    $titleArray[] = $item->title;
                    $captionArray[] = $item->caption;
                }
                $background = "'" . implode("', '", $backgroundArray) . "'";
                $title = "'" . implode("', '", $titleArray) . "'";
                $caption = "'" . implode("', '", $captionArray) . "'";
            @endphp
            let background = [{!! $background !!}]
            let title = [{!! $title !!}]
            let caption = [{!! $caption !!}]
            if (background[0] == '') {
                background = ["{{ asset('templates/landing-page/assets/images/restaurant/bg1.jpg') }}",
                    "{{ asset('templates/landing-page/assets/images/restaurant/bg2.jpg') }}",
                    "{{ asset('templates/landing-page/assets/images/restaurant/bg3.jpg') }}"
                ]
            }
            if (caption[0] == '') {
                caption = ["Ini Adalah Demo untuk Caption, Setting di Dashboard untuk merubah",
                    "Ini Adalah Demo untuk Caption, Setting di Dashboard untuk merubah",
                    "Ini Adalah Demo untuk Caption, Setting di Dashboard untuk merubah"
                ]
            }
            if (title[0] == '') {
                title = ["Ini Adalah tempat Title", "Ini Adalah tempat Title", "Ini Adalah tempat Title"]
            }
            easy_background("#home", {
                slide: background,
                delay: [4000, 4000, 4000],
                caption: caption,
                title: title
            });
        </script>
        @include('templates.custom-script')
</body>

</html>
