@extends("dashboard.layouts.main")

@section("title","Notifikasi")

@section("css")
<style>
    .activity-list {
        padding-left: 86px;
    }

    .activity-list .activity-item {
        position: relative;
        padding-bottom: 26px;
        padding-left: 45px;
        border-left: 2px solid #f3f3f3;
    }

    .activity-list .activity-item:after {
        content: "";
        display: block;
        position: absolute;
        top: 3px;
        left: -7px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #508aeb;
    }

    .activity-list .activity-item .activity-item-img img {
        max-width: 78px;
    }

    .activity-list .activity-item .activity-date {
        position: absolute;
        left: -92px;
        font-size: 14px;
    }

    @media (max-width: 420px) {
        .activity-list {
            padding-left: 0;
        }

        .activity-list .activity-date {
            position: relative !important;
            display: block;
            left: 0 !important;
            margin-bottom: 10px;
        }
    }
</style>
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Notifikasi</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Notifikasi</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Index</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="d-flex justify-content-between">
                <h4 class="">Daftar Notifikasi</h4>
                @if (Auth::user()->unreadNotifications->count() > 0)
                <div>
                    <a href="{{ route('dashboard.notification.markAsRead') }}" class="btn btn-primary btn-sm">
                        Tandai Sudah Baca
                    </a>
                </div>
                @endif
            </div>
            @if ($notifications->count() > 0)
                <ul class="my-3 list-unstyled activity-list">
                    @foreach ($notifications as $key => $notification)
                        <li class="activity-item">
                            <span class="activity-date">
                                {{ $notification->created_at->format("d-m-Y") }} <br>
                                {{ $notification->created_at->format("H:i:s") }}
                            </span>
                            @if (empty($notification->read_at))
                                <span class="badge bg-danger float-end py-1 px-3">NEW</span>
                            @endif
                            <span class="activity-text">
                                <a href="{{ route('dashboard.notification.read', $notification->id) }}">
                                    {!! $notification->data['title'] !!}
                                </a>
                            </span>
                            <p class="text-muted mt-2">{!! $notification->data['message'] !!}</p>
                        </li>
                    @endforeach
                </ul>
                <div>
                    {!! $notifications->links() !!}
                </div>
            @else
                <h5 class="text-center">Tidak terdapat notifikasi</h5>
            @endif
        </div>
    </div>
</div>
@endsection

@include("dashboard.components.loader")

@section("script")
@endsection