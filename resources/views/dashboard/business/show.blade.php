@extends("dashboard.layouts.main")

@section("title","Bisnis")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Bisnis</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Bisnis</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row pb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Bisnis</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                Nama Bisnis
                            </div>
                            <div class="col-md-8">
                                : {{$result->name}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Kategori
                            </div>
                            <div class="col-md-8">
                                : {{$result->category->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Pemilik Usaha
                            </div>
                            <div class="col-md-8">
                                : {{$result->user->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Provinsi
                            </div>
                            <div class="col-md-8">
                                : {{$result->village->district->city->province->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Kota/Kabupaten
                            </div>
                            <div class="col-md-8">
                                : {{$result->village->district->city->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Kecamatan
                            </div>
                            <div class="col-md-8">
                                : {{$result->village->district->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Desa
                            </div>
                            <div class="col-md-8">
                                : {{$result->village->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Alamat
                            </div>
                            <div class="col-md-8">
                                : {{$result->location}}
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
                                QRcode Katalog
                            </div>
                            <div class="col-md-8">
                                : <a href="{{route('dashboard.business.qrcode',$result->id)}}">
                                    {{\QrCode::size(100)->generate(route('landing-page.shops.index',$result->slug))}}
                                </a>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                URL Katalog
                            </div>
                            <div class="col-md-8">
                                : <a href="{{route('landing-page.shops.index',$result->slug)}}">
                                    {{route('landing-page.shops.index',$result->slug)}}
                                </a>
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
                            <a href="{{route('dashboard.business.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{route('dashboard.business.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}" style="margin-right: 10px;"><i class="fa fa-trash"></i> Hapus</a>
                            <a href="#" class="btn btn-success btn-sm btn-apply" data-id="{{$result->id}}"><i class="fa fa-check"></i> Terapkan Bisnis Page</a>
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

<form id="frmApply" method="POST" action="{{route('dashboard.profile.updateBusinessPage')}}">
    @csrf
    @method('PUT')
    <input type="hidden" name="business_id" />
</form>

@include("dashboard.components.loader")
@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.business.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on("click", ".btn-apply", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin mengaktifkan halaman bisnis ini ?")) {
                $("#frmApply").find('input[name="business_id"]').val(id);
                $.ajax({
                    url : $("#frmApply").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmApply')[0]),
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType : "JSON",
                    beforeSend : function(){
                        return openLoader();
                    },
                    success : function(resp){
                        if(resp.success == false){
                            responseFailed(resp.message);                   
                        }
                        else{
                            responseSuccess(resp.message,"{{url()->current()}}");
                        }
                    },
                    error: function (request, status, error) {
                        if(request.status == 422){
                            responseFailed(request.responseJSON.message);
                        }
                        else{
                            responseInternalServerError();
                        }
                    },
                    complete :function(){
                        return closeLoader();
                    }
                })
            }
        })
    })
</script>
@endsection