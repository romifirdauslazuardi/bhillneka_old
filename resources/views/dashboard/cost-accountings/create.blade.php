@extends("dashboard.layouts.main")

@section("title","Akuntansi")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Akuntansi</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Akuntansi</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.cost-accountings.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Jenis Akuntansi<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="type">
                                    <option value="">==Pilih Jenis Akuntansi==</option>
                                    @foreach($type as $index => $row)
                                    <option value="{{$index}}">{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date" placeholder="Tanggal" value="{{old('date')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Kegiatan" value="{{old('name')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Deskripsi</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" rows="5" placeholder="Deskripsi">{{old('description')}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nominal <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control nominal" name="nominal" placeholder="Nominal" value="{{old('nominal')}}" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.cost-accountings.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        $(document).on("change",".checkbox-customer",function(e){
            if($(this).is(":checked")){
                $(".select-customer > option").prop("selected", false).trigger("change");
                $(".select-customer > option").prop("selected", true).trigger("change");
            }
            else{
                $(".select-customer > option").prop("selected", false).trigger("change");
            }
        });

        $(document).on("keyup",".nominal",function(e){
            e.preventDefault();

            let val = $(this).val();

            $(this).val(formatRupiah(val,undefined));

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
                            return responseFailed(resp.message);
                        }
                        else{
                            return responseSuccess(resp.message,"{{route('dashboard.cost-accountings.index')}}");
                        }
                    },
                    error: function (request, status, error) {
                        if(request.status == 422){
                            return responseFailed(request.responseJSON.message);
                        }
                        else if(request.status == 419){
                            return sessionTimeOut();
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
