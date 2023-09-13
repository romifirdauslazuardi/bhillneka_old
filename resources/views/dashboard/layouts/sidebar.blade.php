@php
    function getClass($route)
    {
        $current = Route::currentRouteName();
        if ($current == $route) {
            return 'active';
        }
    }
@endphp
@php
    $businessData = App\Models\Business::where('id', auth()->user()->business_id)
        ->with('category')
        ->first();
    $businessCategory = App\Models\BusinessCategory::where('id', $businessData?->category?->id)
        ->with('template')
        ->first();
@endphp
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @if (Auth::user()->hasRole([
                        \App\Enums\RoleEnum::OWNER,
                        \App\Enums\RoleEnum::AGEN,
                        \App\Enums\RoleEnum::ADMIN_AGEN,
                        \App\Enums\RoleEnum::CUSTOMER,
                    ]))
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Main</h6>
                        <ul>
                            <li>
                                <a class="{{ getClass('dashboard.index') }}" href="{{ route('dashboard.index') }}"><i
                                        data-feather="grid"></i><span>Dashboard</span></a>
                            </li>
                            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                                    !empty(Auth::user()->business_id))
                                <li>
                                    <a class="{{ getclass('dashboard.orders.create') }}"
                                        href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.orders.create') }}">
                                        <i
                                            data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'plus' }}"></i><span>Create
                                            Order</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Products</h6>
                    <ul>
                        <li><a class="{{ getClass('dashboard.products.index') }}"
                                href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.products.index') }}"><i
                                    data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'box' }}"></i><span>Products</span></a>
                        </li>
                        <li><a class="{{ getClass('dashboard.products.create') }}"
                                href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.products.create') }}"><i
                                    data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'plus-square' }}"></i><span>Create
                                    Product</span></a>
                        </li>
                        <li><a class="{{ getClass('dashboard.product-categories.index') }}"
                                href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.product-categories.index') }}"><i
                                    data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'box' }}"></i><span>Category</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                                (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                                    !empty(Auth::user()->business_id)))
                            <li><a class="{{ getClass('dashboard.reports.incomes.index') }}"
                                    href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.reports.incomes.index') }}"><i
                                        data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'bar-chart-2' }}"></i><span>Sales</span></a>
                            </li>
                        @endif
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                                !empty(Auth::user()->business_id))
                            <li>
                                <a class="{{ getclass('dashboard.orders.create') }}"
                                    href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.orders.create2') }}">
                                    <i
                                        data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'hard-drive' }}"></i><span>POS</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li><a
                                href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.users.index') }}"><i
                                    data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'user-check' }}"></i><span>Users</span></a>
                        </li>
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]))
                            <li><a class="{{ getClass('dashboard.business.index') }}"
                                    href="{{ route('dashboard.business.index') }}"><i
                                        data-feather="home"></i>Bussiness</a></li>
                        @endif
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                                (!empty(Auth::user()->business_id) &&
                                    in_array(Auth::user()->business->category->name, [App\Enums\BusinessCategoryEnum::MIKROTIK])))
                            <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.reports.order-mikrotiks.index') }}"
                                    class="{{ getClass('dashboard.reports.order-mikrotiks.index') }}"><i
                                        data-feather="users"></i>Mikrotik Users</a>
                            </li>
                        @endif
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                                (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                                    !empty(Auth::user()->business_id)) ||
                                Auth::user()->hasRole([\App\Enums\RoleEnum::CUSTOMER]))
                            <li><a class="{{ getClass('dashboard.orders.index') }}"
                                    href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.orders.index') }}"><i
                                        data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'pie-chart' }}"></i><span>Orders</span></a>
                            </li>
                        @endif
                        <li><a class="{{ getClass('dashboard.product-stocks.index') }}"
                                href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.product-stocks.index') }}"><i
                                    data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'credit-card' }}"></i><span>Stock
                                    Report</span></a></li>
                        <li><a class="{{ getClass('dashboard.cost-accountings.index') }}"
                                href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.cost-accountings.index') }}"><i
                                    data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'pie-chart' }}"></i><span>Accounting
                                    Report</span></a></li>
                    </ul>
                </li>
                @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                        (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                            !empty(Auth::user()->business_id)))
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">User Management</h6>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);"><i
                                        data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'users' }}"></i><span>Manage
                                        Users</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a class="{{ getClass('dashboard.users.create') }}"
                                            href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.users.create') }}">New
                                            User </a></li>
                                    <li><a class="{{ getClass('dashboard.users.index') }}"
                                            href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.users.index') }}">Users
                                            List</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                        (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                            !empty(Auth::user()->business_id)))
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Notifications</h6>
                        <ul>
                            <li>
                                <a class="{{ getClass('dashboard.news.index') }}"
                                    href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.news.index') }}">
                                    <i
                                        data-feather="{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'bell' }}"></i>Broadcast
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                                (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]) && !empty(Auth::user()->business_id)))
                            <li><a class="{{ getClass('dashboard.user-banks.index') }}"
                                    href="{{ route('dashboard.user-banks.index') }}"><i
                                        data-feather="credit-card"></i>Bank Account</a></li>
                        @endif

                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                                (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) &&
                                    !empty(Auth::user()->business_id)))

                            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ||
                                    (!empty(Auth::user()->business_id) &&
                                        in_array(Auth::user()->business->category->name, [App\Enums\BusinessCategoryEnum::MIKROTIK])))
                                <li><a class="{{ getClass('dashboard.mikrotik-configs.index') }}"
                                        href="{{ route('dashboard.mikrotik-configs.index') }}"><i
                                            data-feather="wifi"></i><span>Mikrotik Configuration</span></a></li>
                            @endif

                            @if (\SettingHelper::payLaterActive() == true)
                                <li><a class="{{ getClass('dashboard.user-pay-laters.index') }}"
                                        href="{{ route('dashboard.user-pay-laters.index') }}"><i
                                            data-feather="file-text"></i><span>Paylater Setting</span></a></li>
                            @endif
                        @endif

                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i data-feather="slack"></i>
                                    <span>Master</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a class="{{ getClass('dashboard.business-categories.index') }}"
                                            href="{{ route('dashboard.business-categories.index') }}">Business
                                            Category</a></li>
                                    <li><a class="{{ getClass('dashboard.banks.index') }}"
                                            href="{{ route('dashboard.banks.index') }}">Bank</a></li>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i data-feather="layers"></i>
                                    <span>Landing Page</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a class="{{ getClass('dashboard.landing-page.pages.index') }}"
                                            href="{{ route('dashboard.landing-page.pages.index') }}">Pages</a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.landing-page.our-services.index') }}"
                                            href="{{ route('dashboard.landing-page.our-services.index') }}">Service Us</a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.landing-page.faqs.index') }}"
                                            href="{{ route('dashboard.landing-page.faqs.index') }}">Faqs</a></li>
                                    <li><a class="{{ getClass('dashboard.landing-page.testimonials.index') }}"
                                            href="{{ route('dashboard.landing-page.testimonials.index') }}">Testimonis</a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.landing-page.why-us.index') }}"
                                            href="{{ route('dashboard.landing-page.why-us.index') }}">Why Us</a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.landing-page.partners.index') }}"
                                            href="{{ route('dashboard.landing-page.partners.index') }}">Partners</a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.landing-page.google-analytics.index') }}"
                                            href="{{ route('dashboard.landing-page.google-analytics.index') }}">Visitor Reports</a></li>
                                    <li><a class="{{ getClass('dashboard.settings.landing-page.index') }}"
                                            href="{{ route('dashboard.settings.landing-page.index') }}">Base Setting</a></li>
                                </ul>
                            </li>
                            @if (auth()->user()->business_id != null)
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <i data-feather="layers"></i>
                                        <span>Landing Page {{ auth()->user()->business->name }}</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a class="{{ getClass('dashboard.settings.landing-page-agen.index') }}"
                                                href="{{ route('dashboard.settings.landing-page-agen.index') }}">
                                                Base Setting
                                            </a>
                                        </li>
                                        <li>
                                            <a class="{{ getClass('landing-page.landing-page-agen.' . $businessCategory->template?->name . '.index') }}"
                                                href="{{ route('landing-page.landing-page-agen.' . $businessCategory->template?->name . '.index') }}">
                                                Landing Page
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endif
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]) && Auth::user()->business_id != null)
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i data-feather="layers"></i>
                                    <span>Landing Page</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a class="{{ getClass('dashboard.settings.landing-page-agen.index') }}"
                                            href="{{ route('dashboard.settings.landing-page-agen.index') }}">
                                            Base Settings
                                        </a>
                                    </li>
                                    <li>
                                        <a class="{{ getClass('landing-page.landing-page-agen.' . $businessCategory->template?->name . '.index') }}"
                                            href="{{ route('landing-page.landing-page-agen.' . $businessCategory->template?->name . '.index') }}">
                                            Landing Page
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i data-feather="briefcase"></i>
                                    <span>Payments</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a class="{{ getClass('dashboard.settings.customer-fee.index') }}"
                                            href="{{ route('dashboard.settings.customer-fee.index') }}">
                                            Customer Fee
                                        </a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.settings.fee.index') }}"
                                            href="{{ route('dashboard.settings.fee.index') }}">Transaction Fee</a>
                                    </li>
                                    <li><a class="{{ getClass('dashboard.providers.index') }}"
                                            href="{{ route('dashboard.providers.index') }}">Payment Methode</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i data-feather="aperture"></i>
                                    <span>Website</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ url('dashboard/user-activity') }}">User Activities</a></li>
                                    <li><a class="{{ getClass('dashboard.settings.dashboard.index') }}"
                                            href="{{ route('dashboard.settings.dashboard.index') }}">Dashboard Settings</a>
                                    </li>
                                    <li><a href="{{ url('dashboard/logs') }}">Logs</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->

{{-- <!-- sidebar-wrapper -->
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content" data-simplebar style="height: calc(100% - 60px);">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard.index') }}">
                <img src="{{ !empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/') . '/templates/dashboard/assets/images/logo-dark.png' }}"
                    height="24" class="logo-light-mode" alt="">
                <span class="sidebar-colored">
                    <img src="{{ !empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/') . '/templates/dashboard/assets/images/logo-light.png' }}"
                        height="24" alt="">
                </span>
            </a>
        </div>

        <ul class="sidebar-menu">

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN, \App\Enums\RoleEnum::CUSTOMER]))
                <li>
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-tachometer"></i>Dashboard</a></li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id))
                <li>
                <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.orders.create') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'plus' }}"></i>Buat
                        Order</a></li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li style="margin-left: 25px;font-size:15px;"><small>Halaman Bisnis</small></li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li>
                <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.products.index') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'cube' }}"></i>Produk</a>
                </li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)) || Auth::user()->hasRole([\App\Enums\RoleEnum::CUSTOMER]))
                <li>
                <li>
                    <a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.orders.index') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'shopping-cart' }}"></i>
                        @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]))
                            Penjualan
                        @else
                            Pembelian
                        @endif
                    </a>
                </li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]))
                    @if (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name, [App\Enums\BusinessCategoryEnum::FNB]))
                        <li>
                        <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.tables.index') }}"
                                class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                                    class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'table' }}"></i>Meja</a>
                        </li>
                        </li>
                    @endif
                @endif
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li>
                <li>
                    <a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.users.index') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}">
                        <i class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'users' }}"></i>
                        User Management
                    </a>
                </li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li class="sidebar-dropdown">
                    <a href="javascript:void(0)"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'file' }}"></i>Laporan</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.reports.incomes.index') }}"
                                    class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}">Laporan
                                    Pendapatan</a></li>
                            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name, [App\Enums\BusinessCategoryEnum::MIKROTIK])))
                                <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.reports.order-mikrotiks.index') }}"
                                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}">Laporan
                                        Pengguna Mikrotik</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li>
                <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.product-stocks.index') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'tasks' }}"></i>Inventaris</a>
                </li>
                </li>
                <li>
                <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.cost-accountings.index') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'money' }}"></i>Akuntan</a>
                </li>
                </li>
                <li>
                <li><a href="{{ \SettingHelper::hasBankActive() == false ? '#' : route('dashboard.news.index') }}"
                        class="{{ \SettingHelper::hasBankActive() == false ? 'hasBankNonActive' : '' }}"><i
                            class="fa fa-{{ \SettingHelper::hasBankActive() == false ? 'lock' : 'bullhorn' }}"></i>News</a>
                </li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))
                <li style="margin-left: 25px;font-size:15px;"><small>Settings</small></li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER, \App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]))
                <li>
                <li><a href="{{ route('dashboard.business.index') }}"><i class="fa fa-building"></i>List Bisnis</a>
                </li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]) && !empty(Auth::user()->business_id)))
                <li>
                <li><a href="{{ route('dashboard.user-banks.index') }}"><i class="fa fa-bank"></i>Rekening Bank</a>
                </li>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN, \App\Enums\RoleEnum::ADMIN_AGEN]) && !empty(Auth::user()->business_id)))

                @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (!empty(Auth::user()->business_id) && in_array(Auth::user()->business->category->name, [App\Enums\BusinessCategoryEnum::MIKROTIK])))
                    <li>
                    <li><a href="{{ route('dashboard.mikrotik-configs.index') }}"><i
                                class="fa fa-cog"></i>Konfigurasi
                            Mikrotik</a></li>
                    </li>
                @endif

                @if (\SettingHelper::payLaterActive() == true)
                    <li>
                    <li><a href="{{ route('dashboard.user-pay-laters.index') }}"><i class="fa fa-cogs"></i>Pengaturan
                            Bayar Nanti</a></li>
                    </li>
                @endif
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                <li class="sidebar-dropdown">
                    <a href="javascript:void(0)"><i class="fa fa-database"></i>Master</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('dashboard.business-categories.index') }}">Kategori Bisnis</a></li>
                            <li><a href="{{ route('dashboard.banks.index') }}">Bank</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                <li class="sidebar-dropdown">
                    <a href="javascript:void(0)"><i class="fa fa-chrome"></i>Landing Page Utama</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('dashboard.landing-page.pages.index') }}">Halaman</a></li>
                            <li><a href="{{ route('dashboard.landing-page.our-services.index') }}">Layanan Kami</a>
                            </li>
                            <li><a href="{{ route('dashboard.landing-page.faqs.index') }}">Faq</a></li>
                            <li><a href="{{ route('dashboard.landing-page.testimonials.index') }}">Testimoni</a></li>
                            <li><a href="{{ route('dashboard.landing-page.why-us.index') }}">Why Us</a></li>
                            <li><a href="{{ route('dashboard.landing-page.partners.index') }}">Partner</a></li>
                            <li><a href="{{ route('dashboard.landing-page.google-analytics.index') }}">Laporan
                                    Pengunjung</a></li>
                            <li><a href="{{ route('dashboard.settings.landing-page.index') }}">Pengaturan Landing
                                    Page</a></li>
                        </ul>
                    </div>
                </li>
                @if (auth()->user()->business_id != null)
                    <li class="sidebar-dropdown">
                        <a href="javascript:void(0)"><i class="fa fa-chrome"></i>Landing Page
                            {{ auth()->user()->business?->name }}</a>
                        <div class="sidebar-submenu">
                            <ul>
                                <li><a href="{{ route('dashboard.landing-page-agen.pages.index') }}">Halaman</a></li>
                                <li><a href="{{ route('dashboard.landing-page-agen.our-services.index') }}">Layanan
                                        Kami</a></li>
                                <li><a href="{{ route('dashboard.landing-page-agen.faqs.index') }}">Faq</a></li>
                                <li><a
                                        href="{{ route('dashboard.landing-page-agen.testimonials.index') }}">Testimoni</a>
                                </li>
                                <li><a href="{{ route('dashboard.landing-page-agen.why-us.index') }}">Why Us</a></li>
                                <li><a href="{{ route('dashboard.landing-page-agen.partners.index') }}">Partner</a>
                                </li>
                                <li><a href="{{ route('dashboard.landing-page-agen.google-analytics.index') }}">Laporan
                                        Pengunjung</a></li>
                                <li><a href="{{ route('dashboard.settings.landing-page-agen.index') }}">Pengaturan
                                        Landing
                                        Page</a></li>
                            </ul>
                        </div>
                    </li>
                @endif
            @endif
            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]) && Auth::user()->business_id != null)
                <li class="sidebar-dropdown">
                    <a href="javascript:void(0)"><i class="fa fa-chrome"></i>Landing Page</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('dashboard.landing-page-agen.pages.index') }}">Halaman</a></li>
                            <li><a href="{{ route('dashboard.landing-page-agen.our-services.index') }}">Layanan
                                    Kami</a></li>
                            <li><a href="{{ route('dashboard.landing-page-agen.faqs.index') }}">Faq</a></li>
                            <li><a href="{{ route('dashboard.landing-page-agen.testimonials.index') }}">Testimoni</a>
                            </li>
                            <li><a href="{{ route('dashboard.landing-page-agen.why-us.index') }}">Why Us</a></li>
                            <li><a href="{{ route('dashboard.landing-page-agen.partners.index') }}">Partner</a></li>
                            <li><a href="{{ route('dashboard.landing-page-agen.google-analytics.index') }}">Laporan
                                    Pengunjung</a></li>
                            <li><a href="{{ route('dashboard.settings.landing-page-agen.index') }}">Pengaturan Landing
                                    Page</a></li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                <li class="sidebar-dropdown">
                    <a href="javascript:void(0)"><i class="fa fa-bank"></i>Pengaturan Pembayaran</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ route('dashboard.settings.customer-fee.index') }}">Fee Customer</a></li>
                            <li><a href="{{ route('dashboard.settings.fee.index') }}">Fee Transaksi</a></li>
                            <li><a href="{{ route('dashboard.providers.index') }}">Metode Pembayaran</a></li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                <li class="sidebar-dropdown">
                    <a href="javascript:void(0)"><i class="fa fa-cog"></i>Pengaturan Website</a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="{{ url('dashboard/user-activity') }}">Aktivitas User</a></li>
                            <li><a href="{{ route('dashboard.settings.dashboard.index') }}">Pengaturan Dashboard</a>
                            </li>
                            <li><a href="{{ url('dashboard/logs') }}">Logs</a></li>
                        </ul>
                    </div>
                </li>
            @endif
        </ul>
        <!-- sidebar-menu  -->
    </div>
</nav>
<!-- sidebar-wrapper  --> --}}
