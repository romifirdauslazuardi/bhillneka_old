<!-- sidebar-wrapper -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content" data-simplebar style="height: calc(100% - 60px);">
        <div class="sidebar-brand">
            <a href="{{route('dashboard.index')}}">
                <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/').'/templates/dashboard/assets/images/logo-dark.png'}}" height="24" class="logo-light-mode" alt="">
                <span class="sidebar-colored">
                    <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/').'/templates/dashboard/assets/images/logo-light.png'}}" height="24" alt="">
                </span>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN,\App\Enums\RoleEnum::CUSTOMER]))
            <li>
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-tachometer"></i>Dashboard</a></li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::CUSTOMER]))
            <li>
                <li>
                    <a href="{{route('dashboard.orders.index')}}"><i class="fa fa-shopping-cart"></i>
                        Pembelian
                    </a>
                </li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,App\Enums\RoleEnum::ADMIN_AGEN]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-building"></i>Halaman Bisnis</a>
                <div class="sidebar-submenu">
                    <ul>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                            <li><a href="{{route('dashboard.products.index')}}">Produk</a></li>
                            <li><a href="{{route('dashboard.orders.index')}}">Penjualan</a></li>
                        @endif
                        <li>
                            <a href="{{route('dashboard.users.index')}}">
                                @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                Pengguna
                                @elseif(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                                Pelanggan
                                @endif
                            </a>
                        </li>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name,[App\Enums\BusinessCategoryEnum::FNB])))
                        <li><a href="{{route('dashboard.tables.index')}}">Meja</a></li>
                        @endif
                        <li><a href="{{route('dashboard.reports.incomes.index')}}">Laporan Pendapatan</a></li>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name,[App\Enums\BusinessCategoryEnum::MIKROTIK])))
                        <li><a href="{{route('dashboard.reports.order-mikrotiks.index')}}">Laporan Pengguna Mikrotik</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-cog"></i>Settings</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.business.index')}}">List Bisnis</a></li>
                        <li><a href="{{route('dashboard.user-banks.index')}}">Rekening Bank</a></li>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name,[App\Enums\BusinessCategoryEnum::MIKROTIK])))
                        <li><a href="{{route('dashboard.mikrotik-configs.index')}}">Konfigurasi Mikrotik</a></li>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Master</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.business-categories.index')}}">Kategori Bisnis</a></li>
                        <li><a href="{{route('dashboard.banks.index')}}">Bank</a></li>
                    </ul>
                </div>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-chrome"></i>Landing Page</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.landing-page.pages.index')}}">Halaman</a></li>
                        <li><a href="{{route('dashboard.landing-page.our-services.index')}}">Layanan Kami</a></li>
                        <li><a href="{{route('dashboard.landing-page.faqs.index')}}">Faq</a></li>
                        <li><a href="{{route('dashboard.landing-page.testimonials.index')}}">Testimoni</a></li>
                        <li><a href="{{route('dashboard.landing-page.why-us.index')}}">Why Us</a></li>
                        <li><a href="{{route('dashboard.landing-page.google-analytics.index')}}">Laporan Pengunjung</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-bank"></i>Pengaturan Pembayaran</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.settings.fee.index')}}">Fee Transaksi</a></li>
                        <li><a href="{{route('dashboard.providers.index')}}">Metode Pembayaran</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-cog"></i>Pengaturan Website</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{url('dashboard/user-activity')}}">Aktivitas User</a></li>
                        <li><a href="{{route('dashboard.settings.dashboard.index')}}">Pengaturan Dashboard</a></li>
                        <li><a href="{{route('dashboard.settings.landing-page.index')}}">Pengaturan Landing Page</a></li>
                        <li><a href="{{url('dashboard/logs')}}">Logs</a></li>
                    </ul>
                </div>
            </li>
            @endif
        </ul>
        <!-- sidebar-menu  -->
    </div>
</nav>
<!-- sidebar-wrapper  -->