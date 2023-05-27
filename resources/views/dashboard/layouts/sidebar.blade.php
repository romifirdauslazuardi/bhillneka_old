<!-- sidebar-wrapper -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content" data-simplebar style="height: calc(100% - 60px);">
        <div class="sidebar-brand">
            <a href="index.html">
                <img src="{{URL::to('/')}}/templates/dashboard/assets/images/logo-dark.png" height="24" class="logo-light-mode" alt="">
                <img src="{{URL::to('/')}}/templates/dashboard/assets/images/logo-light.png" height="24" class="logo-dark-mode" alt="">
                <span class="sidebar-colored">
                    <img src="{{URL::to('/')}}/templates/dashboard/assets/images/logo-light.png" height="24" alt="">
                </span>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <li><a href="{{route('dashboard.index')}}"><i class="ti ti-home me-2"></i>Dashboard</a></li>
            </li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="ti ti-browser me-2"></i>Master</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.business-categories.index')}}">Kategori Bisnis</a></li>
                        <li><a href="{{route('dashboard.business.index')}}">Bisnis</a></li>
                        <li><a href="{{route('dashboard.banks.index')}}">Bank</a></li>
                        <li><a href="{{route('dashboard.user-banks.index')}}">Rekening Bank Pengguna</a></li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="ti ti-browser me-2"></i>Produk</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="index-dark.html">Unit</a></li>
                        <li><a href="index-dark.html">Kategori</a></li>
                        <li><a href="index-dark.html">Produk</a></li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="ti ti-browser me-2"></i>Transaksi</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="index-dark.html">Order</a></li>
                        <li><a href="index-dark.html">Pembayaran</a></li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="ti ti-browser me-2"></i>Laporan</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="index-dark.html">Laporan Penjualan</a></li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="ti ti-browser me-2"></i>Pengaturan Pembayaran</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="index-dark.html">Fee Transaksi</a></li>
                        <li><a href="index-dark.html">Penyedia Layanan</a></li>
                        <li><a href="index-dark.html">Metode Pembayaran</a></li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-dropdown">
                <a href="javascript:void(0)"><i class="ti ti-browser me-2"></i>Pengaturan Website</a>
                <div class="sidebar-submenu">
                    <ul>
                        <li><a href="{{route('dashboard.users.index')}}">User</a></li>
                        <li><a href="{{url('dashboard/user-activity')}}">Aktivitas User</a></li>
                        <li><a href="index-dark.html">Pengaturan Dashboard</a></li>
                        <li><a href="index-dark.html">Pengaturan Landing Page</a></li>
                        <li><a href="{{url('dashboard/logs')}}">Logs</a></li>
                    </ul>
                </div>
            </li>
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