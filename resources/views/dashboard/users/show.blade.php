@extends("dashboard.layouts.main")

@section("title","User")

@section("css")
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.css" type="text/css" rel="stylesheet" />
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">User</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">User</a></li>
            <li class="breadcrumb-item active">Show User</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row pb-5">
    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-body">
                <div class="text-center d-flex flex-column h-100">
                    <div>
                        <img src="{{ $result->avatar ? asset($result->avatar) : 'https://avatars.dicebear.com/api/initials/'. $result->name .'.png?background=blue&width=100&height=100' }}"
                                alt=""
                                class="avatar-lg mx-auto img-thumbnail rounded-circle">
                    </div>

                    <div class="mt-3 d-flex justify-content-between flex-column flex-1">
                        <div class="mx-auto">
                            <h6>{{ $result->name }}</h6>
                            <p class="text-body mt-1 mb-1">
                                <b>{{$result->getRoleNames()->implode(', ') }}</b>
                            </p>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{route('dashboard.users.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{route('dashboard.users.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            @if(Auth::user()->id != $result->id)
                                @if(!$result->trashed())
                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                @else
                                    @if(Auth::user()->hasRole(\App\Enums\RoleEnum::OWNER))
                                    <a href="#" class="btn btn-warning btn-sm mr-1 btn-restore" data-id="{{$result->id}}"><i class="fa fa-undo"></i> Restore</a>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Personal</h5>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Nama Lengkap</p>
                    <h6 class="">{{ $result->name }}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Phone</p>
                    <h6 class="">{{ $result->phone }}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Email</p>
                    <h6 class="">{{ $result->email }}</h6>
                </div>

                @if($result->hasRole([\App\Enums\RoleEnum::CUSTOMER,\App\Enums\RoleEnum::ADMIN_AGEN]))
                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Agen</p>
                    <h6 class="">{{ $result->user->name ?? null }}</h6>
                </div>
                @endif

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Author</p>
                    <h6 class="">{{ $result->author->name ?? "-" }}</h6>
                </div>

                @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Tipe Registrasi</p>
                    <h6 class="">{{ $result->provider() ?? null }}</h6>
                </div>
                @endif

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Status</p>
                    <h6 class="">
                        <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                    </h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Email Verified At</p>
                    <h6 class="">@if(!empty($result->email_verified_at)) {{ date('d-m-Y H:i:s',strtotime($result->email_verified_at)) }} @endif</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Tanggal Dibuat</p>
                    <h6 class="">{{ date('d-m-Y H:i:s',strtotime($result->created_at)) }}</h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Tanggal Diperbarui</p>
                    <h6 class="">{{ date('d-m-Y H:i:s',strtotime($result->updated_at)) }}</h6>
                </div>

                @if(!empty($result->deleted_at))
                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Tanggal Dihapus</p>
                    <h6 class="">{{ date('d-m-Y H:i:s',strtotime($result->deleted_at)) }}</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

<form id="frmRestore" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="id"/>
</form>

@include("dashboard.components.loader")

@endsection

@section("script")
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/moment/moment.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/axios/axios.min.js"></script>
<script>
    $(function(){
        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.users.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on("click", ".btn-restore", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin mengembalikan data ini ?")) {
                $("#frmRestore").attr("action", "{{ route('dashboard.users.restore', '_id_') }}".replace("_id_", id));
                $("#frmRestore").find('input[name="id"]').val(id);
                $("#frmRestore").submit();
            }
        })
    })
</script>
@endsection
