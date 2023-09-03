<!--Login button Start-->
<ul class="buy-button list-inline mb-0">
    <li class="list-inline-item mb-0">
        @guest
            <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight">
                <div class="login-btn-primary"><span class="btn btn-icon btn-pills btn-soft-primary"><i
                            data-feather="settings" class="fea icon-sm"></i></span></div>
                <div class="login-btn-light"><span class="btn btn-icon btn-pills btn-light"><i
                            data-feather="settings" class="fea icon-sm"></i></span></div>
            </a>
        @else
            <a href="{{ url('dashboard') }}" title="Dashboard">
                <div class="login-btn-primary"><span class="btn btn-icon btn-pills btn-soft-primary"><i
                            data-feather="user" class="fea icon-sm"></i></span></div>
                <div class="login-btn-light"><span class="btn btn-icon btn-pills btn-light"><i
                            data-feather="user" class="fea icon-sm"></i></span></div>
            </a>
        @endguest
    </li>

    <li class="list-inline-item ps-1 mb-0">
        <a href="{{ url('shops/' . $business->slug) }}" target="_blank">
            <div class="login-btn-primary"><span class="btn btn-icon btn-pills btn-primary"><i
                        data-feather="shopping-cart" class="fea icon-sm"></i></span></div>
            <div class="login-btn-light"><span class="btn btn-icon btn-pills btn-light"><i
                        data-feather="shopping-cart" class="fea icon-sm"></i></span></div>
        </a>
    </li>
</ul>
<!--Login button End-->
