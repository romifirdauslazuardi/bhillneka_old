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
<div class="row">
    <div class="col-12 mt-4">
        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) && !empty(Auth::user()->business_id))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        Halo owner , bisnis page sedang diaktifkan , halaman rekening akan tampil sesuai rekening bisnis page yang sedang aktif . Nonaktifkan bisnis page jika ingin menambahkan/menampilkan rekening Anda sendiri
                    </div>
                </div>
            </div>
        @endif
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.user-banks.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <input type="hidden" name="user_id" value="{{$result->user_id}}">
                <input type="hidden" name="business_id" value="{{$result->business_id}}">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pengguna<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="{{$result->user->name ?? null}}" readonly disabled>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Bank<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="bank_id" >
                                    <option value="">==Pilih Bank==</option>
                                    @foreach ($banks as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('bank_id',$result->bank_id)) selected @endif>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Cabang <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="branch" placeholder="Cabang" value="{{old('branch',$result->branch)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Atas Nama <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Atas Nama" value="{{old('name',$result->name)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="number" placeholder="Nomor Rekening" value="{{old('number',$result->number)}}" >
                            </div>
                        </div>
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Status<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-status" name="status" >
                                    <option value="">==Pilih Status Rekening==</option>
                                    @foreach ($status as $index => $row)
                                    <option value="{{$index}}" @if($index == $result->status) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3 display-bank-settlement-id @if($result->status != App\Enums\UserBankEnum::STATUS_APPROVED) d-none @endif">
                            <label class="col-md-3 col-form-label">Bank Settlement ID</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="bank_settlement_id" placeholder="Bank Settlement ID" value="{{old('bank_settlement_id',$result->bank_settlement_id)}}" >
                                <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Wajib diisi ketika status rekening terverifikasi</i></small></p>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Default<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="default" >
                                    <option value="">==Pilih Default Rekening==</option>
                                    @foreach ($default as $index => $row)
                                    <option value="{{$index}}" @if($index == $result->default) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.user-banks.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        @if(!empty($result->business_id))
            getBusiness('.select-business','{{$result->business->user_id ?? null}}','{{$result->business_id}}');
        @else
            getBusiness('.select-business','{{$result->user_id}}',null);
        @endif

        $(document).on('change','.select-user',function(e){
            e.preventDefault();
            let val = $(this).val();

            $('.select-business').html('<option value="">==Pilih Bisnis==</option>');

            if(val != null && val != "" && val != undefined){
                getBusiness('.select-business',val,null);
            }
        });

        $(document).on('change','.select-status',function(e){
            e.preventDefault();
            let val = $(this).val();

            if(val == '{{App\Enums\UserBankEnum::STATUS_APPROVED}}'){
                $('.display-bank-settlement-id').removeClass("d-none");
            }
            else{
                $('.display-bank-settlement-id').removeClass("d-none");
                $('.display-bank-settlement-id').addClass("d-none");
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
                            responseFailed(resp.message);                   
                        }
                        else{
                            responseSuccess(resp.message,"{{route('dashboard.user-banks.index')}}");
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