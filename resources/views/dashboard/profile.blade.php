@extends("dashboard.layouts.main")

@section("title","Edit Profile")

@section("css")
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.css" type="text/css" rel="stylesheet" />
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">Profil</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Profil</a></li>
            <li class="breadcrumb-item active">Edit Profil</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.profile.update')}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{old('name',$result->name)}}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Phone <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{old('phone',$result->phone)}}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email',$result->email)}}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Foto</label>
                            <div class="col-md-9">
                                <input class="form-control" type="file" name="avatar" accept="image/*">
                                <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosongkan jika tidak diubah</i></small></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                                <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosongkan jika tidak diubah</i></small></p>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Konfirmasi Password</label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Password Konfirmasi">
                                <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosongkan jika tidak diubah</i></small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/moment/moment.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/axios/axios.min.js"></script>
<script>
    $(function(){
        $.datetimepicker.setDateFormatter('moment');
        $.datetimepicker.setLocale('id');

        $('.datetimepicker').datetimepicker({
              format:'YYYY-MM-DD HH:mm:ss',
              formatTime:'HH:mm:ss',
              formatDate:'YYYY-MM-DD'
        });

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
                            responseSuccess(resp.message,"{{route('dashboard.profile.index')}}");
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
