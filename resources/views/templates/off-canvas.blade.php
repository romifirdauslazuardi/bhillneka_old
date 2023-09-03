<!-- Offcanvas Start -->
<div class="offcanvas offcanvas-end shadow border-0" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header p-4 border-bottom">
        <h5 id="offcanvasRightLabel" class="mb-0">
            <img src="{{ $data?->logo_dark != null || $data?->logo_dark != '' ? asset($data?->logo_dark) : asset('templates/landing-page/assets/images/logo-dark.png') }}"
                height="24" class="light-version" alt="">
            <img src="{{ $data?->logo != null || $data?->logo != '' ? asset($data?->logo) : asset('templates/landing-page/assets/images/logo-light.png') }}"
                height="24" class="dark-version" alt="">
        </h5>
        <button type="button" class="btn-close d-flex align-items-center text-dark" data-bs-dismiss="offcanvas"
            aria-label="Close"><i class="uil uil-times fs-4"></i></button>
    </div>
    <div class="offcanvas-body p-4">
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('templates/landing-page/assets/images/contact.svg') }}"
                    class="img-fluid d-block mx-auto" alt="">
                <div class="card border-0 mt-4" style="z-index: 1">
                    <div class="card-body p-0">
                        <h4 class="card-title text-center">Login</h4>
                        <form class="login-form mt-4" method="POST" action="{{ url('dashboard/auth/login') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Your Email <span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input type="email" class="form-control ps-5" placeholder="Email"
                                                name="email" required="true">
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="form-icon position-relative">
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control ps-5" placeholder="Password"
                                                required="true" name="password">
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-lg-12 mb-0">
                                    <div class="d-grid">
                                        <button class="btn btn-primary" type="submit">Sign in</button>
                                    </div>
                                </div><!--end col-->

                                <div class="col-12 text-center">
                                    <p class="mb-0 mt-3"><small class="text-dark me-2">Don't have an account
                                            ?</small> <a href="{{ url('dashboard/auth/register') }}"
                                            class="text-dark fw-bold">Sign Up</a></p>
                                </div><!--end col-->
                            </div><!--end row-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas-footer p-4 border-top text-center">
        @include('templates.list-icon')
    </div>
</div>
<!-- Offcanvas End -->
