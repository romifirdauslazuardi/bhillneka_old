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
            <li class="breadcrumb-item active">Edit User</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.users.update',$result->id)}}" id="frmUpdate" autocomplete="off">
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
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email Verified At</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datetimepicker" name="email_verified_at" placeholder="Email Verified At" value="{{old('email_verified_at',$result->email_verified_at)}}">
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Role<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-role" name="roles">
                                    @foreach ($roles as $index => $row)
                                    <option value="{{$row}}"  @if(in_array($row,$result->roles->pluck('name')->toArray())) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) && !empty(Auth::user()->business_id))
                        <div class="form-group row mb-3 display-agen @if(!$result->hasRole([\App\Enums\RoleEnum::CUSTOMER])) d-none @endif">
                            <label class="col-md-3 col-form-label">Agen<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="user_id" >
                                    <option value="">==Pilih Agen==</option>
                                    @foreach ($users as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('user_id',$result->user_id)) selected @endif>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3 display-business @if(!$result->hasRole([\App\Enums\RoleEnum::CUSTOMER])) d-none @endif">
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

        @if($result->hasRole([\App\Enums\RoleEnum::CUSTOMER]))
            getBusiness('.select-business','{{$result->user_id}}','{{$result->business->id ?? null}}');
        @endif

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
        });

        $(document).on('change','.select-user',function(e){
            e.preventDefault();
            let val = $(this).val();

            $('.select-business').html('<option value="">==Pilih Bisnis==</option>');

            if(val != null && val != "" && val != undefined){
                getBusiness('.select-business',val,null);
            }
        });

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
                            return responseFailed(resp.message);
                        }
                        else{
                            return responseSuccess(resp.message,"{{route('dashboard.users.index')}}");
                        }
                    },
                    error: function (request, status, error) {
                        if(request.status == 422){
                            return responseFailed(request.responseJSON.message);
                        }
                        else{
                            return responseInternalServerError();
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
