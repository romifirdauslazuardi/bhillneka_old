@extends("dashboard.layouts.main")

@section("title","User")

@section("css")
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.css" type="text/css" rel="stylesheet" />
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Users</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Users</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.users.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{old('name')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Phone <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{old('phone')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Foto</label>
                            <div class="col-md-9">
                                <input class="form-control" type="file" name="avatar" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Password <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password" placeholder="Password" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Password Konfirmasi" >
                            </div>
                        </div>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email Verified At</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datetimepicker" name="email_verified_at" placeholder="Email Verified At" value="{{old('email_verified_at')}}">
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Roles<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-role" name="roles" >
                                    <option value="">==Pilih Role Pengguna==</option>
                                    @foreach ($roles as $index => $row)
                                    <option value="{{$row}}" @if($row == old('roles')) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) && empty(Auth::user()->business_id))
                        <div class="form-group row mb-3 display-agen d-none">
                            <label class="col-md-3 col-form-label">Agen<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-user" name="user_id" >
                                    <option value="">==Pilih Agen==</option>
                                    @foreach ($users as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('user_id')) selected @endif>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3 display-business d-none">
                            <label class="col-md-3 col-form-label">Bisnis<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-business" name="business_id" >
                                    <option value="">==Pilih Bisnis==</option>
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.users.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        $(document).on('change','.select-role',function(e){
            e.preventDefault();
            let val = $(this).val();
            let agen = false;
            
            if(val == '{{\App\Enums\RoleEnum::CUSTOMER}}' || val == '{{\App\Enums\RoleEnum::ADMIN_AGEN}}'){
                agen = true;
            }

            if(agen == true){
                $(".display-agen").removeClass("d-none");
            }
            else{
                $(".display-agen").addClass("d-none");
            }

            $(".display-business").removeClass("d-none").addClass("d-none");

            if(val == '{{\App\Enums\RoleEnum::CUSTOMER}}'){
                $(".display-business").removeClass("d-none");
            }
        });

        $(document).on('change','.select-user',function(e){
            e.preventDefault();
            let val = $(this).val();

            $('.select-business').html('<option value="">==Pilih Bisnis==</option>');

            if(val != null && val != "" && val != undefined){
                getBusiness('.select-business',val,null);
            }
        });

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
                            responseSuccess(resp.message,"{{route('dashboard.users.index')}}");
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