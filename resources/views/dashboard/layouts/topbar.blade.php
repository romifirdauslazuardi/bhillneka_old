<!-- Header -->
<div class="header">
    <!-- Logo -->
    <div class="header-left active">
        <a href="index.html" class="logo logo-normal">
            <img src="{{ !empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : asset('assets/dreampos/assets/img/logo.png') }}" alt="">
        </a>
        <a href="index.html" class="logo logo-white">
            <img src="{{ asset('assets/dreampos/assets/img/logo-white.png') }}" alt="">
        </a>
        <a href="index.html" class="logo-small">
            <img src="{{ asset('assets/dreampos/assets/img/logo-small.png') }}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Menu -->
    <ul class="nav user-menu">

        <!-- Search -->
        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">

                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#">
                    <div class="searchinputs">
                        <input type="text" placeholder="Search">
                        <div class="search-addon">
                            <span><i data-feather="search" class="feather-14"></i></span>
                        </div>
                    </div>
                    <!-- <a class="btn"  id="searchdiv"><img src="{{ asset('assets/dreampos/assets/img/icons/search.svg') }}" alt="img"></a> -->
                </form>
            </div>
        </li>
        <!-- /Search -->
        @if (Auth::user()->hasRole([App\Enums\RoleEnum::OWNER, App\Enums\RoleEnum::AGEN, App\Enums\RoleEnum::ADMIN_AGEN]))
            <li class="nav-item nav-item-box" style="background: #f7f7f7;border-radius: 11px;">
                <div class="btn btn-sm business-setting" href="#" style="margin-left: 5px;">
                    @if (!empty(Auth::user()->business_id))
                        <div class="business-setting-medium-screen">
                            <span class="text-success">
                                {{ Auth::user()->business->name }} @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    - ({{ Auth::user()->business->user->name ?? null }})
                                @endif
                            </span>
                            <i class="fa fa-caret-down text-success"></i>
                            <br>
                            <small><i>(Klik untuk ubah bisnis page)</i></small>
                        </div>
                        <div class="business-setting-small-screen" style="display: none;">
                            <span class="text-success">
                                {{ substr(Auth::user()->business->name ?? null, 0, 8) . '...' }} @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                @endif
                            </span>
                            <i class="fa fa-caret-down text-success"></i>
                        </div>
                    @else
                        <div class="business-setting-medium-screen">
                            <span class="text-warning">==Pilih Bisnis Page==</span>
                            <i class="fa fa-caret-down text-warning"></i>
                            <br>
                            <small><i>(Klik untuk mengaktifkan bisnis page)</i></small>
                        </div>
                        <div class="business-setting-small-screen" style="display: none;">
                            <span class="text-warning">==Pilih Bisnis Page==</span>
                            <i class="fa fa-caret-down text-warning"></i>
                        </div>
                    @endif
                </div>
            </li>
        @endif

        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>
        <!-- Notifications -->
        <li class="nav-item dropdown nav-item-box">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <i data-feather="bell"></i><span class="badge rounded-pill" {{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}>{{ Auth::user()->unreadNotifications->count() > 0 ? Auth::user()->unreadNotifications->count() : null }}</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            @foreach (Auth::user()->unreadNotifications as $notification)
                                <li class="notification-message">
                                    <a href="{{ route('dashboard.notification.read', $notification->id) }}" <span
                                        class="avatar flex-shrink-0">
                                        <img alt=""
                                            src="{{ asset('assets/dreampos/assets/img/profiles/avatar-02.jpg') }}">
                                        </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">John Doe</span> added
                                                new
                                                task <span
                                                    class="noti-title">{{ $notification['data']['title'] }}</span>
                                            </p>
                                            <p class="noti-time"><span
                                                    class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <span class="align-items-center d-flex dropdown-item justify-content-center notify-item"
                                id="notifEmpty">Tidak
                                terdapat notifikasi</span>
                        @endif
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="{{ route('dashboard.notification') }}">View all Notifications</a>
                </div>
            </div>
        </li>
        <!-- /Notifications -->
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="@if (!empty(Auth::user()->avatar)) {{ asset(Auth::user()->avatar) }} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name ?? null }}.svg?margin=10 @endif"
                            alt="" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ auth()->user()->roles[0]->name }}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            <img src="@if (!empty(Auth::user()->avatar)) {{ asset(Auth::user()->avatar) }} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name ?? null }}.svg?margin=10 @endif"
                                alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ Auth::user()->name }}</h6>
                            <h5>{{ auth()->user()->roles[0]->name }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ route('dashboard.profile.index') }}"> <i class="me-2"
                            data-feather="user"></i> My
                        Profile</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('dashboard.auth.logout') }}">
                        <img src="{{ asset('assets/dreampos/assets/img/icons/log-out.svg') }}" class="me-2"
                            alt="img">
                        @if (session('impersonated_by'))
                            Leave Impersonate
                        @else
                            Logout
                        @endif
                    </a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('dashboard.profile.index') }}">My Profile</a>
            <a class="dropdown-item" href="{{ route('dashboard.auth.logout') }}">
                @if (session('impersonated_by'))
                    Leave Impersonate
                @else
                    Logout
                @endif
            </a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- Header -->

{{-- <!-- Top Header -->
<div class="top-header">
    <div class="header-bar d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <a href="#" class="logo-icon me-3">
                <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo_icon')) ? asset(\SettingHelper::settings('dashboard', 'logo_icon')) : URL::to('/').'/templates/dashboard/assets/images/logo-icon.png'}}" height="30" class="small" alt="">
                <span class="big">
                    <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/').'/templates/dashboard/assets/images/logo-icon.png'}}" height="24" class="logo-light-mode" alt="">
                </span>
            </a>
            <a id="close-sidebar" class="btn btn-icon btn-soft-light" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
            </a>
            @if (Auth::user()->hasRole([App\Enums\RoleEnum::OWNER, App\Enums\RoleEnum::AGEN, App\Enums\RoleEnum::ADMIN_AGEN]))
            <a class="btn btn-sm business-setting" href="#" style="margin-left: 5px;">
                @if (!empty(Auth::user()->business_id))
                <div class="business-setting-medium-screen">
                    <span class="text-success">
                    {{Auth::user()->business->name}} @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER])) - ({{Auth::user()->business->user->name ?? null}}) @endif
                    </span>
                    <i class="fa fa-caret-down text-success"></i>
                    <br>
                    <small><i>(Klik untuk ubah bisnis page)</i></small>
                </div>
                <div class="business-setting-small-screen" style="display: none;">
                    <span class="text-success">
                    {{substr(Auth::user()->business->name ?? null, 0, 8) . '...'}} @if (Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER])) @endif
                    </span>
                    <i class="fa fa-caret-down text-success"></i>
                </div>
                @else
                <div class="business-setting-medium-screen">
                    <span class="text-warning">==Pilih Bisnis Page==</span>
                    <i class="fa fa-caret-down text-warning"></i>
                    <br>
                    <small><i>(Klik untuk mengaktifkan bisnis page)</i></small>
                </div>
                <div class="business-setting-small-screen" style="display: none;">
                    <span class="text-warning">==Pilih Bisnis Page==</span>
                    <i class="fa fa-caret-down text-warning"></i>
                </div>
                @endif
            </a>
            @endif
        </div>

        <ul class="list-unstyled mb-0">

            <li class="list-inline-item mb-0 ms-1">
                <div class="dropdown dropdown-primary">
                    <button type="button" class="btn btn-icon btn-soft-light dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></button>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>

                    <div class="dropdown-menu dd-menu shadow rounded border-0 mt-3 p-0" data-simplebar style="width: 300px;max-height:300px;">
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <h6 class="mb-0 text-dark">Notifications</h6>
                            <span class="badge bg-soft-danger rounded-pill">{{ Auth::user()->unreadNotifications->count() > 0 ? Auth::user()->unreadNotifications->count() : null }}</span>
                        </div>
                        <div class="p-3">
                            @if (Auth::user()->unreadNotifications->count() > 0)
                            @foreach (Auth::user()->unreadNotifications as $notification)
                            <a href="{{route('dashboard.notification.read',$notification->id)}}" class="dropdown-item features feature-primary key-feature pb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon text-center rounded-circle me-2">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 text-dark title">{{$notification['data']['title']}}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @else
                            <span class="align-items-center d-flex dropdown-item justify-content-center notify-item" id="notifEmpty">Tidak terdapat notifikasi</span>
                            @endif
                        </div>

                        <a href="{{ route('dashboard.notification') }}" class="dropdown-item notify-all text-center">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>
            </li>

            <li class="list-inline-item mb-0 ms-1">
                <div class="dropdown dropdown-primary">
                    <button type="button" class="btn btn-soft-light dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="@if (!empty(Auth::user()->avatar)) {{asset(Auth::user()->avatar)}} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name  ?? null}}.svg?margin=10 @endif" class="avatar avatar-ex-small rounded" alt=""></button>
                    <div class="dropdown-menu dd-menu dropdown-menu-end shadow border-0 mt-3 py-3" style="min-width: 200px;">
                        <a class="dropdown-item d-flex align-items-center text-dark pb-3" href="{{route('dashboard.profile.index')}}">
                            <img src="@if (!empty(Auth::user()->avatar)) {{asset(Auth::user()->avatar)}} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name  ?? null}}.svg?margin=10 @endif" class="avatar avatar-md-sm rounded-circle border shadow" alt="">
                            <div class="flex-1 ms-2">
                                <span class="d-block">{{Auth::user()->name}}</span>
                                <small class="text-muted">{{Auth::user()->email}}</small>
                            </div>
                        </a>
                        <a class="dropdown-item text-dark text-center" href="{{route('dashboard.profile.index')}}"><span class="mb-0 d-inline-block me-1">Profile</a>
                        <a class="dropdown-item text-dark text-center" href="{{route('dashboard.auth.logout')}}"><span class="mb-0 d-inline-block me-1">@if (session('impersonated_by')) Leave Impersonate @else Logout @endif</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- Top Header --> --}}
