@extends("dashboard.layouts.main")

@section("title","Akuntansi")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">Akuntansi</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Akuntansi</a></li>
            <li class="breadcrumb-item active">edit</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.cost-accountings.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Jenis Akuntansi<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="type">
                                    <option value="">==Pilih Jenis Akuntansi==</option>
                                    @foreach($type as $index => $row)
                                    <option value="{{$index}}" @if($index == $result->type) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date" placeholder="Tanggal" value="{{old('date',$result->date)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Kegiatan" value="{{old('name',$result->name)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Deskripsi</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" rows="5" placeholder="Deskripsi">{{old('description',$result->description)}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nominal <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control nominal" name="nominal" placeholder="Nominal" value="{{old('nominal',number_format($result->nominal,0,',','.'))}}" >
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
                            responseSuccess(resp.message,"{{route('dashboard.cost-accountings.index')}}");
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
