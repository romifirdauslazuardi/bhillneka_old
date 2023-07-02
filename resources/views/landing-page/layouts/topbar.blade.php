<!-- Navbar Start -->
<header id="topnav" class="defaultscroll sticky">
    <div class="container">
        <!-- Logo container-->
        <a class="logo" href="{{route('landing-page.home.index')}}">
            <img src="{{!empty(\SettingHelper::settings('landing_page', 'logo_dark')) ? asset(\SettingHelper::settings('landing_page', 'logo_dark')) : URL::to('/').'/templates/landing-page/assets/images/logo-dark.png'}}" height="24" class="logo-light-mode" alt="">
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

        <div id="navigation">
            <!-- Navigation Menu-->   
            <ul class="navigation-menu">
                <li><a href="{{route('landing-page.home.index')}}" class="sub-menu-item">Home</a></li>
                <li><a href="{{route('landing-page.pages.index','tentang-kami')}}" class="sub-menu-item">Tentang Kami</a></li>
                <li><a href="{{route('landing-page.our-services.index')}}" class="sub-menu-item">Layanan Kami</a></li>
                <li><a href="{{route('landing-page.faqs.index')}}" class="sub-menu-item">Faq</a></li>
                <li><a href="{{route('landing-page.contact-us.index')}}" class="sub-menu-item">Hubungi Kami</a></li>
                <li><a href="{{route('landing-page.orders.index')}}" class="sub-menu-item">Status Pesanan</a></li>
            </ul><!--end navigation menu-->
        </div><!--end navigation-->
    </div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->