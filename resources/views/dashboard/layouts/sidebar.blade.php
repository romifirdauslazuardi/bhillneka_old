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

            @if((Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li>
                <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.orders.create')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'plus'}}"></i>Buat Order</a></li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li style="margin-left: 25px;font-size:15px;"><small>Halaman Bisnis</small></li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li>
                    <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.products.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'cube'}}"></i>Produk</a></li>
                </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)) || Auth::user()->hasRole([\App\Enums\RoleEnum::CUSTOMER]))
            <li>
                <li>
                    <a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.orders.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'shopping-cart'}}"></i>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                            Penjualan
                        @else
                            Pembelian
                        @endif
                    </a>
                </li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                    @if(!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name,[App\Enums\BusinessCategoryEnum::FNB]))
                    <li>
                        <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.tables.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'table'}}"></i>Meja</a></li>
                    </li>
                    @endif
                @endif
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li>
                <li>
                    <a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.users.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}">
                        <i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'users'}}"></i>
                        User Management
                    </a>
                </li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'file'}}"></i>Laporan</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.reports.incomes.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}">Laporan Pendapatan</a></li>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name,[App\Enums\BusinessCategoryEnum::MIKROTIK])))
                        <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.reports.order-mikrotiks.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}">Laporan Pengguna Mikrotik</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li>
                <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.product-stocks.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'tasks'}}"></i>Inventaris</a></li>
            </li>
            <li>
                <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.cost-accountings.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'money'}}"></i>Akuntan</a></li>
            </li>
            <li>
                <li><a href="{{(\SettingHelper::hasBankActive()==false) ? '#' : route('dashboard.news.index')}}" class="{{(\SettingHelper::hasBankActive()==false) ? 'hasBankNonActive' : ''}}"><i class="fa fa-{{(\SettingHelper::hasBankActive()==false) ? 'lock' : 'bullhorn'}}"></i>News</a></li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
            <li style="margin-left: 25px;font-size:15px;"><small>Settings</small></li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
            <li>
                <li><a href="{{route('dashboard.business.index')}}"><i class="fa fa-building"></i>List Bisnis</a></li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]) && !empty(Auth::user()->business_id)))
            <li>
                <li><a href="{{route('dashboard.user-banks.index')}}"><i class="fa fa-bank"></i>Rekening Bank</a></li>
            </li>
            @endif

            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))

                @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name,[App\Enums\BusinessCategoryEnum::MIKROTIK])))
                <li>
                    <li><a href="{{route('dashboard.mikrotik-configs.index')}}"><i class="fa fa-cog"></i>Konfigurasi Mikrotik</a></li>
                </li>
                @endif

                @if(\SettingHelper::payLaterActive()==true)
                <li>
                    <li><a href="{{route('dashboard.user-pay-laters.index')}}"><i class="fa fa-cogs"></i>Pengaturan Bayar Nanti</a></li>
                </li>
                @endif
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
                        <li><a href="{{route('dashboard.landing-page.partners.index')}}">Partner</a></li>
                        <li><a href="{{route('dashboard.landing-page.google-analytics.index')}}">Laporan Pengunjung</a></li>
                        <li><a href="{{route('dashboard.settings.landing-page.index')}}">Pengaturan Landing Page</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-bank"></i>Pengaturan Pembayaran</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.settings.customer-fee.index')}}">Fee Customer</a></li>
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