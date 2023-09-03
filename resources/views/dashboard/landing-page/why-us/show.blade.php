@extends("dashboard.layouts.main")

@section("title","Why Us")

@section("css")
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Why Us</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Why Us</a></li>
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
                <h5 class="card-title mb-3">Informasi Data Why Us</h5>
                <div class="row">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Judul
                            </div>
                            <div class="col-md-8">
                                : {{$result->title}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Sub Judul
                            </div>
                            <div class="col-md-8">
                                : {{$result->sub_title}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Konten
                            </div>
                            <div class="col-md-8">
                                : {!! $result->trixRender('content') !!}
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
                            <a href="{{route('dashboard.landing-page.why-us.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{route('dashboard.landing-page.why-us.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
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
                $("#frmDelete").attr("action", "{{ route('dashboard.landing-page.why-us.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
