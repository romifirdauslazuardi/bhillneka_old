@extends("dashboard.layouts.main")

@section("title","Notifikasi")

@section("css")
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @if ($notifications->count() > 0)
                                    @foreach($notifications as $index => $notification)
                                    <tr>
                                        <td>{{$notifications->firstItem() + $index}}</td>
                                        <td>{!! $notification->data['title'] !!}</td>
                                        <td>{!! $notification->data['message'] !!}</td>
                                        <td>
                                            {{ Carbon\Carbon::parse($notification->created_at)->format("d M y") }} <br>
                                            {{ Carbon\Carbon::parse($notification->created_at)->format("H:i:s") }}
                                        </td>
                                        <td>
                                            @if (empty($notification->read_at))
                                            <span class="badge bg-danger">Belum Dibaca</span>
                                            @else
                                            <span class="badge bg-success">Sudah Dibaca</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route("dashboard.notification.read", ["go" => $notification->id]) }}" class="btn btn-success btn-sm">Baca</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak terdapat notifikasi</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {!!$notifications->links()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include("dashboard.components.loader")

@section("script")
@endsection