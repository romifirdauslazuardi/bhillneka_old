<!-- Top Header -->
<div class="top-header">
    <div class="header-bar d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <a href="#" class="logo-icon me-3">
                <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo_icon')) ? asset(\SettingHelper::settings('dashboard', 'logo_icon')) : URL::to('/').'/templates/dashboard/assets/images/logo-icon.png'}}" height="30" class="small" alt="">
                <span class="big">
                    <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo_dark')) ? asset(\SettingHelper::settings('dashboard', 'logo_dark')) : URL::to('/').'/templates/dashboard/assets/images/logo-icon.png'}}" height="24" class="logo-light-mode" alt="">
                    <img src="{{!empty(\SettingHelper::settings('dashboard', 'logo')) ? asset(\SettingHelper::settings('dashboard', 'logo')) : URL::to('/').'/templates/dashboard/assets/images/logo-icon.png'}}" height="24" class="logo-dark-mode" alt="">
                </span>
            </a>
            <a id="close-sidebar" class="btn btn-icon btn-soft-light" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
            </a>
        </div>

        <ul class="list-unstyled mb-0">

            <li class="list-inline-item mb-0 ms-1">
                <div class="dropdown dropdown-primary">
                    <button type="button" class="btn btn-icon btn-soft-light dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></button>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                    
                    <div class="dropdown-menu dd-menu shadow rounded border-0 mt-3 p-0" data-simplebar style="width: 290px;">
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                            <h6 class="mb-0 text-dark">Notifications</h6>
                            <span class="badge bg-soft-danger rounded-pill">{{ Auth::user()->unreadNotifications->count() > 0 ? Auth::user()->unreadNotifications->count() : null }}</span>
                        </div>
                        <div class="p-3">
                            @if (Auth::user()->unreadNotifications->count() > 0)
                            @foreach (Auth::user()->unreadNotifications as $notification)
                            <a href="{{$notification['data']['url']}}" class="dropdown-item features feature-primary key-feature p-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon text-center rounded-circle me-2">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 text-dark title">{!! $notification['data']['title'] !!}</h6>
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
                    <button type="button" class="btn btn-soft-light dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="@if(!empty(Auth::user()->avatar)) {{asset(Auth::user()->avatar)}} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name  ?? null}}.svg?margin=10 @endif" class="avatar avatar-ex-small rounded" alt=""></button>
                    <div class="dropdown-menu dd-menu dropdown-menu-end shadow border-0 mt-3 py-3" style="min-width: 200px;">
                        <a class="dropdown-item d-flex align-items-center text-dark pb-3" href="{{route('dashboard.profile.index')}}">
                            <img src="@if(!empty(Auth::user()->avatar)) {{asset(Auth::user()->avatar)}} @else https://avatars.dicebear.com/api/initials/{{ Auth::user()->name  ?? null}}.svg?margin=10 @endif" class="avatar avatar-md-sm rounded-circle border shadow" alt="">
                            <div class="flex-1 ms-2">
                                <span class="d-block">{{Auth::user()->name}}</span>
                                <small class="text-muted">{{Auth::user()->email}}</small>
                            </div>
                        </a>
                        <a class="dropdown-item text-dark text-center" href="{{route('dashboard.profile.index')}}"><span class="mb-0 d-inline-block me-1">Profile</a>
                        <a class="dropdown-item text-dark text-center" href="{{route('dashboard.auth.logout')}}"><span class="mb-0 d-inline-block me-1">@if(session('impersonated_by')) Leave Impersonate @else Logout @endif</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- Top Header -->