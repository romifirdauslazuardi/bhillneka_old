@extends("dashboard.layouts.main")

@section("title","Akuntansi")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">Akuntansi</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Akuntansi</a></li>
            <li class="breadcrumb-item active">Show</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Akuntansi</h5>
                <div class="row">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Jenis Akuntansi
                            </div>
                            <div class="col-md-8">
                                : {{$result->type()}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Tanggal
                            </div>
                            <div class="col-md-8">
                                : {{date("d-m-Y",strtotime($result->date))}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Nama Kegiatan
                            </div>
                            <div class="col-md-8">
                                : {{$result->name}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Deskripsi
                            </div>
                            <div class="col-md-8">
                                : {{$result->description}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Nominal
                            </div>
                            <div class="col-md-8">
                                : {{number_format($result->nominal,0,',','.')}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Bisnis
                            </div>
                            <div class="col-md-8">
                                : {{$result->business->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Author
                            </div>
                            <div class="col-md-8">
                                : {{$result->author->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Tanggal Dibuat
                            </div>
                            <div class="col-md-8">
                                : {{ date('d-m-Y H:i:s',strtotime($result->created_at)) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Tanggal Diperbarui
                            </div>
                            <div class="col-md-8">
                                : {{ date('d-m-Y H:i:s',strtotime($result->updated_at)) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{route('dashboard.cost-accountings.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                @if(!empty(Auth::user()->business_id))
                                <a href="{{route('dashboard.cost-accountings.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.cost-accountings.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
