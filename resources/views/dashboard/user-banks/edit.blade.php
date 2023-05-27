@extends("dashboard.layouts.main")

@section("title","Rekening Pengguna")

@section("css")
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.css" type="text/css" rel="stylesheet" />
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
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.user-banks.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pengguna<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="user_id" >
                                    <option value="">==Pilih Pengguna==</option>
                                    @foreach ($users as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('user_id',$result->user_id)) selected @endif>{{$row->name}} ({{$row->phone}})</option>
                                    @endforeach
                                </select>
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.user-banks.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include("dashboard.components.loader")

@section("script")
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/moment/moment.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/axios/axios.min.js"></script>
<script>
    $(function(){
        getProvince('.select-province',null);

        $(document).on("change", ".select-province", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-city').html('<option value="">==Pilih Kota/Kabupaten==</option>');
            $('.select-district').html('<option value="">==Pilih Kecamatan==</option>');
            $('.select-village').html('<option value="">==Pilih Desa==</option>');

            if(val != "" || val != undefined || val != null){
                getCity('.select-city',val,null);
            }
        });

        $(document).on("change", ".select-city", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-district').html('<option value="">==Pilih Kecamatan==</option>');
            $('.select-village').html('<option value="">==Pilih Desa==</option>');

            if(val != "" || val != undefined || val != null){
                getDistrict('.select-district',val,null);
            }
        });

        $(document).on("change", ".select-district", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-village').html('<option value="">==Pilih Desa==</option>');

            if(val != "" || val != undefined || val != null){
                getVillage('.select-village',val,null);
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