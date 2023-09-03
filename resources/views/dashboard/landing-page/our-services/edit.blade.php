@extends("dashboard.layouts.main")

@section("title","Layanan")

@section("css")
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Layanan</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Layanan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ul>
        </div>
    </div>
@endsection

@section("content")
@trixassets
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.landing-page.our-services.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama" value="{{old('name',$result->name)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Deskripsi <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="5" placeholder="Deskripsi" name="description">{{old('description',$result->description)}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Icon<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="icon" placeholder="Ex : fa fa-user" value="{{old('icon',$result->icon)}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.landing-page.our-services.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary btn-sm" disabled><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function(){

        $('button[type="submit"]').attr("disabled",false);

        $(document).on('submit','#frmStore',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmStore").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmStore')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.landing-page.our-services.index')}}");
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
