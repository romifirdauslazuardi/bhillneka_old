<!-- sidebar-wrapper -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content" data-simplebar style="height: calc(100% - 60px);">
        <div class="sidebar-brand">
            <a href="index.html">
                <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo_dark')) ? asset(\SettingHelper::settings('dashboard', 'logo_dark')) : URL::to('/').'/templates/dashboard/assets/images/logo-dark.png'}}" height="24" class="logo-light-mode" alt="">
                <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/').'/templates/dashboard/assets/images/logo-light.png'}}" height="24" class="logo-dark-mode" alt="">
                <span class="sidebar-colored">
                    <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/').'/templates/dashboard/assets/images/logo-light.png'}}" height="24" alt="">
                </span>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::USER,\App\Enums\RoleEnum::ADMIN_AGEN]))
            <li>
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-database"></i>Dashboard</a></li>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Master</a>
                <div class="sidebar-submenu">
                    <ul>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <li><a href="{{route('dashboard.business-categories.index')}}">Kategori Bisnis</a></li>
                        @endif
                        <li><a href="{{route('dashboard.business.index')}}">Bisnis</a></li>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <li><a href="{{route('dashboard.banks.index')}}">Bank</a></li>
                        @endif
                        <li><a href="{{route('dashboard.user-banks.index')}}">Rekening Bank Pengguna</a></li>
                        <li><a href="{{route('dashboard.users.index')}}">Pengguna</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Produk</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.units.index')}}">Unit</a></li>
                        <li><a href="{{route('dashboard.product-categories.index')}}">Kategori</a></li>
                        <li><a href="{{route('dashboard.products.index')}}">Produk</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN,\App\Enums\RoleEnum::USER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Transaksi</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.orders.index')}}">Order</a></li>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                        <li><a href="index-dark.html">Pembayaran</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Laporan</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="index-dark.html">Laporan Penjualan</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Pengaturan Pembayaran</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="index-dark.html">Fee Transaksi</a></li>
                        <li><a href="{{route('dashboard.providers.index')}}">Metode Pembayaran</a></li>
                    </ul>
                </div>
            </li>
            @endif
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="fa fa-database"></i>Pengaturan Website</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{url('dashboard/user-activity')}}">Aktivitas User</a></li>
                        <li><a href="{{route('dashboard.settings.dashboard.index')}}">Pengaturan Dashboard</a></li>
                        <li><a href="index-dark.html">Pengaturan Landing Page</a></li>
                        <li><a href="{{url('dashboard/logs')}}">Logs</a></li>
                    </ul>
                </div>
            </li>
            @endif
        </ul>
        <!-- sidebar-menu  -->
    </div>
    <!-- Sidebar Footer -->
    <ul class="sidebar-footer list-unstyled mb-0">
        <li class="list-inline-item mb-0">
            <a href="https://1.envato.market/landrick" target="_blank" class="btn btn-icon btn-soft-light"><i class="ti ti-shopping-cart"></i></a> <small class="text-muted fw-medium ms-1">Buy Now</small>
        </li>
    </ul>
    <!-- Sidebar Footer -->
</nav>
<!-- sidebar-wrapper  -->