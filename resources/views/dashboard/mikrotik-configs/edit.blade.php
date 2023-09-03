@extends("dashboard.layouts.main")

@section("title","Pengaturan Mikrotik")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">Mikrotik</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Mikrotik</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.mikrotik-configs.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Router <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Router" value="{{old('name',$result->name)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Deskripsi</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" placeholder="Deskripsi">{{ old("description",$result->description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">IP <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="ip" placeholder="IP" value="{{old('ip',$result->ip)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Username <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="username" placeholder="Username" value="{{old('username',$result->username)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Password <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password" placeholder="Password" value="{{old('password',$result->password)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Port API <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="port" placeholder="Port API" value="{{old('port',$result->port)}}" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.mikrotik-configs.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        $(document).on('submit','#frmUpdate',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmUpdate").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmUpdate')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.mikrotik-configs.index')}}");
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
