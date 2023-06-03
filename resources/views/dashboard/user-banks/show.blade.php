@extends("dashboard.layouts.main")

@section("title","Rekening Pengguna")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Rekening Pengguna</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Rekening Pengguna</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Rekening Pengguna</h5>
                <div class="row">
                    <div class="col-12">
    
                        <div class="row mb-2">
                            <div class="col-md-3">
                                Atas Nama 
                            </div>
                            <div class="col-md-8">
                                : {{$result->name}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Nomor Rekening
                            </div>
                            <div class="col-md-8">
                                : {{$result->number}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Bank
                            </div>
                            <div class="col-md-8">
                                : {{$result->bank->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Pengguna
                            </div>
                            <div class="col-md-8">
                                : {{$result->user->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Status
                            </div>
                            <div class="col-md-8">
                                : <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
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
                            <a href="{{route('dashboard.user-banks.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{route('dashboard.user-banks.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.user-banks.verified.index")

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
                $("#frmDelete").attr("action", "{{ route('dashboard.user-banks.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection