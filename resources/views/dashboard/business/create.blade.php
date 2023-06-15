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
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.business.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Agen<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="user_id" >
                                    <option value="">==Pilih Agen==</option>
                                    @foreach ($users as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('user_id')) selected @endif>{{$row->name}} - {{$row->phone}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Bisnis <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Bisnis" value="{{old('name')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Kategori<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="category_id" >
                                    <option value="">==Pilih Kategori==</option>
                                    @foreach ($categories as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('category_id')) selected @endif>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Provinsi<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-province">
                                    <option value="">==Pilih Provinsi==</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Kota/Kabupaten<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-city">
                                    <option value="">==Pilih Kota/Kabupaten==</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Kecamatan<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-district">
                                    <option value="">==Pilih Kecamatan==</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Desa<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-village" name="village_code">
                                    <option value="">==Pilih Desa==</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Alamat Lengkap<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="location" rows="5" placeholder="Alamat Lengkap">{{old('location')}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Deskripsi</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" rows="5" placeholder="Deskripsi">{{old('description')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.business.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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
        
        getProvince('.select-province',null);

        $(document).on("change", ".select-province", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-city').html('<option value="">==Pilih Kota/Kabupaten==</option>');
            $('.select-district').html('<option value="">==Pilih Kecamatan==</option>');
            $('.select-village').html('<option value="">==Pilih Desa==</option>');

            if(val != "" && val != undefined && val != null){
                getCity('.select-city',val,null);
            }
        });

        $(document).on("change", ".select-city", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-district').html('<option value="">==Pilih Kecamatan==</option>');
            $('.select-village').html('<option value="">==Pilih Desa==</option>');

            if(val != "" && val != undefined && val != null){
                getDistrict('.select-district',val,null);
            }
        });

        $(document).on("change", ".select-district", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-village').html('<option value="">==Pilih Desa==</option>');

            if(val != "" && val != undefined && val != null){
                getVillage('.select-village',val,null);
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
                            responseSuccess(resp.message,"{{route('dashboard.business.index')}}");
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