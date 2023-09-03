<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    @include('templates.header')
    <!-- Css -->
    <link href="{{ asset('templates/landing-page/assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('templates/landing-page/assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('templates/landing-page/assets/css/bootstrap.min.css') }}" id="bootstrap-style" class="theme-opt"
        rel="stylesheet" type="text/css">
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
                <ul class="navigation-menu nav-light">
                    <li onclick="setActiveNav('menu-home')" id="menu-home"><a href="#home"
                            class="sub-menu-item">Home</a></li>
                    @if ($aboutSection != null)
                        <li onclick="setActiveNav('menu-about')" id="menu-about"><a href="#about"
                                class="sub-menu-item">About</a></li>
                    @endif
                    <li onclick="setActiveNav('menu-contact')" id="menu-contact"><a href="#contact"
                            class="sub-menu-item">Contact</a></li>
                </ul><!--end navigation menu-->
            </div><!--end navigation-->
        </div><!--end container-->
    </header><!--end header-->
    <!-- Navbar End -->

    <!-- Hero Start -->
    <section class="bg-home d-flex align-items-center" id="home">
        <div class="bg-overlay bg-linear-gradient-4"></div>
        <div class="container">
            <div class="row align-items-center mt-5">
                <div class="col-lg-7 col-md-7">
                    <div class="title-heading mt-4">
                        <h1 class="heading mb-3 text-white title-dark" id="titleHeader">Build Anything <br>For Your
                            Project</h1>
                        <p class="para-desc text-white-50" id="captionHeader">Launch your campaign and benefit from our
                            expertise on
                            designing and managing conversion centered bootstrap v5 html page.</p>
                        <div class="mt-4 pt-2">
                            <a href="page-services.html" class="btn btn-primary m-1">Our Services</a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0">
                    <img src="{{ asset('templates/landing-page/assets/images/illustrator/services.svg') }}"
                        alt="">
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <!-- Feature Start -->
    <section class="section pt-5 mt-5" id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-flip-h h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Built for Everyone</h5>
                            <p class="text-muted mb-0">Nisi aenean vulputate eleifend tellus vitae eleifend enim a
                                Aliquam eleifend aenean elementum semper.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 mt-sm-0">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-minus-path h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Responsive Design</h5>
                            <p class="text-muted mb-0">Allegedly, a Latin scholar established the origin of the
                                established text by compiling unusual word.</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 col-12 mt-5 mt-sm-0">
                    <div class="features feature-primary text-center">
                        <div class="image position-relative d-inline-block">
                            <i class="uil uil-layers-alt h2 text-primary"></i>
                        </div>

                        <div class="content mt-4">
                            <h5>Build Everything</h5>
                            <p class="text-muted mb-0">It seems that only fragments of the original text remain in only
                                fragments the Lorem Ipsum texts used today.</p>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Feature End -->

    @if ($aboutSection != null)
        <!-- counter Start -->
        <section class="section bg-light" id="about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 order-2 order-md-1 mt-4 mt-sm-0 pt-2 pt-sm-0">
                        <div class="section-title me-lg-5">
                            <h4 class="title mb-4">{{ $aboutSection?->title }}</h4>
                            <p class="text-muted">{{ $aboutSection?->description }}.</p>
                            <a href="{{ url('/') . '/shops/' . $business->slug }}"
                                class="btn btn-outline-primary">Start Now <i class="uil uil-angle-right-b"></i></a>
                        </div>
                    </div><!--end col-->

                    <div class="col-md-6 order-1 order-md-2">
                        <img src="{{ asset($aboutSection?->image) }}" class="img-fluid" alt="">
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->

            <div class="container mt-100 mt-60">
                <div class="row justify-content-center" id="counter">
                    <div class="col-md-4 mt-4 pt-2">
                        <div class="counter-box text-center px-lg-4">
                            <h2 class="mb-0 text-primary display-4"><span class="counter-value"
                                    data-target="97">3</span>%</h2>
                            <h5 class="counter-head">Happy Client</h5>
                            <p class="text-muted mb-0">The most well-known dummy text is the 'Lorem Ipsum', which is
                                said
                                to have originated in the 16th century.</p>
                        </div><!--end counter box-->
                    </div><!--end col-->

                    <div class="col-md-4 mt-4 pt-2">
                        <div class="counter-box text-center px-lg-4">
                            <h2 class="mb-0 text-primary display-4"><span class="counter-value"
                                    data-target="15">1</span>+</h2>
                            <h5 class="counter-head">Awards</h5>
                            <p class="text-muted mb-0">The most well-known dummy text is the 'Lorem Ipsum', which is
                                said
                                to have originated in the 16th century.</p>
                        </div><!--end counter box-->
                    </div><!--end col-->

                    <div class="col-md-4 mt-4 pt-2">
                        <div class="counter-box text-center px-lg-4">
                            <h2 class="mb-0 text-primary display-4"><span class="counter-value"
                                    data-target="98">3</span>%</h2>
                            <h5 class="counter-head">Project Complete</h5>
                            <p class="text-muted mb-0">The most well-known dummy text is the 'Lorem Ipsum', which is
                                said
                                to have originated in the 16th century.</p>
                        </div><!--end counter box-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- counter End -->
    @endif

    <!-- Testimonial Start -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('templates/landing-page/assets/images/illustrator/analyze_report_4.svg') }}"
                        class="me-md-4" alt="">
                </div><!--end col-->

                <div class="col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="section-title ms-lg-5">
                        <h4 class="title mb-4">Clean And Modern Code</h4>
                        <p class="text-muted">This prevents repetitive patterns from impairing the overall visual
                            impression and facilitates the comparison of different typefaces. Furthermore, it is
                            advantageous when the dummy text is relatively realistic.</p>
                        <a href="javascript:void(0)" class="btn btn-outline-primary">Start Now <i
                                class="uil uil-angle-right-b"></i></a>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Our Happy Customers</h4>
                        <p class="text-muted para-desc mx-auto mb-0">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to
                            generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-12 mt-4">
                    <div class="tiny-three-item">
                        @forelse ($testimoniSection as $item)
                            <div class="tiny-slide text-center">
                                <div class="client-testi rounded shadow m-2 p-4">
                                    <img src="{{ asset($item->image) }}"
                                        class="img-fluid avatar avatar-ex-sm mx-auto" alt="">
                                    <p class="text-muted mt-4">" {{ $item->content }}. "</p>
                                    <h6 class="text-primary">- {{ $item->name }}</h6>
                                </div>
                            </div>
                        @empty
                            <div class="tiny-slide text-center">
                                <div class="client-testi rounded shadow m-2 p-4">
                                    <img src="{{ asset('templates/landing-page/assets/images/client/spotify.svg') }}"
                                        class="img-fluid avatar avatar-ex-sm mx-auto" alt="">
                                    <p class="text-muted mt-4">" Ini adalah contoh Testimoni yang bisa di
                                        Setting/tambahkan lewat Dashboard. "</p>
                                    <h6 class="text-primary">- Christa Smith</h6>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">Subscribe for our Latest Newsletter</h4>
                        <p class="text-muted para-desc mx-auto mb-0">Start working with <span
                                class="text-primary fw-bold">{{ ucfirst($business->name) }}</span> that can provide
                            everything you need to
                            generate awareness, drive traffic, connect.</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row justify-content-center mt-4 pt-2">
                <div class="col-lg-7 col-md-10">
                    <div class="subcribe-form">
                        <form class="ms-0">
                            <input type="email" id="email" name="email" class="rounded-pill border"
                                placeholder="E-mail :">
                            <button type="submit" class="btn btn-pills btn-primary">Submit <i
                                    class="uil uil-arrow-right"></i></button>
                        </form><!--end form-->
                    </div><!--end subscribe form-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Testimonial End -->

    <!-- Footer Start -->
    <!-- Footer Start -->
    <footer class="footer footer-light" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-py-60">
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="#" class="logo-footer">
                                    <img src="{{ asset('templates/landing-page/assets/images/logo-dark.png') }}"
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
                                            <div class="foot-subscribe foot-white mb-3">
                                                <label class="form-label">Write your email <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" name="email" id="emailsubscribe"
                                                        class="form-control border ps-5 rounded"
                                                        placeholder="Your email : " required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-grid">
                                                <input type="submit" id="submitsubscribe" name="send"
                                                    class="btn btn-primary" value="Subscribe">
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

        <div class="footer-py-30 bg-footer text-white-50 border-top">
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
                                        class="avatar avatar-ex-sm" title="American Express" alt=""></a></li>
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

    <!-- Javascript -->
    <!-- JAVASCRIPT -->
    <script src="{{ asset('templates/landing-page/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SLIDER -->
    <script src="{{ asset('templates/landing-page/assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
    <script src="{{ asset('templates/landing-page/assets/js/easy_background.js') }}"></script>
    <!-- Lightbox -->
    <script src="{{ asset('templates/landing-page/assets/libs/tobii/js/tobii.min.js') }}"></script>
    <!-- Main Js -->
    <script src="{{ asset('templates/landing-page/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('templates/landing-page/assets/js/plugins.init.js') }}"></script>
    <!--Note: All init js like tiny slider, counter, countdown, maintenance, lightbox, gallery, swiper slider, aos animation etc.-->
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
